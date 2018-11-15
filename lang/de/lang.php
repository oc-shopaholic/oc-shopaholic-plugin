<?php return [
    'plugin'      => [
        'name'        => 'Shopaholic',
        'description' => 'Produktkatalog für eCommerce',
    ],
    'field'       => [
        'vendor_code'         => 'Lieferanten-Code',
        'price'               => 'Preis',
        'old_price'           => 'Alter Preis',
        'quantity'            => 'Menge',
        'brand'               => 'Marke',
        'offer'               => 'Angebote',
        'currency'            => 'Währung',
        'check_offer_active'  => 'Wenn Sie eine Liste aktiver Produkte erhalten, suchen Sie nach aktiven Angeboten.',
        'additional_category' => 'Weitere Kategorien',
        'promo_block_type'    => 'Werbeblock mit Produktliste',
        'promo_block'         => 'Werbeblock',
        'category_parent_id'  => 'Übergeordnete Kategorie ID',
        'product_id'          => 'Produkt ID',
    ],
    'menu'        => [
        'main'                => 'Katalog',
        'categories'          => 'Kategorien',
        'product'             => 'Produkte',
        'brands'              => 'Marken',
        'shop_catalog'        => 'Produktkatalog',
        'shop_category'       => 'Produktkategorien',
        'all_shop_categories' => 'Alle Produktkategorien',
        'promo_block'         => 'Werbeblock',
        'promo'               => 'Promo',
    ],
    'tab'         => [
        'offer'       => 'Angebote',
        'price'       => 'Preise',
        'permissions' => 'Shopaholic',
    ],
    'category'    => [
        'name'         => 'Kategorie',
        'list_title'   => 'Kategorieübersicht',
        'import_title' => 'Importiere Kategorien',
        'export_title' => 'Exportiere Kategorien',
    ],
    'brand'       => [
        'name'         => 'Marke',
        'list_title'   => 'Marken-Übersicht',
        'import_title' => 'Importiere Marken',
        'export_title' => 'Exportiere Marken',
    ],
    'product'     => [
        'name'         => 'Produkt',
        'list_title'   => 'Produktübersicht',
        'import_title' => 'Importiere Produkte',
        'export_title' => 'Exportiere Produkte',
    ],
    'offer'       => [
        'name'         => 'Angebote',
        'list_title'   => 'Angebotsübersicht',
        'import_title' => 'Importiere Angebote',
        'export_title' => 'Exportiere Angebote',
    ],
    'promo_block' => [
        'name'       => 'Werbeblock',
        'list_title' => 'Werbeblock-Übersicht',
    ],
    'component'   => [

        //Product components
        'product_page_name'            => 'Produktseite',
        'product_page_description'     => 'Daten für die Produktseite abrufen',
        'product_data_name'            => 'Produktdaten',
        'product_data_description'     => 'Produktdaten nach ID abrufen',
        'product_list_name'            => 'Produktübersicht',
        'product_list_description'     => 'Produktübersicht abrufen',

        //Brand components
        'brand_page_name'              => 'Markenseite',
        'brand_page_description'       => 'Daten für Markenseite abrufen',
        'brand_data_name'              => 'Markendaten',
        'brand_data_description'       => 'Markendaten nach ID abrufen',
        'brand_list_name'              => 'Markenübersicht',
        'brand_list_description'       => 'Markenübersicht abrufen',

        //Promo block components
        'promo_block_page_name'        => 'Werbeblockseite',
        'promo_block_page_description' => 'Daten für Werbeblockseite abrufen',
        'promo_block_data_name'        => 'Werbeblockdaten',
        'promo_block_data_description' => 'Werbeblock-Daten nach ID abrufen',
        'promo_block_list_name'        => 'Werbeblockübersicht',
        'promo_block_list_description' => 'Werbeblockübersicht abrufen',

        //Category components
        'category_page_name'           => 'Kategorie-Übersicht',
        'category_page_description'    => 'Daten für die Kategorie-Seite abrufen',
        'category_data_name'           => 'Kategorie-Daten',
        'category_data_description'    => 'Kategoriedaten nach ID abrufen',
        'category_list_name'           => 'Kategorie-Übersicht',
        'category_list_description'    => 'Kategoriebaum abrufen',

        //Common components
        'breadcrumbs_name'             => 'Breadcrumbs',
        'breadcrumbs_description'      => 'Daten für Katalog-Breadcrumbs holen',

        //Components settings
        'product_list_sorting'         => 'Standardsortierung',
        'sorting_no'                   => 'Ohne Sortierung',
        'sorting_price_desc'           => 'Teuer',
        'sorting_price_asc'            => 'Billig',
        'sorting_new'                  => 'Neu',
        'sorting_popularity_desc'      => 'Populär',
        'sorting_rating_desc'          => 'Hohe Bewertung',
        'sorting_rating_asc'           => 'Niedrige Bewertung',
        'sorting_date_begin_asc'       => 'Datumsanfang (ASC)',
        'sorting_date_begin_desc'      => 'Datumsanfang (DESC)',
        'sorting_date_end_asc'         => 'Datumsende (ASC)',
        'sorting_date_end_desc'        => 'Datumsende (DESC)',
    ],
    'permission'  => [
        'category'    => 'Kategorien verwalten',
        'brand'       => 'Marken verwalten',
        'product'     => 'Produkte verwalten',
        'settings'    => 'Einstellungen verwalten',
        'promo_block' => 'Werbeblock verwalten',
    ],
    'message'     => [
        'import_additional_category_info' => 'Liste der zusätzlichen Produktkategorien festlegen, getrennt durch Kommas.',
    ],
    'button'      => [
        'import_offer_button' => 'Importiere Angebote mit CSV',
    ],
];
