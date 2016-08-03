<?php
/**
 * Register FF Tab Widget
 * @since 0.0.1
 */
 
function register_Inspira_Tab_Widget() {
    register_widget( 'Inspira_Tab_Widget' );
}
add_action( 'widgets_init', 'register_Inspira_Tab_Widget' );

function inspira_set_post_views($postID) {
    $count_key = 'inspira_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}


/**
 * Add filter to single post
 * @since 0.0.1
 * updated 1.0
 */
 

function inspira_post_view($content) {
    if(is_single()){
        $content .= inspira_set_post_views(get_the_ID());
        // return Dotted_get_post_views(get_the_ID());
    }
    return $content;
}

add_filter( 'the_content', 'inspira_post_view' ); 
 

/**
 * FF Tab Widget Class
 * @since 0.0.1
 */


class Inspira_Tab_Widget extends WP_Widget {
    
    /**
     * Widget actual process
     * Reference: http://codex.wordpress.org/Plugins/WordPress_Widgets_Api
     * @since 0.0.1
     */
    
    public function __construct() {
        $widget_ops = array('classname' => 'Inspira_tab_widget', 'description' => __('Display popular posts, recent posts, recent commets, and tags in an animated tabs.', 'filoxenia'));
        $control_ops = array('width' => 250, 'height' => 350);
        parent::__construct(null, __('Filoxenia Tab Widget', 'filoxenia'), $widget_ops, $control_ops);
    }
    
    
    
    
    /**
     * Widget output
     * Reference: http://codex.wordpress.org/Plugins/WordPress_Widgets_Api
     * @since 0.0.1
     */ 
     
    public function widget( $args, $instance ) {
        extract( $args );
        $pop = empty( $instance['pop'] ) ? '' : $instance['pop'];
        $poplimit = empty( $instance['poplimit'] ) ? '' : $instance['poplimit'];
        $recent = empty( $instance['recent'] ) ? '' : $instance['recent'];
        $recentlimit = empty( $instance['recentlimit'] ) ? '' : $instance['recentlimit'];        
        $title = apply_filters( 'widget_title', $instance['title'] );
        echo htmlspecialchars_decode( $before_widget );
        if ( ! empty( $title ) )
            echo htmlspecialchars_decode( $args['before_title'] ) . esc_attr( $title ) . htmlspecialchars_decode( $args['after_title'] );
    ?>  

    <div class="tab-holder">
        <div class="de_tab">
            <ul class="de_nav">
                <li><span class="active"><?php echo esc_attr( $instance['pop'] ); ?></span></li>
                <li><span><?php echo esc_attr( $instance['recent'] ); ?></span></li>
            </ul>

            <div class="de_tab_content">

                <div class="tab-small-post">
                    <ul>
                    <?php 
                       
                       // Popular posts
                          
                        $popular = new WP_Query( array( 
                         'posts_per_page' => $poplimit,
                         'meta_key' => 'inspira_post_views_count', 
                         'orderby' => 'meta_value_num', 
                         'order' => 'DESC'  
                        ) );?>
                     <?php 
                        while ($popular->have_posts()) : $popular->the_post();?>
                        <li>
                            <?php $params = array( 'width' => 52, 'height' => 52 );
                            $url = bfi_thumb( wp_get_attachment_url(get_post_thumbnail_id()), $params ); ?>
                            <?php if($url) { ?>
                            <img src="<?php echo esc_url( $url ); ?>" alt="">
                            <?php } ?>
                            <span class="post-content <?php if(!$url) echo 'post-content-no-image'; ?>">
                                <a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
                            </span>
                            <span class="post-date <?php if(!$url) echo 'post-date-no-image'; ?>">
                                <?php the_time('M d, Y'); ?>
                            </span>                            
                        </li>

                        <?php endwhile; ?>
                    </ul>
                </div>

                <div class="tab-small-post">
                    <ul>
                        <?php 
                        $recent = new WP_Query( array(
                        'post_type' => 'post', 
                        'posts_per_page' => $recentlimit,
                          ) );
                        while ($recent->have_posts()) :$recent-> the_post();?>
                        <li>
                            <?php $params = array( 'width' => 52, 'height' => 52 );
                            $url = bfi_thumb( wp_get_attachment_url(get_post_thumbnail_id()), $params ); ?>
                            <?php if($url) { ?>
                            <img src="<?php echo esc_url( $url ); ?>" alt="">
                            <?php } ?>
                            <span class="post-content <?php if(!$url) echo 'post-content-no-image'; ?>">
                                <a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></span>
                            <span class="post-date <?php if(!$url) echo 'post-date-no-image'; ?>">
                            <?php the_time('M d, Y'); ?>
                            </span>
                        </li>
                        <?php endwhile; ?>                       
                    </ul>
                </div>
            </div>
        </div>
    </div>       
        
    <?php   
        
        echo htmlspecialchars_decode( $after_widget );
        
    }   
    
    /**
     * Widget form on admin
     * Reference: http://codex.wordpress.org/Plugins/WordPress_Widgets_Api
     * @since 0.0.1
     */ 
    
    public function form( $instance ) {

        $instance = wp_parse_args( (array) $instance, array(  
            'title'         => '',
            'poplimit'      => 3, 
            'recentlimit'   => 3,             
            'pop'           => 'Popular',
            'recent'            => 'Recent',                                 
        ) );
        $pop = strip_tags($instance['pop']);
        $poplimit = strip_tags($instance['poplimit']);
        $recent = strip_tags($instance['recent']);
        $recentlimit = strip_tags($instance['recentlimit']);        
        $title = isset( $instance['title'] ) ? $instance['title'] : 'Blog Post';

    ?>  
        <p>
            <label for="<?php echo htmlspecialchars_decode( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'filoxenia' ); ?></label> 
            <input class="widefat" id="<?php echo htmlspecialchars_decode( $this->get_field_id( 'title' ) ); ?>" name="<?php echo htmlspecialchars_decode( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <!-- = Popular posts setting -->
    
        <p><span><strong><?php _e('Popular posts:', 'filoxenia'); ?></strong></span><br />
        
        <label for="<?php echo htmlspecialchars_decode( $this->get_field_id('pop') ); ?>"><?php _e('Tab name: ', 'filoxenia');?></label><input id="<?php echo htmlspecialchars_decode( $this->get_field_id('pop') ); ?>" class="widefat" name="<?php echo htmlspecialchars_decode( $this->get_field_name('pop') ); ?>" type="text" value="<?php echo esc_attr($pop); ?>" /><br />
        <label for="<?php echo htmlspecialchars_decode( $this->get_field_id('poplimit') ); ?>"><?php _e('Show number:', 'filoxenia');?></label><input id="<?php echo htmlspecialchars_decode( $this->get_field_id('poplimit') ); ?>" class="widefat" name="<?php echo htmlspecialchars_decode( $this->get_field_name('poplimit') ); ?>" type="text" value="<?php echo esc_attr($poplimit); ?>" />
        
        </p>
        
        <!-- / Popular posts setting -->
        
        
        <!-- = Recent posts setting -->
        
        <p><span><strong><?php _e('Recent posts:', 'filoxenia'); ?></strong></span><br />
        
        <label for="<?php echo htmlspecialchars_decode( $this->get_field_id('recent') ); ?>"><?php _e('Tab name: ', 'filoxenia');?></label><input id="<?php echo htmlspecialchars_decode( $this->get_field_id('recent') ); ?>" class="widefat" name="<?php echo htmlspecialchars_decode( $this->get_field_name('recent') ); ?>" type="text" value="<?php echo esc_attr($recent); ?>" /><br />
        <label for="<?php echo htmlspecialchars_decode( $this->get_field_id('recentlimit') ); ?>"><?php _e('Show number: ', 'filoxenia');?></label><input id="<?php echo htmlspecialchars_decode( $this->get_field_id('recentlimit') ); ?>" class="widefat" name="<?php echo htmlspecialchars_decode( $this->get_field_name('recentlimit') ); ?>" type="text" value="<?php echo esc_attr($recentlimit); ?>" />
        
        </p>
        
        <!-- / Recent posts setting -->
        
    
    <?php
    }

    /**
     * Processes widget options to be saved
     * Reference: http://codex.wordpress.org/Plugins/WordPress_Widgets_Api
     * @since 0.0.1
     */ 
     
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['pop'] = strip_tags($new_instance['pop']);
        $instance['poplimit'] = strip_tags($new_instance['poplimit']);
        $instance['recent'] = strip_tags($new_instance['recent']);
        $instance['recentlimit'] = strip_tags($new_instance['recentlimit']);           
        $instance['title'] = strip_tags( $new_instance['title'] );  
        return $instance;
    }   
}