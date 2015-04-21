<?php

namespace Booking\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use General\NomencladorBundle\Entity\TipoActividad;

/**
 * Actividad
 *
 * @ORM\Table()
 * @ORM\Entity
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
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var string
     *
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
     *
     * @ORM\Column(name="pax", type="integer")
     */
    private $pax;

    /**
     * @var float
     *
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
}