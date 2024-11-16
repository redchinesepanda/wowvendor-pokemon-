let WOWPokemonGutenbergFrontend = ( function()
{
	"use strict";

    return {
        ajaxGetPokemon: function ( data )
		{
			let type = data.type;

			const xhr = new XMLHttpRequest();
			
			xhr.onreadystatechange = function()
			{
				if ( xhr.readyState === xhr.DONE && xhr.status === 200 )
				{
					try
					{
						let parsed = JSON.parse( this.responseText );

						let gutenbergNotice = document.querySelector( '.wp-block-wowvendor-gutenberg-gutenberg-notice-block' );
		
						if ( gutenbergNotice != null )
						{
							let gutenbergNoticeFooter = document.createElement( 'p' );

							gutenbergNoticeFooter.innerHTML = parsed.data.join( ', ' );

							gutenbergNoticeFooter.setAttribute( 'class', 'gutenberg-notice-footer' );

							gutenbergNotice.appendChild( gutenbergNoticeFooter );
						}
					}
					catch ( error )
					{
						console.error( error );
					}
				}
			}

			xhr.open( "POST", WOWPokemonAjaxGeneral.ajaxUrl );

			xhr.setRequestHeader( "Content-type", "application/x-www-form-urlencoded" );
			
			xhr.send( "action=" + WOWPokemonAjaxSettings.getPokemon
				
				+ "&" + WOWPokemonAjaxSettings.type + "=" + type
				
				+ "&_ajax_nonce=" + WOWPokemonAjaxSettings.nonce
			);
		}
    }
} )();
document.addEventListener( 'DOMContentLoaded', function ()
{
	function getPokemon( notice )
	{
		WOWPokemonGutenbergFrontend.ajaxGetPokemon( {
			type : notice.getAttribute( 'type' ),
		} );
	}

	const selectors = {
		gutenbergNotice: '.wp-block-wowvendor-gutenberg-gutenberg-notice-block',
	};

	document.querySelectorAll( selectors.gutenbergNotice ).forEach( getPokemon );
} );