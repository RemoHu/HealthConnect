<?php

add_shortcode('wpc-messages', function() {
	echo '<div id="wpchats"';
	$wpchats = new wpChats;
	echo $wpchats->get_settings('rtl') ? ' class="rtl"' : '';
	echo '>';
	include_once( WPC_PLUGIN_PATH . 'messages/template.php' );
	echo "</div>";
});

add_shortcode('wpc-users', function() {
	echo '<div id="wpcusers"';
	$wpchats = new wpChats;
	echo $wpchats->get_settings('rtl') ? ' class="rtl"' : '';
	echo '>';
	include_once( WPC_PLUGIN_PATH . 'users/users.php');
	echo "</div>";
});
/**
 * Enqueue CSS and JavaScript for this plugin
 * global.js containing main scripts
 * style.css main stylesheet
 * mod.css containing css related to front-end moderation panel for moderators
 * masonry to display users icons in a grid mode (Copyright David Desandro)
*/
add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_script( 'wpc-global', WPC_PLUGIN_DIR . 'inc/js/global.js', array('jquery'), '1.0', true );
	wp_enqueue_style( 'wpc-css', WPC_PLUGIN_DIR . 'inc/css/style.min.css' );
	wp_enqueue_style('wpc-mod', WPC_PLUGIN_DIR . 'admin/inc/css/mod.css' );
	wp_localize_script( 'wpc-global', 'ajaxwpchats', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' )
	));
	wp_localize_script( 'wpc-global', 'ajaxwpcusers', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' )
	));	
	wp_localize_script( 'wpc-global', 'ajaxwpcjson', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' )
	));	
	$op_pg = esc_attr( get_option('wpc_pages') );
	$op_pg_exp = explode(",", esc_attr( get_option('wpc_pages') ));
	if( is_singular() && get_the_id() ==  $op_pg_exp[1] && ! isset( $_GET["user"] ) )
		wp_enqueue_script('masonry');
	wp_enqueue_script('jquery');
});
// Callback function for messages AJAX
add_action( 'wp_ajax_wpchats', function() {
	exit;//pro feature
});
add_action( 'wp_ajax_wpcusers', 'wpcusers_ajax' );
// enable ajax for logged-out screen when viewing users
add_action( 'wp_ajax_nopriv_wpcusers', 'wpcusers_ajax' );
// callback function for users AJAX
function wpcusers_ajax() {
	exit;//pro feature
}
// Callback function for JSON AJAX
add_action( 'wp_ajax_wpcjson', function() {
	exit;//pro feature
});
// helps avoiding header errors while redirecting with wp_redirect
add_action('init', function() {ob_start();});
/**
 * Main function callback upon plugin activation
 * Creates a page for messages and a page for users if those pages do not exist already, content for these pages is a simple shortcode
 * Creates 2 database tables, one for main messages, and another for deleted messages to keep them stored until admin trashes them. If those tables exist, then nothing will happen
*/
function wpc_activate() {
	$op_pages = esc_attr( get_option('wpc_pages') );
	if ( ! $op_pages || $op_pages == '' ) {
		$args = array(
			'post_title' 	=> __( 'Messages', 'wordpress' ),
			'post_name' 	=> 'messages',
			'post_type' 	=> 'page',
			'post_status' 	=> 'publish',
			'post_content'  => '[wpc-messages]',
		);
		$pid = wp_insert_post( $args );
		$args2 = array(
			'post_title' 	=> __( 'Users', 'wordpress' ),
			'post_name' 	=> 'users',
			'post_type' 	=> 'page',
			'post_status' 	=> 'publish',
			'post_content'  => '[wpc-users]',
		);
		$pid2 = wp_insert_post( $args2 );
		update_option('wpc_pages', "$pid,$pid2" );
	}
	$op_db = esc_attr( get_option('wpc_db_version') );
	if ( ! $op_db || $op_db == '0.1' ) {
		global $wpdb;
		$tbl = $wpdb->prefix . "mychats"; 
		$tbl2 = $wpdb->prefix . "mychats_temp"; 
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE IF NOT EXISTS $tbl (
		  `id` bigint(20) NOT NULL AUTO_INCREMENT,
		  `chat_id` bigint(20) NOT NULL,
		  `to` bigint(20) NOT NULL,
		  `from` bigint(20) NOT NULL,
		  `body` LONGTEXT NOT NULL,
		  `time` bigint(20) NOT NULL,
		  `seen` bigint(20),
		  `status` varchar(10) DEFAULT 'unread',
		  `deleted` varchar(10) DEFAULT '',
		  UNIQUE (`id`)
		) $charset_collate;";
		$sql2 = "CREATE TABLE IF NOT EXISTS $tbl2 (
		  `id` bigint(20) NOT NULL AUTO_INCREMENT,
		  `chat_id` bigint(20) NOT NULL,
		  `to` bigint(20) NOT NULL,
		  `from` bigint(20) NOT NULL,
		  `body` LONGTEXT NOT NULL,
		  `time` bigint(20) NOT NULL,
		  `seen` bigint(20),
		  `status` varchar(10) DEFAULT 'unread',
		  `deleted` varchar(10) DEFAULT '',
		  UNIQUE (`id`)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		dbDelta( $sql2 );
		update_option( 'wpc_db_version', '0.1' );
	}
}
register_activation_hook( WPC_PLUGIN_FILE, 'wpc_activate' );
// enqueue plugin meta and scripts in the site footer
add_action('wp_footer', function() {
	$wpchats = new wpChats;
	global $current_user;
	echo '<div id="wpc-nm-cont"';
	echo $wpchats->get_settings('rtl') ? ' class="rtl"' : '';
	echo '></div>';
	ob_start();
	?>
		<script type="text/javascript">
		var wpcJSLoaded = false;
		var ScrollCont 		= document.getElementById("wpc-scroll");
		function wpcScroll() {
			if(null !== ScrollCont) {
				if( null !== document.querySelector(".wpc-paginate") && document.querySelector(".wpc-paginate").getAttribute("data-next") > 2 )
					return false;
				else
					ScrollCont.scrollTop = ScrollCont.scrollHeight;
			}
		}
		var trans = { 
			"1": "<?php echo $wpchats->translate(88); ?>","2": "<?php echo $wpchats->translate(89); ?>","3": "<?php echo $wpchats->translate(90); ?>","4": "<?php echo $wpchats->translate(91); ?>","5": "<?php echo $wpchats->translate(92); ?>","6": "<?php echo $wpchats->translate(93); ?>","7": "<?php echo $wpchats->translate(94); ?>","8": "<?php echo $wpchats->translate(95); ?>","9": "<?php echo $wpchats->translate(96); ?>","10": "<?php echo $wpchats->translate(97); ?>","11": "<?php echo $wpchats->translate(98); ?>","12": "<?php echo $wpchats->translate(18); ?>","13": "<?php echo $wpchats->translate(99); ?>","14": "<?php echo $wpchats->translate(100); ?>","15": "<?php echo $wpchats->translate(101); ?>","16": "<?php echo $wpchats->translate(102); ?>","17": "<?php echo $wpchats->translate(103); ?>","18": "<?php echo $wpchats->translate(104); ?>","19": "<?php echo $wpchats->translate(69); ?>","20": "<?php echo $wpchats->translate(105); ?>","21": "<?php echo $wpchats->translate(106); ?>","22": "<?php echo $wpchats->translate(35); ?>","23": "<?php echo $wpchats->translate(160); ?>","24": "<?php echo $wpchats->translate(200); ?>","25": "<?php echo $wpchats->translate(238); ?>","26": "<?php echo $wpchats->translate(239); ?>"
		};
		var isRTL = <?php echo $wpchats->get_settings('rtl') ? 'true' : 'false'; ?>;
		</script>
	<?php
	echo ob_get_clean();

});
/**
 * Plugin's Custom CSS
 * import font icons (powered by fontello.com) stylesheet
 * custom CSS for admin custom emoji
 * main custom CSS implemeneted within settings page
*/
add_action('wp_head', function() {
	$wpchats = new wpchats;
	echo '<style type="text/css" media="all">' . $wpchats->get_settings('custom_css') . '</style>';
});
/**
 * BuddyPress integration
*/
if( function_exists( 'buddypress' ) ) {
	if( get_option('wpc_s_21') == 'on' || get_option('wpc_s_21') !== '' && get_option('wpc_s_21') !== 'on' ) {
		if( is_numeric(bp_displayed_user_id()) ) {
			/* add_action('bp_setup_nav', function() {
				if ( ! is_user_logged_in() || bp_is_group() || ! get_userdata( bp_displayed_user_id() ) ) {
			        return;
			    }
				global $bp;
				global $current_user;
				$wpchats = new wpchats;
				$user_id = bp_displayed_user_id();
				if( $wpchats->is_blocked( $user_id, 'by' ) || $wpchats->is_blocked( $user_id, '' ) )
					return;
				if( $current_user->ID !== $user_id ) {
					bp_core_new_nav_item(
						array(
							'name' 		=> '<div title="' . $wpchats->translate(159) .' '.get_userdata($user_id)->display_name.'">'.$wpchats->translate(159).'</div>',
							'position' 	=> 50, 
							'slug' 		=> '?wpc_redirect='.$wpchats->get_settings( 'messages_page').'?recipient='.$user_id,
						)
					);
				}
			}, 999); */
		}
		if( is_numeric(bp_displayed_user_id()) ) {
			/* add_action('bp_setup_nav', function() {
				if ( !is_user_logged_in() || bp_is_group() || !get_userdata( bp_displayed_user_id() ) ) {
			        return;
			    }
			    global $bp;
				global $current_user;
				$wpchats 	= new wpchats;
				$user_id 	= bp_displayed_user_id();
				if( $current_user->ID !== $user_id ) {
					$url = '?wpc_redirect='.$wpchats->get_settings( 'messages_page').'?todo=';
					$url .= $wpchats->is_blocked( $user_id, '' ) ? 'unblock_user' : 'block_user';
					$url .= '&user='.$user_id.'&rdr='.$_SERVER["REQUEST_URI"];
					bp_core_new_nav_item(
						array(
							'name' 		=> $wpchats->is_blocked( $user_id, '' ) ? '<div title="' . $wpchats->translate(82) . ' '.get_userdata($user_id)->display_name.'">'.$wpchats->translate(82).'</div>' : '<div title="' . $wpchats->translate(81) . ' '.get_userdata($user_id)->display_name.'">'. $wpchats->translate(81) .'</div>',
							'position' 	=> 50,
							'slug' 		=> $url,
						)
					);
				}
			}, 999); */
		}
		if( is_numeric(bp_displayed_user_id()) ) {
			/* add_action('bp_setup_nav', function() {
				if ( ! is_user_logged_in() || bp_is_group() || ! get_userdata( bp_displayed_user_id() ) ) {
			        return;
			    }
				global $bp;
				global $current_user;
				$wpchats 	= new wpchats;
				$user_id 	= bp_displayed_user_id();
				if( $wpchats->get_counts('unread') == 0 )
					return;
				if( $current_user->ID !== $user_id ) {
					return false;
				} else {
					bp_core_new_nav_item(
						array(
							'name' 		=> '<div title="'. str_replace('[count]', '', $wpchats->translate(219)) .'">'.$wpchats->translate(79).' <span class="no-count">'.$wpchats->get_counts('unread').'</span></div>',
							'position' 	=> 50,
							'slug' 		=> '?wpc_redirect='.$wpchats->get_settings( 'messages_page'),
						)
					);
				}
			}, 999); */
		}
		add_action( 'bp_profile_header_meta', function() {
			$wpchats = new wpchats;
			$meta = get_user_meta(bp_displayed_user_id(), 'wpc_last_seen', TRUE);
			if( ! $meta ) {
				echo '<div style="clear:both;"><span class="wpc_offline wpcico-ok-circled">'.$wpchats->translate(48).'</span></div>';
				return;
			}
			echo '<div style="clear:both;">'.$wpchats->online_diff( date("Y-m-d H:i:s", $meta), date("Y-m-d H:i:s", time()) ).'</div>';
		});

		add_action('wp', function() {
			if( isset( $_GET["wpc_redirect"] ) ) {
				$urlV = str_replace( '?wpc_redirect=', '', substr( $_SERVER["REQUEST_URI"], strpos( $_SERVER["REQUEST_URI"], '?wpc_redirect=')) );
				$url = is_numeric( strpos( $urlV, '?scs' ) ) ? str_replace( '?scs=', '', substr( $urlV, 0, strpos( $urlV, '?scs=')) ) : $urlV;
				$url = is_numeric( strpos( $url, 'recipient=' ) ) && substr( $url, -1 ) == '/' ? substr( $url, 0, -1 ) : $url;
				wp_redirect( $url );
				exit;
			}
		});
	}
}
/**
 * bbPress integration
*/
if( function_exists( 'bbpress' ) ) {
	if( get_option('wpc_s_20') == 'on' || get_option('wpc_s_20') !== '' && get_option('wpc_s_20') !== 'on' ) {
		add_filter('bbp_template_after_user_profile', function() {
			global $current_user;
			$wpchats = new wpchats;
			$user_id = bbp_get_user_id( 0, true, false );
			if( $current_user->ID !== $user_id ) {
				echo $wpchats->is_blocked( $user_id, 'by' ) || $wpchats->is_blocked( $user_id, '' ) ? '' : '<div title="'. $wpchats->translate(159) .' '.get_userdata($user_id)->display_name.'"><p><a href="'.$wpchats->get_settings( 'messages_page').'?recipient='.$user_id.'">'. $wpchats->translate(159) .'</a></p></div>';
			}
			if( $current_user->ID !== $user_id && is_user_logged_in() ) {
				$url = $wpchats->get_settings( 'messages_page').'?todo=';
				$url .= $wpchats->is_blocked( $user_id, '' ) ? 'unblock_user' : 'block_user';
				$url .= '&user='.$user_id.'&rdr='.str_replace( array('?scs=8', '?scs=9'), '', $_SERVER["REQUEST_URI"] );
				echo $wpchats->is_blocked( $user_id, '' ) ? '<div title="' . $wpchats->translate(82) . ' ' . get_userdata($user_id)->display_name.'"><p><a href="'.$url.'">'.$wpchats->translate(82).'</a></p></div>' : '<div title="' . $wpchats->translate(81) . ' ' . get_userdata($user_id)->display_name.'"><a href="'.$url.'">' . $wpchats->translate(81) . '</a></p></div>';
			}
			else echo is_user_logged_in() ? $wpchats->get_counts('unread') > 0 && $current_user->ID == $user_id ? '<div title="'. str_replace('[count]', '', $wpchats->translate(219)) .'"><p><a href="'.$wpchats->get_settings( 'messages_page').'">'.$wpchats->translate(79).' ('.$wpchats->get_counts('unread').')</a></p></div>' : '<div><p><a href="'.$wpchats->get_settings( 'messages_page').'">'.$wpchats->translate(79).'</a></p></div>' : '';
			$meta = get_user_meta($user_id, 'wpc_last_seen', TRUE);
			echo $meta ? '<div><p>'.$wpchats->online_diff( date("Y-m-d H:i:s", $meta), date("Y-m-d H:i:s", time()) ).'</p></div>' : '<div><p><span class="wpc_offline wpcico-ok-circled">'.$wpchats->translate(48).'</span></p></div>';
		});
	}


	add_action('bbp_theme_after_reply_author_details', function() {
		global $current_user;
		$wpchats = new wpchats;
		$user_id = bbp_get_reply_author_id();
		if( $user_id && $wpchats->get_settings('add_after_bbp_auth_details') ) {
			$meta = get_user_meta($user_id, 'wpc_last_seen', TRUE);
			if( $current_user->ID !== $user_id ) {
				echo $wpchats->is_blocked( $user_id, 'by' ) || $wpchats->is_blocked( $user_id, '' ) ? '' : '<div title="'. $wpchats->translate(159) .' '.get_userdata($user_id)->display_name.'"><a href="'.$wpchats->get_settings( 'messages_page').'?recipient='.$user_id.'">'. $wpchats->translate(159) .'</a></div>';
			}
			if( ! $meta ) {
				echo '<div style="clear:both;"><span class="wpc_offline wpcico-ok-circled">'.$wpchats->translate(48).'</span></div>';
				return;
			}
			echo '<div style="clear:both;">'.$wpchats->online_diff( date("Y-m-d H:i:s", $meta), date("Y-m-d H:i:s", time()) ).'</div>';
			echo '<div>' . $wpchats->user_links( 'block', $user_id, $_SERVER["REQUEST_URI"] ) . '</div>';
		}
	});


}
// adds a notice listener in pages other than default plugin pages (messages/ users/)
add_action('wp_head', function() {
	if( isset( $_GET["scs"] ) ) {
		$wpchats = new wpchats;
		if( get_the_permalink() == $wpchats->get_settings('messages_page') || get_the_permalink() == $wpchats->get_settings('profile_page') )
			return;
		echo $wpchats->notices( $_GET["scs"] );
	}
});
/**
 * Adds settings and support links in the plugins list within admin interface
 * For support naviguate to http://go.elegance-style.com/?ext=wpc-support
*/
add_filter( "plugin_action_links_".plugin_basename(WPC_PLUGIN_FILE), function($links) {
    array_push( $links, '<a href="admin.php?page=wpchats&section=settings">' . __( 'Settings' ) . '</a>' );
  	return $links;
});
add_filter( "plugin_action_links_".plugin_basename(WPC_PLUGIN_FILE), function($links) {
    array_push( $links, '<a href="https://wordpress.org/support/plugin/wpchats-lite-private-messaging" target="_blank">' . __( 'Support' ) . '</a>' );
  	return $links;
});
add_filter( "plugin_action_links_".plugin_basename(WPC_PLUGIN_FILE), function($links) {
    array_push( $links, '<a href="http://go.samelh.com/get-wpchats" target="_blank">' . __( 'Get Pro' ) . '</a>' );
  	return $links;
});
// update user status without jQuery AJAX
add_action('wp', function() {
	global $current_user;
	$wpchats = new wpchats;
	if( !$wpchats->user_preferences( $current_user->ID, 'go_offline' ) ) {
		update_user_meta( $current_user->ID, 'wpc_last_seen', time() );
	}
});
add_action( 'admin_notices', function() {
	if( get_option( 'permalink_structure' ) == '' ) {
		echo '<div id="updated" class="error notice is-dismissible"><p>WpChats notice: You must <a href="'.admin_url('options-permalink.php').'">update your permalink structure</a> to something other than the default for WpChats to work. (<a href="https://codex.wordpress.org/Using_Permalinks#Choosing_your_permalink_structure" target="_blank"><i>help</i></a>)</p></div>';
	}
});