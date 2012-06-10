<?php
/*
Plugin Name: Post Author Credit
Plugin URI: http://github.com/tommcfarlin/Post-Author-Credit
Description: A simple plugin used to demonstrate how to use Ajax in WordPress development.
Version: 0.5
Author: Tom McFarlin
Author URI: http://tom.mcfarl.in
Author Email: tom@tommcfarlin.com
License:

  Copyright 2012 Tom McFarlin (tom@tommcfarlin.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

class Post_Author_Credit {
	 
	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/
	
	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {
	
		load_plugin_textdomain( 'post-author-credit-locale', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
		register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );
		
		// Register and enqueue admin scripts and styles
		add_action( 'admin_enqueue_scripts', array( &$this, 'register_admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'register_plugin_styles' ) );
	
		// Custom post meta boxes
	    add_action( 'add_meta_boxes' , array( &$this, 'post_author_credit_meta_box' ) );
	    add_action( 'save_post', array( &$this, 'post_author_credit_meta_box_save_postdata' ) );
	    
	    // Render the post author credit on the single post page
	    add_action( 'the_content', array( &$this, 'post_author_credit' ) );

	} // end constructor
	
	/**
	 * Fired when the plugin is deactivated. Will remove all custom post meta data that the plugin created while in use.
	 *
	 * @params	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	function deactivate( $network_wide ) {
		
		// Query for all of the post meta data created with this plugin
		$post_author_credit_query = new WP_Query( array( 'meta_key' => 'post_author_credit' ) );
		if( $post_author_credit_query->have_posts() ) {
		
			while( $post_author_credit_query->have_posts() ) {
			
				// Delete the meta data for the current post
				$post_author_credit_query->the_post();
				delete_post_meta( get_the_ID(), 'post_author_credit' );
				
			} // end while
			
		} // end if
		
	} // end deactivate

	/**
	 * Registers and enqueues admin-specific JavaScript for this plugin.
	 */	
	public function register_admin_scripts() {
	
		// Only include the script on the post screen.
		if( 'post' == get_current_screen()->id ) {
			
			wp_register_script( 'post-author-credit-admin-script', plugins_url( dirname( plugin_basename( __FILE__ ) ) . '/js/admin.js' ) );
			wp_enqueue_script( 'post-author-credit-admin-script' );
			
		} // end if
	
	} // end register_admin_scripts
	
	/**
	 * Registers and enqueues post-specific styles for this plugin.
	 */
	public function register_plugin_styles() {
	
		// Only include the styles on the single post page
		if( is_single() ) {
		
			wp_register_style( 'post-author-credit', plugins_url( dirname( plugin_basename( __FILE__ ) ) . '/css/plugin.css' ) );
			wp_enqueue_style( 'post-author-credit' );
			
		} // end if
	
	} // end register_plugin_styles
	
	/*--------------------------------------------*
	 * Core Functions
	 *---------------------------------------------*/
	
	/**
	 * Add the post meta box to the post edit screen. This box will appear above all other options.
	 */
	function post_author_credit_meta_box() {
		
		add_meta_box(
			'post_author_credit',
			__( 'Post Author Credit', 'post_author_meta_box' ),
			array( &$this, 'post_author_credit_display' ),
			'post',
			'side',
			'high'
		);
		
	} // end post_author_credit_meta_box
	
	/**
	 * Saves or updates the post author credit option for the current post.
	 *
	 * @params	$post_id	The ID of the post to which we're associating this option.
	 */
	function post_author_credit_meta_box_save_postdata( $post_id ) {
		
		if( isset( $_POST['post_author_credit_nonce'] ) && isset( $_POST['post_type'] ) ) {
	
			// Don't save if the user hasn't submitted the changes
			if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			} // end if
	
			// Verify that the input is coming from the proper form
			if( ! wp_verify_nonce( $_POST['post_author_credit_nonce'], plugin_basename( __FILE__ ) ) ) {
				return;
			} // end if
	
			// Make sure the user has permissions to post
			if( 'post' == $_POST['post_type']) {
				if( ! current_user_can( 'edit_post', $post_id ) ) {
					return;
				} // end if
			} // end if/else
	
			// Check to see if the user has set the post author credit option
			$post_author_credit = '';
			if( isset( $_POST['post_author_credit'] ) ) {
				$post_author_credit = $_POST['post_author_credit'];
			} // end if

			// Update it for this post
			update_post_meta( $post_id, 'post_author_credit', $post_author_credit );

		} // end if
		
	} // end post_author_credit_meta_box_save_postdata
	
	/**
	 * Renders the post author credit meta box on the post edit page.
	 */
	function post_author_credit_display( $post ) {

		wp_nonce_field( plugin_basename( __FILE__ ), 'post_author_credit_nonce' );

		$html .= '<input type="checkbox" id="post_author_credit" name="post_author_credit" value="1"' . checked( get_post_meta( $post->ID, 'post_author_credit', true ), 1, false ) . ' />';
	
		$html .= '&nbsp;';
	
		$html .= '<label for="post_author_credit">';
			$html .= __( 'Display <a href="profile.php">author name &amp; email</a>?', 'post_author_credit' );
		$html .= '</label>';
		
		echo $html;
		
	} // end post_author_credit_display
	
	/**
	 * Appends post author credit information to the beginning of the post.
	 * 
	 * @params	$content	The current post's content.
	 * @returns				The content containing the post author credit.
	 */
	function post_author_credit( $content ) {
		
		if( '1' == get_post_meta( get_the_ID(), 'post_author_credit', true ) ) {
			
			// The also expects that the user has specified an email address and display name.
			if( '' != get_the_author_meta( 'user_nicename' ) && '' != get_the_author_meta( 'user_email' ) ) {
			
				$html = '<div id="post-author-credit"><p>';
					$html .= __( 'This post was written by ' . get_the_author_meta( 'user_nicename' ) . '. Contact the author <a href="mailto:' . get_the_author_meta( 'user_email' ) . '">here</a>.', 'post_author_credit' );
				$html .= '</p></div>';
				
				$content = $html . $content;
				
			} // end if
			
		} // end if
		
		return $content;
		
	} // end post_author_credit
  
} // end class
new Post_Author_Credit();
?>