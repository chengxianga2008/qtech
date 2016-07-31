<?php
/**
 * Template Name: Blog Sidebar
 */
 global $theme_option;
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

<!-- content begin -->
<section class="blog-section single-blog">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="list-blog">
                <?php if(have_posts()) : ?>  

                <?php 

                $args = array(    

                  'paged' => $paged,

                  'post_type' => 'post',

                  );

                $wp_query = new WP_Query($args);

                while ($wp_query -> have_posts()): $wp_query -> the_post(); 

                $link_audio = get_post_meta(get_the_ID(),'_cmb_link_audio', true);

                $link_video = get_post_meta(get_the_ID(),'_cmb_link_video', true);

                ?>                                      

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

                        <h4 class="single-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>

                        <p class="upper">

                            <small><?php _e('Post By ', 'filoxenia') ?><?php the_author_posts_link(); ?> <?php _e('in ', 'filoxenia'); the_category(', '); ?>  <?php _e('on', 'filoxenia') ?> <?php the_time('M d, Y'); ?></small>

                        </p>

                         <p class="rm"><?php echo filoxenia_excerpt(); ?><a href="<?php the_permalink(); ?>">read more</a></p>

                      </div>

                  </div>

                <?php endwhile;?> 

            

                <?php else: ?>

                <h1><?php _e('Nothing Found Here!', 'filoxenia'); ?></h1>

                <?php endif; ?>
                </div>
                <div class="pagination text-center ">
                    <ul>
                        <?php echo filoxenia_pagination(); ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">

                <?php get_sidebar();?>

            </div>
        </div>
    </div>
</section>
<!-- content close -->
<?php get_footer(); ?>