<?php

namespace HTML;

class ComponentProps implements \ArrayAccess, \IteratorAggregate {
	private $__items__ = [];

	public function __construct(
		string|array $props = [],
	) {
		if (is_string($props)) {
			$this->__items__ = json_decode($props, true);
		} elseif (is_array($props)) {
			$this->__items__ = $props;
		}
	}

	public function __isset($key) {
		return isset($this->__items__[$key]);
	}

	public function __set(
		string $name,
		mixed $value,
	) {
		$this->__items__[$name] = $value;
	}

	public function __get($name) {
		return $this->__items__[$name];
	}

	public function safe($name) {
		$val = $this->__items__[$name];
		if (is_string($val)) {
			$val = Utils::encode_output($val);
		}

		return $val;
	}

	public function __unset($key) {
		unset($this->__items__[$key]);
	}

	public function filter(
		array|callable $filter,
	) {
		$out = [];

		if (is_callable($filter)) {
			$prop_names = array_filter(
				array_keys($this->__items__),
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
					...array_keys($this->__items__),
					...$prop_names,
				];
			}

			$prop_names = array_diff($prop_names, $skip);
		}

		foreach ($prop_names as $name) {
			if (
				$name !== 'children' &&
				key_exists($name, $this->__items__)
			) {
				$out[$name] = $this->$name;
			}
		}

		return new self($out);
	}

	/* ArrayAccess */
	public function offsetSet($offset, $value): void {
		if (is_null($offset)) {
			$this->__items__[] = $value;
		} else {
			$this->__items__[$offset] = $value;
		}
	}

	public function offsetExists($offset): bool {
		return isset($this->__items__[$offset]);
	}

	public function offsetUnset($offset): void {
		unset($this->__items__[$offset]);
	}

	public function offsetGet($offset): mixed {
		return isset($this->__items__[$offset]) ? $this->__items__[$offset] : null;
	}

	/* Data acquisition */
	function __toString() {
		$out = array_map(
			fn($name, $value) => Utils::encode_output($name) . '="' . Utils::encode_output($value)  . '"',
			array_keys($this->__items__),
			array_values($this->__items__),
		);

		$out = implode(' ', $out);

		return $out;
	}

	function __toUnsafeString() {
		$out = array_map(
			fn($name, $value) => $name . '="' . $value . '"',
			array_keys($this->__items__),
			array_values($this->__items__),
		);

		$out = implode(' ', $out);

		return $out;
	}

	public function getIterator(): \Traversable {
		return new \ArrayIterator($this->__items__);
	}
}
