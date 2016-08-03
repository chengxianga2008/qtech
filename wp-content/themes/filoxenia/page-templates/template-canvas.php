<?php
/*
 * Template Name: Canvas
 * Description: A Page Template with a Page Builder design.
 */
get_header('home'); ?>

<?php if (have_posts()){ ?>
	
		<?php while (have_posts()) : the_post()?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	
	<?php }else {
		_e('Page Canvas For Page Builder', 'filoxenia'); 
	}?>

<?php get_footer(); ?>