<?php

namespace Testings\Helpers;

class Normalizers {
	static function queryListNormalizer(array $list): array
	{
		foreach ( $list as &$row ) {
			foreach ( $row as $key => &$value ) {
				if ( $key == 'options' ) {
					$value = json_decode( $value );
				}
				if ( is_numeric( $value ) ) {
					$value = (int) $value;
				}
			}

			unset($value);
		}

		unset($row);

		return $list;
	}
}