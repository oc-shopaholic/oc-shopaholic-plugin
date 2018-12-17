<?php return [
    'plugin'      => [
        'name'        => 'Shopaholic',
        'description' => 'Каталог товарів',
    ],
    'field'       => [
        'vendor_code'         => 'Код виробника',
        'price'               => 'Ціна',
        'old_price'           => 'Стара ціна',
        'quantity'            => 'Кількість',
        'brand'               => 'Бренд',
        'offer'               => 'Пропозиції',
        'currency'            => 'Валюта',
        'check_offer_active'  => 'При отриманні списка активних товарів, перевірте наявність активних пропозицій',
        'additional_category' => 'Додаткові категорії',
        'promo_block_type'    => 'Рекламний блок із списком товарів',
        'promo_block'         => 'Рекламний блок',
        'category_parent_id'  => 'ID батьківського категорії',
        'product_id'          => 'Ідентифікатор товару',
    ],
    'menu'        => [
        'main'                => 'Каталог',
        'categories'          => 'Категорії',
        'product'             => 'Товари',
        'brands'              => 'Бренди',
        'shop_catalog'        => 'Каталог товарів',
        'shop_category'       => 'Категорії товарів',
        'all_shop_categories' => 'Всі категорії товарів',
        'promo_block'         => 'Рекламні блоки',
        'promo'               => 'Промо-акції',
    ],
    'tab'         => [
        'offer'       => 'Товарні пропозиції',
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
        'name'         => 'бренд',
        'list_title'   => 'Список брендів',
        'import_title' => 'Імпорт брендів',
        'export_title' => 'Експорт брендів',
    ],
    'product'     => [
        'name'         => 'товар',
        'list_title'   => 'Список товарів',
        'import_title' => 'Імпорт товарів',
        'export_title' => 'Експорт товарів',
    ],
    'offer'       => [
        'name'         => 'пропозиція',
        'list_title'   => 'Список пропозицій',
        'import_title' => 'Імпорт пропозицій',
        'export_title' => 'Експорт пропозицій',
    ],
    'promo_block' => [
        'name'       => 'промо блок',
        'list_title' => 'Список промо блоків',
    ],
    'component'   => [

        //Product components
        'product_page_name'            => 'Сторінка товару',
        'product_page_description'     => 'Отримати дані для сторінки товару',
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
        'sorting_date_begin_asc'       => 'Дата початку (ASC)',
        'sorting_date_begin_desc'      => 'Дата початку (DESC)',
        'sorting_date_end_asc'         => 'Дата завершення (ASC)',
        'sorting_date_end_desc'        => 'Дата завершення (DESC)',
    ],
    'permission'  => [
        'category'    => 'Управління категоріями',
        'brand'       => 'Управління брендами',
        'product'     => 'Управління товарами',
        'settings'    => 'Керування налаштуваннями',
        'promo_block' => 'Управління промо блоками',
    ],
    'message'     => [
        'import_additional_category_info' => 'Вкажіть список додаткових категорій продуктів, розділених комами.',
    ],
    'button'      => [
        'import_offer_button' => 'Імпортувати пропозиції з CSV',
    ],
];
