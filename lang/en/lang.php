<?php return [
    'plugin' => [
        'name'          => 'Shopaholic',
        'description'   => 'Catalogue',
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
        'main'                  => 'Catalogue',
        'categories'            => 'Categories',
        'product'               => 'Products',
        'offers_archive'        => 'Deleted offers',
        'brands'                => 'Brands',
    ],
    'tab' => [
        'offers'                => 'Product offers',
        'field_view'            => 'Displaying fields',
        'prices_format'         => 'Price format',
        'permissions'           => 'Shopaholic',
    ],
    'message' => [
        'not_active_product'        => 'You cannot make a product active, if you have no active product offers',
    ],
    'category' => [
        'name'                      => 'categories',
        'list_title'                => 'List of categories',
    ],
    'brand' => [
        'name'                      => 'brand',
        'list_title'                => 'List of brands',
    ],
    'product' => [
        'name'                      => 'product\'s',
        'list_title'                => 'Product List',
    ],
    'offer' => [
        'name'                      => 'offers',
        'list_title'                => 'List of offers',
    ],
    'component' => [

        //Product components
        'product_page_name'                 => 'Product page',
        'product_page_description'          => 'Retrieving data for product page',
        'product_data_name'                 => 'Product data',
        'product_data_description'          => 'Product data retrieval',
        'product_list_name'                 => 'Product List',
        'product_list_description'          => 'Retrieving the list of goods',

        //Category components
        'category_page_name'                => 'Category page',
        'category_page_description'         => 'Data output on the category page',
        'category_data_name'                => 'Category data',
        'category_data_description'         => 'Retrieving category data',
        'category_list_name'                => 'Category List',
        'category_list_description'         => 'Retrieving the catalogue',

        //Common components
        'breadcrumbs_name'                  => 'Bread crumbs',
        'breadcrumbs_description'           => 'Retrieving data for the formation of crumbs in a directory',
        'currency_name'                     => 'Currency',
        'currency_description'              => 'Retrieving the value of the currency',

        //Components settings
        'product_list_sorting'               => 'Default sorting',
        'sorting_no'                        => 'Without sorting',
        'sorting_price_desc'                => 'Expensive',
        'sorting_price_asc'                 => 'Cheap',
        'sorting_new'                       => 'New',
        'sorting_popularity_desc'           => 'Popular',
    ],
    'settings' => [
        'view_off'              => 'Hide fields',
        'brand'                 => 'Hide brand fields',
        'category'              => 'Hide category fields',
        'product'               => 'Hide product fields',
        'offer'                 => 'Hide offer fields',
    ],
    'permission' => [
        'category'              => 'Managing categories',
        'brand'                 => 'Managing brands',
        'product'               => 'Managing products',
        'offers'                => 'Managing remote offers',
    ],
    'buttons' => [
        'restore_selected'      => 'Restore',
    ],
];