<?php namespace Lovata\Shopaholic\Classes\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

use Lovata\Shopaholic\Classes\Import\ImportBrandModelFromXML;
use Lovata\Shopaholic\Classes\Import\ImportCategoryModelFromXML;
use Lovata\Shopaholic\Classes\Import\ImportOfferModelFromXML;
use Lovata\Shopaholic\Classes\Import\ImportOfferPriceFromXML;
use Lovata\Shopaholic\Classes\Import\ImportProductModelFromXML;

/**
 * Class ImportFromXML
 * @package Lovata\Shopaholic\Classes\Console
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ImportFromXML extends Command
{
    /**
     * @var string command name.
     */
    protected $name = 'shopaholic:import_from_xml';

    /**
     * @var string The console command description.
     */
    protected $description = 'Run import catalog from XML';

    protected $arClassList = [
        'brand'    => ImportBrandModelFromXML::class,
        'category' => ImportCategoryModelFromXML::class,
        'property' => 'Lovata\PropertiesShopaholic\Classes\Import\ImportPropertyModelFromXML',
        'product'  => ImportProductModelFromXML::class,
        'offer'    => ImportOfferModelFromXML::class,
        'price'    => ImportOfferPriceFromXML::class,
    ];

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['import', null, InputOption::VALUE_OPTIONAL, 'Available values: brand,category,property,product,offer.', null],
        ];
    }

    /**
     * Execute the console command.
     * @throws \Throwable
     */
    public function handle()
    {
        $arImportList = explode(',', $this->option('import'));
        $arImportList = array_filter($arImportList);

        foreach ($this->arClassList as $sKey => $sImportClass) {
            if (!class_exists($sImportClass) || (!empty($arImportList) && !in_array($sKey, $arImportList))) {
                continue;
            }

            /** @var \Lovata\Toolbox\Classes\Helper\AbstractImportModelFromXML $obImport */
            $obImport = new $sImportClass();
            $obImport->openMainFile();
            if (empty($obImport->getTotalCount())) {
                continue;
            }

            $this->info("Start import for \"$sKey\"");

            $obProgressBar = $this->output->createProgressBar($obImport->getTotalCount());
            $obImport->import($obProgressBar);
            $obProgressBar->finish();

            $this->info("\nFinish import for \"$sKey\"\n");
            $this->info("Created - {$obImport->getCreatedCount()}");
            $this->info("Updated - {$obImport->getUpdatedCount()}");
            $this->warn("Skipped - {$obImport->getSkippedCount()}");
            $this->info("Processed - {$obImport->getProcessedCount()}\n");
        }
    }
}
