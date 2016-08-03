<?php
 global $theme_option;

 $link_audio = get_post_meta(get_the_ID(),'_cmb_link_audio', true);
 $link_video = get_post_meta(get_the_ID(),'_cmb_link_video', true);
get_header();
?>

<!-- subheader begin -->
<div class="breadcrumb-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php filoxenia_breadcrumbs(); ?>
            </div>
        </div>
    </div>
</div>
<!-- subheader close -->
<!-- CONTENT BLOG -->
<?php while (have_posts()) : the_post(); ?>
  <div class="blog-section single-blog">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="blog-list">                  
                  <div class="post-content">
                      <div class="post-image">
                        <?php $format = get_post_format(); ?>
                        <?php if($format=='audio'){ ?>

                          <iframe style="width:100%" src="<?php echo esc_url($link_audio); ?>"></iframe>
            
                          <?php } elseif($format=='video'){ ?>

                            <iframe height="58px" width="100%" src="<?php echo esc_url($link_video); ?>"></iframe>
           
                          <?php } elseif($format=='gallery'){ ?>

                            <div class="postMedia postSlider slider flexslider">
                              <?php
                                /**
                                 * Detect plugin. For use on Front End only.
                                 */
                                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                                // check for plugin using plugin name
                                if ( is_plugin_active('meta-box/meta-box.php') ) { 
                              ?>
                                <?php $images = rwmb_meta( '_cmb_images', "type=image" ); ?>
                                <?php if($images){ ?>
                                  <ul class="slides">
                                    <?php                                                        
                                      foreach ( $images as $image ) {                              
                                    ?>
                                    <?php $img = $image['full_url']; ?>
                                      <li><img src="<?php echo esc_url($img); ?>" alt=""></li> 
                                    <?php } ?>                   
                                  </ul>
                                <?php } ?>
                              <?php } ?>
                            </div>
              
                          <?php } else { $format=='image' ?>
                          <?php
                            /**
                             * Detect plugin. For use on Front End only.
                             */
                            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                            // check for plugin using plugin name
                            if ( is_plugin_active('meta-box/meta-box.php') ) { 
                          ?>
                            <?php $images = rwmb_meta( '_cmb_image', "type=image" ); ?>
                            <?php if($images){ ?>
                            <?php                                                        
                              foreach ( $images as $image ) {                              
                              ?>
                              <?php $img = $image['full_url']; ?>
                              <img src="<?php echo esc_url($img); ?>" alt="">
                              <?php } ?>
                            <?php } ?>
                          <?php } ?>

                          <?php } ?>

                      </div>
                      <div class="post-text page-content">
                        <h4 class="single-title"><?php the_title(); ?></h4>
                        <p class="upper">
                            <small><?php _e('Post By ', 'filoxenia') ?><?php the_author_posts_link(); ?> <?php _e('in ', 'filoxenia'); the_category(', '); ?>  <?php _e('on', 'filoxenia') ?> <?php the_time('M d, Y'); ?></small>
                        </p>
                         <?php the_content(); ?>
                      </div>
                  </div>
                    
                  <div class="post-meta">
                    <?php if(has_tag()) { ?>
                      <?php the_tags('', ' ' ); ?>
                    <?php } ?>               
                  </div>
              </div>
              <div class='comments-box'>
                <h4><?php comments_number( __('0 comment', 'filoxenia'), __('1 comment', 'filoxenia'), __('% comments', 'filoxenia') ); ?></h4>
              </div>
              <?php comments_template(); ?> 
              
            </div>

            <div class="col-md-4">
                <?php get_sidebar();?>
            </div>
        </div>
    </div>
 </div>

<?php endwhile;?>
  <!-- END CONTENT BLOG -->
<?php get_footer(); ?>	





  