<?php return [
    'plugin'      => [
        'name'        => 'Shopaholic',
        'description' => 'Электрондық коммерцияға арналған тауарлар каталогы',
    ],
    'field'       => [
        'vendor_code'         => 'Артикул',
        'price'               => 'Бағасы',
        'old_price'           => 'Ескі бағасы',
        'quantity'            => 'Сан',
        'brand'               => 'Бренд',
        'offer'               => 'Ұсыныстар',
        'currency'            => 'Валюта',
        'check_offer_active'  => 'When you receive a list of active products, check for active offers',
        'additional_category' => 'Қосымша категориялар',
        'promo_block_type'    => 'Promo block with product list',
        'promo_block'         => 'Промо блок',
        'category_parent_id'  => 'Жоғарғы категория ID',
        'product_id'          => 'Тауар ID',
    ],
    'menu'        => [
        'main'                => 'Каталог',
        'categories'          => 'Категориялар',
        'product'             => 'Тауарлар',
        'brands'              => 'Брендтар',
        'shop_catalog'        => 'Тауарлар каталогы',
        'shop_category'       => 'Тауарлар категориясы',
        'all_shop_categories' => 'Тауарлардын барлық категориясы',
        'promo_block'         => 'Промо блоктар',
        'promo'               => 'Промо-акциялар',
    ],
    'tab'         => [
        'offer'       => 'Тауарлар ұсыныстары',
        'price'       => 'Бағалары',
        'permissions' => 'Shopaholic',
    ],
    'category'    => [
        'name'         => 'категория',
        'list_title'   => 'Категория тізімі',
        'import_title' => 'Категорияларды импорттау',
        'export_title' => 'Категорияларды экспорттау',
    ],
    'brand'       => [
        'name'         => 'бренд',
        'list_title'   => 'Брендтер тізімі',
        'import_title' => 'Брендтерді импорттау',
        'export_title' => 'Брендтерді экспорттау',
    ],
    'product'     => [
        'name'         => 'тауар',
        'list_title'   => 'Тауар тізімі',
        'import_title' => 'Тауарларды импорттау',
        'export_title' => 'Тауарларды экспорттау',
    ],
    'offer'       => [
        'name'         => 'ұсыныстар',
        'list_title'   => 'Ұсыныстар тізімі',
        'import_title' => 'Ұсыныстарды импорттау',
        'export_title' => 'Ұсыныстарды импорттау',
    ],
    'promo_block' => [
        'name'       => 'промо блоктар',
        'list_title' => 'Промо блоктар тізімі',
    ],
    'component'   => [

        //Product components
        'product_page_name'            => 'Тауар парағы',
        'product_page_description'     => 'Get data for product page',
        'product_data_name'            => 'Тауар деректері',
        'product_data_description'     => 'Get product data by ID',
        'product_list_name'            => 'Тауар тізімі',
        'product_list_description'     => 'Тауарлар тізімін алу',

        //Brand components
        'brand_page_name'              => 'Бренд парағы',
        'brand_page_description'       => 'Get data for brand page',
        'brand_data_name'              => 'Бренд деректері',
        'brand_data_description'       => 'Get brand data by ID',
        'brand_list_name'              => 'Брендтер тізімі',
        'brand_list_description'       => 'Брендтер тізімін алу',

        //Promo block components
        'promo_block_page_name'        => 'Промо блок парағы',
        'promo_block_page_description' => 'Get data for promo block page',
        'promo_block_data_name'        => 'Промо блок деректері',
        'promo_block_data_description' => 'Промо блок деректерін алу',
        'promo_block_list_name'        => 'Промо блоктар тізімі',
        'promo_block_list_description' => 'Промо блоктар тізімін алу',

        //Category components
        'category_page_name'           => 'Категория парағы',
        'category_page_description'    => 'Get data for category page',
        'category_data_name'           => 'Категория деректері',
        'category_data_description'    => 'Get category data by ID',
        'category_list_name'           => 'Категория тізімі',
        'category_list_description'    => 'Get category tree',

        //Common components
        'breadcrumbs_name'             => 'Breadcrumbs',
        'breadcrumbs_description'      => 'Get data for catalog breadcrumbs',

        //Components settings
        'product_list_sorting'         => 'Default sorting',
        'sorting_no'                   => 'Without sorting',
        'sorting_price_desc'           => 'Expensive',
        'sorting_price_asc'            => 'Арзандар',
        'sorting_new'                  => 'Жаңа',
        'sorting_popularity_desc'      => 'Танымалды',
        'sorting_rating_desc'          => 'Рейтингі жоғары',
        'sorting_rating_asc'           => 'Рейтингі төмен',
        'sorting_date_begin_asc'       => 'Date begin (ASC)',
        'sorting_date_begin_desc'      => 'Date begin (DESC)',
        'sorting_date_end_asc'         => 'Date end (ASC)',
        'sorting_date_end_desc'        => 'Date end (DESC)',
    ],
    'permission'  => [
        'category'    => 'Категорияларды басқару',
        'brand'       => 'Брендтарды басқару',
        'product'     => 'Тауарларды басқару',
        'settings'    => 'Баптауларды басқару',
        'promo_block' => 'Промо блоктармен басқару',
    ],
    'message'     => [
        'import_additional_category_info' => 'Set list of additional product categories, separated by commas.',
    ],
    'button'      => [
        'import_offer_button' => 'CSV файлдан ұсыныстарды импорттау',
    ],
];
