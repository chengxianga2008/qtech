<?php 


//Custom Heading
if(function_exists('vc_map')){

   vc_map( array(

   "name"      => __("OT Heading", 'filoxenia'),

   "base"      => "heading",

   "class"     => "",

   "icon" => "icon-st",

   "category"  => 'Content',

   "params"    => array(

      array(

         "type"      => "textarea",

         "holder"    => "div",

         "class"     => "",

         "heading"   => __("Text", 'filoxenia'),

         "param_name"=> "text",

         "value"     => "Heading",

         "description" => __("", 'filoxenia')

      ),
      array(

        "type" => "dropdown",

        "heading" => __('Element Tag', 'filoxenia'),

        "param_name" => "tag",

        "value" => array(   
                     __('h1', 'filoxenia') => 'h1',

                     __('h2', 'filoxenia') => 'h2',

                     __('h3', 'filoxenia') => 'h3',  

                     __('h4', 'filoxenia') => 'h4',

                     __('h5', 'filoxenia') => 'h5',

                     __('h6', 'filoxenia') => 'h6',  

                     __('p', 'filoxenia')  => 'p',

                     __('div', 'filoxenia') => 'div',
                    ),

        "description" => __("Section Element Tag", 'filoxenia'),      

      ),
      array(

        "type" => "dropdown",

        "heading" => __('Text Align', 'filoxenia'),

        "param_name" => "align",

        "value" => array(   

                     __('left', 'filoxenia') => 'left',

                     __('right', 'filoxenia') => 'right',  

                     __('center', 'filoxenia') => 'center',

                     __('justify', 'filoxenia') => 'justify',
                     
                    ),

        "description" => __("Section Overlay", 'filoxenia'),      

      ),
      array(

         "type"      => "textfield",

         "holder"    => "div",

         "class"     => "",

         "heading"   => __("Font Size", 'filoxenia'),

         "param_name"=> "size",

         "value"     => "",

         "description" => __("", 'filoxenia')

      ),
      array(

         "type"      => "colorpicker",

         "holder"    => "div",

         "class"     => "",

         "heading"   => __("Color", 'filoxenia'),

         "param_name"=> "color",

         "value"     => "",

         "description" => __("", 'filoxenia')

      ),
      array(

         "type"      => "textfield",

         "holder"    => "div",

         "class"     => "",

         "heading"   => __("Margin Bottom", 'filoxenia'),

         "param_name"=> "bot",

         "value"     => "",

         "description" => __("", 'filoxenia')

      ),
      array(

         "type"      => "textfield",

         "holder"    => "div",

         "class"     => "",

         "heading"   => __("Class Extra", 'filoxenia'),

         "param_name"=> "class",

         "value"     => "",

         "description" => __("", 'filoxenia')

      ),
    )));

}


//Call To Action
if(function_exists('vc_map')){
   vc_map( array(
   "name" => __("OT Call To Action", 'filoxenia'),
   "base" => "cta",
   "class" => "",
   "category" => 'Content',
   "icon" => "icon-st",
   "params" => array(
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => "Title",
         "param_name" => "title",
         "value" => "",
         "description" => __("", 'filoxenia')
      ),
      array(
         "type" => "textarea_html",
         "holder" => "div",
         "class" => "",
         "heading" => __("Content", 'filoxenia'),
         "param_name" => "content",
         "value" => "",
         "description" => __("Content Call", 'filoxenia')
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => "Label Button",
         "param_name" => "btn",
         "value" => "",
         "description" => __("", 'filoxenia')
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Link Button", 'filoxenia'),
         "param_name" => "link",
         "value" => "",
         "description" => __("", 'filoxenia')
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Class scroll", 'filoxenia'),
         "param_name" => "class_scroll",
         "value" => "",
         "description" => __("Add class : 'scroll' for scroll down section below, leave a blank do not use.", 'filoxenia')
      ),
    )
    ));
}

