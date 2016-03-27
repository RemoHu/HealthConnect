<?php
/**
 *  The template for displaying Header.
 *
 *  @package ThemeIsle.
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<header>
			<div class="wide-header">
				<div class="wrapper cf">
					<div id="header-top" class="cf">
						<div class="header-left cf">
							<a class="logo" href="<?php echo esc_url( home_url() ); ?>" title="<?php bloginfo( 'name' ); ?>">
								<?php
								if ( get_header_image() != NULL ) {
									echo '<img src="'. esc_url( get_header_image() ) .'" width="'. get_custom_header()->width .'" height="'. get_custom_header()->height .'" title="'. get_bloginfo( 'name' ) .'" alt="'. get_bloginfo( 'name' ) .'" />';
								} else {
									echo '<div class="logo-title">';
									echo get_bloginfo( 'name' );
									echo '</div><!--/.logo-title-->';
									echo '<div class="logo-description">';
									echo get_bloginfo( 'description' );
									echo '</div>';
								}
								?>
							</a><!--/a .logo-->
							<div class="header-contact">
								<?php
								if ( get_theme_mod( 'medica_lite_general_contactinfo_telephonetitle', 'Telephone' ) ) {
									echo esc_attr( get_theme_mod( 'medica_lite_general_contactinfo_telephonetitle', 'Telephone' ) ) . '<br />';
								} else {
									echo '<br />';
								}

								if ( get_theme_mod( 'medica_lite_general_contactinfo_telephonenumber', '+1 223 456 23' ) ) {
									echo '<a href="tel:'. esc_attr( get_theme_mod( 'medica_lite_general_contactinfo_telephonenumber', '+1 223 456 23' ) ) .'" title="'. esc_attr( get_theme_mod( 'medica_lite_general_contactinfo_telephonetitle', 'Telephone' ) ) .'"><i class="icon-phone"></i> '. get_theme_mod( 'medica_lite_general_contactinfo_telephonenumber', '+1 223 456 23' ) .'</a>';
								} else {
									echo '<div style="margin-top: 24px;"></div>';
								}
								?>
								<ul class="cf">
									<?php
									if ( get_theme_mod( 'medica_lite_general_socialslink_facebooklink', 'http://www.facebook.com' ) ) {
										echo '<li class="header-facebook-icon"><a href="'. esc_url( get_theme_mod( 'medica_lite_general_socialslink_facebooklink', 'http://www.facebook.com' ) ) .'" title="'. __( 'Facebook', 'medica-lite' ) .'" target="_blank"></a></li>';
									}

									if ( get_theme_mod( 'medica_lite_general_socialslink_twitterlink', 'http://www.twitter.com' ) ) {
										echo '<li class="header-twitter-icon"><a href="'. esc_url( get_theme_mod( 'medica_lite_general_socialslink_twitterlink', 'http://www.twitter.com' ) ) .'" title="'. __( 'Twitter', 'medica-lite' ) .'" target="_blank"></a></li>';
									}

									if ( get_theme_mod( 'medica_lite_general_socialslink_youtubelink', 'http://www.youtube.com' ) ) {
										echo '<li class="header-youtube-icon"><a href="'. esc_url( get_theme_mod( 'medica_lite_general_socialslink_youtubelink', 'http://www.youtube.com' ) ) .'" title="'. __( 'YouTube', 'medica-lite' ) .'" target="_blank"></a></li>';
									}

									if ( get_theme_mod( 'medica_lite_general_socialslink_linkedinlink', 'http://www.linkedin.com' ) ) {
										echo '<li class="header-linkedin-icon"><a href="'. esc_url( get_theme_mod( 'medica_lite_general_socialslink_linkedinlink', 'http://www.linkedin.com' ) ) .'" title="'. __( 'LinkedIn', 'medica-lite' ) .'" target="_blank"></a></li>';
									}
									?>
								</ul>
							</div><!--/div .header-contact-->
						</div><!--/div .header-left-->
					</div><!--/div #header-top-->
					<nav class="navigation">
						<div class="openresponsivemenu">
						</div><!--/.openresponsivemenu-->
						<div class="navigation-menu">
							<?php
							wp_nav_menu( array(
									'theme_location'	=> 'header-navigation',
									'container'			=> '',
									'container_class'	=> ''
								)
							);
							?>
						</div><!--/.navigation-menu-->
						</div>
					</nav><!--/.navigation-->
				</div><!--/div .wrapper-->
			</div><!--/div .wide-header-->