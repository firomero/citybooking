<?php
/**
 * Customized Booking functions  and configurations
 * @package Booking
 * @author firomero<firomerorom4@gmail.com>
 */

/**
 * Array unique callback
 * @param array $arr
 * @param callable $callback
 * @param bool $strict
 * @return array
 */
function array_unique_callback(array $arr, callable $callback, $strict = false)
{
    return array_filter(
        $arr,
        function ($item) use ($strict, $callback) {
            static $haystack = array();
            $needle = $callback($item);
            if (in_array($needle, $haystack, $strict)) {
                return false;
            } else {
                $haystack[] = $needle;
                return true;
            }
        }
    );
}

/**
 * Array unique in object
 * @param $obj
 * @return mixed
 */
function object_unique($obj)
{
    $objArray = (array)$obj;

    $objArray = array_intersect_assoc(array_unique($objArray), $objArray);

    foreach ($obj as $n => $f) {
        if (!array_key_exists($n, $objArray)) unset($obj->$n);
    }

    return $obj;
}

/**
 * The QuickSort Method
 * @param $array
 * @return array
 */
function quicksort($array)
{
    if (count($array) < 2) {
        return $array;
    }
    $left = $right = array();
    reset($array);
    $pivot_key = key($array);
    $pivot = array_shift($array);
    foreach ($array as $k => $v) {
        if ($v->getPeso() > $pivot->getPeso()) {
            $left[$k] = $v;
        } else {
            $right[$k] = $v;
        }
    }

    return array_merge(quicksort($left), array($pivot_key => $pivot), quicksort($right));
}

/**
 * The BubbleSort Method
 * @param $array
 */
function bubbleSortByTipoHab(&$array)
{
    if (!$length = count($array)) {
        return $array;
    }
    for ($outer = 0; $outer < $length; $outer++) {
        for ($inner = 0; $inner < $length; $inner++) {
            if (($array[$inner]->getPeso() > $array[$outer]->getPeso())
            ) {
                $tmp = $array[$outer];
                $array[$outer] = $array[$inner];
                $array[$inner] = $tmp;
            }
        }
    }
}

/**
 * Binary Search Uncentered
 * This is the method of binary search calculating the exact pivote for the search.
 * @param array $haystack
 * @param $first
 * @param $last
 * @param $needle
 * @return bool
 */
function binary_search_uncentered(array $haystack, $first, $last,$needle){

    $nterc = round(sizeof($haystack)/3);

    if ($first>=$last) {
        if ($haystack[$last]==$needle) {
            return true;
        } else {
            return false;
        }
    }

    $nterc = round(($last-$first+1)/3);
    if ($needle==$haystack[$first+$nterc]) {
        return true;
    }elseif($needle<$haystack[$first+$nterc]){
        return binary_search_uncentered($haystack,$first,$first+$nterc-1,$needle);

    } elseif ($needle==$haystack[$last-$nterc]) {
        return true;
    } elseif ($needle<$haystack[$last-$nterc]) {
        return binary_search_uncentered($haystack,$first+$nterc+1,$last-$nterc-1,$needle);
    } else {
        return binary_search_uncentered($haystack,$last-$nterc+1,$last,$needle);
    }

    return false;


}

/**
 * Binary Search Uncentered with Callback
 * This is the method of binary search calculating the exact pivote for the search.
 * @param array $haystack
 * @param $first
 * @param $last
 * @param $needle
 * @param callable $callback
 * @return bool
 */
function binary_search_uncentered_callable(array $haystack, $first, $last,$needle, callable $callback){

    $nterc = round(sizeof($haystack)/3);

    if ($first>=$last) {
        if ($callback($haystack[$last])==$callback($needle)) {
            return $last;
        } else {
            return -1;
        }
    }

    $nterc = round(($last-$first+1)/3);
    if ($callback($needle)==$callback($haystack[$first+$nterc])) {
        return $first+$nterc;
    }elseif($callback($needle)<$callback($haystack[$first+$nterc])){
        return binary_search_uncentered($haystack,$first,$first+$nterc-1,$needle);

    } elseif ($callback($needle)==$callback($haystack[$last-$nterc])) {
        return $last-$nterc;
    } elseif ($callback($needle)<$callback($haystack[$last-$nterc])) {
        return binary_search_uncentered($haystack,$first+$nterc+1,$last-$nterc-1,$needle);
    } else {
        return binary_search_uncentered($haystack,$last-$nterc+1,$last,$needle);
    }

    return false;


}



