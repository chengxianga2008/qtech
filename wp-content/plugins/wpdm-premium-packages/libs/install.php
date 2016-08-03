<?php

function wpdmpp_install() {
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $sql[] = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ahm_orders` (
  `order_id` varchar(100) NOT NULL,
  `trans_id` varchar(200) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` int(11) NOT NULL,
  `expire_date` int(11) NOT NULL,
  `items` text NOT NULL,
  `cart_data` text NOT NULL,
  `total` double NOT NULL,
  `order_status` enum('Pending','Processing','Completed','Cancelled','Expired') NOT NULL,
  `payment_status` enum('Pending','Processing','Completed','Bonus','Gifted','Cancelled','Refunded','Disputed','Expired') NOT NULL,
  `uid` int(11) NOT NULL,
  `ipn` text NOT NULL,
  `unit_prices` text NOT NULL,
  `subtotal` double NOT NULL,
  `discount` double NOT NULL,
  `tax` float NOT NULL,
  `order_notes` text NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `billing_info` text,
  `cart_discount` float DEFAULT NULL,
  `currency` text NOT NULL,
  `download` int(11) NOT NULL,
  `IP` varchar(20) NOT NULL,
  PRIMARY KEY (`order_id`)

)";

    $sql[] = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ahm_order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL,
  `variations` TEXT NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double NOT NULL,
  `status` int(11) NOT NULL,
  `coupon` varchar(255) DEFAULT NULL,
  `coupon_discount` float DEFAULT NULL,
  `role_discount` float DEFAULT NULL,
  `site_commission` float DEFAULT NULL,
  PRIMARY KEY (`id`)
)";

    $sql[] = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ahm_payment_methods` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `title` varchar(100) NOT NULL,
          `description` text NOT NULL,
          `class_name` varchar(80) NOT NULL,
          `enabled` int(11) NOT NULL,
          `default` int(11) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM";
    $sql[] = "INSERT INTO `{$wpdb->prefix}ahm_payment_methods` (`id`, `title`, `description`, `class_name`, `enabled`, `default`) VALUES(1, 'PayPal', 'PayPal', 'paypal', 1, 1)";

    $sql[] = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ahm_feature_products` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `productid` int(11) NOT NULL,
      `startdate` int(11) NOT NULL,
      `enddate` int(11) NOT NULL,
      PRIMARY KEY (`id`)
    )";

    $sql[] = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ahm_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(50) DEFAULT NULL,
  `country_name` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
)";

    $sql[] = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ahm_licenses` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `domain` text NOT NULL,
                  `licenseno` varchar(255) NOT NULL,
                  `status` int(11) NOT NULL,
                  `oid` varchar(100) NOT NULL,
                  `pid` int(11) NOT NULL,
                  `activation_date` int(11) NOT NULL,
                  `expire_date` int(11) NOT NULL,
                  `expire_period` int(11) NOT NULL,
                  PRIMARY KEY (`id`)
                )";

    $sql[] = "INSERT INTO `{$wpdb->prefix}ahm_country` (`id`, `country_code`, `country_name`, `status`) VALUES
        (1, 'AD', 'ANDORRA', NULL),
        (2, 'AE', 'UNITED ARAB EMIRATES', NULL),
        (3, 'AF', 'AFGHANISTAN', NULL),
        (4, 'AG', 'ANTIGUA AND BARBUDA', NULL),
        (5, 'AI', 'ANGUILLA', NULL),
        (6, 'AL', 'ALBANIA', NULL),
        (7, 'AM', 'ARMENIA', NULL),
        (8, 'AN', 'NETHERLANDS ANTILLES', NULL),
        (9, 'AO', 'ANGOLA', NULL),
        (10, 'AQ', 'ANTARCTICA', NULL),
        (11, 'AR', 'ARGENTINA', NULL),
        (12, 'AS', 'AMERICAN SAMOA', NULL),
        (13, 'AT', 'AUSTRIA', NULL),
        (14, 'AU', 'AUSTRALIA', NULL),
        (15, 'AW', 'ARUBA', NULL),
        (16, 'AZ', 'AZERBAIJAN', NULL),
        (17, 'BA', 'BOSNIA AND HERZEGOVINA', NULL),
        (18, 'BB', 'BARBADOS', NULL),
        (19, 'BD', 'BANGLADESH', NULL),
        (20, 'BE', 'BELGIUM', NULL),
        (21, 'BF', 'BURKINA FASO', NULL),
        (22, 'BG', 'BULGARIA', NULL),
        (23, 'BH', 'BAHRAIN', NULL),
        (24, 'BI', 'BURUNDI', NULL),
        (25, 'BJ', 'BENIN', NULL),
        (26, 'BM', 'BERMUDA', NULL),
        (27, 'BN', 'BRUNEI DARUSSALAM', NULL),
        (28, 'BO', 'BOLIVIA', NULL),
        (29, 'BR', 'BRAZIL', NULL),
        (30, 'BS', 'BAHAMAS', NULL),
        (31, 'BT', 'BHUTAN', NULL),
        (32, 'BV', 'BOUVET ISLAND', NULL),
        (33, 'BW', 'BOTSWANA', NULL),
        (34, 'BY', 'BELARUS', NULL),
        (35, 'BZ', 'BELIZE', NULL),
        (36, 'CA', 'CANADA', NULL),
        (37, 'CC', 'COCOS (KEELING) ISLANDS', NULL),
        (38, 'CD', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', NULL),
        (39, 'CF', 'CENTRAL AFRICAN REPUBLIC', NULL),
        (40, 'CG', 'CONGO', NULL),
        (41, 'CH', 'SWITZERLAND', NULL),
        (42, 'CI', 'COTE DIVOIRE', NULL),
        (43, 'CK', 'COOK ISLANDS', NULL),
        (44, 'CL', 'CHILE', NULL),
        (45, 'CM', 'CAMEROON', NULL),
        (46, 'CN', 'CHINA', NULL),
        (47, 'CO', 'COLOMBIA', NULL),
        (48, 'CR', 'COSTA RICA', NULL),
        (49, 'CS', 'SERBIA AND MONTENEGRO', NULL),
        (50, 'CU', 'CUBA', NULL),
        (51, 'CV', 'CAPE VERDE', NULL),
        (52, 'CX', 'CHRISTMAS ISLAND', NULL),
        (53, 'CY', 'CYPRUS', NULL),
        (54, 'CZ', 'CZECH REPUBLIC', NULL),
        (55, 'DE', 'GERMANY', NULL),
        (56, 'DJ', 'DJIBOUTI', NULL),
        (57, 'DK', 'DENMARK', NULL),
        (58, 'DM', 'DOMINICA', NULL),
        (59, 'DO', 'DOMINICAN REPUBLIC', NULL),
        (60, 'DZ', 'ALGERIA', NULL),
        (61, 'EC', 'ECUADOR', NULL),
        (62, 'EE', 'ESTONIA', NULL),
        (63, 'EG', 'EGYPT', NULL),
        (64, 'EH', 'WESTERN SAHARA', NULL),
        (65, 'ER', 'ERITREA', NULL),
        (66, 'ES', 'SPAIN', NULL),
        (67, 'ET', 'ETHIOPIA', NULL),
        (68, 'FI', 'FINLAND', NULL),
        (69, 'FJ', 'FIJI', NULL),
        (70, 'FK', 'FALKLAND ISLANDS (MALVINAS)', NULL),
        (71, 'FM', 'MICRONESIA, FEDERATED STATES OF', NULL),
        (72, 'FO', 'FAROE ISLANDS', NULL),
        (73, 'FR', 'FRANCE', NULL),
        (74, 'GA', 'GABON', NULL),
        (75, 'GB', 'UNITED KINGDOM', NULL),
        (76, 'GD', 'GRENADA', NULL),
        (77, 'GE', 'GEORGIA', NULL),
        (78, 'GF', 'FRENCH GUIANA', NULL),
        (79, 'GH', 'GHANA', NULL),
        (80, 'GI', 'GIBRALTAR', NULL),
        (81, 'GL', 'GREENLAND', NULL),
        (82, 'GM', 'GAMBIA', NULL),
        (83, 'GN', 'GUINEA', NULL),
        (84, 'GP', 'GUADELOUPE', NULL),
        (85, 'GQ', 'EQUATORIAL GUINEA', NULL),
        (86, 'GR', 'GREECE', NULL),
        (87, 'GS', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', NULL),
        (88, 'GT', 'GUATEMALA', NULL),
        (89, 'GU', 'GUAM', NULL),
        (90, 'GW', 'GUINEA-BISSAU', NULL),
        (91, 'GY', 'GUYANA', NULL),
        (92, 'HK', 'HONG KONG', NULL),
        (93, 'HM', 'HEARD ISLAND AND MCDONALD ISLANDS', NULL),
        (94, 'HN', 'HONDURAS', NULL),
        (95, 'HR', 'CROATIA', NULL),
        (96, 'HT', 'HAITI', NULL),
        (97, 'HU', 'HUNGARY', NULL),
        (98, 'ID', 'INDONESIA', NULL),
        (99, 'IE', 'IRELAND', NULL),
        (100, 'IL', 'ISRAEL', NULL),
        (101, 'IN', 'INDIA', NULL),
        (102, 'IO', 'BRITISH INDIAN OCEAN TERRITORY', NULL),
        (103, 'IQ', 'IRAQ', NULL),
        (104, 'IR', 'IRAN, ISLAMIC REPUBLIC OF', NULL),
        (105, 'IS', 'ICELAND', NULL),
        (106, 'IT', 'ITALY', NULL),
        (107, 'JM', 'JAMAICA', NULL),
        (108, 'JO', 'JORDAN', NULL),
        (109, 'JP', 'JAPAN', NULL),
        (110, 'KE', 'KENYA', NULL),
        (111, 'KG', 'KYRGYZSTAN', NULL),
        (112, 'KH', 'CAMBODIA', NULL),
        (113, 'KI', 'KIRIBATI', NULL),
        (114, 'KM', 'COMOROS', NULL),
        (115, 'KN', 'SAINT KITTS AND NEVIS', NULL),
        (116, 'KP', 'KOREA, DEMOCRATIC PEOPLE''S REPUBLIC OF', NULL),
        (117, 'KR', 'KOREA, REPUBLIC OF', NULL),
        (118, 'KW', 'KUWAIT', NULL),
        (119, 'KY', 'CAYMAN ISLANDS', NULL),
        (120, 'KZ', 'KAZAKHSTAN', NULL),
        (121, 'LA', 'LAO PEOPLE''S DEMOCRATIC REPUBLIC', NULL),
        (122, 'LB', 'LEBANON', NULL),
        (123, 'LC', 'SAINT LUCIA', NULL),
        (124, 'LI', 'LIECHTENSTEIN', NULL),
        (125, 'LK', 'SRI LANKA', NULL),
        (126, 'LR', 'LIBERIA', NULL),
        (127, 'LS', 'LESOTHO', NULL),
        (128, 'LT', 'LITHUANIA', NULL),
        (129, 'LU', 'LUXEMBOURG', NULL),
        (130, 'LV', 'LATVIA', NULL),
        (131, 'LY', 'LIBYAN ARAB JAMAHIRIYA', NULL),
        (132, 'MA', 'MOROCCO', NULL),
        (133, 'MC', 'MONACO', NULL),
        (134, 'MD', 'MOLDOVA, REPUBLIC OF', NULL),
        (135, 'MG', 'MADAGASCAR', NULL),
        (136, 'MH', 'MARSHALL ISLANDS', NULL),
        (137, 'MK', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', NULL),
        (138, 'ML', 'MALI', NULL),
        (139, 'MM', 'MYANMAR', NULL),
        (140, 'MN', 'MONGOLIA', NULL),
        (141, 'MO', 'MACAO', NULL),
        (142, 'MP', 'NORTHERN MARIANA ISLANDS', NULL),
        (143, 'MQ', 'MARTINIQUE', NULL),
        (144, 'MR', 'MAURITANIA', NULL),
        (145, 'MS', 'MONTSERRAT', NULL),
        (146, 'MT', 'MALTA', NULL),
        (147, 'MU', 'MAURITIUS', NULL),
        (148, 'MV', 'MALDIVES', NULL),
        (149, 'MW', 'MALAWI', NULL),
        (150, 'MX', 'MEXICO', NULL),
        (151, 'MY', 'MALAYSIA', NULL),
        (152, 'MZ', 'MOZAMBIQUE', NULL),
        (153, 'NA', 'NAMIBIA', NULL),
        (154, 'NC', 'NEW CALEDONIA', NULL),
        (155, 'NE', 'NIGER', NULL),
        (156, 'NF', 'NORFOLK ISLAND', NULL),
        (157, 'NG', 'NIGERIA', NULL),
        (158, 'NI', 'NICARAGUA', NULL),
        (159, 'NL', 'NETHERLANDS', NULL),
        (160, 'NO', 'NORWAY', NULL),
        (161, 'NP', 'NEPAL', NULL),
        (162, 'NR', 'NAURU', NULL),
        (163, 'NU', 'NIUE', NULL),
        (164, 'NZ', 'NEW ZEALAND', NULL),
        (165, 'OM', 'OMAN', NULL),
        (166, 'PA', 'PANAMA', NULL),
        (167, 'PE', 'PERU', NULL),
        (168, 'PF', 'FRENCH POLYNESIA', NULL),
        (169, 'PG', 'PAPUA NEW GUINEA', NULL),
        (170, 'PH', 'PHILIPPINES', NULL),
        (171, 'PK', 'PAKISTAN', NULL),
        (172, 'PL', 'POLAND', NULL),
        (173, 'PM', 'SAINT PIERRE AND MIQUELON', NULL),
        (174, 'PN', 'PITCAIRN', NULL),
        (175, 'PR', 'PUERTO RICO', NULL),
        (176, 'PS', 'PALESTINIAN TERRITORY, OCCUPIED', NULL),
        (177, 'PT', 'PORTUGAL', NULL),
        (178, 'PW', 'PALAU', NULL),
        (179, 'PY', 'PARAGUAY', NULL),
        (180, 'QA', 'QATAR', NULL),
        (181, 'RE', 'REUNION', NULL),
        (182, 'RO', 'ROMANIA', NULL),
        (183, 'RU', 'RUSSIAN FEDERATION', NULL),
        (184, 'RW', 'RWANDA', NULL),
        (185, 'SA', 'SAUDI ARABIA', NULL),
        (186, 'SB', 'SOLOMON ISLANDS', NULL),
        (187, 'SC', 'SEYCHELLES', NULL),
        (188, 'SD', 'SUDAN', NULL),
        (189, 'SE', 'SWEDEN', NULL),
        (190, 'SG', 'SINGAPORE', NULL),
        (191, 'SH', 'SAINT HELENA', NULL),
        (192, 'SI', 'SLOVENIA', NULL),
        (193, 'SJ', 'SVALBARD AND JAN MAYEN', NULL),
        (194, 'SK', 'SLOVAKIA', NULL),
        (195, 'SL', 'SIERRA LEONE', NULL),
        (196, 'SM', 'SAN MARINO', NULL),
        (197, 'SN', 'SENEGAL', NULL),
        (198, 'SO', 'SOMALIA', NULL),
        (199, 'SR', 'SURINAME', NULL),
        (200, 'ST', 'SAO TOME AND PRINCIPE', NULL),
        (201, 'SV', 'EL SALVADOR', NULL),
        (202, 'SY', 'SYRIAN ARAB REPUBLIC', NULL),
        (203, 'SZ', 'SWAZILAND', NULL),
        (204, 'TC', 'TURKS AND CAICOS ISLANDS', NULL),
        (205, 'TD', 'CHAD', NULL),
        (206, 'TF', 'FRENCH SOUTHERN TERRITORIES', NULL),
        (207, 'TG', 'TOGO', NULL),
        (208, 'TH', 'THAILAND', NULL),
        (209, 'TJ', 'TAJIKISTAN', NULL),
        (210, 'TK', 'TOKELAU', NULL),
        (211, 'TL', 'TIMOR-LESTE', NULL),
        (212, 'TM', 'TURKMENISTAN', NULL),
        (213, 'TN', 'TUNISIA', NULL),
        (214, 'TO', 'TONGA', NULL),
        (215, 'TR', 'TURKEY', NULL),
        (216, 'TT', 'TRINIDAD AND TOBAGO', NULL),
        (217, 'TV', 'TUVALU', NULL),
        (218, 'TW', 'TAIWAN, PROVINCE OF CHINA', NULL),
        (219, 'TZ', 'TANZANIA, UNITED REPUBLIC OF', NULL),
        (220, 'UA', 'UKRAINE', NULL),
        (221, 'UG', 'UGANDA', NULL),
        (222, 'UM', 'UNITED STATES MINOR OUTLYING ISLANDS', NULL),
        (223, 'US', 'UNITED STATES', NULL),
        (224, 'UY', 'URUGUAY', NULL),
        (225, 'UZ', 'UZBEKISTAN', NULL),
        (226, 'VA', 'HOLY SEE (VATICAN CITY STATE)', NULL),
        (227, 'VC', 'SAINT VINCENT AND THE GRENADINES', NULL),
        (228, 'VE', 'VENEZUELA', NULL),
        (229, 'VG', 'VIRGIN ISLANDS, BRITISH', NULL),
        (230, 'VI', 'VIRGIN ISLANDS, U.S.', NULL),
        (231, 'VN', 'VIET NAM', NULL),
        (232, 'VU', 'VANUATU', NULL),
        (233, 'WF', 'WALLIS AND FUTUNA', NULL),
        (234, 'WS', 'SAMOA', NULL),
        (235, 'YE', 'YEMEN', NULL),
        (236, 'YT', 'MAYOTTE', NULL),
        (237, 'ZA', 'SOUTH AFRICA', NULL),
        (238, 'ZM', 'ZAMBIA', NULL),
        (239, 'ZW', 'ZIMBABWE', NULL);
         ";

    $sql[] = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ahm_withdraws` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `uid` int(11) NOT NULL DEFAULT '0',
      `date` int(11) NOT NULL DEFAULT '0',
      `amount` double NOT NULL DEFAULT '0',
      `status` int(11) NOT NULL DEFAULT '1',
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM ";

    $sql[] = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ahm_feature_set` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `feature_name` text NOT NULL, 
          `enabled` int(1) NOT NULL DEFAULT '1',
          `del` int(1) NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`)
        )";

    $sql[] = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ahm_feature_set_meta` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `fid` int(11) NOT NULL,
          `field_type` varchar(80) NOT NULL,
          `option_name` text NOT NULL,
          `option_value` longtext NOT NULL, 
          `enabled` int(1) NOT NULL DEFAULT '1',
          `del` int(1) NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`)
        )";

    $sql[] = "ALTER TABLE `{$wpdb->prefix}ahm_order_items` ADD `coupon` VARCHAR( 255 ) NULL , ADD `coupon_amount` FLOAT NULL   ";
    $sql[] = "ALTER TABLE `{$wpdb->prefix}ahm_order_items` ADD `site_commission` float not NULL DEFAULT '0'";
    $sql[] = "ALTER TABLE `{$wpdb->prefix}ahm_order_items` ADD `variations` TEXT NOT NULL AFTER `pid`";
    $sql[] = "ALTER TABLE `{$wpdb->prefix}ahm_order_items` ADD `role_discount` float NOT NULL";
    $sql[] = "ALTER TABLE `{$wpdb->prefix}ahm_order_items` CHANGE `coupon_amount` `coupon_discount` FLOAT NULL DEFAULT NULL ";
    $sql[] = "ALTER TABLE `{$wpdb->prefix}ahm_orders` ADD `discount` FLOAT NOT NULL";
    $sql[] = "ALTER TABLE `{$wpdb->prefix}ahm_orders` ADD `coupon_discount` FLOAT NOT NULL";
    $sql[] = "ALTER TABLE `{$wpdb->prefix}ahm_orders` ADD `tax` FLOAT NOT NULL AFTER `discount` ";
    $sql[] = "ALTER TABLE `{$wpdb->prefix}ahm_orders` ADD `currency` TEXT NOT NULL";
    $sql[] = "ALTER TABLE `{$wpdb->prefix}ahm_orders` ADD `order_notes` TEXT NOT NULL";
    $sql[] = "ALTER TABLE `{$wpdb->prefix}ahm_orders` ADD `download` INT NOT NULL";
    $sql[] = "ALTER TABLE `{$wpdb->prefix}ahm_orders` ADD `IP` VARCHAR( 20 ) NOT NULL";
    $sql[] = "ALTER TABLE `{$wpdb->prefix}ahm_orders` ADD `ipn` TEXT NOT NULL";
    $sql[] = "ALTER TABLE `{$wpdb->prefix}ahm_orders` ADD `unit_prices` TEXT NOT NULL";
    $sql[] = "ALTER TABLE `{$wpdb->prefix}ahm_orders` ADD `billing_info` TEXT NOT NULL";
    $sql[] = "ALTER TABLE `{$wpdb->prefix}ahm_orders` ADD `expire_date` INT NOT NULL";
    $sql[] = "ALTER TABLE `{$wpdb->prefix}ahm_orders` ADD `trans_id` VARCHAR(200) NOT NULL AFTER `order_id`";
    $sql[] = "ALTER TABLE `{$wpdb->prefix}ahm_orders` CHANGE `order_notes` `order_notes` TEXT CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL";
    $sql[] = "ALTER TABLE `{$wpdb->prefix}ahm_orders` CHANGE `order_status` `order_status` ENUM('Pending','Processing','Completed','Cancelled','Expired') NOT NULL, CHANGE `payment_status` `payment_status` ENUM('Pending','Processing','Completed','Bonus','Gifted','Cancelled','Refunded','Disputed','Expired')  NOT NULL";

    foreach ($sql as $qry) {
        $wpdb->query($qry);
    }

    if (!$wpdb->get_var("select id from {$wpdb->prefix}posts where post_type='page' AND post_content like '%[wpdm-pp-cart]%'")) {
        $cart_id = wp_insert_post(array('post_title' => 'Cart', 'post_content' => '[wpdm-pp-cart]', 'post_type' => 'page', 'post_status' => 'publish'));
        $orders_page = wp_insert_post(array('post_title' => 'Purchases', 'post_content' => '[wpdm-pp-purchases]', 'post_type' => 'page', 'post_status' => 'publish'));
    }

    $_wpdmpp_settings['page_id'] = $cart_id;
    $_wpdmpp_settings['orders_page_id'] = $orders_page;
    $_wpdmpp_settings['continue_shopping_url'] = site_url('/');
    $_wpdmpp_settings['wpdmpp_after_addtocart_redirect'] = 1;
    $_wpdmpp_settings['Paypal']['enabled'] = 1;
    $_wpdmpp_settings['Paypal']['Paypal_mode'] = 'live';
    $_wpdmpp_settings['Paypal']['return_url'] = get_permalink($orders_page);

    if (!get_option('_wpdmpp_settings')) {
        update_option('_wpdmpp_settings', $_wpdmpp_settings);
        update_option('wpdmpp_access_level', 'level_2');
    }


}

?>