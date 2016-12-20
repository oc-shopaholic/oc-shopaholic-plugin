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
        'list_title'                => 'Category List',
    ],
    'brand' => [
        'name'                      => 'brand',
        'list_title'                => 'List of brands',
    ],
    'product' => [
        'name'                      => 'products',
        'list_title'                => 'Product List',
    ],
    'offer' => [
        'name'                      => 'offers',
        'list_title'                => 'List of proposals',
    ],
    'component' => [

        //Product components
        'product_page_name'                 => 'Product page',
        'product_page_description'          => 'Retrieving data for product page',
        'product_data_name'                 => 'Product data',
        'product_data_description'          => 'Data acquisition product',
        'product_list_name'                 => 'Product List',
        'product_list_description'          => 'Receiving the list of the goods',

        //Category components
        'category_page_name'                => 'Pages/Categories',
        'category_page_description'         => 'Data output on category page',
        'category_data_name'                => 'Data category',
        'category_data_description'         => 'Retrieving data categories',
        'category_list_name'                => 'Category List',
        'category_list_description'         => 'Getting the catalog',

        //Common components
        'breadcrumbs_name'                  => 'Bread crumbs',
        'breadcrumbs_description'           => 'Retrieving data for the formation of crumbs directory',
        'currency_name'                     => 'Currency',
        'currency_description'              => 'Getting the value of the currency',

        //Components settings
        'product_list_sorting'               => 'Default sorting',
        'sorting_no'                        => 'Without sorting',
        'sorting_price_desc'                => 'Dear',
        'sorting_price_asc'                 => 'Cheap',
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
        'offers'                => 'Remote management proposals',
    ],
    'buttons' => [
        'restore_selected'      => 'Restore',
    ],
];