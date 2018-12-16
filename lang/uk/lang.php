<?php return [
    'plugin'      => [
        'name'        => 'Shopaholic',
        'description' => 'Каталог товарів для eCommerce',
    ],
    'field'       => [
        'vendor_code'         => 'Код виробника',
        'price'               => 'Ціна',
        'old_price'           => 'Стара ціна',
        'quantity'            => 'Кількість',
        'brand'               => 'Brand',
        'offer'               => 'Offers',
        'currency'            => 'Валюта',
        'check_offer_active'  => 'When you receive a list of active products, check for active offers',
        'additional_category' => 'Додаткові категорії',
        'promo_block_type'    => 'Promo block with product list',
        'promo_block'         => 'Promo block',
        'category_parent_id'  => 'Ідентифікатор батьківського категорії',
        'product_id'          => 'Ідентифікатор товару',
    ],
    'menu'        => [
        'main'                => 'Каталог',
        'categories'          => 'Категорії',
        'product'             => 'Товари',
        'brands'              => 'Brands',
        'shop_catalog'        => 'Catalog of products',
        'shop_category'       => 'Category of products',
        'all_shop_categories' => 'Всі категорії товарів',
        'promo_block'         => 'Promo blocks',
        'promo'               => 'Promo',
    ],
    'tab'         => [
        'offer'       => 'Offers',
        'price'       => 'Ціни',
        'permissions' => 'Shopaholic',
    ],
    'category'    => [
        'name'         => 'категорія',
        'list_title'   => 'Список категорій',
        'import_title' => 'Імпорт категорій',
        'export_title' => 'Експорт категорій',
    ],
    'brand'       => [
        'name'         => 'brand',
        'list_title'   => 'Brand list',
        'import_title' => 'Import brands',
        'export_title' => 'Export brands',
    ],
    'product'     => [
        'name'         => 'товар',
        'list_title'   => 'Список товарів',
        'import_title' => 'Імпорт товарів',
        'export_title' => 'Експорт товарів',
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
        'product_page_name'            => 'Сторінка товару',
        'product_page_description'     => 'Get data for product page',
        'product_data_name'            => 'Product data',
        'product_data_description'     => 'Get product data by ID',
        'product_list_name'            => 'Список товарів',
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
        'category_page_name'           => 'Сторінка категорії',
        'category_page_description'    => 'Get data for category page',
        'category_data_name'           => 'Category data',
        'category_data_description'    => 'Get category data by ID',
        'category_list_name'           => 'Category list',
        'category_list_description'    => 'Get category tree',

        //Common components
        'breadcrumbs_name'             => 'Breadcrumbs',
        'breadcrumbs_description'      => 'Get data for catalog breadcrumbs',

        //Components settings
        'product_list_sorting'         => 'Сортування за замовчуванням',
        'sorting_no'                   => 'Без сортування',
        'sorting_price_desc'           => 'Expensive',
        'sorting_price_asc'            => 'Cheap',
        'sorting_new'                  => 'Новий',
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
