<?php

/*
Plugin Name: WpChats Lite
Plugin URI: 
Description: WpChats - WordPress Live Chat & Instant Messaging plugin
Author: Samuel Elh
Version: 2.1
Author URI: http://samelh.com
*/

// define constants
defined( 'WPC_PLUGIN_DIR' )  	|| define( 'WPC_PLUGIN_DIR', plugin_dir_url(__FILE__) );
defined( 'WPC_PLUGIN_PATH' ) 	|| define( 'WPC_PLUGIN_PATH', plugin_dir_path(__FILE__) );
defined( 'WPC_PLUGIN_FILE' )	|| define( 'WPC_PLUGIN_FILE', __FILE__ );
defined( 'WPC_VERSION' ) 		|| define( 'WPC_VERSION', '0.2.1' );

// load plugin files
include_once( 'core/init.php' );
include_once( 'classes/wpchats.php' );
include_once( 'admin/classes/wpc.php' );
include_once( 'admin/admin.php' );
include_once( 'core/widget.php' );
include_once( 'core/cron.php' );
include_once( 'core/shortcodes.php' );