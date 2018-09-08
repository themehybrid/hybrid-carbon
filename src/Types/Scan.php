<?php
/**
 * Scan location type class.
 *
 * Scans the post content for image attachment IDs.
 *
 * @package   HybridCarbon
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2018, Justin Tadlock
 * @link      https://github.com/justintadlock/hybrid-carbon
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Carbon\Types;

use Hybrid\Carbon\Image\Attachment;
use Hybrid\Carbon\Util\Helpers;

/**
 * Scan location class.
 *
 * @since  1.0.0
 * @access public
 */
class Scan extends Base {

	/**
	 * Array of WP 5.0+ (Gutenberg) block names to search for an image ID
	 * from with its JSON string.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array
	 */
	protected $blocks = [
		'image',
		'cover-image'
	];

	 /**
 	 * Returns an `Image` object or `false` if no image is found.
 	 *
 	 * @since  1.0.0
 	 * @access protected
 	 * @param  array      $args
 	 * @return Image|bool
 	 */
	public function make() {

		$image_ids = [];

		// Get the post content.
		$post_content = get_post_field( 'post_content', $this->manager->option( 'post_id' ) );

		$methods = [
			'scanBlocks',
			'scanDataIds',
			'scanIds'
		];

		foreach ( $methods as $method ) {

			$image_ids = $this->$method( $post_content );

			if ( $image_ids ) {
				break;
			}
		}

		// Loop through any found image IDs.
		if ( is_array( $image_ids ) ) {

			foreach ( $image_ids as $attachment_id ) {

				$image = '';

				if ( 0 < $attachment_id && Helpers::isImageAttachment( $attachment_id ) ) {

					$image = new Attachment( $this->manager, [
						'attachment_id' => $attachment_id
					] );
				}

				if ( $image && $this->validate( $image ) ) {
					return $image;
				}
			}
		}

		return false;
	}

	/**
	 * Searches for Gutenberg blocks.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @param  string    $post_content
	 * @return array
	 */
	protected function scanBlocks( $post_content ) {

		$this->blocks = apply_filters( 'hybrid/carbon/scan/blocks', $this->blocks );
		$image_ids    = [];

		// Block pattern.
		$block_string = str_replace(
			'core/',
			'(?:core/)?',
			implode( '|', array_map( 'preg_quote', $this->blocks ) )
		);

		$block_string = str_replace( '/', '\/', $block_string );

		$pattern = '/<!--\s+wp:(' . $block_string . ')(\s+(\{.*?\}))?\s+(\/)?-->/';

		// Check for image block patterns.
		preg_match_all( $pattern, $post_content, $block_matches );

		if ( ! empty( $block_matches[3] ) ) {

			foreach ( (array) $block_matches[3] as $json ) {

				$decoded = json_decode( $json, true );

				if ( $decoded && isset( $decoded['id'] ) ) {
					$image_ids[] = absint( $decoded['id'] );
				}
			}
		}

		return $image_ids;
	}

	/**
	 * Searches for `<img>` elements with `data-id` attributes. This is the
	 * method used for Gutenberg gallery images.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @param  string    $post_content
	 * @return array
	 */
	protected function scanDataIds( $post_content ) {

		preg_match( '/<img.*?data-id=[\'"]([\d]*)[\'"]/i', $post_content, $image_ids );

		return $image_ids;
	}

	/**
	 * Searches for `<img>` elements with an ID of `wp-image-{$id}`.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @param  string    $post_content
	 * @return array
	 */
	protected function scanIds( $post_content ) {

		preg_match( '/id=[\'"]wp-image-([\d]*)[\'"]/i', $post_content, $image_ids );

		return $image_ids;
	}
}
