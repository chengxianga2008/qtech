<?php
/**
 * The template for displaying 404 pages (Not Found)
 */
global $theme_option; 
get_header(); ?>

<section class="page404">
	<div class="container">
    	<div class="col-md-12">
			<div class="text-center">
				<h1><?php echo htmlspecialchars_decode($theme_option['404_title']); ?></h1>
				<h4 class="content_404">
				<?php echo htmlspecialchars_decode($theme_option['404_content']); ?>
				</h4>
				<div class="blog-link dark"><a class="button" href="<?php echo esc_url(home_url()); ?>"><i class="icon-long-arrow-left"></i> <?php echo htmlspecialchars_decode( $theme_option['back_404'] ); ?></a></div>
			</div>
       </div> 	
    </div><!-- end container -->
</section><!-- end postwrapper -->

<?php get_footer(); ?>
