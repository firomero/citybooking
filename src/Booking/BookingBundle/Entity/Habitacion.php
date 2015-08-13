<?php

namespace Booking\BookingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use General\NomencladorBundle\Entity\TipoHab;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;


/**
 * Habitacion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Booking\BookingBundle\Entity\HabitacionRepository")
 */
class Habitacion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Casa
     *
     * @ORM\ManyToOne(targetEntity="Casa", inversedBy="habitaciones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="casaid", referencedColumnName="id")
     * })
     */
    private $casa;

    /**
     * @var TipoHab
     *
     * @ORM\ManyToOne(targetEntity="\General\NomencladorBundle\Entity\TipoHab")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipoid", referencedColumnName="id")
     * })
     */
    private $tipo;

//
//    /**
//     * @ORM\ManyToMany(targetEntity="Reservacion")
//     * @ORM\JoinTable(name="ntipo_habitacion",
//     *      joinColumns={@ORM\JoinColumn(name="tipoid", referencedColumnName="id")},
//     *      inverseJoinColumns={@ORM\JoinColumn(name="reservacionid", referencedColumnName="id")}
//     * )
//     */
//
//    protected $reservaciones;

    public function __construct()
    {
//        $this->reservaciones = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set casa
     *
     * @param \Booking\BookingBundle\Entity\Casa $casa
     * @return Habitacion
     */
    public function setCasa(\Booking\BookingBundle\Entity\Casa $casa = null)
    {
        $this->casa = $casa;
    
        return $this;
    }

    /**
     * Get casa
     *
     * @return \Booking\BookingBundle\Entity\Casa 
     */
    public function getCasa()
    {
        return $this->casa;
    }

    /**
     * Set tipo
     *
     * @param \General\NomencladorBundle\Entity\TipoHab $tipo
     * @return Habitacion
     */
    public function setTipo(\General\NomencladorBundle\Entity\TipoHab $tipo = null)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Get tipo
     *
     * @return \General\NomencladorBundle\Entity\TipoHab
     */
    public function getTipo()
    {
        return $this->tipo;
    }

//    /**
//     * Add reservaciones
//     *
//     * @param \Booking\BookingBundle\Entity\Reservacion $reservaciones
//     * @return Habitacion
//     */
//    public function addReservacione(\Booking\BookingBundle\Entity\Reservacion $reservaciones)
//    {
//        $this->reservaciones[] = $reservaciones;
//
//        return $this;
//    }

//    /**
//     * Remove reservaciones
//     *
//     * @param \Booking\BookingBundle\Entity\Reservacion $reservaciones
//     */
//    public function removeReservacione(\Booking\BookingBundle\Entity\Reservacion $reservaciones)
//    {
//        $this->reservaciones->removeElement($reservaciones);
//    }
//
//    /**
//     * Get reservaciones
//     *
//     * @return \Doctrine\Common\Collections\Collection
//     */
//    public function getReservaciones()
//    {
//        return $this->reservaciones;
//    }

    public function toArray(){
        return array(
            $this->id,
            $this->casa->getNombre(),
            $this->tipo->getNombre()
        );
    }
    public function __toString()
    {
        return $this->casa->getNombre().':'.$this->tipo->getNombre();
    }
}