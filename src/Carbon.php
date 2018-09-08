<?php
/**
 * The core project class.
 *
 * Carbon is a highly-intuitive image script that displays post-specific images
 * (an image-based representation of a post). This class pulls everything
 * together and loops through the various methods for finding an image.
 *
 * @package   HybridCarbon
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2018, Justin Tadlock
 * @link      https://github.com/justintadlock/hybrid-carbon
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Carbon;

use Hybrid\Carbon\Contracts\Image;
use Hybrid\Carbon\Contracts\ImageGrabber;
use Hybrid\Carbon\Contracts\Type;
use Hybrid\Carbon\Types\Attached;
use Hybrid\Carbon\Types\Featured;
use Hybrid\Carbon\Types\Meta;
use Hybrid\Carbon\Types\Scan;

/**
 * Carbon core class.
 *
 * @since  1.0.0
 * @access public
 */
class Carbon implements ImageGrabber {

	/**
	 * The methods used to locate an image.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array
	 */
	protected $type = [];

	/**
	 * Array of registered types. Key is the type name and value is the class
	 * to be called.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array
	 */
	protected $registered_types = [];

	/**
	 * Array of arguments passed in.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array
	 */
	protected $args = [];

	/**
	 * Image object.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    Image
	 */
	protected $image = null;

	/**
	 * Creates a new Carbon object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array|string  $type
	 * @param  array         $args
	 * @return void
	 */
	public function __construct( $type = [], array $args = [] ) {

		$this->args = wp_parse_args( $args, [
			'post_id'           => get_the_ID(),
			'meta_key'          => [ 'thumbnail', 'Thumbnail' ],
			'size'              => has_image_size( 'post-thumbnail' ) ? 'post-thumbnail' : 'thumbnail',
			'link'              => false,
			'link_class'        => 'entry__image-link',
			'attr'              => [],
			'class'             => 'entry__image',
			'before'            => '',
			'after'             => '',
			'min_width'         => 0,
			'min_height'        => 0,
			'caption'           => false
		] );

		// Compatibility with the core WP `post_thumbnail_size` hook.
		$this->args['size'] = apply_filters( 'post_thumbnail_size', $this->args['size'] );

		// Types to locate image. Custom types must implement the `Type`
		// contract to work.
		$this->registered_types = apply_filters( 'hybrid/carbon/types', [
			'attached' => Attached::class,
			'featured' => Featured::class,
			'meta'     => Meta::class,
			'scan'     => Scan::class
		] );

		// Get the type(s) of image location methods.
		$this->type = $type ? (array) $type : [ 'featured' ];

		// If a type is not registered, remove it.
		foreach ( $this->type as $key => $value ) {

			if ( ! isset( $this->registered_types[ $value ] ) ) {
				unset( $this->type[ $key ] );
			}
		}
	}

	/**
	 * Builds the image object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return ImageGrabber
	 */
	public function make() {

		foreach ( $this->type as $type ) {

			// Get the registered type class and create a new instance.
			$class = $this->registered_types[ $type ];

			$locate = new $class( $this );

			// Bail if we do not have a `Type` contract.
			if ( ! $locate instanceof Type ) {
				continue;
			}

			// Attempt to make an image.
			$image = $locate->make();

			// Set the image if it implements the `Image` contract.
			if ( $image instanceof Image ) {
				$this->image = $image;
				break;
			}
		}

		// Return the object for chaining.
		return $this;
	}

	/**
	 * Returns the image object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return Image
	 */
	public function image() {

		return $this->image;
	}

	/**
	 * Returns a specific option or `false` if the option doesn't exist.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @return mixed
	 */
	public function option( $name ) {

		return isset( $this->args[ $name ] ) ? $this->args[ $name ] : false;
	}
}
