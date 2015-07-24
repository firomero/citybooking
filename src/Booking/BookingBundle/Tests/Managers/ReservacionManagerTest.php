<?php


namespace Booking\BookingBundle\Tests\Managers;

class ReservacionManagerTest extends \PHPUnit_Framework_TestCase {

    /**
     *
     */
    public function testCasa(){
        $casa = [
            4,3,2,1,

        ];

        $quest = [
            2,2
        ];

        $this->assertGreaterThanOrEqual(count($quest),count($casa),'Wont run');


        if (count($casa)>=count($quest)) {

            $result = array_filter($quest, function($value)use($casa){
                return current(array_filter($casa,function($valor)use($value){
                    if ($value<=$valor) {
                        return $valor;
                    }
                }));
            });

            $this->assertGreaterThanOrEqual(count($quest),count($result),"Dont match");
        }
    }

    public function testdoubleRoom(){

        $casa = [
            4,3,2,1

        ];

        $quest = [
            2,2,2
        ];

        $check = [
            4,3,2,1,

        ];

        $dc = [2];

        $this->assertGreaterThanOrEqual(count($quest),count($casa),'Wont run');



        if (count($casa)>=count($quest)) {

            foreach ($quest as $pos=> $only) {
                $el = current(array_filter($check,function($value)use($only){
                    if ($value>=$only) {
                        return $value;
                    }
                }));

                unset($check[array_search($el,$check)]);
            }



            $result = count($check);
            $subs = count($casa)-count($quest);


            $this->assertEquals($result ,$subs,'oops');


        }

    }

    public function testSort(){
        $casa = [
            4,3,2,1

        ];

        $quest = [
            2,1,2
        ];

        $check = [
            4,3,2,1,

        ];


        sort($casa);
        sort($quest);
        sort($check);
        $indexQuest = count($quest);

        var_dump(count($check)-$indexQuest);

        $this->assertTrue(max($check)>=max($quest)&&min($quest)<=$quest[count($check)-$indexQuest]);





        $this->assertTrue(true);
    }

}
 