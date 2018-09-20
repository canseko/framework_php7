<?php
class Cache {
	private $memcached;
	private $cacheFile;
	private $tmp = 'tmp';
	public function __construct() {
		$this->cacheFile = !class_exists('Memcached');
		if( !$this->cacheFile ){
			$this->memcached = new Memcached();
			if( !$this->memcached->addServer('127.0.0.1', 11211) ) {
				$this->cacheFile = false;
			}
		}
	}
	public function save( $key, $value ) {
		if( !$this->cacheFile ){
			$this->memcached->set( $key, array( time(), $value ) );
		} else {
			$handle = fopen( $this->tmp . "/" . sha1( $key ), "w" );
			fwrite( $handle, serialize( $value ) );
			fclose( $handle );
		}
	}
	public function read( $key, $duration = 86400 ) {
		if( !$this->cacheFile ){
			if( !$value = $this->memcached->get( $key ) ) {
				return false;
			}
			if( ( $value[0] + $duration ) >= time() ) {
				return $value[1];
			} else {
				return false;
			}
		} else {
			$file = $this->tmp . "/" . sha1( $key );
			if( !file_exists( $file ) ) {
				return false;
			}
			$timestamp = filectime( $file );
			if( ( $timestamp + $duration ) >= time() ) {
				$handle = fopen( $file, "r" );
				$content = unserialize( fread( $handle, filesize( $file ) ) );
				fclose( $handle );
				return $content;
			} else {
				return false;
			}
		}
	}
}