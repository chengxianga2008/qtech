<?php
 global $theme_option;
get_header(); ?>
	
<section class="blog-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="list-blog">
                <?php 
                  while (have_posts()) : the_post();
                  get_template_part( 'content', get_post_format() ) ;   // End the loop.
                  endwhile;
                   ?>
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