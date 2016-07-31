<!DOCTYPE html>

<!--[if IE 7]>

<html class="ie ie7" <?php language_attributes(); ?>>

<![endif]-->

<!--[if IE 8]>

<html class="ie ie8 no-js lt-ie9" <?php language_attributes(); ?>>

<![endif]-->

<!--[if !(IE 7) | !(IE 8) ]><!-->

<html <?php language_attributes(); ?>>

<!--<![endif]-->

<?php global $theme_option; ?>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<!-- Favicons  
	================================================== -->

	<?php if($theme_option['favicon']['url'] !=''){ ?>
        <link rel="icon" href="<?php echo esc_url($theme_option['favicon']['url']); ?>" type="image/x-icon">    
    <?php } ?>

	
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php if($theme_option['preload_opt'] != '0') { ?>
<div class="images-preloader">
    <div class="rectangle-bounce">
      <div class="rect1"></div>
      <div class="rect2"></div>
      <div class="rect3"></div>
      <div class="rect4"></div>
      <div class="rect5"></div>
    </div>
</div>
<?php } ?>
<div id="wrapper">
<?php if ( is_active_sidebar('slide-area-1') || is_active_sidebar('slide-area-2') || is_active_sidebar('slide-area-3') || is_active_sidebar('slide-area-4') ) : ?>
<div id="sliding-bar">
    <div class="sliding-content">
        <div class="container">
            <div class="row">
            <?php get_sidebar('header'); ?>
            </div>
        </div>
    </div>
    <div class="sliding-toggle"></div>
</div>
<?php endif; ?>

<!-- header begin -->
<header class="contain-to-grid">

    <div class="container">

        <div class="row">

            <div class="col-md-12">

                <nav class="top-bar" data-topbar role="navigation" data-options="back_text: <i class='fa fa-chevron-left'></i> Back; mobile_show_parent_link: false;">

                    <ul class="title-area">

                        <li class="name">

                            <a href="<?php echo esc_url( home_url() ); ?>">

                                <img src="<?php echo esc_url($theme_option['logo']['url']); ?>" alt="">

                            </a>

                        </li>

                        <li class="toggle-topbar menu-icon">

                            <a href="#"><span><?php _e('Menu','filoxenia'); ?></span></a>

                        </li>

                    </ul>



                    <div class="top-bar-section">

                        <?php

                            $primarymenu = array(

                            'theme_location'  => 'primary',

                            'menu'            => '',

                            'container'       => '',

                            'container_class' => '',

                            'container_id'    => '',

                            'menu_class'      => 'right',

                            'menu_id'         => 'mainmenu',

                            'echo'            => true,

                            'fallback_cb'     => 'wp_bootstrap_navwalker::fallback',

                            'walker'          => new wp_bootstrap_navwalker(),

                            'before'          => '',

                            'after'           => '',

                            'link_before'     => '',

                            'link_after'      => '',

                            'items_wrap'      => '<ul data-breakpoint="800" id="%1$s" class="%2$s">%3$s</ul>',

                            'depth'           => 0,

                        );

                        if ( has_nav_menu( 'primary' ) ) {

                            wp_nav_menu( $primarymenu );

                        }

                        ?>

                    </div>



                </nav>

            </div>

        </div>

    </div>

</header>

<div class="space"></div>

<main>