<?php
$classes = $wpc->is_user('mod', $user_id) ? ' moderator': '';
$classes .= $wpchats->is_blocked( $user_id, '' ) ? ' blocked': '';
$classes .= $wpchats->is_online( $user_id ) ? ' online': ' offline';
$classes .= $wpchats->get_settings('rtl') ? ' rtl' : '';
$wpc_user_ajax = is_numeric( strpos( $wpchats->user_links('link', $user_id, ''), $wpchats->get_settings('profile_page') ) ) ? 'wpc_user_ajax' : '';
ob_start();
?>
<div class="wpc-user-card<?php echo $classes; ?>">
	<div class="user-avatar-cont">
		<a href="<?php echo $wpchats->user_links('link', $user_id, ''); ?>" class="<?php echo $wpc_user_ajax; ?>" data-user="<?php echo get_userdata($user_id)->user_login; ?>">
			<?php echo $wpchats->user_avatar( $user_id, apply_filters('_wpc_user_card_avatar_size', $size = 80 ) ); ?>
			<span><?php echo get_userdata( $user_id )->display_name; ?></span>

		</a>
	</div>
	<div class="user-info">
		<?php if($wpc->is_user('mod', $user_id)):; ?>
			<div><i class="wpcico-eye"></i><?php echo $wpchats->translate(78); ?></div>
		<?php endif; ?>			
		<?php echo $wpchats->online_status( $user_id ); ?>
		<div><a href="<?php echo $wpchats->user_links('link', $user_id, ''); ?>" class="<?php echo $wpc_user_ajax; ?>" data-user="<?php echo get_userdata($user_id)->user_login; ?>"><i class="wpcico-user-1"></i><?php echo $wpchats->translate(141); ?></a></div>
		<?php if( intval($current_user->ID) !== intval($user_id) ):; ?>
			<div><?php echo $wpchats->user_links( 'message', $user_id, ''); ?></div>
		<?php endif; ?>
		<?php if($current_user->ID !== $user_id):; ?>
			<div><?php echo $wpchats->user_links( 'block', $user_id, 'users' ); ?></div>
		<?php endif; ?>
		<?php do_action('_wpc_add_user_card_element', $user_id); ?>
	</div>
</div>
<?php

echo apply_filters('_wpc_user_card_content', ob_get_clean(), $user_id);