<?php
if ( ! class_exists( 'ReduxFramewrk' ) ) {
    require_once( get_template_directory() . '/framework/sample-config.php' );
    function removeDemoModeLink() { // Be sure to rename this function to something more unique
        if ( class_exists('ReduxFrameworkPlugin') ) {
            remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2 );
        }
        if ( class_exists('ReduxFrameworkPlugin') ) {
            remove_action('admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );    
        }
    }
    add_action('init', 'removeDemoModeLink');
}
//Custom fields:
require_once get_template_directory() . '/framework/bfi_thumb-master/BFI_Thumb.php';
require_once get_template_directory() . '/framework/meta-boxes.php';
require_once get_template_directory() . '/framework/widget/widget.php';
require_once get_template_directory() . '/shortcodes.php';
require_once get_template_directory() . '/framework/wp_bootstrap_navwalker.php';

//Theme Set up:
function filoxenia_theme_setup() {

   /*

     * Make theme available for translation.

     * Translations can be filed in the /languages/ directory.

     * If you're building a theme based on cubic, use a find and replace

     * to change 'cubic' to the name of your theme in all the template files

     */

    load_theme_textdomain( 'filoxenia', get_template_directory() . '/languages' );

    /** Set Content width **/
    if ( ! isset( $content_width ) ) {
        $content_width = 900;
    }
    /*
     * This theme uses a custom image size for featured images, displayed on
     * "standard" posts and pages.
     */
	add_theme_support( 'custom-header' ); 
	add_theme_support( 'custom-background' );
	add_theme_support( "title-tag" );
    add_theme_support( 'post-thumbnails' );
    // Adds RSS feed links to <head> for posts and comments.
    add_theme_support( 'automatic-feed-links' );
    // Switches default core markup for search form, comment form, and comments
    // to output valid HTML5.
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );
    //Post formats
    add_theme_support( 'post-formats', array(
        'audio',  'gallery', 'image', 'video',
    ) );    
    // This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary'   => __('Primary Menu', 'filoxenia')
	) );
}
add_action( 'after_setup_theme', 'filoxenia_theme_setup' );

