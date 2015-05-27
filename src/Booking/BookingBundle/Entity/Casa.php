<?php

namespace Booking\BookingBundle\Entity;

use Booking\BookingBundle\BookingBundle;
use Doctrine\ORM\Mapping as ORM;
use Booking\BookingBundle\Entity\Propietario;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Casa
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Booking\BookingBundle\Entity\CasaRepository")
 */
class Casa
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
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="telefono", type="integer")
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="categoria", type="string", length=5)
     */
    private $categoria;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidadHab", type="integer")
     */
    private $cantidadHab;

    /**
     * @var string
     *
     * @ORM\Column(name="clima", type="string", length=255)
     */
    private $clima;

    /**
     * @var string
     *
     * @ORM\Column(name="banno", type="string", length=255)
     */
    private $banno;

    /**
     * @var boolean
     *
     * @ORM\Column(name="disponible", type="boolean")
     */
    private $disponible;

    /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="text")
     */
    private $observacion;


    /**
     * @var Propietario
     *
     * @ORM\ManyToOne(targetEntity="Propietario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="propietarioid", referencedColumnName="id")
     * })
     */
    private $propietario;


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
     * Set direccion
     *
     * @param string $direccion
     * @return Casa
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    
        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Casa
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set telefono
     *
     * @param integer $telefono
     * @return Casa
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    
        return $this;
    }

    /**
     * Get telefono
     *
     * @return integer 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set categoria
     *
     * @param string $categoria
     * @return Casa
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    
        return $this;
    }

    /**
     * Get categoria
     *
     * @return string 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set cantidadHab
     *
     * @param integer $cantidadHab
     * @return Casa
     */
    public function setCantidadHab($cantidadHab)
    {
        $this->cantidadHab = $cantidadHab;
    
        return $this;
    }

    /**
     * Get cantidadHab
     *
     * @return integer 
     */
    public function getCantidadHab()
    {
        return $this->cantidadHab;
    }

    /**
     * Set clima
     *
     * @param string $clima
     * @return Casa
     */
    public function setClima($clima)
    {
        $this->clima = $clima;
    
        return $this;
    }

    /**
     * Get clima
     *
     * @return string 
     */
    public function getClima()
    {
        return $this->clima;
    }

    /**
     * Set banno
     *
     * @param string $banno
     * @return Casa
     */
    public function setBanno($banno)
    {
        $this->banno = $banno;
    
        return $this;
    }

    /**
     * Get banno
     *
     * @return string 
     */
    public function getBanno()
    {
        return $this->banno;
    }

    /**
     * Set disponible
     *
     * @param boolean $disponible
     * @return Casa
     */
    public function setDisponible($disponible)
    {
        $this->disponible = $disponible;
    
        return $this;
    }

    /**
     * Get disponible
     *
     * @return boolean 
     */
    public function getDisponible()
    {
        return $this->disponible;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     * @return Casa
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
     * Set propietario
     *
     * @param \Booking\BookingBundle\Entity\Propietario $propietario
     * @return Casa
     */
    public function setPropietario(\Booking\BookingBundle\Entity\Propietario $propietario = null)
    {
        $this->propietario = $propietario;
    
        return $this;
    }

    /**
     * Get propietario
     *
     * @return \Booking\BookingBundle\Entity\Propietario 
     */
    public function getPropietario()
    {
        return $this->propietario;
    }

    public function toArray()
    {
//        $columns = array('nombre','direccion','telefono','categoria','cantidadHab','clima','banno','disponible','observacion');
        return array(
            $this->nombre,
            $this->direccion,
            $this->telefono,
            $this->categoria,
            $this->cantidadHab,
            $this->clima,
            $this->banno,
            $this->disponible,
            $this->observacion
        );
    }
}