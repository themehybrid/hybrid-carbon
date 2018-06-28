<?php

namespace Hybrid\Carbon\Methods;

use function Hybrid\Carbon\has_min_dimension;

class Base {

	protected $args = [];

	public $attachment_id = 0;

	public $caption = '';

	public $attr = [];

	public $classes = [];

	public $srcsets = [];

	protected $html = '';

	public function __construct( $args ) {

		$this->args = $args;

		$this->make();
	}

	// overwrite in sub-classes.
	public function make() {}

	public function render() {

		echo $this->fetch();
	}

	public function fetch() {

		return $this->toHtml();
	}

	public function hasImage() {

		return isset( $this->attr['src'] ) || $this->html;
	}

	public function toHtml() {

		if ( $this->html ) {
			return $this->html;
		}

		if ( ! $this->hasImage() ) {
			return '';
		}

		if ( isset( $this->attr['width'] ) && ! has_min_dimension( $this->attr['width'], $this->args['min_width'] ) ) {

			return '';

		} elseif ( isset( $this->attr['height'] ) && ! has_min_dimension( $this->attr['height'], $this->args['min_height'] ) ) {

			return '';
		}

		$esc_attr = $url = '';

		foreach ( $this->attr as $name => $value ) {

			$esc_attr .= false !== $value
			             ? sprintf( ' %s="%s"', esc_html( $name ), esc_attr( $value ) )
				     : esc_html( " {$name}" );
		}

		$this->html = sprintf( '<img %s />', $esc_attr );

		// If $link is set to true, link the image to its post.
		if ( false !== $this->args['link'] ) {

			if ( 'post' === $this->args['link'] || true === $this->args['link'] ) {

				$url = get_permalink( $this->args['post_id'] );

			} elseif ( 'file' === $this->args['link'] ) {

				$url = $this->image_args['src'];

			} elseif ( 'attachment' === $this->args['link'] && isset( $this->image_args['id'] ) ) {

				$url = get_permalink( $this->image_args['id'] );
			}

			if ( $url ) {

				$link_class = $this->args['link_class'] ? sprintf( ' class="%s"', esc_attr( $this->args['link_class'] ) ) : '';

				$this->html = sprintf( '<a href="%s"%s>%s</a>', esc_url( $url ), $link_class, $this->html );
			}
		}

			// If there is a $post_thumbnail_id, apply the WP filters normally associated with get_the_post_thumbnail().
		//	if ( ! empty( $this->image_args['post_thumbnail_id'] ) )
		//		$html = apply_filters( 'post_thumbnail_html', $html, $this->args['post_id'], $this->image_args['post_thumbnail_id'], $this->args['size'], '' );

		// If we're showing a caption.
		if ( true === $this->args['caption'] && $this->caption && isset( $this->attr['width'] ) ) {

			$this->html = img_caption_shortcode( [
				'caption' => $this->caption,
				'width'   => $this->attr['width']
			], $this->html );
		}

		return $this->html;
	}

	public function attr() {

		$attr = [];

		// Add the class attribute.
		$attr['class'] = join( ' ', $this->class() );

		// If there is a width or height, set them.
		//if ( $this->args['width'] ) {

		//	$this->attr['width'] = $this->args['width'];
		//}

		//if ( $this->args['height'] ) {

		//	$this->attr['height'] = $this->args['height'];
		//}

		// If there is alt text, set it.  Otherwise, default to the post title.
		if ( ! isset( $this->attr['alt'] ) ) {

			$this->attr['alt'] = '';
		}

		// Add the itemprop attribute.
		$this->attr['itemprop'] = 'image';

		// Add the srcset attribute.
		//if ( ! isset( $this->attr['srcset'] ) && $this->srcsets ) {

		//}

		// Parse the args with the user inputted args.
		$this->attr = wp_parse_args( $this->args['image_attr'], $this->attr );

		$this->attr = apply_filters( 'hybrid/postimage/attr', $attr );

		return $this->attr;

	}

