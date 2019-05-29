<?php namespace Lovata\Shopaholic\Controllers;

use Lang;
use Flash;
use Event;
use BackendMenu;
use Backend\Classes\Controller;
use Backend\Classes\BackendController;

use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Classes\Import\ImportCategoryModelFromXML;

/**
 * Class Categories
 * @package Lovata\Shopaholic\Controllers
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Categories extends Controller
{
    public $implement = [
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ReorderController',
        'Backend.Behaviors.RelationController',
        'Backend.Behaviors.ImportExportController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $relationConfig = 'config_relation.yaml';
    public $importExportConfig = 'config_import_export.yaml';

    /**
     * Categories constructor.
     */
    public function __construct()
    {
        if (BackendController::$action == 'import') {
            Category::extend(function ($obModel) {
                $obModel->rules['external_id'] = 'required';
            });
        }

        parent::__construct();
        BackendMenu::setContext('Lovata.Shopaholic', 'shopaholic-menu-main', 'shopaholic-menu-categories');
    }

    /**
     * Ajax handler onReorder event
     *
     * @return mixed
     */
    public function onReorder()
    {
        $obResult = parent::onReorder();
        Event::fire('shopaholic.category.update.sorting');

        return $obResult;
    }

    /**
     * Start import from XML
     */
    public function onImportFromXML()
    {
        $obImport = new ImportCategoryModelFromXML();
        $obImport->import();

        $arReportData = [
            'created' => $obImport->getCreatedCount(),
            'updated' => $obImport->getUpdatedCount(),
            'skipped' => $obImport->getSkippedCount(),
            'processed' => $obImport->getProcessedCount(),
        ];

        Flash::info(Lang::get('lovata.toolbox::lang.message.import_from_xml_report', $arReportData));

        return $this->listRefresh();
    }
}
