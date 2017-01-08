<?php return [
    'plugin' => [
        'name'          => 'Shopaholic',
        'description'   => 'Catalog',
    ],
    'field' => [

        //Common fields
        'vendor_code'                       => 'Vendor code',

        //Offer fields
        'price'                             => 'Price',
        'old_price'                         => 'Old price',
        'quantity'                          => 'Quantity',

        //Product fields
        'brand'                             => 'Brand',
        'offers'                            => 'Offers',

        //Settings fields
        'decimals'                          => 'Число знаков после запятой',
        'dec_point'                         => 'Разделитель дробной части',
        'thousands_sep'                     => 'Разделитель тысяч',
        'dot'                               => 'Точка',
        'comma'                             => 'Запятая',
        'together'                          => 'Слитно',
        'space'                             => 'Пробел',
        'double_space'                      => 'Двойной пробел',
        'hyphen'                            => 'Дефис',
        'currency'                          => 'Currency',
        'category_preview_limit_max'        => 'Ограничение на длинну краткого описания категории (max)',
        'product_preview_limit_max'         => 'Ограничение на длинну краткого описания товара (max)',
        'offer_preview_limit_max'           => 'Ограничение на длинну краткого описания товарного предложения (max)',
        'check_offer_active'                => 'При получении списка активных товаров проверять наличие активных предложений',
    ],
    'menu' => [
        'main'                  => 'Catalog',
        'categories'            => 'Categories',
        'product'               => 'Products',
        'brands'                => 'Brands',
    ],
    'tab' => [
        'offers'                => 'Offers',
        'field_view'            => 'Отображение полей',
        'prices_format'         => 'Формат цен',
        'permissions'           => 'Shopaholic',
    ],
    'category' => [
        'name'                      => 'category',
        'list_title'                => 'Category list',
    ],
    'brand' => [
        'name'                      => 'brand',
        'list_title'                => 'Brand list',
    ],
    'product' => [
        'name'                      => 'product',
        'list_title'                => 'Product list',
    ],
    'offer' => [
        'name'                      => 'offer',
        'list_title'                => 'Offer list',
    ],
    'component' => [

        //Product components
        'product_page_name'                 => 'Product page',
        'product_page_description'          => 'Get data for product page',
        'product_data_name'                 => 'Product data',
        'product_data_description'          => 'Get product data by ID',
        'product_list_name'                 => 'Product list',
        'product_list_description'          => 'Get sorting product list',

        //Category components
        'category_page_name'                => 'Category page',
        'category_page_description'         => 'Get data for category page',
        'category_data_name'                => 'Category data',
        'category_data_description'         => 'Get category data by ID',
        'category_list_name'                => 'Category list',
        'category_list_description'         => 'Get category tree',

        //Common components
        'breadcrumbs_name'                  => 'Breadcrumbs',
        'breadcrumbs_description'           => 'Get data for catalog breadcrumbs',
        'currency_name'                     => 'Currency',
        'currency_description'              => 'Get default currency value',

        //Components settings
        'product_list_sorting'              => 'Default sorting',
        'sorting_no'                        => 'Without sorting',
        'sorting_price_desc'                => 'Дорогие',
        'sorting_price_asc'                 => 'Дешевые',
        'sorting_new'                       => 'New',
        'sorting_popularity_desc'           => 'More popular',
    ],
    'settings' => [
        'view_off'              => 'Hide fields',
        'brand'                 => 'Hide fields for brands',
        'category'              => 'Hide fields for categories',
        'product'               => 'Hide fields for products',
        'offer'                 => 'Hide fields for offers',
    ],
    'permission' => [
        'category'              => 'Manage categories',
        'brand'                 => 'Manage brands',
        'product'               => 'Manage products',
    ],
];