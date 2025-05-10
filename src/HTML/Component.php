<?php

namespace HTML;

abstract class Component {
	public $__props__ = [];
	public static $__instance_index__ = -1;

	public function __construct(
		string|array $props = [],
	) {
		if (is_string($props)) {
			$this->__props__ = json_decode($props, true);
		} elseif (is_array($props)) {
			$this->__props__ = $props;
		}

		static::$__instance_index__++;

		ob_start();
	}

	public function __get($name) {
		$val = $this->__props__[$name];

		if ($name !== 'children') {
			$val = Utils::encode_output($val);
		}

		return $val;
	}

	public function __set(
		$name,
		$value,
	) {
		$this->__props__[$name] = $value;
	}

	final private function _render(
		bool $return,
	) {
		ob_start();
		$fallback = $this->render();

		$res = ob_get_clean();

		if ($return) {
			return $res ?: $fallback;
		}

		print($res ?: $fallback);
	}

	abstract public function render();

	public static function closed(
		string|array $props = [],
		bool $return = false,
	) {
		$element = new static($props);
		ob_end_clean();

		return $element->_render($return);
	}

	public function close(
		bool $return = false,
	) {
		$this->__props__['children'] = ob_get_clean();

		return $this->_render($return);
	}
}
