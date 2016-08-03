<?php

/**
 * Tweets widget class
 *
 * @since 2.8.0
 */

class Instagram_Widget extends WP_Widget {



    function Instagram_Widget() {

        $widget_ops = array( 'classname' => 'latest-instagram', 'description' => __('Latest instagram widget ', 'mint') );

        

        $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'instagram-widget' );

        

        parent::__construct( 'instagram-widget', __('Instagram Widget', 'mint'), $widget_ops, $control_ops );

    }

    

    function getAgo($timestamp) {

        $difference = time() - $timestamp;



        if ($difference < 60) {

            return $difference." seconds ago";

        } else {

            $difference = round($difference / 60);

        }



        if ($difference < 60) {

            return $difference." minutes ago";

        } else {

            $difference = round($difference / 60);

        }



        if ($difference < 24) {

            return $difference." hours ago";

        }

        else {

            $difference = round($difference / 24);

        }



        if ($difference < 7) {

            return $difference." days ago";

        } else {

            $difference = round($difference / 7);

            return $difference." weeks ago";

        }

    }

    function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {

      $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);

      return $connection;

    }

    function widget( $args, $instance ) {

        extract( $args );



        //Our variables from the widget settings.

        $title = apply_filters('widget_title', $instance['title'] );

        $notweets = $instance['num'];

        $accessToken = $instance['custommerkey'];

        $clientID = $instance['custommersecret'];

        //Test

        

        

        echo htmlspecialchars_decode( $before_widget );

		if ( ! empty( $title ) )
            echo htmlspecialchars_decode( $args['before_title'] ) . esc_attr( $title ) . htmlspecialchars_decode( $args['after_title'] );

        ?>

            <ul class="instaFeed"></ul>
            <script type="text/javascript">
            (function($) {
             "use strict";
                jQuery.fn.spectragram.accessData={
                    accessToken:'<?php echo esc_attr( $accessToken ); ?>',
                    clientID:'<?php echo esc_attr( $clientID ); ?>'}
                    
                $('.instaFeed').spectragram('getUserFeed', {
                        query: 'insideenvato', //Change the instagram feed user to display the feed that you want.
                        size: 'small',
                        max: <?php echo esc_attr( $notweets ); ?>
                });
            })(jQuery);    
            </script>
        <?php 
                    
        echo htmlspecialchars_decode( $after_widget );

    }



    //Update the widget 

     

    function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

        //Strip tags from title and name to remove HTML 

        $instance['title'] = strip_tags( $new_instance['title'] );

        $instance['num'] = strip_tags( $new_instance['num'] );

        $instance['custommerkey'] = strip_tags( $new_instance['custommerkey'] );

        $instance['custommersecret'] = strip_tags( $new_instance['custommersecret'] );

        return $instance;

    }



    

    function form( $instance ) {



        //Set up some default widget settings.

        $defaults = array( 'title' => __('Instagram', 'mint'), 'custommerkey' => __('305801553.467ede5.94e8f22591af44eea81bdbd1ca3bff04', 'mint'),'custommersecret' => __('e659391279a64365a13bfb26b4caf15d', 'mint'), 'num' => __('12', 'mint'));

        $instance = wp_parse_args( (array) $instance, $defaults ); ?>



        <p>

            <label for="<?php echo htmlspecialchars_decode( $this->get_field_id( 'title' ) ); ?>"><?php _e('Title:', 'mint'); ?></label>

            <input type="text" class="widefat" id="<?php echo htmlspecialchars_decode( $this->get_field_id( 'title' ) ); ?>" name="<?php echo htmlspecialchars_decode( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />

        </p>

        <p>

            <label for="<?php echo htmlspecialchars_decode( $this->get_field_id( 'custommerkey' ) ); ?>"><?php _e(' Access Token:', 'mint'); ?></label></br>(the number you copied)

            <input type="text" class="widefat" id="<?php echo htmlspecialchars_decode( $this->get_field_id( 'custommerkey' ) ); ?>" name="<?php echo htmlspecialchars_decode( $this->get_field_name( 'custommerkey' ) ); ?>" value="<?php echo esc_attr( $instance['custommerkey'] ); ?>" />

        </p>

        <p>

            <label for="<?php echo htmlspecialchars_decode( $this->get_field_id( 'custommersecret' ) ); ?>"><?php _e('Client ID:', 'mint'); ?></label><br>(the number you copied)

            <input type="text" class="widefat" id="<?php echo htmlspecialchars_decode( $this->get_field_id( 'custommersecret' ) ); ?>" name="<?php echo htmlspecialchars_decode( $this->get_field_name( 'custommersecret' ) ); ?>" value="<?php echo esc_attr( $instance['custommersecret'] ); ?>" />

        </p>

        <p>

            <label for="<?php echo htmlspecialchars_decode( $this->get_field_id( 'num' ) ); ?>"><?php _e('Number of feed:', 'mint'); ?></label><br>(between 1 and 20)

            <input type="text" class="widefat" id="<?php echo htmlspecialchars_decode( $this->get_field_id( 'num' ) ); ?>" name="<?php echo htmlspecialchars_decode( $this->get_field_name( 'num' ) ); ?>" value="<?php echo esc_attr( $instance['num'] ); ?>" />

        </p>


    <?php

    }

}

function inspira_register_instagram_widgets() {

    register_widget( 'Instagram_Widget' );

}

add_action( 'widgets_init', 'inspira_register_instagram_widgets' ); 

?>