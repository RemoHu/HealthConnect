<?php
do_action('_wpc_before_users_page');
$classes = isset($_GET["mod"]) ? 'mod-panel' : '';
$classes .= isset($_GET["edit"]) ? ' settings' : '';
$classes .= isset($_GET["user"]) && !isset($_GET["edit"]) ? ' single-user '.$_GET["user"] : '';
echo "<div class=\"wpcusers $classes\">";
global $current_user;
$wpchats = new wpChats;
$wpc = new wpc;
if( isset( $_GET["id"] ) ) {
	if( get_userdata( $_GET["id"] )->ID ) {
		wp_redirect( $wpchats->user_links('link', get_userdata( $_GET["id"] )->ID, '') );
		exit;
	} else {
		wp_redirect( $wpchats->get_settings('profile_page') . '?scs=15' );
		exit;
	}
}
if( isset( $_GET["scs"] ) ) {
	$wpchats->notices( $_GET["scs"] );
}
if( $wpchats->user_preferences( $current_user->ID, 'go_offline' ) ) :; ?>
<div class="wpc_notices wpc_fail">
	<p>
		<span><i class="wpcico-attention"></i>&nbsp;<?php echo $wpchats->translate(46); ?>. <a href="<?php echo $wpchats->get_settings('profile_page'); ?>?edit=1&amp;go=online"><?php echo $wpchats->translate(73); ?></a></span>
		<i class="wpcico-cancel cancel_notice" title="<?php echo $wpchats->translate(47); ?>"></i>
	</p>
</div>
<?php endif; ?>
<?php ob_start(); ?>
<div class="wpcuser-topcont">
	<?php ob_start(); ?>
	<div<?php echo ! isset( $_GET["user"] ) && ! isset( $_GET["filter"] ) && ! isset( $_GET["wpc_search"] ) && ! isset( $_GET["mod"] ) && ! isset( $_GET["edit"] ) ? ' class="active"': ''; ?>>
		<a href="<?php echo $wpchats->get_settings('profile_page'); ?>" class="wpc_users_ajax wpc-tooltip"><i class="wpcico-users"></i><?php echo $wpchats->translate(150); ?></a>
	</div>
	<?php echo apply_filters('_wpc_users_top_header_users', ob_get_clean()); ?>
	<?php ob_start(); ?>
	<div<?php echo isset( $_GET["filter"] ) && $_GET["filter"] == 'online' ? ' class="active"': ''; ?>>
		<a href="<?php echo $wpchats->get_settings('profile_page'); ?>?filter=online" class="wpc_users_online wpc-tooltip"><i class="wpcico-ok-circled <?php echo $wpchats->get_counts('online') > 0 ? 'wpc_online' : 'wpc_offline'; ?>"></i><?php echo $wpchats->translate(4); ?></a> <?php echo '(<span class="wpc_on_cnt">' . $wpchats->get_counts('online') . '</span>)'; ?>
	</div>
	<?php echo apply_filters('_wpc_users_top_header_online', ob_get_clean()); ?>
	<?php if( is_user_logged_in() && $wpchats->get_counts('blocked') > 0 ) :; ?>
	<?php ob_start(); ?>
	<div<?php echo isset( $_GET["filter"] ) && $_GET["filter"] == 'blocked' ? ' class="active"': ''; ?>>
		<a href="<?php echo $wpchats->get_settings('profile_page'); ?>?filter=blocked" class="wpc_users_blocked wpc-tooltip"><i class="wpcico-block"></i><?php echo $wpchats->translate(151); ?></a> <?php echo '(' . $wpchats->get_counts('blocked') . ')'; ?>
	</div>
	<?php echo apply_filters('_wpc_users_top_header_blocked', ob_get_clean()); ?>
	<?php endif; ?>
	<?php ob_start(); ?>
	<div id="wusc"<?php echo isset( $_GET["wpc_search"] ) ? ' class="active"': ''; ?>>
		<form action="<?php echo $wpchats->get_settings('profile_page'); ?>" method="get" id="wpc_user_search" class="wpc-tooltip">
			<label for="wpc_user_search_s"><i class="wpcico-search"></i></label>
			<input type="text" name="wpc_search" value="<?php echo isset( $_GET["wpc_search"] ) ? $_GET["wpc_search"] : ''; ?>" placeholder="<?php echo $wpchats->translate(2); ?>" size="12" id="wpc_user_search_s" />
		</form>
	</div>
	<?php echo apply_filters('_wpc_users_top_header_search', ob_get_clean()); ?>
	<?php if( $wpc->is_user('mod', $current_user->ID) && ! $wpc->is_user('banned', $current_user->ID) ) :;?>
	<?php ob_start(); ?>
	<div<?php echo isset( $_GET["mod"] ) ? ' class="active"': ''; ?>>
		<a href="<?php echo $wpchats->get_settings('profile_page'); ?>?mod=1" class="wpc_load_mod_panel wpc-tooltip"><i class="wpcico-eye"></i><?php echo $wpchats->translate(152); ?></a>
	</div>
	<?php echo apply_filters('_wpc_users_top_header_mod', ob_get_clean()); ?>
	<?php endif; ?>
	<?php if( is_user_logged_in() ):; ?>
	<?php ob_start(); ?>
	<div<?php echo !isset( $_GET["user"] ) && isset( $_GET["edit"] ) ? ' class="active"': ''; ?>>
		<a href="<?php echo $wpchats->get_settings('profile_page'); ?>?edit=1" class="wpc_edit_settings wpc-tooltip" data-user="<?php echo get_userdata($current_user->ID)->user_login; ?>"><i class="wpcico-wrench"></i><?php echo $wpchats->translate(153); ?></a>
	</div>
	<?php echo apply_filters('_wpc_users_top_header_edit', ob_get_clean()); ?>
	<?php do_action('_wpc_users_add_top_header_element'); ?>
	<?php endif; ?>
	<?php
	if( isset( $_GET["wpc_search"] ) ) {
		$countres = count( $wpchats->get_users('search', esc_attr($_GET["wpc_search"])) );
		if( $countres > 0 )
			echo '<div class="wpc-count-res">'.str_replace( '[number]', $countres, $wpchats->translate(154) ).":</div>";
	}
	?>
