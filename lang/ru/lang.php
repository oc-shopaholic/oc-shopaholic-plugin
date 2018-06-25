<?php return [
    'plugin'     => [
        'name'        => 'Shopaholic',
        'description' => 'Каталог товаров',
    ],
    'field' => [
        'vendor_code'         => 'Артикул',
        'price'               => 'Цена',
        'old_price'           => 'Старая цена',
        'quantity'            => 'Количество',
        'brand'               => 'Бренд',
        'offer'               => 'Товарные предложения',
        'currency'            => 'Валюта',
        'check_offer_active'  => 'При получении списка активных товаров проверять наличие активных предложений',
        'additional_category' => 'Дополнительные категории',
    ],
    'menu' => [
        'main'                => 'Каталог товаров',
        'categories'          => 'Категории товаров',
        'product'             => 'Товары',
        'brands'              => 'Бренды',
        'shop_catalog'        => 'Каталог товаров',
        'shop_category'       => 'Категория товаров',
        'all_shop_categories' => 'Все категории товаров',
    ],
    'tab'        => [
        'offer'         => 'Товарные предложения',
        'permissions'   => 'Shopaholic',
    ],
    'category'   => [
        'name'       => 'категории',
        'list_title' => 'Список категорий',
    ],
    'brand'      => [
        'name'       => 'бренда',
        'list_title' => 'Список брендов',
    ],
    'product'    => [
        'name'       => 'товара',
        'list_title' => 'Список товаров',
    ],
    'offer'      => [
        'name'       => 'предложения',
        'list_title' => 'Список предложений',
    ],
    'component'  => [

        //Product components
        'product_page_name'         => 'Страница товара',
        'product_page_description'  => 'Получение данных для страницы товара',
        'product_data_name'         => 'Данные товара',
        'product_data_description'  => 'Получение данных товара',
        'product_list_name'         => 'Список товаров',
        'product_list_description'  => 'Получение списка товаров',

        //Brand components
        'brand_page_name'           => 'Страница бренда',
        'brand_page_description'    => 'Получение данных для страницы бренда',
        'brand_data_name'           => 'Данные бренда',
        'brand_data_description'    => 'Получение данных бренда',
        'brand_list_name'           => 'Список брендов',
        'brand_list_description'    => 'Получение списка брендов',

        //Category components
        'category_page_name'        => 'Страница категории',
        'category_page_description' => 'Вывод данных на странице категории',
        'category_data_name'        => 'Данные категории',
        'category_data_description' => 'Получение данных категории',
        'category_list_name'        => 'Список категорий',
        'category_list_description' => 'Получение дерева категорий',

        //Common components
        'breadcrumbs_name'          => 'Хлебные крошки',
        'breadcrumbs_description'   => 'Получение данных для формирования хлебных крошек каталога',

        //Components settings
        'product_list_sorting'      => 'Сортировка по умолчанию',
        'sorting_no'                => 'Без сортировки',
        'sorting_price_desc'        => 'Дорогие',
        'sorting_price_asc'         => 'Дешевые',
        'sorting_new'               => 'Новые',
        'sorting_popularity_desc'   => 'Популярные',
        'sorting_rating_desc'       => 'Высокий рейтинг',
        'sorting_rating_asc'        => 'Низкий рейтинг',
    ],
    'permission' => [
        'category' => 'Управление категориями',
        'brand'    => 'Управление брендами',
        'product'  => 'Управление товарами',
        'settings' => 'Управление настройками',
    ],
];