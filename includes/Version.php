<?php
class Version {
	private static $aLookup = array();
	public static function Get( $sFilename ) {
		if( !array_key_exists( $sFilename, self::$aLookup ) ) {
			$sRealPath = realpath($_SERVER['DOCUMENT_ROOT'] . "/$sFilename");
			if( file_exists( $sRealPath ) && ( $iTimestamp = filemtime( $sRealPath ) ) ) {
				self::$aLookup[ $sFilename ] = $iTimestamp;
				$sFilename .= "?v=$iTimestamp";
			}
		} else {
			$sFilename .= '?v='.self::$aLookup[ $sFilename ];
		}
		return $sFilename;
	}
}