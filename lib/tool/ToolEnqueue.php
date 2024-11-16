<?php

namespace wowpokemon\lib\tool;

use \wowpokemon\lib\WOWPokemonMain;

/**
 * 
 * Enqueue styles and scripts tool
 * 
 */

class ToolEnqueue
{
    /**
     * 
     * Register and enqueu style
     * 
     */

    public static function register_style( $styles = [] ) : void
    {
        foreach ( $styles as $name => $item ) {
            $path = $item;

            $ver = false;

            $deps = [];

            if ( is_array( $item ) ) {
                $path = $item[ 'path' ];

                $ver = $item[ 'ver' ];

                if ( !empty( $item[ 'deps' ] ) )
                {
                    $deps = $item[ 'deps' ];
                }
            }

            wp_enqueue_style( $name, $path, $deps, $ver );
        }
    }

    /**
     * 
     * Register and enqueu script
     * 
     */

    public static function register_script( $scripts = [] ) : void
    {
        foreach ( $scripts as $name => $item ) {
            $path = $item;

            $ver = false;

            $deps = [];

            $args = true;

            if ( is_array( $item ) ) {
                $path = $item[ 'path' ];

                $ver = $item[ 'ver' ];

                if ( ! empty( $item[ 'deps' ] ) )
                {
                    $deps = $item[ 'deps' ];
                }
            }

            wp_register_script( $name, $path, $deps, $ver, $args, false );

            wp_enqueue_script( $name );
        }
    }

    /**
     * 
     * Localize data for registered script
     * 
     */

    public static function localize_script( $scripts = [] ) : void
    {
        foreach ( $scripts as $handle => $item )
        {
            wp_localize_script( $handle, $item[ 'object_name' ], $item[ 'data' ] );
        }
    }
}

?>