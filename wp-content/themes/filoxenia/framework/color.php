<?php
$root =dirname(dirname(dirname(dirname(dirname(__FILE__)))));
if ( file_exists( $root.'/wp-load.php' ) ) {
    require_once( $root.'/wp-load.php' );
} elseif ( file_exists( $root.'/wp-config.php' ) ) {
    require_once( $root.'/wp-config.php' );
}
header("Content-type: text/css; charset=utf-8");
global $theme_option; 

function hex2rgb($hex) {

   $hex = str_replace("#", "", $hex);



   if(strlen($hex) == 3) {

      $r = hexdec(substr($hex,0,1).substr($hex,0,1));

      $g = hexdec(substr($hex,1,1).substr($hex,1,1));

      $b = hexdec(substr($hex,2,1).substr($hex,2,1));

   } else {

      $r = hexdec(substr($hex,0,2));

      $g = hexdec(substr($hex,2,2));

      $b = hexdec(substr($hex,4,2));

   }

   $rgb = array($r, $g, $b);

   //return implode(",", $rgb); // returns the rgb values separated by commas

   return $rgb; // returns an array with the rgb values

}

  $b=$theme_option['main-color'];

  $rgba = hex2rgb($b);

?>
/* Color Theme - Amethyst /Violet/
color - <?php echo esc_attr( $theme_option['main-color'] ); ?>
bg-footer - <?php echo esc_attr( $theme_option['background_footer'] ); ?>
bot-footer - <?php echo esc_attr( $theme_option['sub_footer'] ); ?>

/* 01 MAIN STYLES
****************************************************************************************************/

/**** Custom color ****/
header {border-top: 3px solid <?php echo esc_attr( $theme_option['main-color'] ); ?>;}
.top-bar-section ul li.has-form > a {background-color: <?php echo esc_attr( $theme_option['main-color'] ); ?>;}
a, a:hover, a:focus, .top-bar-section li.active:not(.has-form) a:not(.button),
.breadcrumbs > *, .breadcrumbs > * a, .top-bar-section ul#mainmenu li a:hover,
.hero h1, .features h4, .features i, .search-form:before,
.sidebar .widget > ul li a:hover, footer a:hover, .de_tab .de_nav li span.active,
.sliding-content li a:hover, .footer-widget.widget ul li a:hover, .features ul li:before {
	color: <?php echo esc_attr( $theme_option['main-color'] ); ?>;
}

.form-submit input[type=submit],
.widget_tag_cloud li a, .rectangle-bounce div,
input.secondary:hover, button, .button,
.pricing-table.highlight .title, .pricing-table.highlight .price span, .about-social a:hover,
.pagination li span.current, .pagination li a:hover, #wp-calendar tbody td#today
{
	background: <?php echo esc_attr( $theme_option['main-color'] ); ?>;
}

.pricing-table.highlight .price {
  background: rgba(<?php echo esc_attr( $rgba[0] ); ?>, <?php echo esc_attr( $rgba[1] ); ?>, <?php echo esc_attr( $rgba[2] ); ?>, 0.6);
}

footer{
  background-color: <?php echo esc_attr( $theme_option['background_footer'] ); ?>;
  color: <?php echo esc_attr( $theme_option['color_footer'] ); ?>;
}
footer p, footer a, footer .social a i{
  color: <?php echo esc_attr( $theme_option['color_footer'] ); ?>;
}

.images-preloader{
	background: <?php echo esc_attr( $theme_option['bg_color'] ); ?>;
}
blockquote {border-color: <?php echo esc_attr( $theme_option['main-color'] ); ?>;}

#back-top,
.popup-overlay{
 background: rgba(<?php echo esc_attr( $rgba[0] ); ?>, <?php echo esc_attr( $rgba[1] ); ?>, <?php echo esc_attr( $rgba[2] ); ?>, 0.6);
}

.filoxenia-search-domain .domain-search {
  background: <?php echo esc_attr( $theme_option['main-color'] ); ?>;
  border-color: <?php echo esc_attr( $theme_option['main-color'] ); ?>;
}