<?php

namespace Booking\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use General\NomencladorBundle\Entity\TipoActividad;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Actividad
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Booking\BookingBundle\Entity\ActividadRepository")
 */
class Actividad
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
     * @var \DateTime
     * @Assert\DateTime()
     * @Assert\NotBlank(message = "Por favor, inserte la fecha")
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var string
     * @Assert\Regex(pattern="/[A-Za-z0-9]/")
     * @Assert\Length( min =3 )
     * @Assert\NotBlank(message = "Por favor, inserte el nombre del guia.")
     * @ORM\Column(name="guia", type="string", length=255)
     */
    private $guia;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     */
    private $total;

    /**
     * @var integer
     * @Assert\GreaterThanOrEqual(value = 0)
     * @Assert\NotBlank()
     * @ORM\Column(name="pax", type="integer")
     */
    private $pax;

    /**
     * @var float
     * @Assert\NotBlank()
     * @ORM\Column(name="precioguia", type="float")
     */
    private $precioguia;


    /**
     * @var TipoActividad
     *
     * @ORM\ManyToOne(targetEntity="\General\NomencladorBundle\Entity\TipoActividad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipoactividadid", referencedColumnName="id")
     * })
     */
    private $tipoActividad;


    /**
     * @var Reservacion
     *
     * @ORM\ManyToOne(targetEntity="Reservacion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="reservacionid", referencedColumnName="id")
     * })
     */
    private $reservacion;


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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Actividad
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set guia
     *
     * @param string $guia
     * @return Actividad
     */
    public function setGuia($guia)
    {
        $this->guia = $guia;
    
        return $this;
    }

    /**
     * Get guia
     *
     * @return string 
     */
    public function getGuia()
    {
        return $this->guia;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return Actividad
     */
    public function setTotal($total)
    {
        $this->total = $total;
    
        return $this;
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set pax
     *
     * @param integer $pax
     * @return Actividad
     */
    public function setPax($pax)
    {
        $this->pax = $pax;
    
        return $this;
    }

    /**
     * Get pax
     *
     * @return integer 
     */
    public function getPax()
    {
        return $this->pax;
    }

    /**
     * Set precioguia
     *
     * @param float $precioguia
     * @return Actividad
     */
    public function setPrecioguia($precioguia)
    {
        $this->precioguia = $precioguia;
    
        return $this;
    }

    /**
     * Get precioguia
     *
     * @return float 
     */
    public function getPrecioguia()
    {
        return $this->precioguia;
    }

    /**
     * Set tipoActividad
     *
     * @param \General\NomencladorBundle\Entity\TipoActividad $tipoActividad
     * @return Actividad
     */
    public function setTipoActividad(\General\NomencladorBundle\Entity\TipoActividad $tipoActividad = null)
    {
        $this->tipoActividad = $tipoActividad;
    
        return $this;
    }

    /**
     * Get tipoActividad
     *
     * @return \General\NomencladorBundle\Entity\TipoActividad
     */
    public function getTipoActividad()
    {
        return $this->tipoActividad;
    }

    /**
     * Set reservacion
     *
     * @param \Booking\BookingBundle\Entity\Reservacion $reservacion
     * @return Actividad
     */
    public function setReservacion(\Booking\BookingBundle\Entity\Reservacion $reservacion = null)
    {
        $this->reservacion = $reservacion;
    
        return $this;
    }

    /**
     * Get reservacion
     *
     * @return \Booking\BookingBundle\Entity\Reservacion 
     */
    public function getReservacion()
    {
        return $this->reservacion;
    }

    public function toArray(){
        return array(
            $this->id,
            $this->tipoActividad->getNombre(),
            $this->fecha->format('d/m/Y'),
            $this->guia,
            $this->precioguia,
            $this->total
        );
    }

    public function __toString(){
        return $this->getTipoActividad()->getNombre();
    }
}