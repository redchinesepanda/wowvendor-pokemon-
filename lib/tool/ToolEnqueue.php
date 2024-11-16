<?php

namespace wowpokemon\lib\tool;

use \wowpokemon\lib\WOWPokemonMain;

class ToolEnqueue
{
    public static function add_editor_styles( $styles = [] )
    {
		foreach ( $styles as $style )
        {
			add_editor_style( $style );
		}
	}

    public static function dequeue_script( $scripts = [] )
    {
        foreach ( $scripts as $name )
        {
            wp_dequeue_script( $name );
        }
    }

    public static function dequeue_style( $styles = [] )
    {
        foreach ( $styles as $name )
        {
            wp_dequeue_style( $name );
        }
    }

    public static function register_style( $styles = [] )
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

    const ARGS_SCRIPT = [
        'in_footer' => false,

        'strategy'  => 'defer',
    ];

    public static function register_script( $scripts = [] )
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
            
            // wp_register_script( $name, $path, $deps, $ver, $args, self::ARGS_SCRIPT );

            wp_enqueue_script( $name );
        }
    }

    public static function localize_script( $scripts = [] )
    {
        foreach ( $scripts as $handle => $item )
        {
            wp_localize_script( $handle, $item[ 'object_name' ], $item[ 'data' ] );
        }
    }

	public static function register_inline_style( $name, $data )
    {
		wp_register_style( $name, false, [], true, true );
		
		wp_add_inline_style( $name, $data );
		
		wp_enqueue_style( $name );
    }

	public static function register_inline_base( $name )
    {
		wp_register_style( $name, false, [], true, true );
    }

	public static function enqueue_inline_style( $name, $data )
    {
		wp_add_inline_style( $name, $data );
		
		wp_enqueue_style( $name );
    }

	public static function register_inline_script( $name, $data )
    {
        wp_register_script( $name, false, [], true, true );
        
        wp_localize_script( $name, str_replace( '-', '_', $name ), $data );
        
        wp_enqueue_script( $name );
    }

    public static function register()
    {
        // $handler = new self();

		// add_filter( 'style_loader_tag', [ $handler, 'link_type' ], 10, 2 );

        // add_filter( 'script_loader_tag', [ $handler, 'script_type' ], 10, 2 );

        // // add_action( 'wp_print_scripts', [ $handler, 'inspect_scripts' ], 999);

        // add_filter( 'script_loader_tag', [ $handler, 'legal_script_defer' ], 10, 2 );
    }

    // public static function legal_script_defer( $tag, $handle )
    // {
    //     if ( WOWPokemonMain::check_admin() )
    //     {
    //         return $tag;
    //     }

    //     return str_replace( ' src=', ' defer="defer" src=', $tag );
    // }

    // public static function inspect_scripts()
    // {
    //     global $wp_scripts;

    //     LegalDebug::debug( [
    //         'function' => 'ToolEnqueue::inspect_scripts',

    //         'queue' => $wp_scripts->queue,
    //     ] );
        
    // }

    // public static function crunchify_print_scripts_styles()
    // {
    //     $result = [];

    //     $result['scripts'] = [];

    //     $result['styles'] = [];
    
    //     // Print all loaded Scripts
    //     global $wp_scripts;

    //     foreach( $wp_scripts->queue as $script ) :
    //        $result['scripts'][ $script ] =  $wp_scripts->registered[$script]->src . ";";
    //     endforeach;
    
    //     // Print all loaded Styles (CSS)

    //     global $wp_styles;

    //     foreach( $wp_styles->queue as $style ) :
    //        $result['styles'][ $style ] =  $wp_styles->registered[$style]->src . ";";
    //     endforeach;
    
    //     return $result;
    // }

    // public static function link_type( $html, $handle )
	// {
    //     $html = str_replace(
	// 		" type='text/css'",

	// 		"",

	// 		$html
	// 	);

	// 	$html = str_replace(
	// 		" />",

	// 		">",

	// 		$html
	// 	);

	// 	return $html;
	// }

    // function script_type( $tag, $handle, $src = 'unset' )
    // {
    //     $tag = str_replace(
	// 		" type='text/javascript'",

	// 		"",

	// 		$tag
	// 	);
                
    //     return $tag;
    // }
}

?>