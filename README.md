# Post Author Credit

A simple plugin used to demonstrate how to use Ajax in WordPress development. Includes fully documented and localized code following WordPress coding conventions.

* Version 0.5 provides the basic foundation on the plugin using the typical model of saving options upon a POST
* Version 1.0 implements Ajax functionality using the WordPress Ajax API

View the [full article](http://tommcfarlin.com/wordpress-ajax-api).

## Installation

1.	Download a copy of the plugin
2. 	Install in your `wp-content/plugins` directory
3.	Navigate to your 'Plugins' dashboard
4.	Activate the plugin
5. 	Navigate to any post page (new or existing) and the new feature will appear above the 'Publish' options
6.	Activating the option will render a new content box above post content

## Screenshots

![Post Editor with custom meta boxes](http://tommcfarlin.com/wp-content/uploads/2012/06/Screen-Shot-2012-06-11-at-9.49.29-AM-768x603.png "Post Editor with custom meta boxes.")

Post Editor with custom meta boxes

![Public-facing view of the theme](http://tommcfarlin.com/wp-content/uploads/2012/06/Screen-Shot-2012-06-11-at-9.49.29-AM-768x603.png "Public-facing view of the theme.")

Public-facing view of the theme

## Changelog

_0.5_

* Provides standard functionality without Ajax. Used to lay the foundation for implementing Ajax.

_1.0_

* Implements Ajax functionality to asynchronously save the custom post meta. 
* This functionality is used to demonstrate how to use the WordPress Ajax API.

## WordPress API References

Notable functions used throughout this plugin are:

* [register_deactivation_hook](http://codex.wordpress.org/Function_Reference/register_deactivation_hook)
* [add_meta_box](http://codex.wordpress.org/Function_Reference/add_meta_box)
* [the_author_meta](http://codex.wordpress.org/Function_Reference/the_author_meta)
* [WP_Query](http://codex.wordpress.org/Class_Reference/WP_Query) 
* [Ajax API](http://codex.wordpress.org/AJAX_in_Plugins)
* [WP Ajax](http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_(action)