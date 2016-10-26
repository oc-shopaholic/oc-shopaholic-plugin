<?php namespace Kharanenka\Helper;

/**
 * Class PaginationHelper
 * @package Kharanenka\Helper
 * @author Andrey Kharanenka, kharanenka@gmail.com
 */
class PaginationHelper {

    const FIRST_BUTTON_CODE = 'first';
    const FIRST_MORE_BUTTON_CODE = 'first-more';
    const PREV_BUTTON_CODE = 'prev';
    const PREV_MORE_BUTTON_CODE = 'prev-more';
    const NEXT_BUTTON_CODE = 'next';
    const NEXT_MORE_BUTTON_CODE = 'next-more';
    const LAST_BUTTON_CODE = 'last';
    const LAST_MORE_BUTTON_CODE = 'last-more';
    const ACT_BUTTON_CODE = 'act';

    protected static $arResult = [];
    protected static $iCurrentPage;
    protected static $iTotalCountPages;
    protected static $iPosition;

    protected static $arSettings = [
        //Common settings
        'count_per_page' => 10,
        'pagination_limit' => 5,
        'active_class' => '_act',

        //Button "First"
        'first_button_on' => true,                      // Switch on/off button
        'first_button_name' => 'First',                 // Button name
        'first_button_limit' => 1,                      // Show button if current page > this value
        'first_button_number' => false,                 // true - button name = page number
        'first_button_class' => null,                   // Button class

        //Button "First-More"
        'first-more_button_on' => true,                 // Switch on/off button
        'first-more_button_name' => '...',              // Button name
        'first-more_button_limit' => 1,                 // Show button if current page > this value
        'first-more_button_class' => null,              // Button class

        //Button "Prev"
        'prev_button_on' => true,                       // Switch on/off button
        'prev_button_name' => 'Prev',                   // Button name
        'prev_button_limit' => 1,                       // Show button if current page > this value
        'prev_button_number' => false,                  // true - button name = page number
        'prev_button_class' => null,                    // Button class

        //Button "Prev-More"
        'prev-more_button_on' => true,                  // Switch on/off button
        'prev-more_button_name' => '...',               // Button name
        'prev-more_button_limit' => 1,                  // Show button if current page > this value
        'prev-more_button_class' => null,               // Button class

        //Main buttons
        'main_button_on' => true,                       // Switch on/off button
        'main_button_class' => null,                    // Button class

        //Button "Next-More"
        'next-more_button_on' => true,                  // Switch on/off button
        'next-more_button_name' => '...',               // Button name
        'next-more_button_limit' => 1,                  // Show button if current page + this value <= total page count
        'next-more_button_class' => null,               // Button class

        //Button "Next"
        'next_button_on' => true,                       // Switch on/off button
        'next_button_name' => 'Next',                   // Button name
        'next_button_limit' => 1,                       // Show button if current page + this value <= total page count
        'next_button_number' => false,                  // true - button name = page number
        'next_button_class' => null,                    // Button class

        //Button "Last-More"
        'last-more_button_on' => true,                  // Switch on/off button
        'last-more_button_name' => '...',               // Button name
        'last-more_button_limit' => 1,                  // Show button if current page + this value <= total page count
        'last-more_button_class' => null,               // Button class

        //Button "Last"
        'last_button_on' => true,                       // Switch on/off button
        'last_button_name' => 'Last',                   // Button name
        'last_button_limit' => 1,                       // Show button if current page + this value <= total page count
        'last_button_number' => false,                  // true - button name = page number
        'last_button_class' => null,                    // Button class
    ];

