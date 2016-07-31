<?php
 global $theme_option;
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

<section class="blog-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
              <h2 class="text-center">
                <?php
                  if ( is_day() ) :
                    printf( __( 'Daily Archives: %s', 'filoxenia' ), get_the_date() );

                  elseif ( is_month() ) :
                    printf( __( 'Monthly Archives: %s', 'filoxenia' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'filoxenia' ) ) );

                  elseif ( is_year() ) :
                    printf( __( 'Yearly Archives: %s', 'filoxenia' ), get_the_date( _x( 'Y', 'yearly archives date format', 'filoxenia' ) ) );

                  else :
                    _e( 'Archives', 'filoxenia' );

                  endif;
                ?> 
                
              </h2>
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