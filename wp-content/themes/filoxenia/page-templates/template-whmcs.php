<?php
/**
 * Template Name: Template WHMCS
 */
 global $theme_option;
get_header();
 ?>
<!-- subheader begin -->
<div class="head_whmcs">
    <div class="parallax-project"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-title"><?php the_title(); ?></h1>
            </div>
        </div>
    </div>
</div>
<!-- subheader close -->

<!-- content begin -->
<section class="whmcs-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="whmcs-content">
                    <?php if(have_posts()) : while (have_posts()): the_post();                         

                        the_content();

                    endwhile;?> 
                    <?php else: ?>
                    <h1><?php _e('Nothing Found Here!', 'filoxenia'); ?></h1>
                    <?php endif; ?>
                </div>               
            </div>
        </div>
    </div>
</section>
<!-- content close -->
<?php get_footer(); ?>