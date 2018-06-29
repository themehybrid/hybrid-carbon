<?php

namespace Hybrid\Carbon\Locate\Types;

class ScanRaw {

	/**
	 * Scans the post content for a complete image.  This method will attempt to grab the complete
	 * HTML for an image.  If an image is found, pretty much all arguments passed in may be ignored
	 * in favor of getting the actual image used in the post content.  It works with both captions
	 * and linked images.  However, it can't account for all possible HTML wrappers for images used
	 * in all setups.
	 *
	 * This method was created for use with the WordPress "image" post format where theme authors
	 * might want to pull the whole image from the content as the user added it.  It's also meant
	 * to be used (not required) with the `split_content` option.
	 *
	 * Note: This option should not be used if returning the image as an array.  If that's desired,
	 * use the `scan` option instead.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function get_scan_raw_image() {

		// Get the post content.
		$post_content = get_post_field( 'post_content', $this->args['post_id'] );

		// Apply filters to content.
		$post_content = apply_filters( 'get_the_image_post_content', $post_content );

		// Finds matches for shortcodes in the content.
		preg_match_all( '/' . get_shortcode_regex() . '/s', $post_content, $matches, PREG_SET_ORDER );

		if ( !empty( $matches ) ) {

			foreach ( $matches as $shortcode ) {

				if ( in_array( $shortcode[2], array( 'caption', 'wp_caption' ) ) ) {

					preg_match( '#id=[\'"]attachment_([\d]*)[\'"]|class=[\'"].*?wp-image-([\d]*).*?[\'"]#i', $shortcode[0], $matches );

					if ( !empty( $matches ) && isset( $matches[1] ) || isset( $matches[2] ) ) {

						$attachment_id = !empty( $matches[1] ) ? absint( $matches[1] ) : absint( $matches[2] );

						$image_src = wp_get_attachment_image_src( $attachment_id, $this->args['size'] );

						if ( !empty( $image_src ) ) {

							// Old-style captions.
							if ( preg_match( '#.*?[\s]caption=[\'"](.+?)[\'"]#i', $shortcode[0], $caption_matches ) )
								$image_caption = trim( $caption_matches[1] );

							$caption_args = array(
								'width'   => $image_src[1],
								'align'   => 'center'
							);

							if ( !empty( $image_caption ) )
								$caption_args['caption'] = $image_caption;

							// Set up the patterns for the 'src', 'width', and 'height' attributes.
							$patterns = array(
								'/(src=[\'"]).+?([\'"])/i',
								'/(width=[\'"]).+?([\'"])/i',
								'/(height=[\'"]).+?([\'"])/i',
							);

							// Set up the replacements for the 'src', 'width', and 'height' attributes.
							$replacements = array(
								'${1}' . $image_src[0] . '${2}',
								'${1}' . $image_src[1] . '${2}',
								'${1}' . $image_src[2] . '${2}',
							);

							// Filter the image attributes.
							$shortcode_content = preg_replace( $patterns, $replacements, $shortcode[5] );

							$this->image          = img_caption_shortcode( $caption_args, $shortcode_content );
							$this->original_image = $shortcode[0];
							return;
						}
						else {
							$this->image          = do_shortcode( $shortcode[0] );
							$this->original_image = $shortcode[0];
							return;
						}
					}
				}
			}
		}

		// Pull a raw HTML image + link if it exists.
		if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)#is', $post_content, $matches ) )
			$this->image = $this->original_image = $matches[0];
	}
}