// Call To Action 2
if(function_exists('vc_map')){

   vc_map( array(

   "name" => __("OT Call To Action 2", 'filoxenia'),

   "base" => "cta2",

   "class" => "",

   "category" => 'Content',

   "icon" => "icon-st",

   "params" => array(

      array(

         "type" => "attach_image",

         "holder" => "div",

         "class" => "",

         "heading" => __("Photo", 'filoxenia'),

         "param_name" => "photo",

         "value" => "",

         "description" => __("Image of Box: 500x500", 'filoxenia')

      ),

      array(

         "type" => "textfield",

         "holder" => "div",

         "class" => "",

         "heading" => __("Title", 'filoxenia'),

         "param_name" => "title",

         "value" => "",

         "description" => __("Title call to action", 'filoxenia')

      ),

      array(

         "type" => "textarea_html",

         "holder" => "div",

         "class" => "",

         "heading" => __("Details", 'filoxenia'),

         "param_name" => "content",

         "value" => '',

         "description" => __("Details call to action", 'filoxenia')

      ),
      array(

         "type" => "textfield",

         "holder" => "div",

         "class" => "",

         "heading" => __("Button Text", 'filoxenia'),

         "param_name" => "btntext1",

         "value" => "",

         "description" => __("Label button", 'filoxenia')

      ),

      array(

         "type" => "textfield",

         "holder" => "div",

         "class" => "",

         "heading" => __("Button Link", 'filoxenia'),

         "param_name" => "btnlink1",

         "value" => '',

         "description" => __("Add Link, Leave a blank do not show button.", 'filoxenia')

      ), 

      array(

         "type" => "textfield",

         "holder" => "div",

         "class" => "",

         "heading" => __("Extra Class", 'filoxenia'),

         "param_name" => "extra_class",

         "value" => '',

         "description" => __("Extra Class for style : align-left, align-right, align-center ", 'filoxenia')

      ), 
    )
    ));

}

//Support Box

if(function_exists('vc_map')){
   vc_map( array(
   "name"      => __("OT Support Box", 'filoxenia'),
   "base"      => "support",
   "class"     => "",
   "icon" => "icon-st",
   "category"  => 'Content',
   "params"    => array(
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => "Title",
         "param_name" => "title",
         "value" => "",
         "description" => __("", 'filoxenia')
      ),
      array(
         "type" => "textarea_html",
         "holder" => "div",
         "class" => "",
         "heading" => "Description",
         "param_name" => "content",
         "value" => "",
         "description" => __("", 'filoxenia')
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => "Label Button",
         "param_name" => "btn",
         "value" => "",
         "description" => __("", 'filoxenia')
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => "Link Action",
         "param_name" => "link",
         "value" => "",
         "description" => __("", 'filoxenia')
      ),
    )));
}


//Clients Logo 

if(function_exists('vc_map')){
   vc_map( array(
   "name"      => __("OT Clients Logo", 'filoxenia'),
   "base"      => "logos",
   "class"     => "",
   "icon" => "icon-st",
   "category"  => 'Content',
   "params"    => array(
      array(
         "type" => "attach_images",
         "holder" => "div",
         "class" => "",
         "heading" => "Logo Client",
         "param_name" => "gallery",
         "value" => "",
         "description" => __("", 'filoxenia')
      ), 
      
    )));
}

// filoxenia search domain
if(function_exists('vc_map')){
   vc_map( array(
   "name"      => __("OT Search Domain Form", 'filoxenia'),
   "base"      => "filoxenia_search_domain",
   "class"     => "",
   "icon" => "icon-st",
   "category"  => 'Content',
   "params"    => array(
       array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Tite", 'filoxenia'),
         "param_name" => "title",
         "value" => "",
         "description" => __("Form Title", 'filoxenia')
      ),
       array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Action Link", 'filoxenia'),
         "param_name" => "actionlink",
         "value" => "",
         "description" => __("Enter Action Link, Ex: http://demo.vegatheme.com/filoxenia/whmcs-bridge/?ccce=domainchecker", 'filoxenia')
      ),
       array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Button Text", 'filoxenia'),
         "param_name" => "buttontext",
         "value" => "",
         "description" => __("Button Text, Ex: Search Domain", 'filoxenia')
      ),
      array(
         "type" => "textarea_html",
         "holder" => "div",
         "class" => "",
         "heading" => "Your domain option",
         "param_name" => "content",
         "value" => "",
         "description" => __("Add your domain option for search, need register domain on whmcs-bridge before use.", 'filoxenia')
      ), 
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Link View Domain Price List", 'filoxenia'),
         "param_name" => "view_domain_price",
         "value" => "",
         "description" => __("Link View Domain Price List, Leave a blank do not show.", 'filoxenia')
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Link Bulk Domain Search", 'filoxenia'),
         "param_name" => "bulk_domain",
         "value" => "",
         "description" => __("Link Bulk Domain Search, Leave a blank do not show.", 'filoxenia')
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Link Transfer Domain", 'filoxenia'),
         "param_name" => "transfer_domain",
         "value" => "",
         "description" => __("Link Transfer Domain, Leave a blank do not show.", 'filoxenia')
      ),
    )));
}

