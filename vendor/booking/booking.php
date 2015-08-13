<?php
/**
 * Customized Booking functions  and configurations
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



