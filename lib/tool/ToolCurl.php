<?php

namespace wowpokemon\lib\tool;

/**
 * 
 * Curl requests tool
 * 
 */

class ToolCurl
{
	/**
	 * 
	 * Obtains response from specified url
	 * 
	 * @return string
	 * 
	 */

	public static function get_response( $url = '' ) : string
	{
		if ( ! empty( $url ) )
		{
			$curl = curl_init( $url );
	
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	
			$response = curl_exec( $curl );
	
			curl_close( $curl );
	
			return $response;
		}
		return '';
	}
}

?>