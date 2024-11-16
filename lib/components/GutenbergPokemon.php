<?php

namespace wowpokemon\lib\components;

use \wowpokemon\WOWPokemon;

use \wowpokemon\lib\WOWPokemonDebug;

use \wowpokemon\lib\tool\ToolEnqueue;

use \wowpokemon\lib\tool\ToolCurl;

class GutenbergPokemon
{
	const CSS = [
        'gutenberg-block-pokemon' => [
            'path' => WOWPokemon::URL . '/assets/css/components/gutenberg-notice.css',

            'ver'=> '1.2.1',
        ],
    ];

	public static function register_style() : void
    {
		ToolEnqueue::register_style( self::CSS );
    }

	const JS_ADMIN = [
        'gutenberg-block-notice' => [
			'path' => WOWPokemon::URL . '/assets/js/components/gutenberg-notice.js',

			'ver' => '1.0.0',

			'deps' => [
				'wp-blocks',
				
				'wp-editor',
			],
		],
    ];

	const JS_FRONTEND = [
        'gutenberg-block-notice-frontend' => [
			'path' => WOWPokemon::URL . '/assets/js/components/gutenberg-pokemon-frontend.js',

			'ver' => '1.0.1',
		],
    ];

	public static function register_script() : void
    {
		ToolEnqueue::register_script( self::JS_ADMIN );

		ToolEnqueue::localize_script( self::get_localize_pokemon_type() );
    }

	public static function register_script_ajax() : void
    {
		ToolEnqueue::register_script( self::JS_FRONTEND );

		ToolEnqueue::localize_script( self::get_localize_ajax_general() );

		ToolEnqueue::localize_script( self::get_localize_ajax_pokemon_settings() );
    }

	private static function get_localize_pokemon_type() : array
	{
		return [
			'gutenberg-block-notice' => [
				'object_name' => 'gutenbergBlockNotice',
	
				'data' => self::get_pokemon_type(),
			],
		];
	}

	private static function get_localize_ajax_general()
	{
		return [
			'gutenberg-block-notice-frontend' => [
				'object_name' => 'WOWPokemonAjaxGeneral',
	
				'data' => [
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				],
			],
		];
	}

	const ACTIONS = [
		'get-pokemon' => 'wow-get-pokemon',
	];

	const PAREMETERS = [
		'type' => 'type',
	];

	private static function get_localize_ajax_pokemon_settings()
	{
		return [
			'gutenberg-block-notice-frontend' => [
				'object_name' => 'WOWPokemonAjaxSettings',
	
				'data' => [
					'getPokemon' => self::ACTIONS[ 'get-pokemon' ],

					'type' => self::PAREMETERS[ 'type' ],

					'nonce' => wp_create_nonce( self::NONCE ),
				],
			],
		];
	}

	public static function register_functions() : void
    {
        $handler = new self();
		
		add_action( 'enqueue_block_editor_assets', [ $handler, 'register_script' ] );

		add_action( 'enqueue_block_editor_assets', [ $handler, 'register_style' ] );

		add_action( 'wp_enqueue_scripts', [ $handler, 'register_script_ajax' ] );

		add_action( 'wp_enqueue_scripts', [ $handler, 'register_style' ] );

		add_action( sprintf( 'wp_ajax_%s', self::ACTIONS[ 'get-pokemon' ] ), [ $handler, 'ajax_get_pokemon' ] );

		add_action( sprintf( 'wp_ajax_nopriv_%s', self::ACTIONS[ 'get-pokemon' ] ), [ $handler, 'ajax_get_pokemon' ] );
    }

	public static function register() : void
    {
        // self::get_pokemon_type();
    }

	private static function parse_id( string $url ) : int
	{
		$matches = [];

		// WOWPokemonDebug::debug( [
		// 	'GutenbergPokemon' => 'parse_id-1',

		// 	'url' => $url,
		// ] );

		if ( preg_match( "/type\/(\d+)\/$/", $url, $matches ) )
		{
			return $matches[ 1 ];
		}

		return 0;
	}

	private static function parse_pokemon_type( array $item ) : array
	{
		return [
			'name' => $item[ 'name' ],

			'id' => self::parse_id( $item[ 'url' ] ),
		];
	}

