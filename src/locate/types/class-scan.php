<?php

namespace Hybrid\Carbon\Locate\Types;

use Hybrid\Carbon\Image\Attachment;
use Hybrid\Carbon\Image\Image;
use function Hybrid\Carbon\is_image_attachment;

class Scan extends Base {

	protected $blocks = [
		'image',
		'cover-image'
	];

	private $matched_sources = [];

	/**
	 * Scans the post content for an image.  It first scans and checks for an image with the
	 * "wp-image-xxx" ID.  If that exists, it'll grab the actual image attachment.  If not, it looks
	 * for the image source.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function make() {

		$image_ids = [];

		// Get the post content.
		$post_content = get_post_field( 'post_content', $this->args['post_id'] );

		$methods = [
			'scanBlocks',
			'scanImgs'
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

				if ( 0 < $attachment_id && is_image_attachment( $attachment_id ) ) {

					$image = new Attachment( $attachment_id, $this->args );
				}

				if ( $image && $this->validate( $image ) ) {
					return $image;
				}
			}
		}

		if ( $this->matched_sources ) {

			foreach ( $this->matched_sources as $src ) {

				$image = new Image( $src + $this->args );

				if ( $image && $this->validate( $image ) ) {
					return $image;
				}
			}
		}

		return parent::make();
	}

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

	protected function scanImgs( $post_content ) {

		$image_ids = [];

		preg_match_all( '/<img\s(.+?)\/>/i', $post_content, $matches, PREG_PATTERN_ORDER );

		if ( ! empty( $matches[1] ) && is_array( $matches[1] ) ) {

			foreach ( $matches[1] as $attr_string ) {

				$atts = wp_kses_hair( $attr_string, [ 'https', 'http' ] );

				if ( isset( $atts['data-id'] ) ) {

					$image_ids[] = absint( $atts['data-id']['value'] );

				} elseif ( isset( $atts['id'] ) && false !== strpos( $atts['id']['value'], 'wp-image-' ) ) {

					$image_ids[] = absint( 'wp-image-', '', $atts['id']['value'] );

				} elseif ( isset( $atts['src'] ) ) {

					$img = [ 'src' => $atts['src']['value'] ];

					$_atts = [ 'alt', 'width', 'height' ];

					foreach ( $_atts as $_a ) {

						if ( isset( $atts[ $_a ] ) ) {
							$img[ $_a ] = $atts[ $_a ]['value'];
						}
					}

					$this->matched_sources[] = $img;
				}
			}
		}

		return $image_ids;
	}
}
