<?php namespace Lovata\Shopaholic\Controllers;

use Backend\Classes\Controller;

/**
 * Class Offers
 * @package Lovata\Shopaholic\Controllers
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Offers extends Controller
{
    public $implement = [
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\RelationController'
    ];

    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';
}