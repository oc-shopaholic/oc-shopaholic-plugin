<?php namespace Lovata\Shopaholic\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Flash;
use Illuminate\Http\Request;
use Lang;
use Lovata\Shopaholic\Models\Brand;

/**
 * Class Brands
 * @package Lovata\Shopaholic\Controllers
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Brands extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController',
    ];

    /** @var Request */
    protected $obRequest;
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public function __construct(Request $obRequest)
    {
        $this->obRequest = $obRequest;

        parent::__construct();
        BackendMenu::setContext('Lovata.Shopaholic', 'shopaholic-menu-main', 'shopaholic-menu-brands');
    }
}