<?php

namespace Testings\Services;

class PhoneNormalizeServise {
	public static function normalize( string $phone_number ): string
	{
		return preg_replace( '/[^0-9]/', '', $phone_number );
	}

	public static function denormalize( string $phone_number ): string
	{
		return substr( $phone_number, 3 );
	}
}