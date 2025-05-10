<?php

namespace HTML;

class Props {
	static public function forward(
		Component $class,
		array $prop_names,
		bool $return = false,
	) {
		$out = [];

		foreach ($prop_names as $name) {
			if (key_exists($name, $class->__props__)) {
				$out[] = $name . '="' . $class->__props__[$name] . '"';
			}
		}

		$out = implode(' ', $out);

		if ($return) {
			return $out;
		}

		print($out);
	}

	static public function to_array(
		Component $class,
		array $prop_names,
		string $separator = ',',
	) {
		foreach ($prop_names as $name) {
			$class->$name = array_map(
				fn($item) => trim($item),
				explode($separator, $class->$name),
			);
		}
	}
}
