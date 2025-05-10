<?php

namespace HTML;

class Utils {
	static function encode_output(
		$input,
	) {
		$val = $input;

		if (is_array($val)) {
			$val = join('', $val);
		}

		$val = htmlspecialchars($val);

		return $val;
	}
}
