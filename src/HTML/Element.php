<?php

namespace HTML;

class Element extends \HTML\Component {
	public function render() {
		$props = $this->__props__->filter(['*', '!element']);
		$element = $this->element ?: 'div';

		if (in_array(
			$element,
			['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'],
		)) {
			print("<$element $props />");
			return;
		}

		print(<<<HTML
		<$element $props>
			$this->children
		</$element>
		HTML);
	}
}
