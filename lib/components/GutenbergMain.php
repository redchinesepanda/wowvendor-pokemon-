<?php

namespace wowpokemon\lib\components;

require_once( 'GutenbergPokemon.php' );

use \wowpokemon\lib\components\GutenbergPokemon;

class GutenbergMain
{
	public static function register()
    {
	    GutenbergPokemon::register();
    }

	public static function register_functions()
    {
	    GutenbergPokemon::register_functions();
    }
}

?>