function filoxenia_load_custom_wp_admin_style() {
        wp_register_style( 'custom_wp_admin_css', get_template_directory_uri() . '/framework/admin-style.css', false, '1.0.0' );
        wp_enqueue_style( 'custom_wp_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'filoxenia_load_custom_wp_admin_style' );

function filoxenia_theme_scripts_styles() {
	global $theme_option;
	$protocol = is_ssl() ? 'https' : 'http';

    /** Google Web Font **/	
    wp_enqueue_style( 'fonts-OpenSans', "$protocol://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,700,700italic", true);
	wp_enqueue_style( 'fonts-Raleway', "$protocol://fonts.googleapis.com/css?family=Raleway:300,400,700", true);
	
    /** All frontend css files **/ 
    wp_enqueue_style( 'filoxenia-bootstrap', get_template_directory_uri().'/css/bootstrap.css');
    wp_enqueue_style( 'filoxenia-animate', get_template_directory_uri().'/css/animate.css');
	wp_enqueue_style( 'filoxenia-magnific-popup', get_template_directory_uri().'/css/magnific-popup.css');
    wp_enqueue_style( 'filoxenia-carousel', get_template_directory_uri().'/css/owl.carousel.css');
    wp_enqueue_style( 'filoxenia-owl-theme', get_template_directory_uri().'/css/owl.theme.css');
    wp_enqueue_style( 'filoxenia-flexslider-theme', get_template_directory_uri().'/css/flexslider.css');
	wp_enqueue_style( 'filoxenia-style', get_stylesheet_uri(), array(), '21-05-2015' );		
	wp_enqueue_style( 'filoxenia-font-awesome', get_template_directory_uri().'/css/font-awesome/css/font-awesome.css');
	
    /** theme option for color **/
    wp_enqueue_style( 'filoxenia-color', get_template_directory_uri() .'/framework/color.php');
		
    /** Js for comment on single post **/    
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ){
    	wp_enqueue_script( 'comment-reply' );
	}

    /** All frontend js files **/	
    wp_enqueue_script("filoxenia-modernizr", get_template_directory_uri()."/js/modernizr.js",array('jquery'),false,false);
    wp_enqueue_script("filoxenia-maps-js", "$protocol://maps.googleapis.com/maps/api/js?v=3.exp&hl=en&sensor=true",array(),false,true);
	wp_enqueue_script("filoxenia-fastclick", get_template_directory_uri()."/js/fastclick.js",array(),false,true);
    wp_enqueue_script("filoxenia-fitvids", get_template_directory_uri()."/js/jquery.fitvids.js",array(),false,true);
	wp_enqueue_script("filoxenia-carousel-js", get_template_directory_uri()."/js/owl.carousel.min.js",array(),false,true);
    wp_enqueue_script("filoxenia-flexslider-theme", get_template_directory_uri()."/js/jquery.flexslider-min.js",array(),false,true);
	wp_enqueue_script("filoxenia-magnific", get_template_directory_uri()."/js/jquery.magnific-popup.js",array(),false,true);
	wp_enqueue_script("filoxenia-foundation", get_template_directory_uri()."/js/foundation.js",array(),false,true);
    wp_enqueue_script("filoxenia-easing", get_template_directory_uri()."/js/easing.js",array(),false,true);
	wp_enqueue_script("filoxenia-smooth-scroll", get_template_directory_uri()."/js/jquery.smooth-scroll.js",array(),false,true);
    wp_enqueue_script("filoxenia-instagram", get_template_directory_uri()."/js/specinstagram.min.js",array(),false,false);		
	wp_enqueue_script("filoxenia-custom", get_template_directory_uri()."/js/filoxenia.js",array(),false,true);		
	
}
add_action( 'wp_enqueue_scripts', 'filoxenia_theme_scripts_styles');

if(!function_exists('filoxenia_custom_frontend_style')){
	function filoxenia_custom_frontend_style(){
	global $theme_option;
	echo '<style type="text/css">'.$theme_option['custom-css'].'</style>';
}
}
add_action('wp_head', 'filoxenia_custom_frontend_style');

// Widget Sidebar
function filoxenia_widgets_init() {
	register_sidebar( array(
        'name'          => __( 'Primary Sidebar', 'filoxenia' ),
        'id'            => 'sidebar-1',        
		'description'   => __( 'Appears in the sidebar section of the site.', 'filoxenia' ),        
		'before_widget' => '<div id="%1$s" class="widget %2$s">',        
		'after_widget'  => '</div>',        
		'before_title'  => '<h4>',        
		'after_title'   => '</h4>'
    ) );
    register_sidebar( array(
		'name'          => __( 'Footer One Widget Area', 'filoxenia' ),
		'id'            => 'footer-area-1',
		'description'   => __( 'Footer Widget that appears on the Footer.', 'filoxenia' ),
		'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer Two Widget Area', 'filoxenia' ),
		'id'            => 'footer-area-2',
		'description'   => __( 'Footer Widget that appears on the Footer.', 'filoxenia' ),
		'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer Three Widget Area', 'filoxenia' ),
		'id'            => 'footer-area-3',
		'description'   => __( 'Footer Widget that appears on the Footer.', 'filoxenia' ),
		'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer Fourth Widget Area', 'filoxenia' ),
		'id'            => 'footer-area-4',
		'description'   => __( 'Footer Widget that appears on the Footer.', 'filoxenia' ),
		'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	) );

    register_sidebar( array(
        'name'          => __( 'Bottom Footer Widget Area', 'filoxenia' ),
        'id'            => 'bottom-footer',
        'description'   => __( 'Bottom Footer Widget that appears on the Footer.', 'filoxenia' ),
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => __( 'Sliding One Widget Area', 'filoxenia' ),
        'id'            => 'slide-area-1',
        'description'   => __( 'Sliding Widget that appears on the Top.', 'filoxenia' ),
        'before_widget' => '<div id="%1$s" class="widget sliding-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Sliding Two Widget Area', 'filoxenia' ),
        'id'            => 'slide-area-2',
        'description'   => __( 'Sliding Widget that appears on the Top.', 'filoxenia' ),
        'before_widget' => '<div id="%1$s" class="widget sliding-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Sliding Three Widget Area', 'filoxenia' ),
        'id'            => 'slide-area-3',
        'description'   => __( 'Sliding Widget that appears on the Top.', 'filoxenia' ),
        'before_widget' => '<div id="%1$s" class="widget sliding-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Sliding Fourth Widget Area', 'filoxenia' ),
        'id'            => 'slide-area-4',
        'description'   => __( 'Sliding Widget that appears on the Top.', 'filoxenia' ),
        'before_widget' => '<div id="%1$s" class="widget sliding-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ) );

}
add_action( 'widgets_init', 'filoxenia_widgets_init' );

