<?php

namespace wowpokemon\lib;

require_once( 'WOWPokemonDebug.php' );

require_once( 'components/GutenbergMain.php' );

require_once( 'tool/ToolMain.php' );

use \wowpokemon\lib\WOWPokemonDebug;

use \wowpokemon\lib\components\GutenbergMain;

use \wowpokemon\lib\tool\ToolMain;

class WOWPokemonMain
{
	/**
	 * 
	 * Register frontend componenst on Wordpress hook 'wp' and register functions all or wp-admin only
	 * 
	 */
	
	public static function register() : void
	{
		$handler = new self();
		
		add_action( 'wp', [ $handler, 'register_components' ] );

		self::register_functions();
	}

	/**
     * 
     * Register functions all or wp-admin only
     * 
     */

	public static function register_functions() : void
    {
		GutenbergMain::register_functions();

		if ( self::check_admin() )
		{
		}
	}

	/**
     * 
     * Register frontend componenst
     * 
     * @return bool
	 */

	public static function register_components() : void
	{
	}

	/**
     * 
     * Check if current context is admin
     * 
     * @return bool
	 */

	public static function check_admin() : bool
	{
		return is_admin();
	}
}

?>