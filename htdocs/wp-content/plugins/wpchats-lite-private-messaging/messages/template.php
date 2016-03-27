<?php

require_once( ABSPATH . 'wp-blog-header.php' );
include_once( WPC_PLUGIN_PATH . 'classes/wpchats.php' );

$wpchats = new wpChats;
$wpc = new wpc;
if ( ! is_user_logged_in() ) {
	echo $wpchats->notices(19);
	return wp_login_form();
	return;
}
global $current_user;
if( isset( $_GET["scs"] ) ) {
	$wpchats->notices( $_GET["scs"] );
}
if( $wpchats->user_preferences( $current_user->ID, 'go_offline' ) ) :; ?>
<div class="wpc_notices wpc_fail">
	<p>
		<span><i class="wpcico-attention"></i>&nbsp;You are gone offline. <a href="<?php echo $wpchats->get_settings('profile_page'); ?>?edit=1&amp;go=online&amp;next=<?php echo $wpchats->get_settings('messages_page'); ?>"><?php echo $wpchats->translate(73); ?></a></span>
		<i class="wpcico-cancel cancel_notice" title="<?php echo $wpchats->translate(47); ?>"></i>
	</p>
</div>
<?php endif;
if ( ! isset( $_GET['recipient'] ) && ! isset( $_GET["conversation"] ) ) {
	if( isset( $_GET["todo"] ) ) {
		include_once('todo.php');	
	}
	echo !isset( $_GET["todo"] ) || $_GET["todo"] == '' || !in_array( $_GET["todo"], array( 'mark_unread', 'delete_conversation', 'block_user', 'unblock_user', 'delete_block', 'pm_report', 'pm_delete' ) ) ? $wpchats->messages_index() : '';
}
if ( isset( $_GET["recipient"] ) ) {
	$recipient = esc_attr( $_GET["recipient"] );
	$conversation = $wpchats->get_conversation_id( $current_user->ID, $recipient );
	$conversation_new = ( $wpchats->get_conversation( $conversation, 'id' ) != '' ) ? false : true;
	
	if ( $recipient == $current_user->ID || ! is_numeric( $_GET["recipient"] ) ) {
		wp_redirect( $wpchats->get_settings( "messages_page" ) );
		exit;
	}
	if ( ! get_userdata( $recipient )->ID ) {
		echo "<p>".$wpchats->translate(25)."</p>";
		return;
	}
	if ( ! $conversation_new ) {
		wp_redirect( $wpchats->get_settings( "messages_page" ) . "?conversation=$conversation" );
		exit;
	} else {
		$wpchats->new_conversation( $recipient );
	}

}
if ( isset( $_GET["conversation"] ) ) {
	$conversation = esc_attr( $_GET["conversation"] );
	if ( ! $wpchats->get_conversation( $conversation, 'chat_id' ) ) {
		wp_redirect( $wpchats->get_settings('messages_page').'?scs=7' );
		exit;
	}
	if ( $wpchats->get_conversation( $conversation, 'from' ) !== strval($current_user->ID) && $wpchats->get_conversation( $conversation, 'to' ) !== strval($current_user->ID) ) {
		$recipient 	= $wpchats->get_conversation( $conversation, 'to' ) == $current_user->ID ? $wpchats->get_conversation( $conversation, 'from' ) : $wpchats->get_conversation( $conversation, 'to' );
		$me 		=  $wpchats->get_conversation( $conversation, 'to' ) == $recipient ? $wpchats->get_conversation( $conversation, 'from' ) : $wpchats->get_conversation( $conversation, 'to' );
		if( 
			$wpc->is_user('mod', $current_user->ID) // current user is a moderator
			&& $wpchats->get_settings('mod_can_view_chats') // and admin has allowed mods to spy on converastions
			&& get_userdata($recipient) // recipient user exists
			&& ! in_array('administrator', get_userdata($recipient)->roles ) // recipient is not admin			
		)
		{
			if( get_userdata($me) && in_array('administrator', get_userdata($me)->roles ) ) // sender is admin, bail! no spying on admin, yo mod!
			{
				echo "<p>".$wpchats->translate(29)."</p>";
				return;
			}
			echo "<p><i class=\"wpcico-attention\"></i>".$wpchats->translate(74).":</p>";
		}
		else
		{
			if(! in_array('administrator', get_userdata($current_user->ID)->roles )) {
				echo "<p>Sorry, you are not part of this conversation.</p>";
				return;
			}
			echo "<p><i class=\"wpcico-attention\"></i>".$wpchats->translate(75).":</p>";			
		}

	}
	$current_user_name 	= get_userdata( $current_user->ID )->user_login;
	$recipient 			= ( $wpchats->get_conversation( $conversation, 'to' ) == $current_user->ID ) ? $wpchats->get_conversation( $conversation, 'from' ) : $wpchats->get_conversation( $conversation, 'to' );
	$me 				=  $wpchats->get_conversation( $conversation, 'to' ) == $recipient ? $wpchats->get_conversation( $conversation, 'from' ) : $wpchats->get_conversation( $conversation, 'to' );
	$recipient_name 	= get_userdata( $recipient )->display_name;
	if ( $recipient == $current_user->ID ) {
		return;
	}
	if ( ! get_userdata( $recipient )->ID ) {
		return "<p>".$wpchats->translate(40)."</p>";
		return;
	}
	if( isset($_GET["single"]) ) {
		$m = $_GET["single"] !== '' ? $_GET["single"] : false;
		$adi = isset($_GET["sh"]) ? true : false;
		if( $wpchats->get_message_parts($m, 'to') == $current_user->ID || $wpchats->get_message_parts($m, 'from') == $current_user->ID ) {
			echo $wpchats->single( $m, $adi );
			return;
		}
	}
	if( isset($_GET["load_input"]) ) {
		echo $wpchats->load_input($conversation, false);
		return;
	}
	if( isset($_GET["last"]) ) {
		global $wpdb;
		$me = $current_user->ID;
		$table = $wpdb->prefix."mychats";
		$q = $wpdb->get_results( "SELECT id FROM $table WHERE `chat_id` = '$conversation' AND `from` = '$me' ORDER BY `id` DESC LIMIT 1" );
		foreach( $q as $m ) {
			echo $m->id;
			return;
		}
	}
	$user_1 = $current_user->ID != $me ? get_userdata($me)->display_name : $wpchats->translate(87);// if moderators are allowed to view private conversations
	$sender = $me;
	ob_start();
	?>
	<div class="wpc-topcont">
		<?php ob_start(); ?>
		<div class="wpc-recipient-details">
			<a href="<?php echo $wpchats->user_links( 'link', $recipient, false ); ?>" title="<?php echo str_replace('[user]', $recipient_name, $wpchats->translate(76)); ?>"><?php echo $wpchats->user_avatar( $recipient, 50 ); ?></a>
			<div style="vertical-align: top;text-align: left;">
				<a href="<?php echo $wpchats->user_links( 'link', $recipient, false ); ?>" title="<?php echo str_replace('[user]', $recipient_name, $wpchats->translate(76)); ?>"><i class="wpcico-user"></i><?php echo $recipient_name; ?></a>
				<?php echo $wpc->is_user('mod', $recipient) ? '&mdash; <span style="font-style: italic;"><i class="wpcico-eye"></i>'.$wpchats->translate(78).'</span>' : ''; ?>
				<span style="display: block;"><?php echo $wpchats->online_status( $recipient ); ?></span>
			</div>
			<span style="display: block;"><?php echo str_replace( array( '[user-1]', '[user-2]' ), array( $user_1, $recipient_name ), $wpchats->translate(77) ); ?></span>
		</div>
		<?php echo apply_filters('_wpc_message_header_recipient_details', ob_get_clean(), $recipient, $sender ); ?>
		<?php ob_start(); ?>
		<div class="wpc-conversation-actions">
			<div><a href="<?php echo $wpchats->get_settings( "messages_page" ); ?>" class="wpc-btm wpc-tooltip">&laquo; <?php echo $wpchats->translate(79); ?></a></div>
			<div>
				<form action="<?php echo $wpchats->get_settings( "messages_page" ); ?>" type="get" id="single_search" class="wpc-tooltip">
					<input type="hidden" name="conversation" value="<?php echo $conversation; ?>">
					<input type="text" placeholder="<?php echo $wpchats->translate(2); ?>" <?php echo ! $wpchats->get_conversation( $conversation, 'last_from') ? 'disabled="disabled" title="<?php echo $wpchats->translate(80); ?>" style="cursor: not-allowed;"' : ''; ?> name="wpc_search" value="<?php echo isset($_GET["wpc_search"]) ? esc_attr($_GET["wpc_search"]) : ''; ?>" />
				</form>
			</div>
			<?php if($current_user->ID == $me): ;?>
			<div>
				<select id="single_actions" onchange="singleActions()" data-pm="<?php echo $conversation; ?>" data-user="<?php echo $recipient; ?>">
					<option value="0">&mdash; <?php echo $wpchats->translate(83); ?> &mdash;</option>
					<option <?php echo ($wpchats->get_conversation( $conversation, 'last_from') && $wpchats->get_conversation( $conversation, 'last_from')!=$current_user->ID) ? 'value="mark_unread"' : 'value="0" disabled'; ?>><?php echo $wpchats->translate(84); ?></option>
					<option <?php echo ($wpchats->get_conversation( $conversation, 'last_from')) ? 'value="delete_conversation"' : 'value="0" disabled'; ?>><?php echo $wpchats->translate(85); ?></option>
					<?php
					if( $wpchats->is_blocked( $recipient, '' ) ) {
						?><option value="unblock_user"><?php echo $wpchats->translate(82) . ' ' . get_userdata( $recipient )->user_nicename; ?></option><?php
					} else {
						?><option value="block_user"><?php echo $wpchats->translate(81) . ' ' . get_userdata( $recipient )->user_nicename; ?></option><?php
					}
					?>
					<option <?php echo $wpchats->get_conversation( $conversation, 'last_from') ? 'value="delete_block"' : 'value="0" disabled'; ?>><?php echo $wpchats->translate(86); ?></option>
				</select>
			</div>
			<?php endif; ?>
		</div>
		<?php echo apply_filters('_wpc_message_header_conversation_actions', ob_get_clean(), $recipient, $sender ); ?>

	</div>
	<div class="wpchats-container wpchats-single" id="wpc-scroll">
	<?php echo $wpchats->list_messages( $conversation ); ?>
	</div>
	<?php
	if($current_user->ID == $me) {
		echo $wpchats->get_conversation_form( $recipient );
		$wpchats->mark_seen( $conversation, false );
	}
	?>
	<script type="text/javascript">
		(function() {
			var ScrollCont = document.querySelector('#wpc-scroll');
			if(ScrollCont) {
				if( document.querySelector(".wpc-paginate") && document.querySelector(".wpc-paginate").getAttribute("data-next") > 2 )
					return false;
				else
					ScrollCont.scrollTop = ScrollCont.scrollHeight;
			}
		   //scrollIt();
		})();
		//jQuery(document).ready(function($) { scrollIt(); });
		function singleActions() {
		    var todo  = document.getElementById("single_actions").value;
		    if( todo !== '0' ) {
			    if( todo == 'block_user' || todo == 'unblock_user' ) {
			    	var url = "<?php echo $wpchats->get_settings( "messages_page" ); ?>?todo="+todo+"&user=<?php echo $recipient; ?>&rdr=messages&pm=<?php echo $conversation; ?>";
			    }
			    else if( todo == 'delete_block' ) {
			    	var url = "<?php echo $wpchats->get_settings( "messages_page" ); ?>?todo="+todo+"&pm=<?php echo $conversation; ?>&user=<?php echo $recipient; ?>&rdr=0";
			    } else {
			    	var url = "<?php echo $wpchats->get_settings( "messages_page" ); ?>?todo="+todo+"&pm=<?php echo $conversation; ?>";
			    }
		    	window.location.href = url;
			}
		}
	</script>
	<?php
	echo ob_get_clean();
}