# Hybrid\\Carbon

Post featured image script with magical properties.

This is a refresh of the original [Get the Image](https://github.com/justintadlock/get-the-image) project to make it more suitable for including in both plugins and themes as a drop-in package.  It's also a way to shed some of the 10+ years of baggage from the original plugin.

## Usage

_Please note that this is currently under heavy development and could change drastically._

```
<?php Hybrid\Carbon\render_image( $type, $args = [] ) ?>
```

* `$type` - Can be a string or an array of types (allowed types are `featured`, `meta`, `attached`, `scan`).
* `$args` - Arguments to customize the output:
	* `post_id` - ID of the post to get the image for (defaults to current post).
	* `meta_key` - String or array of meta keys to search for (value must be an attachment ID).
	* `link` - Whether to link to the post.
	* `link_class` - Class applied to the link.
	* `size` - Size of the image to get.
	* `min_width` - Minimum width required of the image.
	* `min_height` - Minimum height required of the image.
	* `attr` - Array of image attributes.
