# WordPress Loop

A generator function that makes working with the WordPress loop a dream.

**When will this land in WordPress core?**

At the moment, that is unclear. However, you can follow the progress of this [trac ticket](https://core.trac.wordpress.org/ticket/48193). 

## Installation

- Run `composer require wp-forge/wp-loop`
- Make sure you require the `vendor/autoload.php` file in your project.

## Usage

Here are a few examples of how to use the `wp_loop()` function:

### With the Global `WP_Query` Instance

```php
<?php

foreach ( wp_loop() as $post ) {
	?>
	<article>
		<h1><?php the_title(); ?></h1>
		<div><?php the_content(); ?></div>
	</article>
	<?php
}
```

### With a Custom `WP_Query` Instance

```php
<?php

$query = new WP_Query( [ 'post_type' => 'post' ] );

foreach ( wp_loop( $query ) as $post ) {
	?>
	<article>
		<h1><?php the_title(); ?></h1>
		<div><?php the_content(); ?></div>
	</article>
	<?php
}
```

There is no need to run `wp_reset_postdata()` after the loop. It is taken care of automatically, even if you break out of the loop early!

### With an Array of `WP_Post` Objects

```php
<?php

$query = new WP_Query( [ 'post_type' => 'post' ] );
$posts = $query->posts;

foreach ( wp_loop( $posts ) as $post ) {
	?>
	<article>
		<h1><?php the_title(); ?></h1>
		<div><?php the_content(); ?></div>
	</article>
	<?php
}
```

### With an Array of Post IDs

```php
<?php

$query = new WP_Query( [
	'post_type' => 'post',
	'fields'    => 'ids',
] );

$post_ids = $query->posts;

foreach ( wp_loop( $post_ids ) as $post ) {
	?>
	<article>
		<h1><?php the_title(); ?></h1>
		<div><?php the_content(); ?></div>
	</article>
	<?php
}
```

### With an Iterator

```php
<?php

$query    = new WP_Query( [ 'post_type' => 'post' ] );
$iterator = new ArrayIterator( $query->posts );

foreach ( wp_loop( $iterator ) as $post ) {
	?>
	<article>
		<h1><?php the_title(); ?></h1>
		<div><?php the_content(); ?></div>
	</article>
	<?php
}
```

### Other Notes

The `wp_loop()` function is meant to be used in a `foreach` loop. If you need to check if there are results before looping, you can do that the way you normally would.

For example:

```php
<?php

if( have_posts() ) {
    // For global query approach
}

if( $query->have_posts() ) {
    // For custom query approach
}

if( ! empty( $posts ) ) {
    // For post or post ID approach
}

if( $iterator->valid() ) {
    // For iterator approach
}
```

The `wp_loop()` function goes one step further than the standard WordPress loop does and automatically sets and restores the global `$post` object for each iteration.

For more details, read this blog post on [Creating a Better WordPress Loop](https://wpscholar.com/blog/creating-better-wordpress-loop/). The current implementation is a bit different, but the reasoning is laid out quite well.
