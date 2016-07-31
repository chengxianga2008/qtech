<?php

    /**
     * For full documentation, please visit: http://docs.reduxframework.com/
     * For a more extensive sample-config file, you may look at:
     * https://github.com/reduxframework/redux-framework/blob/master/sample/sample-config.php
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }

    // This is your option name where all the Redux data is stored.
    $opt_name = "theme_option";

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        'opt_name' => 'theme_option',
        'use_cdn' => TRUE,
        'display_name'     => $theme->get('Name'),
        'display_version'  => $theme->get('Version'),
        'page_title' => 'Filoxenia Options',
        'update_notice' => FALSE,
        'admin_bar' => TRUE,
        'menu_type' => 'menu',
        'menu_title' => 'Filoxenia Options',
        'allow_sub_menu' => TRUE,
        'page_parent_post_type' => 'your_post_type',
        'customizer' => FALSE,
        'dev_mode'   => false,
        'default_mark' => '*',
        'hints' => array(
            'icon_position' => 'right',
            'icon_color' => 'lightgray',
            'icon_size' => 'normal',
            'tip_style' => array(
                'color' => 'light',
            ),
            'tip_position' => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect' => array(
                'show' => array(
                    'duration' => '500',
                    'event' => 'mouseover',
                ),
                'hide' => array(
                    'duration' => '500',
                    'event' => 'mouseleave unfocus',
                ),
            ),
        ),
        'output' => TRUE,
        'output_tag' => TRUE,
        'settings_api' => TRUE,
        'cdn_check_time' => '1440',
        'compiler' => TRUE,
        'page_permissions' => 'manage_options',
        'save_defaults' => TRUE,
        'show_import_export' => TRUE,
        'database' => 'options',
        'transient_time' => '3600',
        'network_sites' => TRUE,
    );    

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */

    /*
     * ---> START HELP TABS
     */

    $tabs = array(
        array(
            'id'      => 'redux-help-tab-1',
            'title'   => esc_html__( 'Theme Information 1', 'filoxenia' ),
            'content' => esc_html__( '<p>This is the tab content, HTML is allowed.</p>', 'filoxenia' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => esc_html__( 'Theme Information 2', 'filoxenia' ),
            'content' => esc_html__( '<p>This is the tab content, HTML is allowed.</p>', 'filoxenia' )
        )
    );
    Redux::setHelpTab( $opt_name, $tabs );

    // Set the help sidebar
    $content = esc_html__( '<p>This is the sidebar content, HTML is allowed.</p>', 'filoxenia' );
    Redux::setHelpSidebar( $opt_name, $content );


    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */

    // ACTUAL DECLARATION OF SECTIONS          
    Redux::setSection( $opt_name, array(
        'icon' => 'el-icon-stackoverflow',
        'title' => __('Preload Settings', 'filoxenia'),
        'fields' => array(                                           
            array(
                'id'       => 'preload_opt',
                'type'     => 'switch',
                'title'    => __('On/Off Preload', 'filoxenia'),
                'subtitle' => __('Look, it\'s on!', 'filoxenia'),
                'default'  => true,
            ),   
            array(
                'id'       => 'bg_color',
                'type'     => 'color',
                'title'    => __('Background Color.', 'filoxenia'),
                'subtitle' => __('', 'filoxenia'),
                'default'  => '#f9f9f9',
            ),   
         )
    ) );

    Redux::setSection( $opt_name, array(
        'icon' => ' el-icon-picture',
        'title' => __('Logo & Favicon Settings', 'filoxenia'),
        'fields' => array(
            array(
                'id' => 'favicon',
                'type' => 'media',
                'url' => true,
                'title' => __('Custom Favicon', 'filoxenia'),
                'compiler' => 'true',
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc' => __('Upload your Favicon.', 'filoxenia'),
                'subtitle' => __('', 'filoxenia'),
                'default' => array('url' => get_template_directory_uri().'/images/favicon.png'),
            ),
            array(
                'id' => 'logo',
                'type' => 'media',
                'url' => true,
                'title' => __('Logo', 'filoxenia'),
                'compiler' => 'true',
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc' => __('logo.', 'filoxenia'),
                'subtitle' => __('', 'filoxenia'),
               'default' => array('url' => get_template_directory_uri().'/images/logo.png'),                     
            ),                                      
        )
    ) );
    
    Redux::setSection( $opt_name, array(
        'icon' => 'el-icon-group',
        'title' => __('Socials Settings', 'filoxenia'),
        'fields' => array(
            array(
                'id' => 'facebook',
                'type' => 'text',
                'title' => __('Facebook Url', 'filoxenia'),
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'default' => 'https://www.facebook.com/',
            ),
            array(
                'id' => 'twitter',
                'type' => 'text',
                'title' => __('Twitter Url', 'filoxenia'),
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'default' => 'https://twitter.com/',
            ),                    
            array(
                'id' => 'google',
                'type' => 'text',
                'title' => __('Google+ Url', 'filoxenia'),
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'default' => 'https://plus.google.com',
            ),                                    
            array(
                'id' => 'vimeo',
                'type' => 'text',
                'title' => __('Vimeo Url', 'filoxenia'),
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'default' => '',
            ),
            array(
                'id' => 'youtube',
                'type' => 'text',
                'title' => __('Youtube Url', 'filoxenia'),
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'default' => '',
            ),
            array(
                'id' => 'linkedin',
                'type' => 'text',
                'title' => __('Linkedin Url', 'filoxenia'),
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'default' => 'https://www.linkedin.com/',
            ),
            array(
                'id' => 'dribbble',
                'type' => 'text',
                'title' => __('Dribbble Url', 'filoxenia'),
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'default' => '',
            ),
            array(
                'id' => 'instagram',
                'type' => 'text',
                'title' => __('Instagram Url', 'filoxenia'),
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'default' => ''
            ),
            array(
                'id' => 'github',
                'type' => 'text',
                'title' => __('Github Url', 'filoxenia'),
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'default' => '#'
            ),
            array(
                'id' => 'skype',
                'type' => 'text',
                'title' => __('Skype Url', 'filoxenia'),
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'default' => ''
            ), 
            array(
                'id' => 'behance',
                'type' => 'text',
                'title' => __('Behance Url', 'filoxenia'),
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'default' => ''
            ), 
            array(
                'id' => 'rss',
                'type' => 'text',
                'title' => __('Rss Url', 'filoxenia'),
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'default' => ''
            ), 
         )
    ) );    

    Redux::setSection( $opt_name, array(
        'icon' => 'el-icon-blogger',
        'title' => __('Blog Settings', 'filoxenia'),
        'fields' => array(
            array(
                'id' => 'blog_excerpt',
                'type' => 'text',
                'title' => __('Blog custom excerpt leng', 'filoxenia'),
                'subtitle' => __('Input Blog custom excerpt leng', 'filoxenia'),
                'desc' => __('', 'filoxenia'),
                'default' => '30'
            ),   
            array(
                'id' => 'read_more',
                'type' => 'text',
                'title' => __('Button Text For Post', 'filoxenia'),
                'subtitle' => __('Input Button Text', 'filoxenia'),
                'desc' => __('', 'filoxenia'),
                'default' => 'Read more'
            ),
         )
    ) );
    
    Redux::setSection( $opt_name, array(
        'icon' => 'el-icon-graph',
        'title' => __('404 Settings', 'filoxenia'),
        'fields' => array(
             array(
                'id' => '404_title',
                'type' => 'text',
                'title' => __('404 Title', 'filoxenia'),
                'subtitle' => __('Input 404 Title', 'filoxenia'),
                'desc' => __('', 'filoxenia'),
                'default' => '404'
            ),                              
             array(
                'id' => '404_content',
                'type' => 'editor',
                'title' => __('404 Content', 'filoxenia'),
                'subtitle' => __('Enter 404 Content', 'filoxenia'),
                'desc' => __('', 'filoxenia'),
                'default' => 'The page you are looking for no longer exists. Perhaps you can return back to the sites homepage see you can find what you are looking for.'
            ),      
            array(
                'id' => 'back_404',
                'type' => 'text',
                'title' => __('Button Back Home', 'filoxenia'),                        
                'desc' => __('Text Button Go To Home.', 'filoxenia'),
                'subtitle' => __('', 'filoxenia'),
                'default' => 'Back To Home',
            ),                  
         )
    ) );

    Redux::setSection( $opt_name, array(
        'icon' => ' el-icon-credit-card',
        'title' => __('Footer Settings', 'filoxenia'),
        'fields' => array(  
            
            array(
                'id' => 'footer_text',
                'type' => 'editor',
                'title' => __('Footer Text', 'filoxenia'),
                'subtitle' => __('Copyright Text', 'filoxenia'),
                'default' => '©2015 ALL RIGHT RESERVED. MADE BY OCEANTHEMES',
            ),
            
        )
    ) );
    Redux::setSection( $opt_name, array(
        'icon' => 'el-icon-website',
        'title' => __('Styling Options', 'filoxenia'),
        'fields' => array(
            array(
                'id' => 'main-color',
                'type' => 'color',
                'title' => __('Theme Main Color', 'filoxenia'),
                'subtitle' => __('Pick the main color for the theme (default: #f25050).', 'filoxenia'),
                'default' => '#f25050',
                'validate' => 'color',
            ),  
            array(
                'id' => 'background_footer',
                'type' => 'color',
                'title' => __('Footer Background Color', 'filoxenia'),
                'subtitle' => __('Pick a background color for the footer (default: #35383f).', 'filoxenia'),
                'default' => '#35383f',
                'validate' => 'color',
            ),
            array(
                'id' => 'color_footer',
                'type' => 'color',
                'title' => __('Footer Text Color', 'filoxenia'),
                'subtitle' => __('Pick a color for the text footer (default: #989da8).', 'filoxenia'),
                'default' => '#989da8',
                'validate' => 'color',
            ),
            
            array(
                'id' => 'body-font2',
                'type' => 'typography',
                'output' => array('body'),
                'title' => __('Body Font', 'filoxenia'),
                'subtitle' => __('Specify the body font properties.', 'filoxenia'),
                'google' => true,
                'default' => array(
                    'color' => '',
                    'font-size' => '',
                    'line-height' => '',
                    'font-family' => '',
                    'font-weight' => ''
                ),
            ),
             array(
                'id' => 'custom-css',
                'type' => 'ace_editor',
                'title' => __('CSS Code', 'filoxenia'),
                'subtitle' => __('Paste your CSS code here.', 'filoxenia'),
                'mode' => 'css',
                'theme' => 'monokai',
                'desc' => 'Possible modes can be found at <a href="http://ace.c9.io" target="_blank">http://ace.c9.io/</a>.',
                'default' => "#header{\nmargin: 0 auto;\n}"
            ),
        )
    ) );

    /*
     * <--- END SECTIONS
     */