	protected function class() {
		global $content_width;

		$classes = [];

		// Get image height and width.
		$width  = isset( $this->attr['width'] )  ? $this->attr['width']  : false;
		$height = isset( $this->attr['height'] ) ? $this->attr['height'] : false;

		// If there's a width/height for the image.
		if ( $width && $height ) {

			// Set a class based on the orientation.
			$classes[] = $height > $width ? 'portrait' : 'landscape';

			// Set class based on the content width (defined by theme).
			if ( 0 < $content_width ) {

				if ( $content_width == $width ) {

					$classes[] = 'cw-equal';

				} elseif ( $content_width <= $width ) {

					$classes[] = 'cw-lesser';

				} elseif ( $content_width >= $width ) {

					$classes[] = 'cw-greater';
				}
			}
		}

		// Add the meta key(s) to the classes array.
		if ( ! empty( $this->args['meta_key'] ) )
			$classes = array_merge( $classes, (array)$this->args['meta_key'] );

		// Add the $size to the class.
		$classes[] = $this->args['size'];

		// Get the custom image class.
		if ( $this->args['image_class'] ) {

			if ( ! is_array( $this->args['image_class'] ) ) {

				$this->args['image_class'] = preg_split( '#\s+#', $this->args['image_class'] );
			}

			$classes = array_merge( $classes, $this->args['image_class'] );
		}

		return apply_filters( 'get_the_image_class', $this->sanitize_class( $classes ), $this );
	}

	/**
	 * Handles an image attachment.  Other methods rely on this method for
	 * getting the image data since most images are actually attachments.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int    $attachment_id
	 * @return void
	 */
	protected function attachment( $attachment_id ) {

		// Get the attachment image.
		$image = wp_get_attachment_image_src( $attachment_id, $this->args['size'] );

		// Get the attachment alt text.
		$alt = trim( strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );

		// Get the attachment caption.
		$caption = get_post_field( 'post_excerpt', $attachment_id );

		// Only use the image if we have an image and it meets the size requirements.
		if (
			! $image
			|| ! has_min_dimension( $image[1], $this->args['min_width'] )
			|| ! has_min_dimension( $image[2], $this->args['min_height'] )
		) {
			return;

		}

		// Save the attachment as the 'featured image'.
		if ( true === $this->args['thumbnail_id_save'] ) {
			$this->saveThumbnailId( $attachment_id );
		}

		// Set the image args.
		$this->attachment_id = $attachment_id;
		$this->caption       = $caption;

		$this->attr = [
			'src'    => $image[0],
			'width'  => $image[1],
			'height' => $image[2],
			'alt'    => $image[3]
		];

		// Get the image srcset sizes.
		$this->srcset( $attachment_id );
	}

	/**
	 * Adds array of srcset image sources and descriptors based on the
	 * `srcset_sizes` argument provided by the developer.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int     $attachment_id
	 * @return void
	 */
	public function srcset( $attachment_id ) {

		// Bail if no sizes set.
		if ( ! $this->args['srcset_sizes'] ) {
			return;
		}

		foreach ( $this->args['srcset_sizes'] as $size => $descriptor ) {

			$image = wp_get_attachment_image_src( $attachment_id, $size );

			// Make sure image doesn't match the image used for the
			// `src` attribute. This will happen often if the
			// particular image size doesn't exist.
			if ( $this->image_args['src'] !== $image[0] ) {

				$this->srcsets[ $descriptor ] = $image[0];

				//$this->srcsets[] = sprintf( "%s %s", esc_url( $image[0] ), esc_attr( $descriptor ) );
			}
		}
	}

	/**
	 * Saves the image attachment as the WordPress featured image.  This is useful for setting the
	 * featured image for the post in the case that the user forgot to (win for client work!).  It
	 * should not be used in publicly-distributed themes where you don't know how the user will be
	 * setting up their site.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	protected function saveThumbnailId( $attachment_id ) {

		// Save the attachment as the 'featured image'.
		if ( true === $this->args['thumbnail_id_save'] ) {

			set_post_thumbnail( $this->args['post_id'], $attachment_id );
		}
	}






}
