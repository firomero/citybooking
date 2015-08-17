<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 13/08/2015
 * Time: 19:32
 */

namespace Booking\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * BookedHab
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Booking\BookingBundle\Entity\BookedHabRepository")
 */
class BookedHab
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var Propietario
     *
     * @ORM\ManyToOne(targetEntity="Habitacion", cascade={"persist"}, inversedBy="books")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="habitacionid", referencedColumnName="id")
     * })
     */
    protected $hab;

    /**
     * @return \DateTime
     */
    public function getCheckin()
    {
        return $this->checkin;
    }

    /**
     * @return \DateTime
     */
    public function getCheckout()
    {
        return $this->checkout;
    }

    /**
     * @return Propietario
     */
    public function getHab()
    {
        return $this->hab;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @var \DateTime
     * @Assert\NotBlank(message = "Por favor, escriba la fecha")
     * @Assert\DateTime()
     * @ORM\Column(name="checkin", type="datetime")
     */
    protected $checkin;

    /**
     * @param \DateTime $checkin
     */
    public function setCheckin($checkin)
    {
        $this->checkin = $checkin;
    }

    /**
     * @param \DateTime $checkout
     */
    public function setCheckout($checkout)
    {
        $this->checkout = $checkout;
    }

    /**
     * @param Propietario $hab
     */
    public function setHab($hab)
    {
        $this->hab = $hab;
    }

    /**
     * @var \DateTime
     * @Assert\DateTime()
     * @ORM\Column(name="checkout", type="datetime")
     */
    protected $checkout;

    public function __toString()
    {
        return 'booked: '.$this->checkin->format('Y-m-d').'to: '.$this->checkout->format('Y-m-d');
    }

    /**
     * @Assert\True(message="Debe introducir un rango válido ")
     * @return bool
     */
    public function isValidRange()
    {
        return $this->checkout > $this->checkin;
    }
}