function filoxenia_breadcrumbs() {
    $text['home']     = __('Home', 'filoxenia'); // text for the 'Home' link
    $text['category'] = '%s'; // text for a category page
    $text['tax']      = '%s'; // text for a taxonomy page
    $text['search']   = '%s'; // text for a search results page
    $text['tag']      = '%s'; // text for a tag page
    $text['author']   = '%s'; // text for an author page
    $text['404']      = '404'; // text for the 404 page
 
    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $showOnHome  = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter   = ''; // delimiter between crumbs
    $before      = '<li class="active">'; // tag before the current crumb
    $after       = '</li>'; // tag after the current crumb
    
 
    global $post;
    $homeLink = home_url() . '';
    $linkBefore = '<li>';
    $linkAfter = '</li>';
    $linkAttr = ' rel="v:url" property="v:title"';
    $link = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;
 
    if (is_home() || is_front_page()) {
 
        if ($showOnHome == 1) echo '<div id="crumbs"><a href="' . $homeLink . '">' . $text['home'] . '</a></div>';
 
    } else {
 
        echo '<ul class="breadcrumbs animated bounceInDown">' . sprintf($link, $homeLink, $text['home']) . $delimiter;
 
        
        if ( is_category() ) {
            $thisCat = get_category(get_query_var('cat'), false);
            if ($thisCat->parent != 0) {
                $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                echo htmlspecialchars_decode( $cats );
            }
            echo htmlspecialchars_decode( $before ) . sprintf($text['category'], single_cat_title('', false)) . htmlspecialchars_decode( $after );
 
        } elseif( is_tax() ){
            $thisCat = get_category(get_query_var('cat'), false);
            if ($thisCat->parent != 0) {
                $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                echo htmlspecialchars_decode( $cats );
            }
            echo htmlspecialchars_decode( $before ) . sprintf($text['tax'], single_cat_title('', false)) . htmlspecialchars_decode( $after );
        
        }elseif ( is_search() ) {
            echo htmlspecialchars_decode( $before ) . sprintf($text['search'], get_search_query()) . htmlspecialchars_decode( $after );
 
        } elseif ( is_day() ) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
            echo htmlspecialchars_decode( $before ) . get_the_time('d') . htmlspecialchars_decode( $after );
 
        } elseif ( is_month() ) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo htmlspecialchars_decode( $before ) . get_the_time('F') . htmlspecialchars_decode( $after );
 
        } elseif ( is_year() ) {
            echo htmlspecialchars_decode( $before ) . get_the_time('Y') . htmlspecialchars_decode( $after );
 
        } elseif ( is_single() && !is_attachment() ) {
            if ( get_post_type() != 'post' ) {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                if ( get_post_type() == 'portfolio' ) {
                 printf(''); //Translate breadcrumb.
             }else{
              printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
             }
                if ($showCurrent == 1) echo htmlspecialchars_decode( $delimiter ) . $before . get_the_title() . $after;
            } else {
                $cat = get_the_category(); $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, $delimiter);
                if ($showCurrent == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                echo htmlspecialchars_decode( $cats );
                if ($showCurrent == 1) echo htmlspecialchars_decode( $before ) . get_the_title() . $after;
            }
 
        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
            $post_type = get_post_type_object(get_post_type());
            echo htmlspecialchars_decode( $before ) . $post_type->labels->singular_name . htmlspecialchars_decode( $after );
 
        } elseif ( is_attachment() ) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID); $cat = $cat[0];
            $cats = get_category_parents($cat, TRUE, $delimiter);
            $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
            $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
            echo htmlspecialchars_decode( $cats );
            printf($link, get_permalink($parent), $parent->post_title);
            if ($showCurrent == 1) echo htmlspecialchars_decode( $delimiter ) . $before . get_the_title() . $after;
 
        } elseif ( is_page() && !$post->post_parent ) {
            if ($showCurrent == 1) echo htmlspecialchars_decode( $before ) . get_the_title() . $after;
 
        } elseif ( is_page() && $post->post_parent ) {
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                $parent_id  = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            for ($i = 0; $i < count($breadcrumbs); $i++) {
                echo htmlspecialchars_decode( $breadcrumbs[$i] );
                if ($i != count($breadcrumbs)-1) echo htmlspecialchars_decode( $delimiter );
            }
            if ($showCurrent == 1) echo htmlspecialchars_decode( $delimiter ) . $before . get_the_title() . $after;
 
        } elseif ( is_tag() ) {
            echo htmlspecialchars_decode( $before ) . sprintf($text['tag'], single_tag_title('', false)) . $after;
 
        } elseif ( is_author() ) {
             global $author;
            $userdata = get_userdata($author);
            echo htmlspecialchars_decode( $before ) . sprintf($text['author'], $userdata->display_name) . $after;
 
        } elseif ( is_404() ) {
            echo htmlspecialchars_decode( $before ) . $text['404'] . $after;
        }
 
        if ( get_query_var('paged') ) {
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() );
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
        }
 
        echo '</ul>';
 
    }
}

