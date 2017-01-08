<?php return [
    'plugin' => [
        'name'          => 'Shopaholic',
        'description'   => 'Каталог товаров',
    ],
    'field' => [

        //Common fields
        'vendor_code'                       => 'Артикул',

        //Offer fields
        'price'                             => 'Цена',
        'old_price'                         => 'Старая цена',
        'quantity'                          => 'Количество',

        //Product fields
        'brand'                             => 'Бренд',
        'offers'                            => 'Товарные предложения',

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
        'check_offer_active'                => 'При получении списка активных товаров проверять наличие активных предложений',
    ],
    'menu' => [
        'main'                  => 'Каталог товаров',
        'categories'            => 'Категории товаров',
        'product'               => 'Товары',
        'brands'                => 'Бренды',
    ],
    'tab' => [
        'offers'                => 'Товарные предложения',
        'field_view'            => 'Отображение полей',
        'prices_format'         => 'Формат цен',
        'permissions'           => 'Shopaholic',
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
        'product_list_sorting'              => 'Сортировка по умолчанию',
        'sorting_no'                        => 'Без сортировки',
        'sorting_price_desc'                => 'Дорогие',
        'sorting_price_asc'                 => 'Дешевые',
        'sorting_new'                       => 'Новые',
        'sorting_popularity_desc'           => 'Популярные',
    ],
    'settings' => [
        'view_off'              => 'Скрыть отображение полей',
        'brand'                 => 'Скрыть отображение полей для брендов',
        'category'              => 'Скрыть отображение полей для категорий',
        'product'               => 'Скрыть отображение полей для товаров',
        'offer'                 => 'Скрыть отображение полей для предложений',
    ],
    'permission' => [
        'category'              => 'Управление категориями',
        'brand'                 => 'Управление брендами',
        'product'               => 'Управление товарами',
    ],
];