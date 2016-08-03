<?php 

// Custom Heading
add_shortcode('heading','heading_func');
function heading_func($atts, $content = null){
	extract(shortcode_atts(array(
		'text'		=>	'',
		'tag'		=> 	'h1',
		'size'		=>	'',
		'color'		=>	'',
		'align'		=>	'left',
		'bot'		=>	'',
		'class'		=>	'',
	), $atts));
	
	$size1 = (!empty($size) ? 'font-size: '.$size.'px;' : '');
	$color1 = (!empty($color) ? 'color: '.$color.';' : '');
	$align1 = (!empty($align) ? 'text-align: '.$align.';' : '');
	$bot = (!empty($bot) ? 'margin-bottom: '.$bot.';' : 'margin-bottom: 25px;');
	$cl = (!empty($class) ? ' class= "'.$class.'"' : '');
	
	$html .= '<'.$tag.$cl.' style="' . $size1 . $align1 . $color1 . $bot .'">'. $text .'</'.$tag.'>';
	
	return $html;
}


// Call To Action
add_shortcode('cta', 'cta_func');
function cta_func($atts, $content = null){
	extract(shortcode_atts(array(
		'btn'		=> 	'',
		'link'		=> 	'',
		'title'		=> 	'',
		'class_scroll' => 	'',
	), $atts));

	ob_start(); ?>

	<div class="hero text-center">
        <div class="hero-messages">
            <h1><?php echo htmlspecialchars_decode($title); ?></h1>
            <h4><?php echo htmlspecialchars_decode($content); ?></h4>
            <?php if($link != ''){ ?><a href="<?php echo esc_url($link); ?>" class="button <?php echo esc_attr($class_scroll); ?>"><?php echo htmlspecialchars_decode($btn); ?></a><?php } ?>            
            <div class="clearfix"></div>
        </div>
    </div>        

	<?php

    return ob_get_clean();
}

// Call To Action 2
add_shortcode('cta2', 'cta2_func');
function cta2_func($atts, $content = null){
	extract(shortcode_atts(array(
		'btntext1' 	=> '',
		'btnlink1' 	=> '',
		'title'		=> '',	
		'photo'		=> '',
		'extra_class'     => '',
	), $atts));

	ob_start(); ?>

	<?php $url = wp_get_attachment_image_src($photo, ''); $image_src = $url[0]; ?>

	<div class="cta-promo <?php echo esc_attr($extra_class); ?>">
		<?php if($photo != ''){ ?><img alt="<?php echo esc_attr($title); ?>" src="<?php echo esc_url($image_src); ?>" class="img-responsive"><?php } ?>
		<h4><?php echo htmlspecialchars_decode($title); ?></h4>
		<p><?php echo htmlspecialchars_decode($content); ?></p>
		<?php if($btnlink1 != ''){ ?><a class="btn btn-primary btn-lg button" href="<?php echo esc_url($btnlink1); ?>"><?php echo htmlspecialchars_decode($btntext1); ?></a><?php } ?>
	</div>

	<?php return ob_get_clean();
}

// Support Box
add_shortcode('support', 'support_func');
function support_func($atts, $content = null){
	extract(shortcode_atts(array(
		'title'		=> '',
		'btn'		=> '',
		'link'		=> '',
	), $atts));

	ob_start(); ?>

	<div class="rows title">
        <h6><?php echo htmlspecialchars_decode($title); ?></h6>
    </div>    
    <p>
        <?php echo htmlspecialchars_decode($content); ?>
    </p>

    <div class="text-center">
        <a href="<?php echo esc_url($link); ?>" class="button"><?php echo esc_attr($btn); ?></a>
    </div>
    

	<?php

    return ob_get_clean();
}

