<?php

namespace Booking\BookingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Cliente
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Cliente
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="referencia", type="string", length=255)
     */
    private $referencia;

    /**
     * @ORM\ManyToMany(targetEntity="Actividad")
     * @ORM\JoinTable(name="cliente_actividad",
     *      joinColumns={@ORM\JoinColumn(name="clienteid", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="actividadid", referencedColumnName="id")}
     * )
     */

    protected $actividades;

    public function __construct()
    {
        $this->actividades = new ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     * @return Cliente
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
     * Set referencia
     *
     * @param string $referencia
     * @return Cliente
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;
    
        return $this;
    }

    /**
     * Get referencia
     *
     * @return string 
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * Add actividades
     *
     * @param \Booking\BookingBundle\Entity\Actividad $actividades
     * @return Cliente
     */
    public function addActividade(\Booking\BookingBundle\Entity\Actividad $actividades)
    {
        $this->actividades[] = $actividades;
    
        return $this;
    }

    /**
     * Remove actividades
     *
     * @param \Booking\BookingBundle\Entity\Actividad $actividades
     */
    public function removeActividade(\Booking\BookingBundle\Entity\Actividad $actividades)
    {
        $this->actividades->removeElement($actividades);
    }

    /**
     * Get actividades
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActividades()
    {
        return $this->actividades;
    }
}