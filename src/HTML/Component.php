<?php

namespace HTML;

abstract class Component {
	public ComponentProps $__props__;
	private static $__instance_counts__ = [];

	public function __construct(
		string|array $props = [],
	) {
		$this->__props__ = new ComponentProps($props);

		if (!isset(static::$__instance_counts__[static::class])) {
			static::$__instance_counts__[static::class] = -1;
		}
		static::$__instance_counts__[static::class]++;

		ob_start();
	}

	public function __get($name) {
		if ($name === 'children') {
			return $this->__props__->$name;
		}

		return $this->__props__->safe($name);
	}

	public function __set(
		$name,
		$value,
	) {
		$this->__props__->$name = $value;
	}

	private function _render(
		bool $return,
	) {
		ob_start();
		$fallback = $this->render();

		$res = ob_get_clean();
		$res = trim($res, "\n\r");

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
		$this->__props__->children = ob_get_clean();

		return $this->_render($return);
	}

	final public static function getInstanceIndex() {
		return static::$__instance_counts__[static::class] ?? 0;
	}
}