/**custom function tag widgets**/
function filoxenia_tag_cloud_widget($args) {
	$args['number'] = 0; //adding a 0 will display all tags
	$args['largest'] = 18; //largest tag
	$args['smallest'] = 11; //smallest tag
	$args['unit'] = 'px'; //tag font unit
	$args['format'] = 'list'; //ul with a class of wp-tag-cloud
	$args['exclude'] = array(20, 80, 92); //exclude tags by ID
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'filoxenia_tag_cloud_widget' );

/** Custom theme option post excerpt **/
function filoxenia_excerpt() {
  global $theme_option;
  if(isset($theme_option['blog_excerpt'])){
    $limit = $theme_option['blog_excerpt'];
  }else{
    $limit = 15;
  }
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }
  $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
  return $excerpt;
}

/** Excerpt Section Blog Post **/
function filoxenia_blog_excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }
  $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
  return $excerpt;
}

//pagination
function filoxenia_pagination($prev = '<i class="fa fa-angle-double-left"></i>', $next = '<i class="fa fa-angle-double-right"></i>', $pages='') {
    global $wp_query, $wp_rewrite;
    $wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
    if($pages==''){
        global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
    }
    $pagination = array(
		'base' 			=> str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ),
		'format' 		=> '',
		'current' 		=> max( 1, get_query_var('paged') ),
		'total' 		=> $pages,
		'prev_text' => $prev,
        'next_text' => $next, 'type'	=> 'list',
		'end_size'		=> 3,
		'mid_size'		=> 3
);
    $return =  paginate_links( $pagination );
	echo str_replace( "<ul class='page-numbers'>", '', $return );
}

/* Custom form search */
function filoxenia_search_form( $form ) {
    $form = '<form role="search" method="get" id="searchform" class="search-form" action="' . home_url( '/' ) . '" >  
    	<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="'.__('type to search and hit enter', 'filoxenia').'" />
    	<input type="submit" class="submit-search" value="ok" name="" id=""/>
    </form>';
    return $form;
}
add_filter( 'get_search_form', 'filoxenia_search_form' );

/* Custom comment List: */
function filoxenia_theme_comment($comment, $args, $depth) {    
   $GLOBALS['comment'] = $comment; ?>
   <li class="post-content-comment grey-section">
   		<div class="img">
		<?php echo get_avatar($comment,$size='100',$default='http://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=70' ); ?>
		</div>
		<div class="comment-content">
			<h6><?php printf(__('%s','filoxenia'), get_comment_author()) ?></h6>
		</div>		
		<div class="date">
			<span class="c_date"><?php the_time('dS M Y'); ?></span>
            <span class="c_reply"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></span>
		</div>
		<div class="comment-content">
		<?php if ($comment->comment_approved == '0'){ ?>
			 <p><em><?php _e('Your comment is awaiting moderation.','filoxenia') ?></em></p>
		<?php }else{ ?>
            <?php comment_text() ?>
         <?php } ?>
		</div>		
	   <div class="clearfix"></div>	
	</li> 
<?php
}

//Code Visual Compurso.
require_once get_template_directory() . '/vc_shortcode.php';

// Add new Param in Row
if(function_exists('vc_add_param')){

vc_add_param('vc_row',array(
                              "type" => "dropdown",
                              "heading" => __('Fullwidth', 'wpb'),
                              "param_name" => "fullwidth",
                              "value" => array(   
                                                __('No', 'wpb') => 'no',  
                                                __('Yes', 'wpb') => 'yes',                                                                                
                                              ),
                              "description" => __("Select Fullwidth or not, Default: No fullwidth", "wpb"),      
                            ) 
    );
	
// Add new Param in Column	

vc_add_param('vc_column',array(
                              "type" => "textfield",
                              "heading" => __('Container Class', 'wpb'),
                              "param_name" => "wap_class",
                              "value" => "",
                              "description" => __("Container Class", "wpb"),      
                            ) 
    );
	
    vc_remove_param( "vc_row", "el_id" );
    vc_remove_param( "vc_row", "parallax" );
    vc_remove_param( "vc_row", "parallax_image" );
    vc_remove_param( "vc_row", "full_width" );
    vc_remove_param( "vc_row", "full_height" );
    vc_remove_param( "vc_row", "video_bg" );
    vc_remove_param( "vc_row", "video_bg_parallax" );
    vc_remove_param( "vc_row", "content_placement" );
    vc_remove_param( "vc_row", "video_bg_url" );
    vc_remove_param( "vc_row", "parallax_speed_bg" );
    vc_remove_param( "vc_row", "parallax_speed_video" );
    vc_remove_param( "vc_row", "columns_placement" );
    vc_remove_param( "vc_row", "equal_height" );
    vc_remove_param( "vc_row", "gap" );
    vc_remove_element( "vc_basic_grid" ); //Note: "vc_basic_grid" was used in the vc_map() function call as a base parameter for "Post Grid" element
    vc_remove_element( "vc_posts_slider" ); //Note: "vc_posts_slider" was used in the vc_map() function call as a base parameter for "Posts Slider" element
    vc_remove_element( "vc_media_grid" ); //Note: "vc_media_grid" was used in the vc_map() function call as a base parameter for "Media Grid" element
    vc_remove_element( "vc_masonry_grid" ); //Note: "vc_masonry_grid" was used in the vc_map() function call as a base parameter for "Post Masonry Grid" element
    vc_remove_element( "vc_masonry_media_grid" ); //Note: "vc_masonry_media_grid" was used in the vc_map() function call as a base parameter for "Masonry Media Grid" element

}
//}

