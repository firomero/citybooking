<?php

namespace Booking\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use General\NomencladorBundle\Entity\Agencia;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineConstraint;

/**
 * Reservacion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Booking\BookingBundle\Entity\ReservacionRepository")
 */
class Reservacion
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
     * @Assert\NotBlank(message = "Por favor, escriba la fecha")
     * @Assert\DateTime()
     * @ORM\Column(name="checkin", type="datetime")
     */
    private $checkin;

    /**
     * @var \DateTime
     * @Assert\DateTime()
     * @ORM\Column(name="checkout", type="datetime")
     */
    private $checkout;

    /**
     * @var integer
     * @Assert\GreaterThanOrEqual(value = 0)
     * @ORM\Column(name="noches", type="integer")
     */
    private $noches;

    /**
     * @var integer
     *
     * @ORM\Column(name="pax", type="integer")
     */
    private $pax;

    /**
     * @var string
     * @Assert\NotBlank( message = "Por favor, inserte una habitación")
     * @ORM\Column(name="habitacion", type="string", length=255)
     */
    private $habitacion;

    /**
     * @var float
     *
     * @ORM\Column(name="precio", type="float")
     */
    private $precio;

    /**
     * @var \DateTime
     * @Assert\DateTime()
     * @ORM\Column(name="confirmado", type="datetime")
     */
    private $confirmado;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=255)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="text")
     */
    private $observacion;

    /**
     * @var Casa
     *
     * @ORM\ManyToOne(targetEntity="Casa")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="casaid", referencedColumnName="id")
     * })
     */
    private $casa;


    /**
     * @var Agencia
     *
     * @ORM\ManyToOne(targetEntity="\General\NomencladorBundle\Entity\Agencia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agenciaid", referencedColumnName="id")
     * })
     */
    private $agencia;

    /**
     * @var Cliente
     *
     * @ORM\ManyToOne(targetEntity="Cliente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="clienteid", referencedColumnName="id")
     * })
     */
    private $cliente;



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
     * Set checkin
     *
     * @param \DateTime $checkin
     * @return Reservacion
     */
    public function setCheckin($checkin)
    {
        $this->checkin = $checkin;
    
        return $this;
    }

    /**
     * Get checkin
     *
     * @return \DateTime 
     */
    public function getCheckin()
    {
        return $this->checkin;
    }

    /**
     * Set checkout
     *
     * @param \DateTime $checkout
     * @return Reservacion
     */
    public function setCheckout($checkout)
    {
        $this->checkout = $checkout;
    
        return $this;
    }

    /**
     * Get checkout
     *
     * @return \DateTime 
     */
    public function getCheckout()
    {
        return $this->checkout;
    }

    /**
     * Set noches
     *
     * @param integer $noches
     * @return Reservacion
     */
    public function setNoches($noches)
    {
        $this->noches = $noches;
    
        return $this;
    }

    /**
     * Get noches
     *
     * @return integer 
     */
    public function getNoches()
    {
        return $this->noches;
    }

    /**
     * Set pax
     *
     * @param integer $pax
     * @return Reservacion
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
     * Set habitacion
     *
     * @param string $habitacion
     * @return Reservacion
     */
    public function setHabitacion($habitacion)
    {
        $this->habitacion = $habitacion;
    
        return $this;
    }

    /**
     * Get habitacion
     *
     * @return string 
     */
    public function getHabitacion()
    {
        return $this->habitacion;
    }

    /**
     * Set precio
     *
     * @param float $precio
     * @return Reservacion
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    
        return $this;
    }

    /**
     * Get precio
     *
     * @return float 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set confirmado
     *
     * @param \DateTime $confirmado
     * @return Reservacion
     */
    public function setConfirmado($confirmado)
    {
        $this->confirmado = $confirmado;
    
        return $this;
    }

    /**
     * Get confirmado
     *
     * @return \DateTime 
     */
    public function getConfirmado()
    {
        return $this->confirmado;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Reservacion
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    
        return $this;
    }

    /**
     * Get estado
     *
     * @return string 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     * @return Reservacion
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;
    
        return $this;
    }

    /**
     * Get observacion
     *
     * @return string 
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set casa
     *
     * @param \Booking\BookingBundle\Entity\Casa $casa
     * @return Reservacion
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
     * Set agencia
     *
     * @param \General\NomencladorBundle\Entity\Agencia $agencia
     * @return Reservacion
     */
    public function setAgencia(\General\NomencladorBundle\Entity\Agencia $agencia = null)
    {
        $this->agencia = $agencia;
    
        return $this;
    }

    /**
     * Get agencia
     *
     * @return \General\NomencladorBundle\Entity\Agencia
     */
    public function getAgencia()
    {
        return $this->agencia;
    }

    /**
     * Set cliente
     *
     * @param \Booking\BookingBundle\Entity\Cliente $cliente
     * @return Reservacion
     */
    public function setCliente(\Booking\BookingBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;
    
        return $this;
    }

    /**
     * Get cliente
     *
     * @return \Booking\BookingBundle\Entity\Cliente 
     */
    public function getCliente()
    {
        return $this->cliente;
    }
}