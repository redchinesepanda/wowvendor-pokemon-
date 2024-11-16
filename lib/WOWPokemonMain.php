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
	public static function register()
	{
		$handler = new self();
		
		add_action( 'wp', [ $handler, 'register_components' ] );

		self::register_functions();
	}

	public static function register_functions()
    {
		ToolMain::register_functions();

		GutenbergMain::register_functions();

		if ( self::check_admin() )
		{
			ToolMain::register_functions_admin();
		}
	}

	public static function register_components()
	{
		GutenbergMain::register();
	}

	public static function check_admin()
	{
		return is_admin();
	}
}

?>