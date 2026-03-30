<?php

namespace HTML;

class ComponentProps implements \ArrayAccess, \IteratorAggregate {
	private $items = [];

	public function __construct(
		string|array $props = [],
	) {
		if (is_string($props)) {
			$this->items = json_decode($props, true);
		} elseif (is_array($props)) {
			$this->items = $props;
		}
	}

	public function __isset($key) {
		return isset($this->items[$key]);
	}

	public function __set(
		string $name,
		mixed $value,
	) {
		$this->items[$name] = $value;
	}

	public function __get($name) {
		return $this->items[$name];
	}

	public function safe($name) {
		$val = $this->items[$name];
		if (is_string($val)) {
			$val = Utils::encode_output($val);
		}

		return $val;
	}

	public function __unset($key) {
		unset($this->items[$key]);
	}

	public function filter(
		array|callable $filter,
	) {
		$out = [];

		if (is_callable($filter)) {
			$prop_names = array_filter(
				array_keys($this->items),
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
					...array_keys($this->items),
					...$prop_names,
				];
			}

			$prop_names = array_diff($prop_names, $skip);
		}

		foreach ($prop_names as $name) {
			if (
				$name !== 'children' &&
				key_exists($name, $this->items)
			) {
				$out[$name] = $this->$name;
			}
		}

		return new self($out);
	}

	/* ArrayAccess */
	public function offsetSet($offset, $value): void {
		if (is_null($offset)) {
			$this->container[] = $value;
		} else {
			$this->container[$offset] = $value;
		}
	}

	public function offsetExists($offset): bool {
		return isset($this->container[$offset]);
	}

	public function offsetUnset($offset): void {
		unset($this->container[$offset]);
	}

	public function offsetGet($offset): mixed {
		return isset($this->container[$offset]) ? $this->container[$offset] : null;
	}

	/* Data acquisition */
	function __toString() {
		$out = array_map(
			fn($name, $value) => Utils::encode_output($name) . '="' . Utils::encode_output($value)  . '"',
			array_keys($this->items),
			array_values($this->items),
		);

		$out = implode(' ', $out);

		return $out;
	}

	function __toUnsafeString() {
		$out = array_map(
			fn($name, $value) => $name . '="' . $value . '"',
			array_keys($this->items),
			array_values($this->items),
		);

		$out = implode(' ', $out);

		return $out;
	}

	public function getIterator(): \Traversable {
		return new \ArrayIterator($this->items);
	}
}
