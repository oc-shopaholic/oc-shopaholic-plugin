<?php return [
    'plugin'      => [
        'name'        => 'Shopaholic',
        'description' => 'eCommerce үчүн буюмдар катологу',
    ],
    'field'       => [
        'vendor_code'         => 'Сатуучунун коду',
        'price'               => 'Нарк',
        'old_price'           => 'Эски нарк',
        'quantity'            => 'Сан',
        'brand'               => 'Бренд',
        'offer'               => 'Сунуштар',
        'currency'            => 'Акча',
        'check_offer_active'  => 'Сиз активдүү буюмдардын тизмесин алганда, активдүү сунуштарды текшериңиз',
        'additional_category' => 'Кошумча категориялар',
        'promo_block_type'    => 'Буюм тизме менен жарнамалык тозмосу',
        'promo_block'         => 'Жарнамалык тозмо',
        'category_parent_id'  => 'Категориянын атасынын аныктагычы',
        'product_id'          => 'Буюмдун аныктагычы',
    ],
    'menu'        => [
        'main'                => 'Каталог',
        'categories'          => 'Категориялар',
        'product'             => 'Буюмдар',
        'brands'              => 'Брендтер',
        'shop_catalog'        => 'Буюмдар каталогу',
        'shop_category'       => 'Буюмдар категориясы',
        'all_shop_categories' => 'Буюмдардын баардык категориясы',
        'promo_block'         => 'Жарнамалык тосмо',
        'promo'               => 'Жарнама',
    ],
    'tab'         => [
        'offer'       => 'Сунуштар',
        'price'       => 'Нарктар',
        'permissions' => 'Shopaholic',
    ],
    'category'    => [
        'name'         => 'категория',
        'list_title'   => 'Категория тизмеги',
        'import_title' => 'Категорияларды импорттоо',
        'export_title' => 'Категорияларды экспортто',
    ],
    'brand'       => [
        'name'         => 'бренд',
        'list_title'   => 'Брендтер тизмеги',
        'import_title' => 'Брендтерди импорттоо',
        'export_title' => 'Брендтерди экспортто',
    ],
    'product'     => [
        'name'         => 'буюм',
        'list_title'   => 'Буюмдар тизмеги',
        'import_title' => 'Буюмдарды импорттоо',
        'export_title' => 'Буюмдарды экспорттоо',
    ],
    'offer'       => [
        'name'         => 'сунуш',
        'list_title'   => 'Сунуштар тизмеги',
        'import_title' => 'Сунуштарды импорттоо',
        'export_title' => 'Сунуштарды экпорттоо',
    ],
    'promo_block' => [
        'name'       => 'жарнамалык тосмо',
        'list_title' => 'Жарнамалык тозмолор тизмеги',
    ],
    'component'   => [

        //Product components
        'product_page_name'            => 'Буюм барагы',
        'product_page_description'     => 'Буюм барагындагы маалыматты алуу',
        'product_data_name'            => 'Буюм маалыматы',
        'product_data_description'     => 'Get product data by ID',
        'product_list_name'            => 'Product list',
        'product_list_description'     => 'Get product list',

        //Brand components
        'brand_page_name'              => 'Brand page',
        'brand_page_description'       => 'Get data for brand page',
        'brand_data_name'              => 'Brand data',
        'brand_data_description'       => 'Get brand data by ID',
        'brand_list_name'              => 'Brand list',
        'brand_list_description'       => 'Get brand list',

        //Promo block components
        'promo_block_page_name'        => 'Promo block page',
        'promo_block_page_description' => 'Get data for promo block page',
        'promo_block_data_name'        => 'Promo block data',
        'promo_block_data_description' => 'Get promo block data by ID',
        'promo_block_list_name'        => 'Promo block list',
        'promo_block_list_description' => 'Get promo block list',

        //Category components
        'category_page_name'           => 'Category page',
        'category_page_description'    => 'Get data for category page',
        'category_data_name'           => 'Category data',
        'category_data_description'    => 'Get category data by ID',
        'category_list_name'           => 'Category list',
        'category_list_description'    => 'Get category tree',

        //Common components
        'breadcrumbs_name'             => 'Breadcrumbs',
        'breadcrumbs_description'      => 'Get data for catalog breadcrumbs',

        //Components settings
        'product_list_sorting'         => 'Default sorting',
        'sorting_no'                   => 'Without sorting',
        'sorting_price_desc'           => 'Expensive',
        'sorting_price_asc'            => 'Cheap',
        'sorting_new'                  => 'New',
        'sorting_popularity_desc'      => 'More popular',
        'sorting_rating_desc'          => 'High rating',
        'sorting_rating_asc'           => 'Low rating',
        'sorting_date_begin_asc'       => 'Date begin (ASC)',
        'sorting_date_begin_desc'      => 'Date begin (DESC)',
        'sorting_date_end_asc'         => 'Date end (ASC)',
        'sorting_date_end_desc'        => 'Date end (DESC)',
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
        'import_offer_button' => 'Import offers from CSV',
    ],
];
