<?php

/**
 * The template for displaying the footer
 */

 global $theme_option; 

?>



</main>

	<!-- footer begin -->

    <footer>

        <div class="container">
            <div class="row">
            <?php get_sidebar('footer'); ?>
            </div>
        </div> 

        <div class="footer_payment_types">
            <div class="container">
                <?php if ( is_active_sidebar( 'bottom-footer' ) ) : ?>                    
                    <?php dynamic_sidebar( 'bottom-footer' ); ?>                    
                <?php endif; ?>
            </div>
        </div>   

        <div class="copyrights">
            <div class="container">
                <div class="row">

                    <div class="col-md-6 col-sm-6 copyright">
                        <p><?php echo htmlspecialchars_decode($theme_option['footer_text']); ?></p>
                    </div>

                    <div class="col-md-6 col-sm-6 social">
                        <?php if($theme_option['twitter']) { ?>

                        <a target="_blank" href="<?php echo esc_url($theme_option['twitter']); ?>"><i class="fa fa-twitter"></i></a>

                        <?php } ?>

                        <?php if($theme_option['github']) { ?>

                        <a target="_blank" href="<?php echo esc_url($theme_option['github']); ?>"><i class="fa fa-github"></i></a>

                        <?php } ?>

                        <?php if($theme_option['dribbble']) { ?>

                        <a target="_blank" href="<?php echo esc_url($theme_option['dribbble']); ?>"><i class="fa fa-dribbble"></i></a>

                        <?php } ?>

                        <?php if($theme_option['linkedin']) { ?>

                        <a target="_blank" href="<?php echo esc_url($theme_option['linkedin']); ?>"><i class="fa fa-linkedin"></i></a>

                        <?php } ?>

                        <?php if($theme_option['behance']) { ?>

                        <a target="_blank" href="<?php echo esc_url($theme_option['behance']); ?>"><i class="fa fa-behance"></i></a>

                        <?php } ?>

                        <?php if($theme_option['facebook']) { ?>

                        <a target="_blank" href="<?php echo esc_url($theme_option['facebook']); ?>"><i class="fa fa-facebook"></i></a>

                        <?php } ?>

                        <?php if($theme_option['instagram']) { ?>

                        <a target="_blank" href="<?php echo esc_url($theme_option['instagram']); ?>"><i class="fa fa-instagram"></i></a>

                        <?php } ?>

                        <?php if($theme_option['youtube']) { ?>

                        <a target="_blank" href="<?php echo esc_url($theme_option['youtube']); ?>"><i class="fa fa-youtube"></i></a>

                        <?php } ?>

                        <?php if($theme_option['skype']) { ?>

                        <a target="_blank" href="<?php echo esc_url($theme_option['skype']); ?>"><i class="fa fa-skype"></i></a>

                        <?php } ?>

                        <?php if($theme_option['google']) { ?>

                        <a target="_blank" href="<?php echo esc_url($theme_option['google']); ?>"><i class="fa fa-google-plus"></i></a>

                        <?php } ?>



                    </div>

                </div>

            </div>
        </div>
    </footer>

    <!-- footer close -->
</div><!-- #wrapper -->    
<?php wp_footer(); ?>
</body>
</html>