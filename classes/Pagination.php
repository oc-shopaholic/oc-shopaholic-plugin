<?php namespace Lovata\Shopaholic\Classes;

use Lang;

/**
 * Class Pagination
 * @package Lovata\Shopaholic\Classes
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Pagination {

    //TODO: Перенести логику в пакет
    const DEFAULT_COUNT_PER_PAGE = '10';
    const DEFAULT_PAGINATION_LIMIT = '5';
    const DEFAULT_PAGINATION_FIRST = '&laquo; first';
    const DEFAULT_PAGINATION_PREV = '&lsaquo; previous';
    const DEFAULT_PAGINATION_MORE = '...';
    const DEFAULT_PAGINATION_NEXT = 'next &rsaquo;';
    const DEFAULT_PAGINATION_LAST = 'last &raquo;';
    const DEFAULT_PAGINATION_ACTIVE_CLASS = '_act';

    public static function getProperties() {
        return [
            'countPerPage' => [
                'title'             => 'lovata.shopaholic::lang.settings.count_per_page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'lovata.shopaholic::lang.settings.count_per_page_validation',
                'default'           => self::DEFAULT_COUNT_PER_PAGE,
            ],
            'paginationLimit' => [
                'title'             => 'lovata.shopaholic::lang.settings.pagination_limit',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'lovata.shopaholic::lang.settings.preview_text_limit_validation',
                'default'           => self::DEFAULT_PAGINATION_LIMIT,
            ],
            'paginationFirst' => [
                'title'             => 'lovata.shopaholic::lang.settings.pagination_first',
                'type'              => 'string',
                'default'           => self::DEFAULT_PAGINATION_FIRST,
            ],
            'paginationPrev' => [
                'title'             => 'lovata.shopaholic::lang.settings.pagination_prev',
                'type'              => 'string',
                'default'           => self::DEFAULT_PAGINATION_PREV,
            ],
            'paginationNext' => [
                'title'             => 'lovata.shopaholic::lang.settings.pagination_next',
                'type'              => 'string',
                'default'           => self::DEFAULT_PAGINATION_NEXT,
            ],
            'paginationMore' => [
                'title'             => 'lovata.shopaholic::lang.settings.pagination_more',
                'type'              => 'string',
                'default'           => self::DEFAULT_PAGINATION_MORE,
            ],
            'paginationLast' => [
                'title'             => 'lovata.shopaholic::lang.settings.pagination_last',
                'type'              => 'string',
                'default'           => self::DEFAULT_PAGINATION_LAST,
            ],
            'paginationActiveClass' => [
                'title'             => 'lovata.shopaholic::lang.settings.pagination_active_class',
                'type'              => 'string',
                'default'           => self::DEFAULT_PAGINATION_ACTIVE_CLASS,
            ],
        ];
    }

    /**
     * Get pagination elements
     * @param int $iCurrentPage - current page number
     * @param int $iTotalCount - total count elements
     * @param array $arSettings - plugin settings array
     *      - countPerPage: count elements per page
     *      - paginationLimit: count pagination buttons
     *      - paginationFirst: "First page" pagination button name
     *      - paginationPrev: "Previous page" pagination button name
     *      - paginationMore: "More page" pagination button name
     *      - paginationNext: "Next page" pagination button name
     *      - paginationLast: "Last page" pagination button name
     *      - paginationActiveClass: active button class name
     * @return array
     */
    public static function getPagination($iCurrentPage, $iTotalCount, $arSettings) {

        if(!isset($arSettings['countPerPage']) || empty($arSettings['countPerPage'])) {
            $iCountPerPage = self::DEFAULT_COUNT_PER_PAGE;
        } else {
            $iCountPerPage = $arSettings['countPerPage'];
        }
        
        //Get total page count
        $iTotalCountPages = ceil($iTotalCount/$iCountPerPage);
        
        $arResult = array();
        
        //Add 'first' and 'prev' buttons 
        if($iCurrentPage > 1) {

            if(isset($arSettings['paginationFirst']) && !empty($arSettings['paginationFirst'])) {
                $arResult[] = [
                    'page' => 1,
                    'name' => Lang::get($arSettings['paginationFirst']),
                    'class' => '',
                    'code' => 'first',
                ];
            }

            if(isset($arSettings['paginationPrev']) && !empty($arSettings['paginationPrev'])) {
                $arResult[] = [
                    'page' => $iCurrentPage - 1,
                    'name' => Lang::get($arSettings['paginationPrev']),
                    'class' => '',
                    'code' => 'prev',
                ];
            }
        }

        //Get pagination buttons limit
        if(!isset($arSettings['paginationLimit']) || empty($arSettings['paginationLimit'])) {
            $iElementLimit = self::DEFAULT_PAGINATION_LIMIT;
        } else {
            $iElementLimit = $arSettings['paginationLimit'];
        }

        //Get position first page button
        $iPosition = $iCurrentPage - floor($iElementLimit/2);

        //Move position first page button
        if(($iPosition + $iElementLimit) > $iTotalCountPages) {
            $iPosition = $iTotalCountPages - $iElementLimit + 1;
        }

        //Get pagination buttons
        $i = 0;
        while($i < $iElementLimit) {

            $sClassCurrentElement = '';
            //Set active page button
            if(isset($arSettings['paginationActiveClass']) && !empty($arSettings['paginationActiveClass']) && $iPosition == $iCurrentPage) {
                $sClassCurrentElement = $arSettings['paginationActiveClass'];
            }

            if($iPosition > 0) {
                $arResult[] = [
                    'page' => $iPosition,
                    'name' =>  $iPosition,
                    'class' => $sClassCurrentElement,
                    'code' => '',
                ];

                $i++;
            }

            $iPosition++;
            if($iPosition > $iTotalCountPages) {
                break;
            }
        }

        //Add 'more' pagination button
        if(isset($arSettings['paginationMore']) && !empty($arSettings['paginationMore']) && $iPosition <= $iTotalCountPages) {

            $sClassCurrentElement = '';
            if(isset($arSettings['paginationActiveClass']) && !empty($arSettings['paginationActiveClass'])) {
                $sClassCurrentElement = $arSettings['paginationActiveClass'];
            }

            $arResult[] = [
                'page' => '',
                'name' => $arSettings['paginationMore'],
                'class' => $sClassCurrentElement,
                'code' => 'more',
            ];
        }

        //Add 'next' и 'last' pagination button
        if($iCurrentPage < $iTotalCountPages) {

            if(isset($arSettings['paginationNext']) && !empty($arSettings['paginationNext'])) {
                $arResult[] = [
                    'page' => $iCurrentPage + 1,
                    'name' =>  Lang::get($arSettings['paginationNext']),
                    'class' => '',
                    'code' => 'next',
                ];
            }

            if(isset($arSettings['paginationLast']) && !empty($arSettings['paginationLast'])) {
                $arResult[] = [
                    'page' => $iTotalCountPages,
                    'name' => Lang::get($arSettings['paginationLast']),
                    'class' => '',
                    'code' => 'last',
                ];
            }
        }

        return $arResult;
    }
} 