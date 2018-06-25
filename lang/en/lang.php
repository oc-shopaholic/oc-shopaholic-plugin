<?php return [
    'plugin'     => [
        'name'        => 'Shopaholic',
        'description' => 'Catalog of products for eCommerce',
    ],
    'field' => [
        'vendor_code'         => 'Vendor code',
        'price'               => 'Price',
        'old_price'           => 'Old price',
        'quantity'            => 'Quantity',
        'brand'               => 'Brand',
        'offer'               => 'Offers',
        'currency'            => 'Currency',
        'check_offer_active'  => 'When you receive a list of active products, check for active offers',
        'additional_category' => 'Additional categories',
    ],
    'menu'       => [
        'main'                => 'Catalog',
        'categories'          => 'Categories',
        'product'             => 'Products',
        'brands'              => 'Brands',
        'shop_catalog'        => 'Catalog of products',
        'shop_category'       => 'Category of products',
        'all_shop_categories' => 'All categories of products',
    ],
    'tab'        => [
        'offer'         => 'Offers',
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
        'product_list_description'  => 'Get product list',

        //Brand components
        'brand_page_name'           => 'Brand page',
        'brand_page_description'    => 'Get data for brand page',
        'brand_data_name'           => 'Brand data',
        'brand_data_description'    => 'Get brand data by ID',
        'brand_list_name'           => 'Brand list',
        'brand_list_description'    => 'Get brand list',

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

        //Components settings
        'product_list_sorting'      => 'Default sorting',
        'sorting_no'                => 'Without sorting',
        'sorting_price_desc'        => 'Expensive',
        'sorting_price_asc'         => 'Cheap',
        'sorting_new'               => 'New',
        'sorting_popularity_desc'   => 'More popular',
        'sorting_rating_desc'       => 'High rating',
        'sorting_rating_asc'        => 'Low rating',
    ],
    'permission' => [
        'category' => 'Manage categories',
        'brand'    => 'Manage brands',
        'product'  => 'Manage products',
        'settings' => 'Manage settings',
    ],
];
