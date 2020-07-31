<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body>

	<div class="header_container">

		<div class="header_top_lane">
			
			<span id="header_logo">
				<a id="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"></a>
			</span>
			<span id="top-menu"> 
				 <?php wp_nav_menu( array(
					'theme_location' => 'second_nav',
					'container_id' => 'top-menu-nav',
					'walker' => new themeslug_walker_nav_menu()
					)); ?>
			</span>

			<span id="h_separator">|</span>
			<span id="social-icons">
				<a href="#"><span id="social-github">
				</span></a>
				<a href="#"><span id="social-facebook">
				</span></a>
				<a href="#"><span id="social-twitter">
				</span></a>
				<a href="#"><span id="social-wordpress">
				</span></a>
			</span>		
		</div>
	</div>

		<?php wp_nav_menu( array(
				'theme_location' => 'first_nav',
 				'container_id' => 'main-nav',
				'walker' => new themeslug_walker_nav_menu()
		)); ?>