    /**
     * Get pagination elements
     * @param int $iCurrentPage - current page number
     * @param int $iTotalCount - total count elements
     * @param array $arSettings - settings array
     * @return array
     */
    public static function get($iCurrentPage, $iTotalCount, $arSettings = []) {

        self::$iCurrentPage = $iCurrentPage;
        self::initSettings($arSettings);

        //Get count per page elements
        $iCountPerPage = self::$arSettings['count_per_page'];

        //Get total page count
        self::$iTotalCountPages = ceil($iTotalCount/$iCountPerPage);

        //get pagination button limit
        $iElementLimit = self::$arSettings['pagination_limit'];

        //init start position
        self::$iPosition = $iCurrentPage - floor($iElementLimit/2);

        //Update position
        if((self::$iPosition + $iElementLimit) > self::$iTotalCountPages) {
            self::$iPosition = self::$iTotalCountPages - $iElementLimit + 1;
        }

        if(self::$iPosition < 1) {
            self::$iPosition = 1;
        }

        self::addPrevButton(self::FIRST_BUTTON_CODE);
        self::addPrevButton(self::FIRST_MORE_BUTTON_CODE);
        self::addPrevButton(self::PREV_BUTTON_CODE);
        self::addPrevButton(self::PREV_MORE_BUTTON_CODE);

        if(self::$arSettings['main_button_on']) {
            //Get pagination buttons
            $i = 0;
            while($i < $iElementLimit) {

                //Set active page button
                $sCode = null;
                $sElementClass = self::$arSettings['main_button_class'];
                if(self::$iPosition == self::$iCurrentPage) {
                    $sElementClass = ' '.self::$arSettings['active_class'];
                    $sCode = self::ACT_BUTTON_CODE;
                }

                if(self::$iPosition > 0) {
                    self::$arResult[] = [
                        'name' => self::$iPosition,
                        'value' => self::$iPosition,
                        'class' => $sElementClass,
                        'code' => $sCode,
                    ];

                    $i++;
                }

                self::$iPosition++;
                if(self::$iPosition > self::$iTotalCountPages) {
                    break;
                }
            }
        }

        self::addNextButton(self::NEXT_MORE_BUTTON_CODE);
        self::addNextButton(self::NEXT_BUTTON_CODE);
        self::addNextButton(self::LAST_MORE_BUTTON_CODE);
        self::addNextButton(self::LAST_BUTTON_CODE);

        return self::$arResult;
    }

    /**
     * Init settings
     * @param array $arSettings
     */
    protected static function initSettings($arSettings) {

        if(empty($arSettings)) {
            return;
        }

        foreach($arSettings as $sKey => $sValue) {
            self::$arSettings[$sKey] = $sValue;
        }
    }

    /**
     * Add previous button
     * @param string $sCode
     */
    protected static function addPrevButton($sCode) {

        if(!self::$arSettings[$sCode.'_button_on'] || self::$iCurrentPage <= self::$arSettings[$sCode.'_button_limit']) {
            return;
        }

        //Get button value
        $sValue = self::getValue($sCode);

        //Get button name
        $sName = self::$arSettings[$sCode.'_button_name'];
        if(isset(self::$arSettings[$sCode.'_button_number']) && self::$arSettings[$sCode.'_button_number']) {
            $sName = $sValue;
        }

        self::$arResult[] = [
            'name' => $sName,
            'value' => $sValue,
            'class' => self::$arSettings[$sCode.'_button_class'],
            'code' => $sCode,
        ];
    }

    /**
     * Add next button
     * @param string $sCode
     */
    protected static function addNextButton($sCode) {

        if(!self::$arSettings[$sCode.'_button_on'] || self::$iCurrentPage + self::$arSettings[$sCode.'_button_limit'] > self::$iTotalCountPages) {
            return;
        }

        //Get button value
        $sValue = self::getValue($sCode);

        //Get button name
        $sName = self::$arSettings[$sCode.'_button_name'];
        if(isset(self::$arSettings[$sCode.'_button_number']) && self::$arSettings[$sCode.'_button_number']) {
            $sName = $sValue;
        }

        self::$arResult[] = [
            'name' => $sName,
            'value' => $sValue,
            'class' => self::$arSettings[$sCode.'_button_class'],
            'code' => $sCode,
        ];
    }

    /**
     * Get page button value
     * @param string $sCode
     * @return int|null
     */
    protected static function getValue($sCode) {

        switch($sCode) {
            case self::FIRST_BUTTON_CODE:
                return 1;
            case self::FIRST_MORE_BUTTON_CODE:
                return null;
            case self::PREV_BUTTON_CODE:
                $iValue = self::$iCurrentPage - 1;
                if($iValue  < 1) {
                    $iValue = 1;
                }

                return $iValue;
            case self::PREV_MORE_BUTTON_CODE:
                return null;
            case self::NEXT_MORE_BUTTON_CODE:
                return null;
            case self::NEXT_BUTTON_CODE:
                $iValue = self::$iCurrentPage + 1;
                if($iValue > self::$iTotalCountPages) {
                    $iValue = self::$iTotalCountPages;
                }

                return $iValue;
            case self::LAST_MORE_BUTTON_CODE:
                return null;
            case self::LAST_BUTTON_CODE:
                return self::$iTotalCountPages;
        }

        return null;
    }
}