require_once get_template_directory() . '/framework/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'filoxenia_register_required_plugins' );
function filoxenia_register_required_plugins() {
    $plugins = array(
        // This is an example of how to include a plugin from the WordPress Plugin Repository.      
        array(
            'name'               => 'Meta Box',
            'slug'               => 'meta-box',
            'required'           => true,
            'force_activation'   => false,
            'force_deactivation' => false,
        ),
        array(
            'name'      => 'Redux Framework',
            'slug'      => 'redux-framework',
            'required'           => true,
            'force_activation'   => false,
            'force_deactivation' => false,
        ),
        array(
            'name'      => 'Contact Form 7',
            'slug'      => 'contact-form-7',
            'required'  => true,
        ),
        array(
            'name'      => 'Newsletter',
            'slug'      => 'newsletter',
            'required'  => false,
        ),  
        array(            
            'name'               => 'WPBakery Visual Composer', // The plugin name.
            'slug'               => 'js_composer', // The plugin slug (typically the folder name).
            'source'             => get_template_directory_uri() . '/framework/plugins/js_composer.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
        ),         
        array(            
            'name'               => 'Slider Revolution', // The plugin name.
            'slug'               => 'revslider', // The plugin slug (typically the folder name).
            'source'             => get_template_directory_uri() . '/framework/plugins/revslider.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
        ),         
        array(            
            'name'               => 'OT One Click Import Demo Content', // The plugin name.
            'slug'               => 'ot-themes-one-click-import', // The plugin slug (typically the folder name).
            'source'             => get_template_directory_uri() . '/framework/plugins/ot-themes-one-click-import.zip', // The plugin source.
            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
        ),
    );
    $config = array(
        'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug'  => 'themes.php',            // Parent menu slug.
        'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
    );

    tgmpa( $plugins, $config );
}

// add by jack

function wpdm_new_download_notification($post_id, $post, $update){

// package is an array with all package data, try
// print_r($package);
//to do: write your notification code here
	
	if($update){
		return;
	}
	
	if ( $post->post_type != "wpdmpro" ) {
		return;
	}
	
	if ( $post->post_status != "publish" ){
		return;
	}
	
	if ( wp_is_post_revision( $post_id ) ){
		return;
	}
	
	$today = getdate();
	$search_arr = array('[site_name]','[date]','[package_name]','[download_url]');
	$replace_arr = array('QTech Technology', $today, $post->post_name, $post->post_name);
	$message = str_replace($search_arr, $replace_arr, file_get_contents(wpdm_tpl_path('wpdm-email-lock-template.html',WPDM_BASE_DIR.'email-templates/')));
	
	//From: ' . $eml['fromname'] . ' <' . $eml['frommail'] . '>' . "\r\n
	$headers = "Content-type: text/html\r\n";
	
	$subscribers = get_users( 'role=subscriber' );
	// Array of WP_User objects.
	foreach ( $subscribers as $subscriber ) {
		
		//error_log($subscriber->display_name);
		wp_mail($subscriber->user_email, "New File Download Available From QTech", stripcslashes($message), $headers);
				
	}

}

add_action("save_post", "wpdm_new_download_notification", 10, 3);


class Logout_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
				'classname' => 'logout_widget',
				'description' => 'Logout Widget',
		);
		parent::__construct( 'logout_widget', 'Logout_Widget', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget		
		echo do_shortcode('[ihc-logout-link]');
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
	}
}

add_action( 'widgets_init', function(){
	register_widget( 'Logout_Widget' );
});

?>