<?php
/**
 * Template Name: Blog Full Width
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
<section class="blog-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="list-blog">
                <?php if(have_posts()) : ?>  

                <?php 

                $args = array(    

                  'paged' => $paged,

                  'post_type' => 'post',

                  );

                $wp_query = new WP_Query($args);

                while ($wp_query -> have_posts()): $wp_query -> the_post();                         

                    get_template_part( 'content', get_post_format() ) ; ?> 

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
        </div>
    </div>
</section>
<!-- content close -->
<?php get_footer(); ?>