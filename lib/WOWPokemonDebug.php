<?php

namespace wowpokemon\lib;

class WOWPokemonDebug
{
    /**
     *
     * Check if current user can see debug information
     *  
     * @return boolean
     */

    public static function check() : bool
    {
        $permission = false;

        $current_user = wp_get_current_user();

        if ( $current_user->exists() )
        {
            if ( $current_user->user_login == 'developmentadmin' )
            {
                $permission = true;
            }
        }

        return $permission;
    }

    /**
     * 
     * Outputs debug information
     * 
     */

    public static function debug( $message ) : void
    {
        if ( self::check() )
        {
            echo ( '<pre>' . __CLASS__ . '::debug: ' . print_r( $message, true ) . '</pre>' . PHP_EOL );
        }
    }

    /**
     * 
     * Outputs debug information and stops the scenario
     * 
     */

    public static function die( $message ) : void
    {
        if ( self::check() )
        {
            wp_die ( '<pre>' . __CLASS__ . '::debug: ' . print_r( $message, true ) . '</pre>' . PHP_EOL );
        }
    }
}

?>