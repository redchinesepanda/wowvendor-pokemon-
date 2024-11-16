<?php

namespace wowpokemon\lib\tool;

class ToolCurl
{
	public static function get_response( $url = '' ) : string
	{
		if ( ! empty( $url ) )
		{
			$curl = curl_init( $url );
	
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	
			$response = curl_exec( $curl );
	
			// WOWPokemonDebug::debug( [
			// 	'GutenbergPokemon' => 'get_pokemon_type_json',
	
			// 	'response' => $response,
	
			// 	'curl_error' => curl_error( $curl ),
			// ] );
	
			curl_close( $curl );
	
			return $response;
		}
		return '';
	}
}

?>