</div>
<?php echo apply_filters('_wpc_users_top_header', ob_get_clean()); ?>
<?php
if( isset($_GET["mod"]) && $wpc->is_user( 'mod', $current_user->ID ) && ! $wpc->is_user( 'banned', $current_user->ID ) || isset($_GET["edit"]) ) {
	$exit = true;
	if( isset($_GET["mod"]) ) {
		include( WPC_PLUGIN_PATH . 'admin/section/moderate.php');
	} else {
		if( isset($_GET["user"]) ) {
			wp_redirect( $wpchats->get_settings( "profile_page" ) . '?edit=1' );
			exit;	
		}
		if( $wpchats->get_settings('can_go_offline') || current_user_can('manage_options') ) {
			if( isset( $_GET["edit"] ) && isset( $_GET["go"] ) && in_array( $_GET["go"], array('offline', 'online') ) ) {
				$url = isset( $_GET["next"] ) ? $_GET["next"] : $wpchats->get_settings( 'profile_page' ) . '?user=' . $current_user->user_nicename;
				$url .= is_numeric( strpos( $url, '?' ) ) ? '&scs=' : '?scs=';
				if( $_GET["go"] == 'online' ) {
					
					delete_user_meta( $current_user->ID, 'wpc_go_offline' );
					wp_redirect( $url . '20' );
					exit;

				}
				if( $_GET["go"] == 'offline' ) {

					update_user_meta( $current_user->ID, 'wpc_go_offline', '1');
					wp_redirect( $url . '21' );
					exit;

				}
			}
		}
		include( WPC_PLUGIN_PATH . 'users/settings.php');
	}
} else {
	if(isset($_GET["user"])) {
		$redirect = true;
		include('user.php');
	} else {
		echo '<div id="wpc-users">';
		if( isset( $_GET["wpc_search"] ) ) {
			$countres = count( $wpchats->get_users('search', esc_attr($_GET["wpc_search"])) );
			if( $countres > 0 )
				echo '';
			else
				echo '<div class="wpc-0"><i class="wpcico-attention"></i>'.$wpchats->translate(155).'.</div>';
		}
		echo '<div id="wpc-users-cont">';
		$arCount = '';
		$res_per_pg = $wpchats->get_settings('users_pagination');
		if( ! isset( $_GET["wpc_search"] ) && ! isset( $_GET["filter"] ) )
			$arCount = count( get_users() );
		if( isset( $_GET["filter"] ) ) {
			$filter = esc_attr( $_GET["filter"] );
			if( $filter == "online" )
				$arCount = strval($wpchats->get_counts('online'));
			if( $filter == "blocked" )
				$arCount = strval($wpchats->get_counts('blocked'));
		}
		if( isset( $_GET["wpc_search"] ) )
			$arCount = count( $wpchats->get_users('search', esc_attr($_GET["wpc_search"])) );
		$total_pg_e = explode( '.', $arCount / $res_per_pg );
		$total 		= ( count( $total_pg_e ) > 1 ) ? abs( $total_pg_e[0] + 1 ) : $total_pg_e[0];
		$pg 		= ( isset( $_GET["pg"] ) && is_numeric( $_GET["pg"] ) && $_GET["pg"] > 0 ) ? $_GET["pg"] : '1';
		$pg 		= ( $pg > $total ) ? $total : $pg;
		$prev 		= ( $pg !== '' && $pg > 1 ) ? abs( $pg - 1 ) : '';
		$cur 		= ( $pg !== '' && is_numeric( $pg ) ) ? $pg : '';
		$nxt 		= $pg == 0 ? 2 : abs( $pg + 1 );
		$usersArray = array_slice( get_users(), ( $pg * $res_per_pg ) - $res_per_pg, $res_per_pg );
		shuffle($usersArray);
		foreach ( $usersArray as $user ) {
			$user_id = $user->ID;
			if( ! isset( $_GET["wpc_search"] ) && ! isset( $_GET["filter"] ) ) {
				include('card.php');
			}
		}
		if( isset( $_GET["filter"] ) && in_array( $_GET["filter"], array( 'online', 'blocked' ) ) ) {
			$filter = esc_attr( $_GET["filter"] );
			if( $filter == "online" ) {
				foreach ( array_slice( $wpchats->get_users('online', ''), ( $pg * $res_per_pg ) - $res_per_pg, $res_per_pg ) as $user_id ) {
					include('card.php');
				}
			}
			if( $filter == "blocked" ) {
				if( !is_user_logged_in() ) {
				  wp_redirect( $wpchats->get_settings( "profile_page" ).'?scs=19' );
				  exit;
				}
				foreach ( array_slice( $wpchats->get_users('blocked', ''), ( $pg * $res_per_pg ) - $res_per_pg, $res_per_pg ) as $user_id ) {
					$user_id == $user;
					include('card.php');
				}
			}
		}
		if( isset( $_GET["wpc_search"] ) ) {
			$q = esc_attr( $_GET["wpc_search"] );
			if( $q == '' ) {
				wp_redirect( $wpchats->get_settings( 'profile_page' ) );
				exit;
			}
			$count = count( $wpchats->get_users('search', esc_attr($_GET["wpc_search"])) );
			if( $count > 0 ) {
				foreach ( array_slice( $wpchats->get_users('search', esc_attr($_GET["wpc_search"])), ( $pg * $res_per_pg ) - $res_per_pg, $res_per_pg ) as $user_id ) {			
					$user_id == $user;
					include('card.php');
				}
			}
		}
		echo '</div>';
		if( $pg <= $total && $total > 1 ) {
			$amp 	= '';
			$extra 	= '';
			if( isset( $_GET["filter"] ) || isset( $_GET["wpc_search"] ) ) {
				$param = ( isset( $_GET["filter"] ) ) ? '?filter='.$_GET["filter"] : '?search='.$_GET["wpc_search"];
				$extra = ( isset( $_GET["filter"] ) ) ? 'data-todo="filter" data-content="' . $_GET["filter"] . '"' : 'data-todo="search" data-content="' . $_GET["wpc_search"] . '"';
				$amp .= $param .'&pg=';
			} else {
				$extra = 'data-todo="none" data-content="none"';
				$amp .= '?pg=';
			}
			echo '<div class="wpc-pagination" data-paginate="wpcusers">';
			echo ( $prev ) ? "<a href=\"$amp$prev\" class=\"wpc-prev wpc-tooltip\" data-target=\"$prev\"$extra>&laquo; ".$wpchats->translate(23)."</a>" : '';
			echo "<span>".$wpchats->translate(158)." $cur/$total</span>";
			echo ( $nxt <= $total ) ? "<a href=\"$amp$nxt\" class=\"wpc-next wpc-tooltip\" data-target=\"$nxt\"$extra>".$wpchats->translate(24)." &raquo;</a>" : '';
			echo '</div>';
		}
		if( isset( $_GET["filter"] ) ) {
			if( $filter == "online" ) {
				$online_count = $wpchats->get_counts('online');
				if( ! $online_count || $online_count == 0 ) {
					?>
						<div class="wpc-0">
							<i class="wpcico-attention"></i><?php echo $wpchats->translate(156); ?>.
							<a href="<?php echo $wpchats->get_settings('profile_page'); ?>?filter=online" class="wpc_users_online"><i class="wpcico-arrows-cw"></i><?php echo $wpchats->translate(1); ?></a>
						</div>
					<?php
				}
			}
			if( $filter == "blocked" && is_user_logged_in() ) {
				$blocked_count = $wpchats->get_counts('blocked');
				if( ! $blocked_count || $blocked_count = 0 ) {
					?>
						<div class="wpc-0">
							<i class="wpcico-attention"></i><?php echo $wpchats->translate(157); ?>.
						</div>
					<?php
				}
			}
		}
		echo "</div><!-- /#wpc-users -->";
	}
}
echo '</div>';
do_action('_wpc_after_users_page');