// Our Team
add_shortcode('team', 'team_func');
function team_func($atts, $content = null){
	extract(shortcode_atts(array(
		'photo'		=> 	'',
		'name'		=>	'',
		'job'		=>	'',
		'icon1'		=>	'',
		'icon2'		=>	'',
		'icon3'		=>	'',
		'icon4'		=>	'',
		'url1'		=>	'',
		'url2'		=>	'',
		'url3'		=>	'',
		'url4'		=>	'',
	), $atts));

	$img = wp_get_attachment_image_src($photo,'full');
	$img = $img[0];

	$icon11 = (!empty($icon1) ? '<a href="'.esc_url($url1).'" target="_blank"><i class="fa fa-'.esc_attr($icon1).'"></i></a>' : '');
	$icon22 = (!empty($icon2) ? '<a href="'.esc_url($url2).'" target="_blank"><i class="fa fa-'.esc_attr($icon2).'"></i></a>' : '');
	$icon33 = (!empty($icon3) ? '<a href="'.esc_url($url3).'" target="_blank"><i class="fa fa-'.esc_attr($icon3).'"></i></a>' : '');
	$icon44 = (!empty($icon4) ? '<a href="'.esc_url($url4).'" target="_blank"><i class="fa fa-'.esc_attr($icon4).'"></i></a>' : '');

	ob_start(); ?>

	<div class="team">

		<h6 class="text-center">
            <?php echo htmlspecialchars_decode($name); ?> <?php if($job) { ?><small><?php echo htmlspecialchars_decode($job); ?></small><?php } ?>
        </h6>

        <div class="popup text-center">
            <a href="<?php echo esc_url($img); ?>"><img class="photo" src="<?php echo esc_url($img); ?>" alt=""></a>
        </div>
        <?php if($content) { ?><div class="team_text"><?php echo htmlspecialchars_decode($content); ?></div><?php } ?>
        <div class="about-social">
        	<?php echo htmlspecialchars_decode($icon11); ?>
            <?php echo htmlspecialchars_decode($icon22); ?>
            <?php echo htmlspecialchars_decode($icon33); ?>
            <?php echo htmlspecialchars_decode($icon44); ?>        
        </div>
       
    </div>

	<?php

    return ob_get_clean();
}

// Services
add_shortcode('services', 'services_func');
function services_func($atts, $content = null){
	extract(shortcode_atts(array(
		'icon'		=> 	'',
		'title'		=> 	'',
		'hei'		=> 	'',
		'style'		=>	'big',
	), $atts));

	ob_start(); ?>
	
	<?php if($style == 'small') { ?>
    <div class="features spacy" style="<?php if($hei) echo 'min-height: '.$hei.';'; ?>">
        <h4>
            <i class="fa fa-<?php echo esc_attr($icon); ?>"></i> <?php echo htmlspecialchars_decode($title); ?>
        </h4>

        <p>
            <?php echo htmlspecialchars_decode($content); ?>
        </p>
    </div>
    <?php }else{ ?>
    <div class="features spacy" style="<?php if($hei) echo 'min-height: '.$hei.';'; ?>">
        <div class="text-center fleft">
            <i class="fa fa-<?php echo esc_attr($icon); ?> text-size--xxl"></i>
        </div>

        <div class="small-10 column">
            <h4><?php echo htmlspecialchars_decode($title); ?></h4>

            <p>
                <?php echo htmlspecialchars_decode($content); ?>
            </p>
        </div>
    </div>    
    <?php } ?>

	<?php

    return ob_get_clean();
}


// Pricing Tables
add_shortcode('pricingtable','pricing_func');
function pricing_func($atts, $content = null){
    extract( shortcode_atts( array(
      'title'   	=> '',
      'price'		=> '',
      'time'		=> '',
      'btntext'		=> '',
      'btnlink'		=> '',
      'featured'	=> 'no',
   ), $atts ) );

    ob_start(); ?>

    <div class="pricing-table<?php if($featured == 'yes') echo ' highlight'; ?>">
        <div class="title"><?php echo htmlspecialchars_decode($title); ?></div>
        <div class="price"><h4><?php echo htmlspecialchars_decode($price); ?></h4><?php if($time) { ?><span> <?php echo htmlspecialchars_decode($time); ?></span><?php } ?></div>
        <?php echo htmlspecialchars_decode($content); ?>
        <div class="cta-button"><a class="button" href="<?php echo esc_url($btnlink); ?>"><?php echo htmlspecialchars_decode($btntext); ?></a></div>
    </div>

    <?php

    return ob_get_clean();
}

// Promotion domain name
add_shortcode('prdomain','prdomain_func');
function prdomain_func($atts, $content = null){
    extract( shortcode_atts( array(
      'title'   	=> '',   
      'photo'      => '',       
   ), $atts ) );

    $img = wp_get_attachment_image_src($photo,'full');
	$img = $img[0];

    ob_start(); ?>

    <div class="promotion-domain">
    	<img class="photo" src="<?php echo esc_url($img); ?>" alt="">
		<?php echo htmlspecialchars_decode($content); ?>
	</div>

<?php
    return ob_get_clean();
}


