<?php 
global $theme_option;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="rows title">
        <div class="col-md-12">
            <h6><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
        </div>
    </div>

    <p><?php echo filoxenia_excerpt(); ?></p>

    <p class="text-center">
        <a href="<?php the_permalink(); ?>" class="button"><?php echo esc_attr( $theme_option['read_more'] ); ?></a>
    </p>

    <p class="text-center upper">
        <small><?php _e('Post By ', 'filoxenia') ?><?php the_author_posts_link(); ?> <?php _e('in ', 'filoxenia'); the_category(', '); ?>  <?php _e('on', 'filoxenia') ?> <a href="<?php echo get_day_link( get_the_time('Y'), get_the_time('m'), get_the_time('d')); ?>"><?php the_time('M d, Y'); ?></a></small>
    </p>
</article>