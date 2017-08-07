<?php return [
    'plugin'     => [
        'name'        => 'Shopaholic',
        'description' => 'Catalog',
    ],
    'field'      => [
        'vendor_code'        => 'Vendor code',
        'price'              => 'Price',
        'old_price'          => 'Old price',
        'quantity'           => 'Quantity',
        'brand'              => 'Brand',
        'offer'              => 'Offers',
        'decimals'           => 'Число знаков после запятой',
        'dec_point'          => 'Разделитель дробной части',
        'thousands_sep'      => 'Разделитель тысяч',
        'dot'                => 'Точка',
        'comma'              => 'Запятая',
        'together'           => 'Слитно',
        'space'              => 'Пробел',
        'currency'           => 'Currency',
        'check_offer_active' => 'При получении списка активных товаров проверять наличие активных предложений',
    ],
    'menu'       => [
        'main'       => 'Catalog',
        'categories' => 'Categories',
        'product'    => 'Products',
        'brands'     => 'Brands',
    ],
    'tab'        => [
        'offer'         => 'Offers',
        'prices_format' => 'Формат цен',
        'permissions'   => 'Shopaholic',
    ],
    'category'   => [
        'name'       => 'category',
        'list_title' => 'Category list',
    ],
    'brand'      => [
        'name'       => 'brand',
        'list_title' => 'Brand list',
    ],
    'product'    => [
        'name'       => 'product',
        'list_title' => 'Product list',
    ],
    'offer'      => [
        'name'       => 'offer',
        'list_title' => 'Offer list',
    ],
    'component'  => [

        //Product components
        'product_page_name'         => 'Product page',
        'product_page_description'  => 'Get data for product page',
        'product_data_name'         => 'Product data',
        'product_data_description'  => 'Get product data by ID',
        'product_list_name'         => 'Product list',
        'product_list_description'  => 'Get sorting product list',

        //Brand components
        'brand_page_name'           => 'Страница бренда',
        'brand_page_description'    => 'Получение данных для страницы бренда',
        'brand_data_name'           => 'Данные бренда',
        'brand_data_description'    => 'Получение данных бренда',
        'brand_list_name'           => 'Список брендов',
        'brand_list_description'    => 'Получение списка брендов',

        //Category components
        'category_page_name'        => 'Category page',
        'category_page_description' => 'Get data for category page',
        'category_data_name'        => 'Category data',
        'category_data_description' => 'Get category data by ID',
        'category_list_name'        => 'Category list',
        'category_list_description' => 'Get category tree',

        //Common components
        'breadcrumbs_name'          => 'Breadcrumbs',
        'breadcrumbs_description'   => 'Get data for catalog breadcrumbs',
        'currency_name'             => 'Currency',
        'currency_description'      => 'Get default currency value',

        //Components settings
        'product_list_sorting'      => 'Default sorting',
        'sorting_no'                => 'Without sorting',
        'sorting_price_desc'        => 'Дорогие',
        'sorting_price_asc'         => 'Дешевые',
        'sorting_new'               => 'New',
        'sorting_popularity_desc'   => 'More popular',
    ],
    'permission' => [
        'category' => 'Manage categories',
        'brand'    => 'Manage brands',
        'product'  => 'Manage products',
    ],
];