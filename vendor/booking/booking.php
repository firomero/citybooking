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
function array_unique_callback(array $arr, callable $callback, $strict = false) {
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
function object_unique( $obj ){
    $objArray = (array) $obj;

    $objArray = array_intersect_assoc( array_unique( $objArray ), $objArray );

    foreach( $obj as $n => $f ) {
        if( !array_key_exists( $n, $objArray ) ) unset( $obj->$n );
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
function bubbleSortByTipoHab($array)
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



