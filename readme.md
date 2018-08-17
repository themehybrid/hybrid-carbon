# Hybrid\\Carbon

Post featured image script with magical properties.

Hybrid Carbon is a drop-in library.  It is not a standalone plugin and should be included in any themes/plugins that you ship.

This is a refresh of the original [Get the Image](https://github.com/justintadlock/get-the-image) project to make it more suitable for including in both plugins and themes as a drop-in package.  It's also a way to shed some of the 10+ years of baggage from the original plugin.

Hybrid Carbon is meant to replace the core WP featured image (i.e., post thumbnail) feature with a version that has a few more possibilities.  Out of the box, it comes with 4 methods for locating featured images:

* `featured` - This is the normal featured/thumbnail image.
* `attached` - Searches for the first image uploaded/attached to a post.
* `meta` - Searches for an image (attachment ID) assigned to a custom meta field.
* `scan` - Scans the post content for image attachment IDs.

All of the core script methods are based around core WP's image attachment feature so that we can utilize the built-in functions already available like responsive images using `srcset` and `sizes`.

## Requirements

* PHP 5.6+ (preferably 7+)
* [Composer](https://getcomposer.org/) for managing PHP dependencies.

Technically, you could make this work without Composer by directly downloading and dropping the package into your theme.  However, using Composer is ideal and the supported method for using this project.

## Documentation

The script should be relatively easy to use if you've ever worked with featured images in WP.  There's not a lot of code to change.  The following docs are written with theme authors in mind because that'll be the most common use case.  If including in a plugin, it shouldn't be much different.

### Installation

First, you'll need to open your command line tool and change directories to your theme folder.

```
cd path/to/wp-content/themes/<your-theme-name>
```

Then, use Composer to install the package.

```
composer require justintadlock/hybrid-carbon
```

Assuming you're not already including the Composer autoload file for your theme and are shipping this as part of your theme package, you'll want something like the following bit of code in your theme's `functions.php` to autoload this package (and any others).

The Composer autoload file will automatically load up Hybrid Carbon for you and make its code available for you to use.

```
if ( file_exists( get_parent_theme_file_path( 'vendor/autoload.php' ) ) ) {
	require_once( get_parent_theme_file_path( 'vendor/autoload.php' ) );
}
```

### Usage

Hybrid Carbon has a few functions, but the primary function that you'll want to use is `display()`.  You'll need to replace any calls to `the_post_thumbnail()` with this function like so:

```
<?php Hybrid\Carbon\display( $type, $args = [] ) ?>
```

_Note that the plugin's namespace is `Hybrid\Carbon`.  If you're working within another namespace, you'll want to add a `use` statement after your own namespace call or call `\Hybrid\Carbon\display()` directly.  I'll assume you know what you're doing if you're working with namespaces.  Otherwise, stick to the above._

The most basic call will look like the following.

```
<?php Hybrid\Carbon\display( 'featured', [
	'size' => 'post-thumbnail',
	'link' => 'post'
] ) ?>
```

### Parameters

There are two parameters:  `$type` and `$args`.

**$type**

The `$type` parameter accepts a string of a single type or an array of types.  A type is just the method to use to locate a featured image.

The following are the built-in types:

* `featured` - This is the normal featured/thumbnail image.
* `attached` - Searches for the first image uploaded/attached to a post.
* `meta` - Searches for an image (attachment ID) assigned to a custom meta field. Must be coupled with a `meta_key` set in `$args`.
* `scan` - Scans the post content for image attachment IDs.

If passing in an array of types, the script will search for images in the order that you add them.

**$args**

The `$args` parameter is an optional array of arguments to customize the image.

* `post_id` - ID of the post to get the image for (defaults to current post).
* `size` - Size of the image to get.
* `meta_key` - String or array of meta keys to search for (value must be an attachment ID).
* `link` - Whether to link to the post.
* `link_class` - Class applied to the link.
* `min_width` - Minimum width required of the image.
* `min_height` - Minimum height required of the image.
* `attr` - Array of image attributes.
* `caption` - Whether to include captions for images that have them (defaults to `false`).
* `before` - HTML string to add before the output of the image.
* `after` - HTML string to add after the output of the image.

### Functions

All of the primary functions you might use follow the same parameter pattern.  Of course, all of these functions are under the `Hybrid\Carbon` namespace.

```php
// Returns an instance of the Carbon class.
make( $type, array $args = [] );

// Returns an instance of the found Image object or false.
image( $type, array $args = [] );

// Renders the HTML output of the found Image object if one is found.
display( $type, array $args = [] );

// Returns the HTML string of the found Image object or an empty string.
render( $type, array $args = [] );
```

### Static helper class

If you'd rather work with a static class than the above functions, `Hybrid\Carbon\Util\Grabber` is also available.

```php
// Returns an instance of the Carbon class.
Grabber::make( $type, array $args = [] );

// Returns an instance of the found Image object or false.
Grabber::image( $type, array $args = [] );

// Renders the HTML output of the found Image object if one is found.
Grabber::display( $type, array $args = [] );

// Returns the HTML string of the found Image object or an empty string.
Grabber::render( $type, array $args = [] );
```
