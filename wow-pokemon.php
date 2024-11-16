<?php
/**
 * @link              https://wowvendor.com/
 * @since             1.0.0
 * @package           wow-pokemon
 *
 * @wordpress-plugin
 * Plugin Name:       Wowvendor Pokemon Gutenberg
 * Description:       Wowvendor module for Pokemons in Gutenberg.
 * Version:           1.0.0
 * Author:            Wowvendor
 * Author URI:        https://wowvendor.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wow-pokemon
 * Domain Path:       /languages
 */

namespace wowpokemon;

if ( ! defined( 'WPINC' ) )
{
	die;
}

require_once('lib/WOWPokemonMain.php');

use \wowpokemon\lib\WOWPokemonMain;

define( 'WOW_POKEMON_VERSION', '1.0.0' );

define( 'WOW_POKEMON_PATH', plugin_dir_path( __FILE__ ) );

define( 'WOW_POKEMON_URL', plugin_dir_url( __FILE__ ) );

define( 'WOW_POKEMON_FILE', plugin_basename(__FILE__) );

class WOWPokemon {
	const VERSION = \WOW_POKEMON_VERSION;

	const PATH = \WOW_POKEMON_PATH;
	
	const URL = \WOW_POKEMON_URL;

	const FILE = \WOW_POKEMON_FILE;

	public static function register()
	{
		register_activation_hook( __FILE__, '__return_true' );

		WOWPokemonMain::register();
	}
}

WOWPokemon::register();

?>