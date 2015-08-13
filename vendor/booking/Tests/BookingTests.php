<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 13/08/2015
 * Time: 14:00
 */

class BookingTests extends \PHPUnit_Framework_TestCase{
    public function testUniqueCallback(){
        $companies = array(
            0 => array(
                'name' => 'Foo - Ltd.',
                'phone' => 'XXX-YYY-ZZZ',
                'category' => 'supplyment',
            ),
            1 => array(
                'name' => 'Bar - Ltd.',
                'phone' => 'xxx-yyy-zzz',
                'category' => 'supplyment',
            ),
            2 => array(
                'name' => 'Baz - Ltd.',
                'phone' => 'AAA-BBB-CCC',
                'category' => 'alcohol',
            ),
        );

        $companies = array_unique_callback(
            $companies,
            function ($company) {
                return $company['category'];
            }
        );

        $this->assertEquals(2,count($companies),'oops');

    }

    public function testUniqueinObject(){

       $obj = new stdClass();
        $obj->pr0 = "string";
        $obj->pr1 = "string1";
        $obj->pr2 = "string";
        $obj->pr3 = "string2";

        $obj = object_unique($obj);
        $vars = get_object_vars($obj);
        $this->assertEquals(3,count($vars),'oops');



    }

} 