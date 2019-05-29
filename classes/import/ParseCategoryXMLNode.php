<?php namespace Lovata\Shopaholic\Classes\Import;

use Lovata\Toolbox\Classes\Helper\ParseXMLNode;

/**
 * Class ParseCategoryXMLNode
 * @package Lovata\Shopaholic\Classes\Import
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ParseCategoryXMLNode extends ParseXMLNode
{
    /**
     * Parse children categories
     * @param string $sFieldPath
     * @return array
     */
    protected function parseChildrenAttribute($sFieldPath)
    {
        $arResult = [];

        //Get children node list
        $arNodeList = $this->obElementNode->findListByPath($sFieldPath);
        if (empty($arNodeList)) {
            return $arResult;
        }

        foreach ($arNodeList as $obCategoryNode) {
            $obParseNode = new ParseCategoryXMLNode($obCategoryNode, $this->arImportSettings);
            $arResult[] = $obParseNode->get();
        }

        return $arResult;
    }
}
