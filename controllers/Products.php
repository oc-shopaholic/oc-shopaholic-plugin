<?php namespace Lovata\Shopaholic\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Flash;
use Illuminate\Http\Request;
use Lang;
use Lovata\Shopaholic\Models\Product;
use System\Classes\PluginManager;

/**
 * Class Products
 * @package Lovata\Shopaholic\Controllers
 * @author Denis Plisko, d.plisko@lovata.com, LOVATA Group
 */
class Products extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\FormController','Backend\Behaviors\ReorderController', 'Backend\Behaviors\RelationController'];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $relationConfig = [];

    /** @var Request */
    protected $obRequest;
    
    public function __construct(Request $obRequest)
    {
        $this->obRequest = $obRequest;

        // for offers
        $this->relationConfig['offers'] = [
            'label' => 'lovata.shopaholic::lang.fields.offers',
            'manage' => [
                'form' => '$/lovata/shopaholic/models/offer/fields.yaml',
                'list' => '$/lovata/shopaholic/models/offer/columns.yaml',
                'showSearch' => true,
                'showSorting' => true,
                'recordsPerPage' => 25,
            ],
            'view' => [
                'list' => '$/lovata/shopaholic/models/offer/columns.yaml',
                'toolbarButtons' => 'create|delete|add|remove',
                'showSearch' => true,
                'showSorting' => true,
                'recordsPerPage' => 25,
            ]
        ];

        //Add relation config
        if(PluginManager::instance()->hasPlugin('Lovata.RelatedProductsShopaholic')) {
            // for related products
            $this->relationConfig['rel_products'] = \Lovata\RelatedProductsShopaholic\Models\RelationProduct::getRelationConfig();
        }
        
        parent::__construct();
        BackendMenu::setContext('Lovata.Shopaholic', 'shopaholic-menu-main', 'shopaholic-menu-products');
    }

    /**
     * Save product data
     * @param null|integer $recordId
     * @param null $context
     * @return bool
     */
    public function update_onSave($recordId = null, $context = null) {
        
        $arProductData = $this->obRequest->get('Product');
        $bProductActive = $arProductData['active'];
        
        //Check active product
        /** @var Product $obProduct */
        $obProduct = Product::find($recordId);
        if(!empty($obProduct)) {
            $bActive = $obProduct->checkActiveOffers();

            if($bProductActive && !$bActive) {
                Flash::error(Lang::get('lovata.shopaholic::lang.message.not_active_product'));
                return;
            }
        }
        
            return parent::update_onSave($recordId, $context);
    }
}