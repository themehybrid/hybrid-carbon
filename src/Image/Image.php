<?php
/**
 * Base image class.
 *
 * This is the image object that's returned to the main plugin class and back to
 * any functions used.
 *
 * @package   HybridCarbon
 * @link      https://github.com/themehybrid/hybrid-carbon
 *
 * @author    Theme Hybrid
 * @copyright Copyright (c) 2008 - 2023, Theme Hybrid
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Carbon\Image;

use Hybrid\Carbon\Contracts\Image as ImageContract;
use Hybrid\Carbon\Contracts\ImageGrabber;
use Hybrid\Carbon\Util\Helpers;

/**
 * Image class.
 *
 * @since  1.0.0
 *
 * @access public
 */
class Image implements ImageContract {

    /**
     * ImageGrabber instance.
     *
     * @since  1.0.0
     * @var \Hybrid\Carbon\Contracts\ImageGrabber
     *
     * @access protected
     */
    protected $manager;

    /**
     * Image src.
     *
     * @since  1.0.0
     * @var    string
     *
     * @access protected
     */
    protected $src = '';

    /**
     * Image width.
     *
     * @since  1.0.0
     * @var    int
     *
     * @access protected
     */
    protected $width = 0;

    /**
     * Image height.
     *
     * @since  1.0.0
     * @var    int
     *
     * @access protected
     */
    protected $height = 0;

    /**
     * Image alternative text.
     *
     * @since  1.0.0
     * @var    string
     *
     * @access protected
     */
    protected $alt = '';

    /**
     * Image caption.
     *
     * @since  1.0.0
     * @var    string
     *
     * @access protected
     */
    protected $caption = '';

    /**
     * Creates a new Image object.
     *
     * @since  1.0.0
     * @param \Hybrid\Carbon\Contracts\ImageGrabber $manager
     * @param  array                                 $args
     * @return void
     *
     * @access public
     */
    public function __construct( ImageGrabber $manager, array $args = [] ) {

        foreach ( array_keys( get_object_vars( $this ) ) as $key ) {

            if ( isset( $args[ $key ] ) ) {
                $this->$key = $args[ $key ];
            }
        }

        $this->manager = $manager;
    }

    /**
     * Renders the image HTML output.
     *
     * @since  1.0.0
     * @return void
     *
     * @access public
     */
    public function display() {

        echo $this->render();
    }

    /**
     * Returns the image HTML output.
     *
     * @since  1.0.0
     * @return string
     *
     * @access public
     */
    public function render() {

        $html = '';

        if ( $attr = $this->attr() ) {

            foreach ( $attr as $name => $value ) {

                if ( 'src' === $name ) {

                    $html .= sprintf( ' %s="%s"', esc_html( $name ), esc_url( $value ) );

                } elseif ( false !== $value ) {

                    $html .= sprintf( ' %s="%s"', esc_html( $name ), esc_attr( $value ) );
                }
            }

            $html = sprintf( '<img%s />', $html );
        }

        return '';
    }

    /**
     * Returns the image source value.
     *
     * @since  1.0.0
     * @return string
     *
     * @access public
     */
    public function src() {

        return $this->src;
    }

    /**
     * Returns the image width value.
     *
     * @since  1.0.0
     * @return int
     *
     * @access public
     */
    public function width() {

        return $this->width;
    }

    /**
     * Returns the image height value.
     *
     * @since  1.0.0
     * @return int
     *
     * @access public
     */
    public function height() {

        return $this->height;
    }

    /**
     * Returns the image alt value.
     *
     * @since  1.0.0
     * @return string
     *
     * @access public
     */
    public function alt() {

        return $this->alt;
    }

    /**
     * Returns the image attributes.
     *
     * @since  1.0.0
     * @return string
     *
     * @access public
     */
    protected function attr() {

        $attr = [];

        if ( ! $this->src() ) {
            return $attr;
        }

        $attr['src']   = $this->src();
        $attr['class'] = join( ' ', $this->class() );
        $attr['alt']   = $this->alt();

        if ( $this->width() ) {
            $attr['width'] = $this->width();
        }

        if ( $this->height() ) {
            $attr['height'] = $this->height();
        }

        return $this->manager->option( 'attr' ) + $attr;
    }

    /**
     * Returns the image element class.
     *
     * @since  1.0.0
     * @return array
     *
     * @access protected
     */
    protected function class() {

        $class = [
            $this->manager->option( 'class' ),
        ];

        if ( $o = $this->orientation() ) {

            $class[] = sprintf(
                '%s--orientation-%s',
                Helpers::classBase( $this->manager->option( 'class' ) ),
                $o
            );
        }

        return $class;
    }

    /**
     * Returns the image caption.
     *
     * @since  1.0.0
     * @return string
     *
     * @access public
     */
    public function caption() {

        return $this->caption;
    }

    /**
     * Returns the image orientation (`landscape` or `portrait`) if one can
     * be determined. Else, returns an empty string.
     *
     * @since  1.0.0
     * @return string
     *
     * @access public
     */
    public function orientation() {

        $w = $this->width();
        $h = $this->height();

        if ( $w && $h ) {

            if ( $w === $h ) {
                return 'square';
            }

            return $w > $h ? 'landscape' : 'portrait';
        }

        return '';
    }

    /**
     * Wraps the image with HTML wrappers.
     *
     * @since  1.0.0
     * @param  string $html
     * @return string
     *
     * @access public
     */
    protected function wrap( $html ) {

        $html = $this->addLink( $html );
        $html = $this->addCaption( $html );

        return $this->manager->option( 'before' ) . $html . $this->manager->option( 'after' );
    }

    /**
     * Wraps the image HTML with a link.
     *
     * @since  1.0.0
     * @param  string $html
     * @return string
     *
     * @access public
     */
    protected function addLink( $html ) {

        if ( 'post' === $this->manager->option( 'link' ) || true === $this->manager->option( 'link' ) ) {

            $html = sprintf(
                '<a href="%s" class="%s">%s</a>',
                esc_url( get_permalink( $this->manager->option( 'post_id' ) ) ),
                esc_attr( $this->manager->option( 'link_class' ) ),
                $html
            );
        }

        return $html;
    }

    /**
     * Wraps the image HTML with a caption.
     *
     * @since  1.0.0
     * @param  string $html
     * @return string
     *
     * @access public
     */
    protected function addCaption( $html ) {

        if ( $this->manager->option( 'caption' ) && $caption = $this->caption() ) {

            $html = img_caption_shortcode( [
                'caption' => $caption,
                'width'   => $this->width(),
            ], $html );
        }

        return $html;
    }

}
