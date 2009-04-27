<?php

/*
 * Author: William Murad
 * Class Tuple which encapsulates methods and properties for a dabatase row
 */

class AbstractData {

    protected $content;
    protected $abstractNumber;
    protected $foods = array();
    protected $relationships = array();
    protected $cancers = array();

    public function  __construct($number, $abstract) {
        $this->content = $abstract;
        $this->abstractNumber = $number;
    }
    
    public function __get($member) {
        return $this->$member;
    }

    public function __set($member, $value) {
            $this->$member = $value;
    }
 
}
?>
