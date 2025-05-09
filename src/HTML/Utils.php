<?php

namespace HTML;

class Utils {
	static function save_output(
		$input,
	) {
		$val = $input;

		if (is_array($val)) {
			$val = join('', $val);
		}

		$val = htmlspecialchars($val);

		return $val;
	}

	static public function forward_props(
		Component $class,
		...$props,
	) {
		foreach ($props as $name) {
			if (key_exists($name, $class->__props__)) {
				print($name . '="' . $class->__props__[$name] . '"');
			}
		}
	}

	static public function string_array(
		string $input,
		$separator = ',',
	) {
		return array_map(
			fn($item) => trim($item),
			explode($separator, $input),
		);
	}
}