//Our Team
if(function_exists('vc_map')){
   vc_map( array(
   "name" => __("OT Our Team", 'filoxenia'),
   "base" => "team",
   "class" => "",
   "icon" => "icon-st",
   "category" => 'Content',
   "params" => array(
      array(
         "type" => "attach_image",
         "holder" => "div",
         "class" => "",
         "heading" => "Photo Member",
         "param_name" => "photo",
         "value" => "",
         "description" => __("Avarta of member, Recomended Size: 420 x 420", 'filoxenia')
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Name", 'filoxenia'),
         "param_name" => "name",
         "value" => "",
         "description" => __("Member's Name", 'filoxenia')
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Job", 'filoxenia'),
         "param_name" => "job",
         "value" => "",
         "description" => __("Member's job.", 'filoxenia')
      ),
      array(
         "type" => "textarea_html",
         "holder" => "div",
         "class" => "",
         "heading" => "Description",
         "param_name" => "content",
         "value" => "",
         "description" => __("", 'filoxenia')
      ), 
      array(
         "type"      => "textfield",
         "holder"    => "div",
         "class"     => "",
         "heading"   => __("Icon 1", 'filoxenia'),
         "param_name"=> "icon1",
         "value"     => "",
         "description" => __("Find here: <a target='_blank' href='http://fontawesome.io/icons/'>http://fontawesome.io/icons/</a>", 'filoxenia')
      ),
     array(
         "type"      => "textfield",
         "holder"    => "div",
         "class"     => "",
         "heading"   => "Url 1",
         "param_name"=> "url1",
         "value"     => "",
         "description" => __("Url.", 'filoxenia')
      ),
     array(
         "type"      => "textfield",
         "holder"    => "div",
         "class"     => "",
         "heading"   => __("Icon 2", 'filoxenia'),
         "param_name"=> "icon2",
         "value"     => "",
         "description" => __("Find here: <a target='_blank' href='http://fontawesome.io/icons/'>http://fontawesome.io/icons/</a>", 'filoxenia')
      ),
     array(
         "type"      => "textfield",
         "holder"    => "div",
         "class"     => "",
         "heading"   => "Url 2",
         "param_name"=> "url2",
         "value"     => "",
         "description" => __("Url.", 'filoxenia')
      ),
     array(
         "type"      => "textfield",
         "holder"    => "div",
         "class"     => "",
         "heading"   => __("Icon 3", 'filoxenia'),
         "param_name"=> "icon3",
         "value"     => "",
         "description" => __("Find here: <a target='_blank' href='http://fontawesome.io/icons/'>http://fontawesome.io/icons/</a>", 'filoxenia')
      ),
     array(
         "type"      => "textfield",
         "holder"    => "div",
         "class"     => "",
         "heading"   => "Url 3",
         "param_name"=> "url3",
         "value"     => "",
         "description" => __("Url.", 'filoxenia')
      ),
     array(
         "type"      => "textfield",
         "holder"    => "div",
         "class"     => "",
         "heading"   => __("Icon 4", 'filoxenia'),
         "param_name"=> "icon4",
         "value"     => "",
         "description" => __("Ex: google-plus, Find here: <a target='_blank' href='http://fontawesome.io/icons/'>http://fontawesome.io/icons/</a>", 'filoxenia')
      ),
     array(
         "type"      => "textfield",
         "holder"    => "div",
         "class"     => "",
         "heading"   => "Url 4",
         "param_name"=> "url4",
         "value"     => "",
         "description" => __("Url.", 'filoxenia')
      ),
    )));
}


// Services Box
if(function_exists('vc_map')){
	vc_map( array(
   "name" => __("OT Features Box", 'filoxenia'),
   "base" => "services",
   "class" => "",
   "category" => 'Content',
   "icon" => "icon-st",
   "params" => array(
	  array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => "Icon Box",
         "param_name" => "icon",
         "value" => "",
         "description" => __("Find here: <a target='_blank' href='http://fortawesome.github.io/Font-Awesome/icons/'>http://fortawesome.github.io/Font-Awesome/icons/</a>", 'filoxenia')
      ), 	
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Title Box", 'filoxenia'),
         "param_name" => "title",
         "value" => "",
         "description" => __("Title display in Services box.", 'filoxenia')
      ),
      array(
         "type" => "textarea_html",
         "holder" => "div",
         "class" => "",
         "heading" => __("Content Box", 'filoxenia'),
         "param_name" => "content",
         "value" => "",
         "description" => __("About your Services.", 'filoxenia')
      ),
      
      array(
         "type" => "dropdown",
         "holder" => "div",
         "class" => "",
         "heading" => __("Type Box", 'filoxenia'),
         "param_name" => "style",
         "value" => array(   

                     __('Style 1: Big Icon', 'filoxenia') => 'big',

                     __('Style 2: Small Icon', 'filoxenia') => 'small',

                    ),
         "description" => __("", 'filoxenia')
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Min Height Box", 'filoxenia'),
         "param_name" => "hei",
         "value" => "",
         "description" => __("Min Height Box. Ex: 100px", 'filoxenia')
      ),
    )
    ));
}


