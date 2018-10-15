<?php return [
    'plugin'     => [
        'name'        => 'Shopaholic',
        'description' => 'Catalogue de produits pour le eCommerce',
    ],
    'field'      => [
        'vendor_code'         => 'Code vendeur',
        'price'               => 'Prix',
        'old_price'           => 'Ancien prix',
        'quantity'            => 'Quantité',
        'brand'               => 'Marque',
        'offer'               => 'Offres',
        'currency'            => 'Devise',
        'check_offer_active'  => 'Lorsque vous recevez une liste de produits actifs, recherchez des offres actives',
        'additional_category' => 'Catégories supplémentaires',
    ],
    'menu'       => [
        'main'                => 'Catalogue',
        'categories'          => 'Catégories',
        'product'             => 'Produits',
        'brands'              => 'Marques',
        'shop_catalog'        => 'Catalogue de produits',
        'shop_category'       => 'Catégorie des produits',
        'all_shop_categories' => 'Toutes les catégories de produits',
    ],
    'tab'        => [
        'offer'       => 'Offres',
        'price'       => 'Des prix',
        'permissions' => 'Shopaholic',
    ],
    'category'   => [
        'name'       => 'category',
        'list_title' => 'Liste des catégories',
    ],
    'brand'      => [
        'name'       => 'brand',
        'list_title' => 'Liste des marques',
    ],
    'product'    => [
        'name'       => 'product',
        'list_title' => 'Liste des produits',
    ],
    'offer'      => [
        'name'       => 'offer',
        'list_title' => 'Liste des offres',
    ],
    'component'  => [

        //Product components
        'product_page_name'         => 'Page du produit',
        'product_page_description'  => 'Obtenir les données pour la page du produit',
        'product_data_name'         => 'Données du produit',
        'product_data_description'  => 'Obtenir les données du produit par ID',
        'product_list_name'         => 'Liste des produits',
        'product_list_description'  => 'Récupérer la liste des produits',

        //Brand components
        'brand_page_name'           => 'Page de la marque',
        'brand_page_description'    => 'Obtenir les données pour la page de la marque',
        'brand_data_name'           => 'Données de la marque',
        'brand_data_description'    => 'Obtenir les données de la marque par ID',
        'brand_list_name'           => 'Liste des marques',
        'brand_list_description'    => 'Récupérer la liste des marques',

        //Category components
        'category_page_name'        => 'Page de la catégorie',
        'category_page_description' => 'Obtenir les données pour la page de catégorie',
        'category_data_name'        => 'Données de la catégorie',
        'category_data_description' => 'Obtenir les données de la catégorie par ID',
        'category_list_name'        => 'Liste des catégories',
        'category_list_description' => 'Récupérer la liste des catégories',

        //Common components
        'breadcrumbs_name'          => "Fil d'Ariane",
        'breadcrumbs_description'   => "Récupérer les données pour le fil d'Ariane du catalogue",

        //Components settings
        'product_list_sorting'      => 'Tri par défaut',
        'sorting_no'                => 'Sans tri',
        'sorting_price_desc'        => 'Coûteux',
        'sorting_price_asc'         => 'Pas cher',
        'sorting_new'               => 'Nouveau',
        'sorting_popularity_desc'   => 'Le plus populaire',
        'sorting_rating_desc'       => 'Note élevée',
        'sorting_rating_asc'        => 'Note basse',
    ],
    'permission' => [
        'category' => 'Gérer les catégories',
        'brand'    => 'Gérer les marques',
        'product'  => 'Gérer les produits',
        'settings' => 'Gérer les paramètres',
    ],
];
