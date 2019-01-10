<?php return [
    'plugin'      => [
        'name'        => 'Shopaholic',
        'description' => '🛍️ Free eCommerce plugin with a large set of extensions.',
    ],
    'field'       => [
        'vendor_code'         => 'Артикул',
        'price'               => 'Ціна',
        'old_price'           => 'Стара ціна',
        'quantity'            => 'Кількість',
        'brand'               => 'Бренд',
        'offer'               => 'Торгові пропозиції',
        'currency'            => 'Валюта',
        'check_offer_active'  => 'Перевіряти наявність активних пропозицій при отриманні списку активних товарів',
        'additional_category' => 'Додаткові категорії',
        'promo_block_type'    => 'Промо-блок зі списком товарів',
        'promo_block'         => 'Промо-блок',
        'category_parent_id'  => 'Parent category ID',
        'product_id'          => 'ID товару',
    ],
    'menu'        => [
        'main'                => 'Каталог',
        'categories'          => 'Категорії',
        'product'             => 'Товари',
        'brands'              => 'Бренди',
        'shop_catalog'        => 'Product catalog',
        'shop_category'       => 'Product category',
        'all_shop_categories' => 'Всі категорії товарів',
        'promo_block'         => 'Промо-блоки',
        'promo'               => 'Promotions',
    ],
    'tab'         => [
        'offer'       => 'Trade offers',
        'price'       => 'Ціни',
        'permissions' => 'Shopaholic',
    ],
    'category'    => [
        'name'         => 'категорії',
        'list_title'   => 'Список категорій',
        'import_title' => 'Імпорт категорій',
        'export_title' => 'Експорт категорій',
    ],
    'brand'       => [
        'name'         => 'бренду',
        'list_title'   => 'Список брендів',
        'import_title' => 'Імпорт брендів',
        'export_title' => 'Експорт брендів',
    ],
    'product'     => [
        'name'         => 'товару',
        'list_title'   => 'Список товарів',
        'import_title' => 'Імпорт товарів',
        'export_title' => 'Експорт товарів',
    ],
    'offer'       => [
        'name'         => 'торгової пропозиції',
        'list_title'   => 'Список торгових пропозицій',
        'import_title' => 'Імпорт торгових пропозицій',
        'export_title' => 'Експорт торгових пропозицій',
    ],
    'promo_block' => [
        'name'       => 'промо-блоку',
        'list_title' => 'Список промо-блоків',
    ],
    'component'   => [

        //Product components
        'product_page_name'            => 'Сторінка товару',
        'product_page_description'     => 'Отримати дані для сторінки товару',
        'product_data_name'            => 'Дані товару',
        'product_data_description'     => 'Отримати дані товару по ID',
        'product_list_name'            => 'Список товарів',
        'product_list_description'     => 'Отримати список товарів',

        //Brand components
        'brand_page_name'              => 'Сторінка бренда',
        'brand_page_description'       => 'Отримати дані для сторінки бренда',
        'brand_data_name'              => 'Дані бренда',
        'brand_data_description'       => 'Отримати дані бренду за ID',
        'brand_list_name'              => 'Список брендів',
        'brand_list_description'       => 'Отримати список брендів',

        //Promo block components
        'promo_block_page_name'        => 'Сторінка промо-блоку',
        'promo_block_page_description' => 'Отримати дані для сторінки промо-блоку',
        'promo_block_data_name'        => 'Дані промо-блоку',
        'promo_block_data_description' => 'Отримати дані промо-блоку за ID',
        'promo_block_list_name'        => 'Список промо-блоків',
        'promo_block_list_description' => 'Отримати список промо-блоків',

        //Category components
        'category_page_name'           => 'Сторінка категорії',
        'category_page_description'    => 'Отримати дані для сторінки категорії',
        'category_data_name'           => 'Дані категорії',
        'category_data_description'    => 'Отримати дані категорії по ID',
        'category_list_name'           => 'Список категорій',
        'category_list_description'    => 'Отримати дерево категорій',

        //Common components
        'breadcrumbs_name'             => 'Хлібні крихти',
        'breadcrumbs_description'      => 'Отримати дані для формування хлібних крихт каталогу',

        //Components settings
        'product_list_sorting'         => 'Сортування за замовчуванням',
        'sorting_no'                   => 'Без сортування',
        'sorting_price_desc'           => 'Дорогі',
        'sorting_price_asc'            => 'Дешеві',
        'sorting_new'                  => 'Нові',
        'sorting_popularity_desc'      => 'Popular',
        'sorting_rating_desc'          => 'Високий рейтинг',
        'sorting_rating_asc'           => 'Низький рейтинг',
        'sorting_date_begin_asc'       => 'Дата початку (ASC)',
        'sorting_date_begin_desc'      => 'Дата початку (DESC)',
        'sorting_date_end_asc'         => 'Дата завершення (ASC)',
        'sorting_date_end_desc'        => 'Дата завершення (DESC)',
    ],
    'permission'  => [
        'category'    => 'Управління категоріями',
        'brand'       => 'Управління брендами',
        'product'     => 'Управління товарами',
        'settings'    => 'Управління налаштуваннями',
        'promo_block' => 'Управління промо-блоками',
    ],
    'message'     => [
        'import_additional_category_info' => 'Set the list of additional product categories separated by commas.',
    ],
    'button'      => [
        'import_offer_button' => 'Імпортувати пропозиції з CSV',
    ],
];
