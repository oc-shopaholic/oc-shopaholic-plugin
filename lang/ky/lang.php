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
        'product_data_description'     => 'Аныктагычу боюнча буюмдун маалыматын алуу',
        'product_list_name'            => 'Буюм тизмеги',
        'product_list_description'     => 'Буюм тизмегин алуу',

        //Brand components
        'brand_page_name'              => 'Бренд барагы',
        'brand_page_description'       => 'Бренд барагы үчүн маалымат алуу',
        'brand_data_name'              => 'Бренд маалыматы',
        'brand_data_description'       => 'Аныктагыч менен брентин маалыматын алуу',
        'brand_list_name'              => 'Бренд тизмеги',
        'brand_list_description'       => 'Бренд тизмегин алуу',

        //Promo block components
        'promo_block_page_name'        => 'Жаранамалык тозмо барагы',
        'promo_block_page_description' => 'Жарнамалык тозмо барагынын маалыматын алуу',
        'promo_block_data_name'        => 'Жарнамалык тозмо маалыматы',
        'promo_block_data_description' => 'Жарнамалык тозмо маалыматын анактагычы менен алуу',
        'promo_block_list_name'        => 'Жарнамалык тозмо тизмеги',
        'promo_block_list_description' => 'Жарнамалык тозмо тизмегин алуу',

        //Category components
        'category_page_name'           => 'Категория барагы',
        'category_page_description'    => 'Категория барагынын маалыматын алуу',
        'category_data_name'           => 'Категория маалыматы',
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
