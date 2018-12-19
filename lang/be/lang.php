<?php return [
    'plugin'      => [
        'name'        => 'Shopaholic',
        'description' => 'Catalog of products for eCommerce',
    ],
    'field'       => [
        'vendor_code'         => 'Артыкул',
        'price'               => 'Цана',
        'old_price'           => 'Старая цана',
        'quantity'            => 'Колькасць',
        'brand'               => 'Брэнд',
        'offer'               => 'Таварныя прапановы',
        'currency'            => 'Валюта',
        'check_offer_active'  => 'When you receive a list of active products, check for active offers',
        'additional_category' => 'Дадатковыя катэгорыі',
        'promo_block_type'    => 'Promo block with product list',
        'promo_block'         => 'Promo block',
        'category_parent_id'  => 'Category parent ID',
        'product_id'          => 'ID тавару',
    ],
    'menu'        => [
        'main'                => 'Каталог',
        'categories'          => 'Катэгорыі',
        'product'             => 'Тавары',
        'brands'              => 'Brands',
        'shop_catalog'        => 'Catalog of products',
        'shop_category'       => 'Category of products',
        'all_shop_categories' => 'All categories of products',
        'promo_block'         => 'Promo blocks',
        'promo'               => 'Promo',
    ],
    'tab'         => [
        'offer'       => 'Offers',
        'price'       => 'Цэны',
        'permissions' => 'Shopaholic',
    ],
    'category'    => [
        'name'         => 'катэгорыі',
        'list_title'   => 'Cпіс катэгорый',
        'import_title' => 'Iмпарт катэгорый',
        'export_title' => 'Export categories',
    ],
    'brand'       => [
        'name'         => 'brand',
        'list_title'   => 'Спіс брэндаў',
        'import_title' => 'Import brands',
        'export_title' => 'Export brands',
    ],
    'product'     => [
        'name'         => 'product',
        'list_title'   => 'Спіс тавараў',
        'import_title' => 'Import products',
        'export_title' => 'Export products',
    ],
    'offer'       => [
        'name'         => 'offer',
        'list_title'   => 'Offer list',
        'import_title' => 'Import offers',
        'export_title' => 'Export offers',
    ],
    'promo_block' => [
        'name'       => 'promo block',
        'list_title' => 'Promo block list',
    ],
    'component'   => [

        //Product components
        'product_page_name'            => 'Старонка тавару',
        'product_page_description'     => 'Атрымаць даныя для старонкі тавару',
        'product_data_name'            => 'Product data',
        'product_data_description'     => 'Get product data by ID',
        'product_list_name'            => 'Спіс тавараў',
        'product_list_description'     => 'Get product list',

        //Brand components
        'brand_page_name'              => 'Brand page',
        'brand_page_description'       => 'Get data for brand page',
        'brand_data_name'              => 'Brand data',
        'brand_data_description'       => 'Get brand data by ID',
        'brand_list_name'              => 'Brand list',
        'brand_list_description'       => 'Атрымаць спіс брэндаў',

        //Promo block components
        'promo_block_page_name'        => 'Promo block page',
        'promo_block_page_description' => 'Get data for promo block page',
        'promo_block_data_name'        => 'Promo block data',
        'promo_block_data_description' => 'Get promo block data by ID',
        'promo_block_list_name'        => 'Promo block list',
        'promo_block_list_description' => 'Get promo block list',

        //Category components
        'category_page_name'           => 'Старонка катэгорыі',
        'category_page_description'    => 'Атрымаць даныя для старонкі катэгорыі',
        'category_data_name'           => 'Category data',
        'category_data_description'    => 'Атрымаць даныя катэгорыі па ID',
        'category_list_name'           => 'Спіс катэгорый',
        'category_list_description'    => 'Атрымаць дрэва катэгорый',

        //Common components
        'breadcrumbs_name'             => 'Хлебныя крошкі',
        'breadcrumbs_description'      => 'Атрымаць даныя для фарміравання хлебных крошак на старонцы каталога',

        //Components settings
        'product_list_sorting'         => 'Сартаванне па змаўчанні',
        'sorting_no'                   => 'Без сартавання',
        'sorting_price_desc'           => 'Дарагiя',
        'sorting_price_asc'            => 'Танныя',
        'sorting_new'                  => 'Новыя',
        'sorting_popularity_desc'      => 'More popular',
        'sorting_rating_desc'          => 'Высокі рэйтынг',
        'sorting_rating_asc'           => 'Нізкі рэйтынг',
        'sorting_date_begin_asc'       => 'Дата пачатку (ASC)',
        'sorting_date_begin_desc'      => 'Дата пачатку (DESC)',
        'sorting_date_end_asc'         => 'Дата заканчэння (ASC)',
        'sorting_date_end_desc'        => 'Дата заканчэння (DESC)',
    ],
    'permission'  => [
        'category'    => 'Manage categories',
        'brand'       => 'Manage brands',
        'product'     => 'Manage products',
        'settings'    => 'Manage settings',
        'promo_block' => 'Manage promo blocks',
    ],
    'message'     => [
        'import_additional_category_info' => 'Set list of additional product categories, separated by commas.',
    ],
    'button'      => [
        'import_offer_button' => 'Імпартаваць прапановы з CSV',
    ],
];
