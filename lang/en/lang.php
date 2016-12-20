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
        'decimals'                          => 'The number of digits after the decimal point',
        'dec_point'                         => 'Decimal separator',
        'thousands_sep'                     => 'Thousands separator',
        'dot'                               => 'Point',
        'comma'                             => 'Comma',
        'together'                          => 'Together',
        'space'                             => 'Space',
        'double_space'                      => 'Dual space',
        'hyphen'                            => 'Hyphen',
        'currency'                          => 'Currency',
        'category_preview_limit_max'        => 'The limit on the length of a brief description of the category (max)',
        'product_preview_limit_max'         => 'The limit on the length of the short product description (max)',
        'offer_preview_limit_max'           => 'The limit on the length of a brief description of the commodity offer (max)',
    ],
    'menu' => [
        'main'                  => 'Catalog',
        'categories'            => 'Categories',
        'product'               => 'Products',
        'offers_archive'        => 'Deleted proposals',
        'brands'                => 'Brands',
    ],
    'tab' => [
        'offers'                => 'Product listings',
        'field_view'            => 'Displaying fields',
        'prices_format'         => 'Price format',
        'permissions'           => 'Shopaholic',
    ],
    'message' => [
        'not_active_product'        => 'You cannot make this product an active, if you have no active product proposals',
    ],
    'category' => [
        'name'                      => 'categories',
        'list_title'                => 'Список категорий',
    ],
    'brand' => [
        'name'                      => 'бренда',
        'list_title'                => 'Список брендов',
    ],
    'product' => [
        'name'                      => 'товара',
        'list_title'                => 'Список товаров',
    ],
    'offer' => [
        'name'                      => 'предложения',
        'list_title'                => 'Список предложений',
    ],
    'component' => [

        //Product components
        'product_page_name'                 => 'Страница товара',
        'product_page_description'          => 'Получение данных для страницы товара',
        'product_data_name'                 => 'Данные товара',
        'product_data_description'          => 'Получение данных товара',
        'product_list_name'                 => 'Список товаров',
        'product_list_description'          => 'Получение списка товаров',

        //Category components
        'category_page_name'                => 'Страница категории',
        'category_page_description'         => 'Вывод данных на странице категории',
        'category_data_name'                => 'Данные категории',
        'category_data_description'         => 'Получение данных категории',
        'category_list_name'                => 'Список категорий',
        'category_list_description'         => 'Получение дерева категорий',

        //Common components
        'breadcrumbs_name'                  => 'Хлебные крошки',
        'breadcrumbs_description'           => 'Получение данных для формирования хлебных крошек каталога',
        'currency_name'                     => 'Валюта',
        'currency_description'              => 'Получение значение валюты',

        //Components settings
        'product_list_sorting'               => 'Default sorting',
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
        'offers'                => 'Управление удаленными предложениями',
    ],
    'buttons' => [
        'restore_selected'      => 'Restore',
    ],
];