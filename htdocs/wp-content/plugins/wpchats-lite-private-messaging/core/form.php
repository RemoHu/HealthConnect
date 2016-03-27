<?php
require_once( '../classes/wpchats.php' );
require_once( './../../../../wp-blog-header.php' );
$wpchats = new wpChats;
if( !isset( $_GET['to'] ) || !isset( $_GET['message'] ) ) {
	wp_redirect( $wpchats->get_settings('messages_page') . '?scs=10' );
	exit;
}
$recipient = $_GET["to"];
$message = $_GET["message"];
global $current_user;
$message = str_replace("\n", '<br/>', $message);
$wpchats->send( $wpchats->get_conversation_id( $current_user->ID, $recipient ), $current_user->ID, $recipient, $message, time() );