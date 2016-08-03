<?php 
global $theme_option;
$link_video = get_post_meta(get_the_ID(),'_cmb_link_video', true);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="rows title">
        <h6><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
    </div>
    <p><iframe height="170px" src="<?php echo esc_url( $link_video ); ?>"></iframe></p>
    <p><?php echo filoxenia_excerpt(); ?></p>

    <p class="text-center">
        <a href="<?php the_permalink(); ?>" class="button"><?php echo esc_attr( $theme_option['read_more'] ); ?></a>
    </p>

    <p class="text-center upper">
        <small><?php _e('Post By ', 'filoxenia') ?><?php the_author_posts_link(); ?> <?php _e('in ', 'filoxenia'); the_category(', '); ?>  <?php _e('on', 'filoxenia') ?> <a href="<?php echo get_day_link( get_the_time('Y'), get_the_time('m'), get_the_time('d')); ?>"><?php the_time('M d, Y'); ?></a></small>
    </p>
</article>