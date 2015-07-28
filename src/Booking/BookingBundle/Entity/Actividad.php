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
     * @var \DateTime
     * @Assert\Time()
     * @Assert\NotBlank(message = "Por favor, inserte la hora")
     * @ORM\Column(name="hora", type="datetime")
     */
    private $hora;

    /**
     * @return \DateTime
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * @param \DateTime $hora
     */
    public function setHora($hora)
    {
        $this->hora = $hora;
    }

    /**
     * @var string
     * @Assert\Regex(pattern="/[A-Za-z0-9]/")
     * @Assert\Length( min =2 )
     * @Assert\NotBlank(message = "Por favor, inserte el nombre del guia.")
     * @ORM\Column(name="lugar", type="string", length=255)
     */
    private $lugar;

    /**
     * @var float
     *
     * @ORM\Column(name="coordinacion", type="float")
     */
    private $coordinacion;

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
     * @ORM\Column(name="precio", type="float")
     */
    private $precio;


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
     * @ORM\ManyToOne(targetEntity="Reservacion", inversedBy="actividades")
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
    public function setLugar($lugar)
    {
        $this->lugar = $lugar;
    
        return $this;
    }

    /**
     * Get guia
     *
     * @return string 
     */
    public function getLugar()
    {
        return $this->lugar;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return Actividad
     */
    public function setCoordinacion($coordinacion)
    {
        $this->coordinacion = $coordinacion;
    
        return $this;
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getCoordinacion()
    {
        return $this->coordinacion;
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
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    
        return $this;
    }

    /**
     * Get precioguia
     *
     * @return float 
     */
    public function getPrecio()
    {
        return $this->precio;
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
            $this->fecha->format('d/m/Y').'-'.$this->hora->format('H:i'),
            $this->lugar,
            $this->coordinacion,
            $this->precio
        );
    }

    /**
     * Total Ampunt of Activity
     * @return float
     */
    public function getTotal(){
        return $this->precio + $this->coordinacion;
    }

    public function __toString(){
        return $this->getTipoActividad()->getNombre();
    }

    /**
     * @Assert\True(message="Debe introducir una fecha válida ")
     * @return bool
     */
    public function isValidConfirm(){
        $today = new \DateTime();
        return $this->fecha>= $today;
    }
}