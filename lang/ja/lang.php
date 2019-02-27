<?php return [
    'plugin'      => [
        'name'        => 'Shopaholic',
        'description' => '🛍️ Free eCommerce plugin with a large set of extensions.',
    ],
    'field'       => [
        'vendor_code'         => 'ベンダーコード',
        'price'               => '価格',
        'old_price'           => '通常価格',
        'quantity'            => '数量',
        'brand'               => 'ブランド',
        'offer'               => 'オファー',
        'currency'            => '通貨',
        'check_offer_active'  => '有効な製品のリストを取得する際、有効なオファーを持つものに限定する',
        'additional_category' => '追加のカテゴリ',
        'promo_block_type'    => '製品リストを含む販促ブロック',
        'promo_block'         => '販促ブロック',
        'category_parent_id'  => '親カテゴリID',
        'product_id'          => '製品ID',
    ],
    'menu'        => [
        'main'                => 'カタログ',
        'categories'          => 'カテゴリ',
        'product'             => '製品',
        'brands'              => 'ブランド',
        'shop_catalog'        => '製品カタログ',
        'shop_category'       => '製品カテゴリ',
        'all_shop_categories' => '全ての製品カテゴリ',
        'promo_block'         => '販促ブロック',
        'promo'               => '販促',
    ],
    'tab'         => [
        'offer'       => 'オファー',
        'price'       => '価格',
        'permissions' => 'Shopaholic',
    ],
    'category'    => [
        'name'       => 'カテゴリ',
        'list_title' => 'カテゴリリスト',
        'import_title' => 'カテゴリデータのインポート',
        'export_title' => 'カテゴリデータのエクスポート',
    ],
    'brand'       => [
        'name'       => 'ブランド',
        'list_title' => 'ブランドリスト',
        'import_title' => 'ブランドデータのインポート',
        'export_title' => 'ブランドデータのエクスポート',
    ],
    'product'     => [
        'name'       => '製品',
        'list_title' => '製品リスト',
        'import_title' => '製品データのインポート',
        'export_title' => '製品データのエクスポート',
    ],
    'offer'       => [
        'name'       => 'オファー',
        'list_title' => 'オファーリスト',
        'import_title' => 'オファーデータのインポート',
        'export_title' => 'オファーデータのエクスポート',
    ],
    'promo_block' => [
        'name'       => '販促ブロック',
        'list_title' => '販促ブロックリスト',
    ],
    'component'   => [

        //Product components
        'product_page_name'            => '製品ページ',
        'product_page_description'     => '製品データをURL slugで取得します',
        'product_data_name'            => '製品データ',
        'product_data_description'     => '製品データをIDで取得します',
        'product_list_name'            => '製品リスト',
        'product_list_description'     => '製品リストを取得します',

        //Brand components
        'brand_page_name'              => 'ブランドページ',
        'brand_page_description'       => 'ブランドデータをURL slugで取得します',
        'brand_data_name'              => 'ブランドデータ',
        'brand_data_description'       => 'ブランドデータをIDで取得します',
        'brand_list_name'              => 'ブランドリスト',
        'brand_list_description'       => 'ブランドリストを取得します',

        //Promo block components
        'promo_block_page_name'        => '販促ブロックページ',
        'promo_block_page_description' => '販促ブロックデータをURL slugで取得します',
        'promo_block_data_name'        => '販促ブロックデータ',
        'promo_block_data_description' => '販促ブロックデータをIDで取得します',
        'promo_block_list_name'        => '販促ブロックリスト',
        'promo_block_list_description' => '販促ブロックリストを取得します',

        //Category components
        'category_page_name'           => 'カテゴリページ',
        'category_page_description'    => 'カテゴリデータをURL slugで取得します',
        'category_data_name'           => 'カテゴリデータ',
        'category_data_description'    => 'カテゴリデータをIDで取得します',
        'category_list_name'           => 'カテゴリリスト',
        'category_list_description'    => 'カテゴリリストを取得します',

        //Common components
        'breadcrumbs_name'             => 'パンくずリスト',
        'breadcrumbs_description'      => 'カタログパンくずリストのためのデータを取得します',

        //Components settings
        'product_list_sorting'         => 'デフォルトソート方法',
        'sorting_no'                   => 'ソートしない',
        'sorting_price_desc'           => '価格高い順',
        'sorting_price_asc'            => '価格安い順',
        'sorting_new'                  => '新しい順',
        'sorting_popularity_desc'      => '人気順',
        'sorting_rating_desc'          => '評価の高い順',
        'sorting_rating_asc'           => '評価の低い順',
        'sorting_date_begin_asc'       => '開始日早い順',
        'sorting_date_begin_desc'      => '開始日遅い順',
        'sorting_date_end_asc'         => '終了日早い順',
        'sorting_date_end_desc'        => '終了日遅い順',
    ],
    'permission'  => [
        'category'    => 'カテゴリ管理',
        'brand'       => 'ブランド管理',
        'product'     => '製品管理',
        'settings'    => '設定管理',
        'promo_block' => '販促ブロック管理',
    ],
    'message'     => [
        'import_additional_category_info' => '追加の製品カテゴリはカンマ区切りで入力してください',
    ],
    'button'      => [
        'import_offer_button' => 'オファーをCSVから読み込む',
    ],
];
