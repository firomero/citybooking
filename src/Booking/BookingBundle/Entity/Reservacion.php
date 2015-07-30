<?php

namespace Booking\BookingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use General\NomencladorBundle\Entity\Agencia;
use General\NomencladorBundle\Entity\TipoHab;
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
    const RESERVADA = 'reservada';
    const CANCELADA = 'cancelada';
    const PENDIENTE = 'pendiente';
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
     * @ORM\Column(name="habitacion", type="string", length=255, nullable=true)
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
    private $estado = self::RESERVADA;

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
     * @ORM\ManyToOne(targetEntity="Cliente", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="clienteid", referencedColumnName="id")
     * })
     */
    private $cliente;


    /**
     * @ORM\ManyToMany(targetEntity="\General\NomencladorBundle\Entity\TipoHab",  inversedBy="reservaciones")
     * @ORM\JoinTable(name="tipo_reservacion",
     *      joinColumns={@ORM\JoinColumn(name="reservacionid", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tipoid", referencedColumnName="id")}
     * )
     */
     protected $tipoHab;

    /**
     * @ORM\OneToMany(targetEntity="Actividad", mappedBy="reservacion")
     **/
    private $actividades;





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

    public function __construct(){
        $this->tipoHab = new ArrayCollection();
        $this->actividades = new ArrayCollection();
    }

    /**
     * @param TipoHab $tipoHab
     */
    public function addTipoHab(TipoHab $tipoHab){

        $this->tipoHab->add($tipoHab);
    }


    /**
     * @param TipoHab $tipoHab
     */
    public function removeTipoHab(TipoHab $tipoHab){

        $this->tipoHab->remove($tipoHab->getId());
    }

    public function setTipoHab($tipohab)
    {
        $this->tipoHab = new ArrayCollection($tipohab);
    }


    /**
     * @param Actividad $actividad
     */
    public function addActividad(Actividad $actividad){
        $this->actividades->add($actividad);
    }

    /**
     * @param Actividad $actividad
     */
    public function removeActividad(Actividad $actividad){
        $this->actividades->remove($actividad->getId());
    }

    /**
     *
     * @return ArrayCollection
     */
    public function getTipoHab(){
        return $this->tipoHab;
    }

    /**
     * @return ArrayCollection
     */
    public function getActividades(){
        return $this->actividades;
    }

    /**
     * Normalize type object
     * @return array
     */
    public function roomList(){
        return array_map(function($value){
            /**
             * @var TipoHab $value
             */
            return $value->getNombre();
        },
            $this->tipoHab->getValues());
    }

    /**
     * Normalize activities
     * @return array
     */
    public function activityList(){
        return array_map(function($value){
                /**
                 * @var Actividad $value
                 */
                return $value->getTipoActividad()->getNombre().'DÍA: '.$value->getFecha()->format('d/m/Y H:i').' '.$this->getCasa()->getNombre().' '.$value->getTotal();
            },
            $this->actividades->getValues());
    }

    /**
     * Normalize bppking object
     * @return array
     */
    public function toArray(){
        return array(
            $this->id,
            $this->agencia->getNombre(),
            $this->cliente->getNombre(),
            $this->checkin->format('d-m-Y'),
            $this->checkout->format('d-m-Y'),
            $this->precio,
            $this->casa->getNombre(),
            $this->estado
        );
    }

    /**
     * Returns text representation from a booking
     * @return string
     */
    public function __toString(){
        return 'Agencia: '.$this->getAgencia()->getNombre().' Casa:'.$this->casa->getNombre().' Booking:'.$this->getCliente()->getReferencia();
    }

    /**
     * @Assert\True(message="Debe introducir un rango válido ")
     * @return bool
     */
    public function isValidRange(){
        $today = new \DateTime();
        return $this->checkin >=  $today  && $this->checkout>=$today && $this->checkout > $this->checkin;
    }

    /**
     * @Assert\True(message="Debe introducir una fecha válida ")
     * @return bool
     */
    public function isValidConfirm(){
        $today = date_create_from_format('Y-m-d','now');
        return $this->confirmado >= $today;
    }
}