// Pricing Table
if(function_exists('vc_map')){
	vc_map( array(
   "name" => __("OT Pricing Table", 'filoxenia'),
   "base" => "pricingtable",
   "class" => "",
   "category" => 'Content',
   "icon" => "icon-st",
   "params" => array(
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Title Pricing", 'filoxenia'),
         "param_name" => "title",
         "value" => "",
         "description" => __("Title display in Pricing Table.", 'filoxenia')
      ),
	   array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Price Pricing", 'filoxenia'),
         "param_name" => "price",
         "value" => "",
         "description" => __("Price display in Pricing Table.", 'filoxenia')
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Per Time", 'filoxenia'),
         "param_name" => "time",
         "value" => "",
         "description" => __("Price per time in Pricing Table.", 'filoxenia')
      ),
      array(
         "type" => "textarea_html",
         "holder" => "div",
         "class" => "",
         "heading" => __("Detail Pricing", 'filoxenia'),
         "param_name" => "content",
         "value" => "",
         "description" => __("Content Pricing Table.", 'filoxenia')
      ),
	  array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Label Button", 'filoxenia'),
         "param_name" => "btntext",
         "value" => "",
         "description" => __("Text display in button, Ex: Order Now", 'filoxenia')
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Link Button", 'filoxenia'),
         "param_name" => "btnlink",
         "value" => "",
         "description" => __("Link in button.", 'filoxenia')
      ),
	   array(
         "type" => "dropdown",
         "holder" => "div",
         "class" => "",
         "heading" => __("Featured Pricing?", 'filoxenia'),
         "param_name" => "featured",
         "value" => array(   

                     __('No', 'filoxenia') => 'no',

                     __('Yes', 'filoxenia') => 'yes',

                    ),
         "description" => __("", 'filoxenia')
      ),
      
    )));
}

//Promotion Domain
if(function_exists('vc_map')){
   vc_map( array(
   "name" => __("OT Promotion Domain", 'filoxenia'),
   "base" => "prdomain",
   "class" => "",
   "icon" => "icon-st",
   "category" => 'Content',
   "params" => array(
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Title", 'filoxenia'),
         "param_name" => "title",
         "value" => "",
         "description" => __("Title", 'filoxenia')
      ),
      array(
         "type" => "attach_image",
         "holder" => "div",
         "class" => "",
         "heading" => "Image Domain name",
         "param_name" => "photo",
         "value" => "",
         "description" => __("Image Domain name, Recomended Size: 70 x 30", 'filoxenia')
      ),
      array(
         "type" => "textarea_html",
         "holder" => "div",
         "class" => "",
         "heading" => __("content", 'filoxenia'),
         "param_name" => "content",
         "value" => "",
         "description" => __("Description for domain name.", 'filoxenia')
      ),      
    )));
}


//Google Map
if(function_exists('vc_map')){
   vc_map( array(
   "name" => __("OT Google Map", 'filoxenia'),
   "base" => "ggmap",
   "class" => "",
   "icon" => "icon-st",
   "category" => 'Content',
   "params" => array(  
    array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("ID Map", 'filoxenia'),
         "param_name" => "idmap",
         "value" => "",
         "description" => __("Please enter ID Map, map-canvas, map-canvas1, map-canvas2, map-canvas3, ..etc", 'filoxenia')
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Height Map", 'filoxenia'),
         "param_name" => "height",
         "value" => 320,
         "description" => __("Please enter number height Map, 300, 350, 380, ..etc. Default: 420.", 'filoxenia')
      ),    
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Latitude", 'filoxenia'),
         "param_name" => "lat",
         "value" => -37.817,
         "description" => __("Please enter <a href='http://www.latlong.net/'>Latitude</a> google map", 'filoxenia')
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Longitude", 'filoxenia'),
         "param_name" => "long",
         "value" => 144.962,
         "description" => __("Please enter <a href='http://www.latlong.net/'>Longitude</a> google map", 'filoxenia')

      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Zoom Map", 'filoxenia'),
         "param_name" => "zoom",
         "value" => 13,
         "description" => __("Please enter Zoom Map, Default: 15", 'filoxenia')
      ),
    array(
            "type" => "colorpicker",
            "holder" => "div",
            "class" => "",
            "heading" => __("Map color", 'filoxenia'),
            "param_name" => "mapcolor",
            "value" => '', //Default White color
            "description" => __("Choose Map color", 'filoxenia')
         ),
    array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Company Name", 'filoxenia'),
         "param_name" => "address",
         "value" => "",
         "description" => __("Please enter Title.", 'filoxenia')
      ),   
       
    array(
         "type" => "attach_image",
         "holder" => "div",
         "class" => "",
         "heading" => "Icon Map marker",
         "param_name" => "icon",
         "value" => "",
         "description" => __("Icon Map marker, 47 x 68", 'filoxenia')
      ),  
       
    )));

}
 ?>