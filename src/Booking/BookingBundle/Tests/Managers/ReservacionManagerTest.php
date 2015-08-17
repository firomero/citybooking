<?php


namespace Booking\BookingBundle\Tests\Managers;

class ReservacionManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testCasa()
    {
        $casa = [
            4,3,2,1,

        ];

        $quest = [
            2,2
        ];

        $this->assertGreaterThanOrEqual(count($quest), count($casa), 'Wont run');


        if (count($casa)>=count($quest)) {
            $result = array_filter($quest, function ($value) use ($casa) {
                return current(array_filter($casa, function ($valor) use ($value) {
                    if ($value<=$valor) {
                        return $valor;
                    }
                }));
            });

            $this->assertGreaterThanOrEqual(count($quest), count($result), "Dont match");
        }
    }

    public function testdoubleRoom()
    {
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

        $this->assertGreaterThanOrEqual(count($quest), count($casa), 'Wont run');



        if (count($casa)>=count($quest)) {
            foreach ($quest as $pos=> $only) {
                $el = current(array_filter($check, function ($value) use ($only) {
                    if ($value>=$only) {
                        return $value;
                    }
                }));

                unset($check[array_search($el, $check)]);
            }



            $result = count($check);
            $subs = count($casa)-count($quest);


            $this->assertEquals($result, $subs, 'oops');
        }
    }

    public function testSort()
    {
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

        $this->assertTrue(max($check)>=max($quest)&&min($quest)<=$quest[count($check)-$indexQuest]);





        $this->assertTrue(true);
    }

    public function testSortedSort()
    {
        $a = [5,1,8,34,9,34,56,1,5,0];
        $this->sortBy($a, 'desc');
        var_dump(print_r($a));
        $this->assertGreaterThan($a[0], $a[count($a)-1], "Not greater");
    }

    /**
     * QuickSort
     * @param $array
     * @return array
     */
    private function quicksort($array)
    {
        if (count($array) < 2) {
            return $array;
        }
        $left = $right = array();
        reset($array);
        $pivot_key = key($array);
        $pivot = array_shift($array);
        foreach ($array as $k => $v) {
            if ($v > $pivot) {
                $left[$k] = $v;
            } else {
                $right[$k] = $v;
            }
        }

        return array_merge($this->quicksort($left), array($pivot_key => $pivot), $this->quicksort($right));
    }


    /**
     * QuickSort de PHP
     * @param $array
     * @param string $direction
     * @return bool
     */
    public function sortBy(&$array, $direction = 'asc')
    {
        usort($array, function ($a, $b) use ($direction) {

            if ($a==$b) {
                return 0;
            }

            return $direction == 'asc'?($a> $b?-1:1):($a < $b?-1:1);
        });

        return true;
    }

    public function testConfirmed()
    {
        $today = new \DateTime('now');
        $confirmed = date('2015-08-03');
        $this->assertGreaterThanOrEqual($confirmed, $today);
    }
}
