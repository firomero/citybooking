<?php


namespace Booking\BookingBundle\Tests\Transformer;

class TransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testtransform()
    {
        $date = new \DateTime();
        $this->assertInstanceOf('\\DateTime', $date, 'oops');
    }
}
