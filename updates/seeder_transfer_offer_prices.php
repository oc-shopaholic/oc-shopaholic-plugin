<?php namespace Lovata\Shopaholic\Updates;

use DB;
use Seeder;
use Lovata\Shopaholic\Models\Offer;

/**
 * Class SeederTransferOfferPrices
 * @package Lovata\Shopaholic\Updates
 */
class SeederTransferOfferPrices extends Seeder
{
    /**
     * Run seeder
     */
    public function run()
    {
        $obPriceList = DB::table('lovata_shopaholic_offers')->get();

        //Get offer list
        $obOfferList = Offer::withTrashed()->get();
        if ($obOfferList->isEmpty()) {
            return;
        }

        foreach ($obOfferList as $obOffer) {
            $obOfferModel = $obPriceList->where('id', $obOffer->id)->first();
            $obOffer->price = $obOfferModel->price;
            $obOffer->old_price = $obOfferModel->old_price;
            $obOffer->save();
        }
    }
}
