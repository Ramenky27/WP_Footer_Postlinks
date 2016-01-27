<?php
/**
 * @package Footer_postlists
 * @version 0.1
 */
/*
Plugin Name: Footer Postslinks
Plugin URI: https://github.com/Underground27/WP_Footer_Postlinks
Description: This plugin displays posts links in the footer.
Author: Underground27
Version: 0.1
Author URI: https://github.com/Underground27
License: GPLv2 or later
*/

/*  Copyright 2016  Underground27  (email: ramenky27@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define('FOOTER_POSTLINKS_DIR', plugin_dir_path(__FILE__));
define('FOOTER_POSTLINKS_URL', plugin_dir_url(__FILE__));

require_once( FOOTER_POSTLINKS_DIR . 'class.footer-postlinks.php' );

add_action( 'init', array( 'footerPostlinks', 'init' ) );

if ( is_admin() ) {
	require_once( FOOTER_POSTLINKS_DIR . 'class.footer-postlinks-admin.php' );
	add_action( 'init', array( 'footerPostlinks_Admin', 'init' ) );
}



