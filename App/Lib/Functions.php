<?php

class Functions {

    /**  Arrays **/
    public static function isElementInArray($element,$array,$index) {

        $isElement = false;
        foreach ($array as $arrayElement) {
            if ($element[$index]==$arrayElement[$index]) {
                $isElement = true;
                break;
            }
        }

        return $isElement;
    }

    public static function returnNewElementsInLastArray($firstArray,$lastArray,$field) {

        $newElements = array();

        foreach ($firstArray as $elementFirstArray) {
            if (!Functions::isElementInArray($elementFirstArray,$lastArray,$field)) {
                array_push($newElements,$elementFirstArray);
            }
        }
        return $newElements;
    }

    public static function getSubarrayBasedOnIndex($array,$index) {

        $subarray = array();
        foreach ($array as $element) {
            array_push($subarray,$element[$index]);
        }
        return $subarray;

    }

    public static function wrapElementsArray($array,$wrapPrev = '',$wrapPost = '') {
        foreach ($array as &$element) {
            $element = $wrapPrev . $element . $wrapPost;
        }

        return $array;
    }

    /** Strings **/
    public static function wrapElement($element,$wrapPrev = '',$wrapPost = '') {
        $element = $wrapPrev . $element . $wrapPost;
        return $element;
    }

}