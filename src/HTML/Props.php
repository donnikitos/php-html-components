<?php

namespace HTML;

class Props {
	static public function forward(
		Component $class,
		array $prop_names,
		bool $return = false,
	) {
		$out = [];
		$skip = array_map(
			fn($name) => substr($name, 1),
			array_filter(
				$prop_names,
				fn($name) => str_starts_with($name, '!'),
			),
		);
		$prop_names =  array_filter(
			$prop_names,
			fn($name) => !str_starts_with($name, '!'),
		);

		if ($prop_names[0] === '*') {
			array_shift($prop_names);
			$prop_names = [
				...array_keys($class->__props__),
				...$prop_names,
			];
		}

		$prop_names = array_diff($prop_names, $skip);

		foreach ($prop_names as $name) {
			if (
				$name !== 'children' &&
				key_exists($name, $class->__props__)
			) {
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
