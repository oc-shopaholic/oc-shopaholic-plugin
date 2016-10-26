<?php namespace Kharanenka\Helper;

/**
 * Generate universal result answer
 *
 * Class ResultStore
 * @package Kharanenka\Helper
 * @author Andrey Kharanenka, kharanenka@gmail.com
 *
 */

class ResultStore {

    /**
     * Status of result (true|false)
     * @var bool
     */
    private $bResult = true;

    /**
     * Data of result
     * @var mixed
     */
    private $obData;

    /** @var ResultStore */
    private static $obThis =  null;

    private function __construct() {}

    /**
     * @return ResultStore
     */
    public static function getInstance() {

        if(empty(self::$obThis)) {
            self::$obThis = new ResultStore();
        }

        return self::$obThis;
    }

    /**
     * Set status of result in true
     * Set data of result
     * @param mixed $obData
     * @return ResultStore
     */
    public function setTrue($obData = null) {

        $this->bResult  = true;
        $this->obData = $obData;
        return $this;
    }

    /**
     * Set status of result in false
     * Set data of result
     * @param mixed $obData
     * @return ResultStore
     */
    public function setFalse($obData = null) {

        $this->bResult  = false;
        $this->obData = $obData;
        return $this;
    }

    /**
     * @return bool
     */
    public function flag() {
        return $this->bResult;
    }

    /**
     * @return mixed
     */
    public function data() {
        return $this->obData;
    }

    /**
     * @return array
     */
    public function get() {

        return [
            'result' => $this->bResult,
            'data'   => $this->obData,
        ];

    }

    /**
     * Generate result JSON string
     * @return string
     */
    public function getJSON() {
        return json_encode($this->get());
    }
}