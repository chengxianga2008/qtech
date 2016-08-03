<?php
/**
 * Recent_Posts widget class
 *
 * @since 2.8.0
 */
class filoxenia_widget_recent_posts extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'widget_recent_entries', 'description' => __( "The most recent posts on your site", 'filoxenia') );
        parent::__construct('recent-posts', __('filoxenia Recent Posts', 'filoxenia'), $widget_ops);
        $this->alt_option_name = 'widget_recent_entries';

        add_action( 'save_post', array($this, 'flush_widget_cache') );
        add_action( 'deleted_post', array($this, 'flush_widget_cache') );
        add_action( 'switch_theme', array($this, 'flush_widget_cache') );
    }

    function widget($args, $instance) {
        $cache = wp_cache_get('filoxenia_widget_recent_posts', 'widget');

        if ( !is_array($cache) )
            $cache = array();

        if ( ! isset( $args['widget_id'] ) )
            $args['widget_id'] = $this->id;

        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            echo htmlspecialchars_decode( $cache[ $args['widget_id'] ] );
            return;
        }

        ob_start();
        extract($args);

        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts', 'filoxenia' );
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 10;
        if ( ! $number )
            $number = 10;
        $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

        $r = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
        if ($r->have_posts()) :
?>
        <?php echo htmlspecialchars_decode( $before_widget ); ?>
        <?php if ( $title ){ echo htmlspecialchars_decode( $before_title ) . esc_attr( $title ) . htmlspecialchars_decode( $after_title ); } ?>

            <ul class="recent_blogs">                            
                <?php while ( $r->have_posts() ) : $r->the_post(); ?> 
                <li>                    
                    <span>
                        <a href="<?php the_permalink() ?>">
                            <?php 
                                    $params = array( 'width' => 60, 'height' => 60 );
                                    $image = bfi_thumb( wp_get_attachment_url(get_post_thumbnail_id()), $params ); 
                            ?>
                            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr( get_the_title() ? get_the_title() : get_the_ID() ); ?>">
                        </a> 
                    </span>                  
                    <a href="<?php the_permalink() ?>" title="<?php echo esc_attr( get_the_title() ? get_the_title() : get_the_ID() ); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a>                                       
                    <?php if ( $show_date ){ ?><i><?php the_time('M d, Y') ?></i><?php } ?> 
                    <em><?php comments_number( __('0 comment', 'filoxenia'), __('1 comment', 'filoxenia'), __('% comments', 'filoxenia') ); ?></em>
                <div class="clearfix"></div>    
                </li>
                <?php endwhile; ?> 
            <div class="clearfix"></div>                     
            </ul>             
		
        <?php echo htmlspecialchars_decode( $after_widget ); ?>
<?php
        // Reset the global $the_post as this query will have stomped on it
        wp_reset_postdata();

        endif;

        $cache[$args['widget_id']] = ob_get_flush();
        wp_cache_set('filoxenia_widget_recent_posts', $cache, 'widget');
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $instance['show_date'] = (bool) $new_instance['show_date'];
        $this->flush_widget_cache();

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['widget_recent_entries']) )
            delete_option('widget_recent_entries');

        return $instance;
    }

    function flush_widget_cache() {
        wp_cache_delete('filoxenia_widget_recent_posts', 'widget');
    }

    function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
?>
        <p><label for="<?php echo htmlspecialchars_decode( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'filoxenia' ); ?></label>
        <input class="widefat" id="<?php echo htmlspecialchars_decode( $this->get_field_id( 'title' ) ); ?>" name="<?php echo htmlspecialchars_decode( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

        <p><label for="<?php echo htmlspecialchars_decode( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of posts to show:', 'filoxenia' ); ?></label>
        <input id="<?php echo htmlspecialchars_decode( $this->get_field_id( 'number' ) ); ?>" name="<?php echo htmlspecialchars_decode( $this->get_field_name( 'number' ) ); ?>" type="number" value="<?php echo esc_attr( $number ); ?>" size="3" /></p>

        <p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo htmlspecialchars_decode( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo htmlspecialchars_decode( $this->get_field_name( 'show_date' ) ); ?>" />
        <label for="<?php echo htmlspecialchars_decode( $this->get_field_id( 'show_date' ) ); ?>"><?php _e( 'Display post date?', 'filoxenia' ); ?></label></p>
<?php
    }
}

function filoxenia_register_custom_widgets() {
    register_widget( 'filoxenia_widget_recent_posts' );
}
add_action( 'widgets_init', 'filoxenia_register_custom_widgets' );	