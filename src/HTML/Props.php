<?php

namespace HTML;

class Props {
	static public function get(
		Component $class,
		array|callable $filter,
	) {
		$out = [];

		if (is_callable($filter)) {
			$prop_names = array_filter(
				array_keys($class->__props__),
				$filter,
			);
		} else {
			$skip = array_map(
				fn($name) => substr($name, 1),
				array_filter(
					$filter,
					fn($name) => str_starts_with($name, '!'),
				),
			);
			$prop_names =  array_filter(
				$filter,
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
		}

		foreach ($prop_names as $name) {
			if (
				$name !== 'children' &&
				key_exists($name, $class->__props__)
			) {
				$out[$name] = $class->__props__[$name];
			}
		}

		return $out;
	}

	/**
	 * @deprecated
	 * @deprecated 1.0.4 Not recommended to use use the Props::get() or Component->__props__() function.
	 */
	static public function forward(
		Component $class,
		array|callable $filter,
		bool $return = false,
	) {
		$props = self::get($class, $filter);
		$out = array_map(
			fn($name, $value) => $name . '="' . $value . '"',
			array_keys($props),
			array_values($props),
		);

		$out = implode(' ', $out);

		if ($return) {
			return $out;
		}

		print($out);
	}

	/**
	 * @deprecated
	 * @deprecated 1.0.4 Will be no longer implemented and not recommended to use.
	 */
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