// Form search domain
add_shortcode('filoxenia_search_domain','filoxenia_search_domain_func');
function filoxenia_search_domain_func($atts, $content = null){
    extract( shortcode_atts( array(
      'title'   	=> '',   
      'actionlink'  => '',  
      'buttontext'  => '', 
      'view_domain_price'  => '',
      'bulk_domain'  => '',
      'transfer_domain' => ''
   ), $atts ) );

    ob_start(); ?>

    <div class="filoxenia-domain-search">
		<form class="filoxenia-search-domain" method="post" action="<?php echo esc_url($actionlink); ?>">			
			<input type="text" size="40" name="domain" placeholder="Enter your domain name here..." class="domain-text fleft">
			<?php echo htmlspecialchars_decode($content); ?>  			
			<input type="submit" value="<?php echo esc_attr($buttontext); ?>" class="domain-search fleft">			
			<div class="clearfix"></div>
		</form>
	</div>
	<div class="clearfix"></div>
	<div class="sm_links">
		<?php if($view_domain_price != ''){ ?><a href="<?php echo esc_url($view_domain_price); ?>">View Domain Price List</a>  | <?php } ?>
		<?php if($bulk_domain != ''){ ?><a href="<?php echo esc_url($bulk_domain); ?>">Bulk Domain Search</a> | <?php } ?>
		<?php if($transfer_domain != ''){ ?><a href="<?php echo esc_url($transfer_domain); ?>">Transfer Domain</a> <?php } ?>
	</div>

<?php
    return ob_get_clean();
}

// Logos Client
add_shortcode('logos', 'logos_func');
function logos_func($atts, $content = null){
	extract(shortcode_atts(array(
		'gallery'		=> 	'',
	), $atts));

	ob_start(); ?>

	<div class="logo-clients owl-carousel">
    	<?php 
		$img_ids = explode(",",$gallery);
		foreach( $img_ids AS $img_id ){
		$meta = wp_prepare_attachment_for_js($img_id);
		$caption = $meta['caption'];
		$title = $meta['title'];		
		$image_src = wp_get_attachment_image_src($img_id,''); ?>
        <div class="item">
        <?php if(!empty($caption)){ ?> 
        	<a target="_blank" href="<?php echo esc_attr($caption); ?>">
	            <img src="<?php echo esc_url( $image_src[0] ); ?>" alt="<?php echo esc_attr($title); ?>">
	        </a>
        <?php }else{ ?>         	
	        <img src="<?php echo esc_url( $image_src[0] ); ?>" alt="<?php echo esc_attr($title); ?>">	        
        <?php } ?>	            
        </div>
        <?php } ?>
    </div>      

	<?php

    return ob_get_clean();
}


// Google Map
add_shortcode('ggmap','ggmap_func');
function ggmap_func($atts, $content = null){
    extract( shortcode_atts( array(
	  'idmap'		=> 'map-canvas',
	  'height'		=> '',	
      'lat'   		=> '',
      'long'	  	=> '',
      'zoom'		=> '',
      'address'		=> '',
	  'mapcolor'	=> '',
	  'icon'		=> '',
   ), $atts ) );
   
   $img = wp_get_attachment_image_src($image,'full');
   $img = $img[0];
   
   $icon1 = wp_get_attachment_image_src($icon,'full');
   $icon1 = $icon1[0];
   		
    ob_start(); ?>
    	 
    <div id="<?php echo esc_attr( $idmap ); ?>" class="contacts-map" style="<?php if($height) echo 'height: '.$height.'px;'; ?>"></div>

    <script type="text/javascript">
	
	(function($) {
    "use strict"
    $(document).ready(function(){
        
        var locations = [
			['<div class="infobox"><span><?php echo htmlspecialchars_decode( $address );?><span></div>', <?php echo esc_attr( $lat );?>, <?php echo esc_attr( $long );?>, 2]
        ];
	
		var map = new google.maps.Map(document.getElementById('<?php echo esc_attr( $idmap ); ?>'), {
		  zoom: <?php echo esc_attr( $zoom );?>,
			scrollwheel: false,
			navigationControl: true,
			mapTypeControl: false,
			scaleControl: false,
			draggable: true,
			styles: [ { "stylers": [ { "hue": "<?php echo esc_attr( $mapcolor );?>" }, { "gamma": 1 } ] } ],
			center: new google.maps.LatLng(<?php echo esc_attr( $lat );?>, <?php echo esc_attr( $long );?>),
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		});
	
		var infowindow = new google.maps.InfoWindow();
	
		var marker, i;
	
		for (i = 0; i < locations.length; i++) {  
	  
			marker = new google.maps.Marker({ 
			position: new google.maps.LatLng(locations[i][1], locations[i][2]), 
			map: map ,
			icon: '<?php echo htmlspecialchars_decode( $icon1 );?>'
			});
		
		  google.maps.event.addListener(marker, 'click', (function(marker, i) {
			return function() {
			  infowindow.setContent(locations[i][0]);
			  infowindow.open(map, marker);
			}
		  })(marker, i));
		}
        
        });
    })(jQuery);   	
   	</script>
<?php

    return ob_get_clean();

}