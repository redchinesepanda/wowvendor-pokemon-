<?php

namespace wowpokemon\lib\components;

use \wowpokemon\WOWPokemon;

use \wowpokemon\lib\WOWPokemonDebug;

use \wowpokemon\lib\tool\ToolEnqueue;

use \wowpokemon\lib\tool\ToolCurl;

/**
 * 
 * Class adds Gutenberg block, style in admin and front, scripts and ajax backend 
 * 
 */

class GutenbergPokemon
{
	/**
	 * 
	 * Frontend CSS file list
	 * 
	 */

	const CSS = [
        'gutenberg-block-pokemon' => [
            'path' => WOWPokemon::URL . '/assets/css/components/gutenberg-notice.css',

            'ver'=> '1.0.0',
        ],
    ];

	/**
	 * 
	 * Register frontend and admin style
	 * 
	 */

	public static function register_style() : void
    {
		ToolEnqueue::register_style( self::CSS );
    }

	/**
	 * 
	 * Admin JS file list
	 * 
	 */

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

	/**
	 * 
	 * Frontend JS file list
	 * 
	 */

	const JS_FRONTEND = [
        'gutenberg-block-notice-frontend' => [
			'path' => WOWPokemon::URL . '/assets/js/components/gutenberg-pokemon-frontend.js',

			'ver' => '1.0.1',
		],
    ];

	/**
	 * 
	 * Register admin scripts and localize data
	 * 
	 */

	public static function register_script() : void
    {
		ToolEnqueue::register_script( self::JS_ADMIN );

		ToolEnqueue::localize_script( self::get_localize_pokemon_type() );
    }

	/**
	 * 
	 * Returns localized pokemon types for admin
	 * 
	 * @return array
	 * 
	 */

	private static function get_localize_pokemon_type() : array
	{
		return [
			'gutenberg-block-notice' => [
				'object_name' => 'gutenbergBlockNotice',
	
				'data' => self::get_pokemon_type(),
			],
		];
	}

	/**
	 * 
	 * Register frontend scripts and localize data
	 * 
	 */

	public static function register_script_ajax() : void
    {
		ToolEnqueue::register_script( self::JS_FRONTEND );

		ToolEnqueue::localize_script( self::get_localize_ajax_general() );

		ToolEnqueue::localize_script( self::get_localize_ajax_pokemon_settings() );
    }

	/**
	 * 
	 * Returns localized ajax url for frontend
	 * 
	 * @return array
	 * 
	 */

	private static function get_localize_ajax_general() : array
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

	/**
	 * 
	 * Returns localized ajax request settings for frontend
	 * 
	 * @return array
	 * 
	 */

	private static function get_localize_ajax_pokemon_settings() : array
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

	/**
	 * 
	 * Register admin scripts and styles on WordPress 'enqueue_block_editor_assets' hook
	 * Register frontend scripts and styles on WordPress 'wp_enqueue_scripts' hook
	 * Register ajax action to retreive pokemons of specified type
	 * 
	 */

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

	/**
	 * 
	 * Parse id from given url by regexp
	 * 
	 * @param string $url commonly is API path
	 * 
	 * @return int
	 */

	private static function parse_id( string $url ) : int
	{
		$matches = [];

		if ( preg_match( "/type\/(\d+)\/$/", $url, $matches ) )
		{
			return $matches[ 1 ];
		}

		return 0;
	}

	/**
	 * 
	 * Parse pokemon type from given json decoded response item
	 * 
	 * @param array $url is decoded array item of json with 'name' and 'url' items
	 * 
	 * @return array
	 */

	private static function parse_pokemon_type( array $item ) : array
	{
		return [
			'name' => $item[ 'name' ],

			'id' => self::parse_id( $item[ 'url' ] ),
		];
	}

	/**
	 * 
	 * Get all pokemon avaible types response from API path
	 * 
	 */

	private static function get_pokemon_type_response() : string
	{
		return ToolCurl::get_response( 'https://pokeapi.co/api/v2/type/' );
	}
	
	/**
	 * 
	 * Parse pokemon type from curl response obtained from API path
	 * 
	 * @return array
	 */

	private static function get_pokemon_type() : array
	{
		$types = [];

		$json = json_decode( self::get_pokemon_type_response(), JSON_OBJECT_AS_ARRAY );

		if ( ! empty( $json[ 'results' ] ) )
		{
			foreach ( $json[ 'results' ] as $item )
			{
				$types[] = self::parse_pokemon_type( $item );
			}
		}

		return $types;
	}

	/**
	 * 
	 * Parse pokemon type from curl response obtained from API path
	 * 
	 * @param array $item is json decoded array item  with 'pokemon' and 'name' items
	 * 
	 * @return string
	 */

	private static function parse_pokemon( array $item ) : string
	{
		return $item[ 'pokemon' ][ 'name' ];
	}

	/**
	 * 
	 * Get all pokemons of specified type
	 * 
	 * @param int $type is numeric type of pokemon in API
	 * 
	 * @return array
	 */
	
	private static function get_pokemon( int $type ) : array
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

	/**
	 * 
	 * Get all avaible pokemon of specified type response from API path
	 * 
	 */

	private static function get_pokemon_response( int $type ) : string
	{
		return ToolCurl::get_response( sprintf( 'https://pokeapi.co/api/v2/type/%d/', $type ) );
	}

	const NONCE = 'wow-pokemon-get';

	/**
	 * 
	 * Ajax buffer for frontend that makes curl request on API
	 * example args: /wp-admin/admin-ajax.php?action=wow-get-pokemon&type=1
	 * 
     */

	public static function ajax_get_pokemon() : void
	{
		$code = 0;

		$status = 'success';
		
		$data = '';

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