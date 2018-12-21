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
        'promo_block'         => 'Прома-блок',
        'category_parent_id'  => 'Category parent ID',
        'product_id'          => 'ID тавару',
    ],
    'menu'        => [
        'main'                => 'Каталог',
        'categories'          => 'Катэгорыі',
        'product'             => 'Тавары',
        'brands'              => 'Брэнды',
        'shop_catalog'        => 'Catalog of products',
        'shop_category'       => 'Катэгорыя тавараў',
        'all_shop_categories' => 'All categories of products',
        'promo_block'         => 'Прома-блокі',
        'promo'               => 'Прома-акцыі',
    ],
    'tab'         => [
        'offer'       => 'Гандлёвыя прапановы',
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
        'name'         => 'брэнду',
        'list_title'   => 'Спіс брэндаў',
        'import_title' => 'Import brands',
        'export_title' => 'Export brands',
    ],
    'product'     => [
        'name'         => 'тавару',
        'list_title'   => 'Спіс тавараў',
        'import_title' => 'Import products',
        'export_title' => 'Export products',
    ],
    'offer'       => [
        'name'         => 'прапановы',
        'list_title'   => 'Offer list',
        'import_title' => 'Import offers',
        'export_title' => 'Export offers',
    ],
    'promo_block' => [
        'name'       => 'прома-блока',
        'list_title' => 'Спіс прома-блокаў',
    ],
    'component'   => [

        //Product components
        'product_page_name'            => 'Старонка тавару',
        'product_page_description'     => 'Атрымаць даныя для старонкі тавару',
        'product_data_name'            => 'Дадзеныя тавару',
        'product_data_description'     => 'Атрымаць дадзеныя тавару па ID',
        'product_list_name'            => 'Спіс тавараў',
        'product_list_description'     => 'Атрымаць спіс тавараў',

        //Brand components
        'brand_page_name'              => 'Старонка брэнду',
        'brand_page_description'       => 'Атрымаць даныя для старонкі брэнда',
        'brand_data_name'              => 'Дадзеныя брэнду',
        'brand_data_description'       => 'Атрымаць дадзеныя брэнда па ID',
        'brand_list_name'              => 'Спіс брэндаў',
        'brand_list_description'       => 'Атрымаць спіс брэндаў',

        //Promo block components
        'promo_block_page_name'        => 'Страница промо-блока',
        'promo_block_page_description' => 'Атрымаць даныя для старонкі прома-блоку',
        'promo_block_data_name'        => 'Дадзеныя прома-блоку',
        'promo_block_data_description' => 'Получить данные промо-блока по ID',
        'promo_block_list_name'        => 'Спіс прома-блокаў',
        'promo_block_list_description' => 'Атрымаць спіс прома-блокаў',

        //Category components
        'category_page_name'           => 'Старонка катэгорыі',
        'category_page_description'    => 'Атрымаць даныя для старонкі катэгорыі',
        'category_data_name'           => 'Дадзеныя катэгорыі',
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
        'sorting_popularity_desc'      => 'Папулярныя',
        'sorting_rating_desc'          => 'Высокі рэйтынг',
        'sorting_rating_asc'           => 'Нізкі рэйтынг',
        'sorting_date_begin_asc'       => 'Дата пачатку (ASC)',
        'sorting_date_begin_desc'      => 'Дата пачатку (DESC)',
        'sorting_date_end_asc'         => 'Дата заканчэння (ASC)',
        'sorting_date_end_desc'        => 'Дата заканчэння (DESC)',
    ],
    'permission'  => [
        'category'    => 'Кіраванне катэгорыямі',
        'brand'       => 'Кіраванне брэндамi',
        'product'     => 'Кіраванне таварамі',
        'settings'    => 'Кіраванне наладамі',
        'promo_block' => 'Кіраванне прома-блокамі',
    ],
    'message'     => [
        'import_additional_category_info' => 'Set list of additional product categories, separated by commas.',
    ],
    'button'      => [
        'import_offer_button' => 'Імпартаваць прапановы з CSV',
    ],
];
