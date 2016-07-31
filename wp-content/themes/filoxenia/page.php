<?php

 global $theme_option;
 $page_detail = get_post_meta(get_the_ID(),'_cmb_page_sub', true);
get_header(); ?>

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

<div class="blog-section single-blog">
    <div class="container">
        <div class="row">
        <?php if (have_posts()){ ?>
            
        	<div class="col-md-8">   
                <div class="page-content">
            		<?php while (have_posts()) : the_post()?>
                        
                        <?php the_post_thumbnail() ?>
            			
                        <?php the_content(); ?>

                        <?php wp_link_pages(); ?>
                        
            		<?php endwhile; ?>
                </div>    
        	</div>

        	<div class="col-md-4">
                <?php get_sidebar();?>
            </div>
            
        	<?php }else {
        		 _e('Page Canvas For Page Builder', 'filoxenia'); 
        }?>
        </div>
    </div>
</div>

<?php get_footer(); ?>