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
        'currency'                          => 'Валюта',
        'category_preview_limit_max'        => 'Ограничение на длинну краткого описания категории (max)',
        'product_preview_limit_max'         => 'Ограничение на длинну краткого описания товара (max)',
        'offer_preview_limit_max'           => 'Ограничение на длинну краткого описания товарного предложения (max)',
    ],
    'menu' => [
        'main'                  => 'Catalog',
        'categories'            => 'Categories',
        'product'               => 'Products',
        'brands'                => 'Brands',
    ],
    'tab' => [
        'offers'                => 'Товарные предложения',
        'field_view'            => 'Отображение полей',
        'prices_format'         => 'Формат цен',
        'permissions'           => 'Shopaholic',
    ],
    'message' => [
        'not_active_product'        => 'Нельзя сделать товар активным, если нет активных товарных предложений',
    ],
    'category' => [
        'name'                      => 'категории',
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
    'buttons' => [
        'restore_selected'      => 'Restore',
    ],
];