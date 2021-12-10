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
        Offer::withTrashed()->chunk(100, function ($obOfferList) {
            foreach ($obOfferList as $obOffer) {
                $obOfferModel       = DB::table('lovata_shopaholic_offers')->where('id', $obOffer->id)->first();
                $obOffer->price     = data_get($obOfferModel, 'price', 0);
                $obOffer->old_price = data_get($obOfferModel, 'old_price', 0);
                $obOffer->save();
            }
        });
    }
}