	private static function get_pokemon_type() : array
	{
		$types = [];

		$json = json_decode( self::get_pokemon_type_response(), JSON_OBJECT_AS_ARRAY );
		
		// WOWPokemonDebug::debug( [
		// 	'GutenbergPokemon' => 'get_pokemon_type_json-1',

		// 	'json' => $json,
		// ] );

		if ( ! empty( $json[ 'results' ] ) )
		{
			// WOWPokemonDebug::debug( [
			// 	'GutenbergPokemon' => 'get_pokemon_type_json-2',
	
			// 	'results' => $json[ 'results' ],
			// ] );

			foreach ( $json[ 'results' ] as $item )
			{
				// $pokemon_item = self::parse_pokemon_type( $item );

				// $pokemon = self::get_pokemon( $pokemon_item[ 'id' ] );

				// WOWPokemonDebug::debug( [
				// 	'GutenbergPokemon' => 'get_pokemon_type_json-3',

				// 	'pokemon_item' => $pokemon_item,
		
				// 	'pokemon' => $pokemon,
				// ] );

				// $pokemon_item[ 'pokemon' ] = $pokemon;

				// $types[] = $pokemon_item;
				
				$types[] = self::parse_pokemon_type( $item );
			}
		}

		// WOWPokemonDebug::debug( [
		// 	'GutenbergPokemon' => 'get_pokemon_type_json-4',

		// 	'types' => $types,
		// ] );

		return $types;
	}

	private static function parse_pokemon( array $item ) : string
	{
		// WOWPokemonDebug::debug( [
		// 	'GutenbergPokemon' => 'parse_pokemon-1',

		// 	'item' => $item,
		// ] );

		// return [
		// 	'name' => $item[ 'pokemon' ][ 'name' ],
		// ];
		
		return $item[ 'pokemon' ][ 'name' ];
	}
	
	private static function get_pokemon( $type ) : array
	{
		$pokemons = [];

		$json = json_decode( self::get_pokemon_response( $type ), JSON_OBJECT_AS_ARRAY );
		
		if ( ! empty( $json[ 'pokemon' ] ) )
		{
			foreach ( $json[ 'pokemon' ] as $item )
			{
				$pokemons[] = self::parse_pokemon( $item );
			}
		}

		return $pokemons;
	}

	private static function get_pokemon_type_response() : string
	{
		return ToolCurl::get_response( 'https://pokeapi.co/api/v2/type/' );
	}

	private static function get_pokemon_response( int $type ) : string
	{
		// WOWPokemonDebug::debug( [
		// 	'GutenbergPokemon' => 'get_pokemon_response-1',

		// 	'type' => $type,

		// 	'sprintf' => sprintf( 'https://pokeapi.co/api/v2/type/%d/', $type ),
		// ] );

		return ToolCurl::get_response( sprintf( 'https://pokeapi.co/api/v2/type/%d/', $type ) );
	}

	const NONCE = 'wow-pokemon-get';

	/* 
	 * Ajax buffer for frontend that makes curl request on API
	 * 
	 * example args: /wp-admin/admin-ajax.php?action=wow-get-pokemon&type=1
     */

	public static function ajax_get_pokemon() : void
	{
		// check_ajax_referer( self::NONCE );

		$code = 0;

		$status = 'success';
		
		$data = '';

		// if ( ! empty( $_GET[ self::PAREMETERS[ 'type' ] ] ) && is_numeric( $_GET[ self::PAREMETERS[ 'type' ] ] ) )
		// {
		// 	$data = self::get_pokemon( $_GET[ self::PAREMETERS[ 'type' ] ] );
		// }

		// WOWPokemonDebug::debug( [
		// 	'GutenbergPokemon' => 'ajax_get_pokemon-1',

		// 	'type' => $_POST[ self::PAREMETERS[ 'type' ] ],

		// 	'is_numeric' => is_numeric( $_POST[ self::PAREMETERS[ 'type' ] ] ),
		// ] );

		if ( ! empty( $_POST[ self::PAREMETERS[ 'type' ] ] ) && is_numeric( $_POST[ self::PAREMETERS[ 'type' ] ] ) )
		{
			$data = self::get_pokemon( $_POST[ self::PAREMETERS[ 'type' ] ] );
		}

		echo json_encode( [
			'code' => $code,
			
			'status' => $status,
			
			'data' => $data,
		] );
		
		die();
	}
}

?>