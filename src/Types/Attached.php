<?php
/**
 * Attached location type class.
 *
 * Grabs the first attached image for a post and returns it.
 *
 * @package   HybridCarbon
 * @link      https://github.com/themehybrid/hybrid-carbon
 *
 * @author    Theme Hybrid
 * @copyright Copyright (c) 2008 - 2023, Theme Hybrid
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Carbon\Types;

use Hybrid\Carbon\Image\Attachment;
use Hybrid\Carbon\Util\Helpers;

/**
 * Attached location class.
 *
 * @since  1.0.0
 *
 * @access public
 */
class Attached extends Base {

    /**
     * Returns an `Image` object or `false` if no image is found.
     *
     * @since  1.0.0
     * @param  array $args
     * @return \Hybrid\Carbon\Types\Image|bool
     *
     * @access protected
     */
    public function make() {

        $image         = '';
        $attachment_id = 0;

        if ( Helpers::isImageAttachment( $this->manager->option( 'post_id' ) ) ) {

            $attachment_id = $this->manager->option( 'post_id' );
        } else {

            $attachments = get_children( [
                'numberposts'    => 1,
                'post_parent'    => $this->manager->option( 'post_id' ),
                'post_status'    => 'inherit',
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'order'          => 'ASC',
                'orderby'        => 'menu_order ID',
                'fields'         => 'ids',
            ] );

            // Check if any attachments were found.
            if ( $attachments ) {
                $attachment_id = array_shift( $attachments );
            }
        }

        if ( 0 < $attachment_id && Helpers::isImageAttachment( $attachment_id ) ) {

            $image = new Attachment( $this->manager, [
                'attachment_id' => $attachment_id,
            ] );
        }

        return $this->validate( $image ) ? $image : false;
    }

}
