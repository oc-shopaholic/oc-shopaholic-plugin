<?php namespace Lovata\Shopaholic\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Flash;
use Illuminate\Http\Request;
use Lang;
use Lovata\Shopaholic\Models\Offer;

/**
 * Class Offers
 * @package Lovata\Shopaholic\Controllers
 * @author Denis Plisko, d.plisko@lovata.com, LOVATA Group
 */
class Offers extends Controller
{
    public $implement = ['Backend\Behaviors\ListController'];
    
    public $listConfig = 'config_list.yaml';

    /** @var Request */
    protected $obRequest;

    public function __construct(Request $obRequest)
    {
        $this->obRequest = $obRequest;

        parent::__construct();
        BackendMenu::setContext('Lovata.Shopaholic', 'shopaholic-menu-main', 'shopaholic-menu-offers');
    }

    public function listExtendQuery($query)
    {
        $query->onlyTrashed();
    }
}