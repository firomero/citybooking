<?php

namespace Booking\BookingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Cliente
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Booking\BookingBundle\Entity\ClienteRepository")
 */
class Cliente
{
    /**
     * @ORM\ManyToMany(targetEntity="Actividad")
     * @ORM\JoinTable(name="cliente_actividad",
     *      joinColumns={@ORM\JoinColumn(name="clienteid", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="actividadid", referencedColumnName="id")}
     * )
     */

    protected $actividades;
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
     * @Assert\Regex(pattern="/[A-Za-z0-9]/")
     * @Assert\Length( min =3 )
     * @Assert\NotBlank(message = "Por favor, escriba el nombre" )
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;
    /**
     * @var string
     * @Assert\Regex(pattern="/[A-Za-z0-9]/")
     * @Assert\Length( min =3 )
     * @ORM\Column(name="referencia", type="string", length=255)
     */
    private $referencia;

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
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
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
     * Get referencia
     *
     * @return string
     */
    public function getReferencia()
    {
        return $this->referencia;
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
     * Add actividades
     *
     * @param Actividad $actividades
     * @return Cliente
     */
    public function addActividad(Actividad $actividades)
    {
        $this->actividades[] = $actividades;
    
        return $this;
    }

    /**
     * Remove actividades
     *
     * @param Actividad $actividades
     */
    public function removeActividad(Actividad $actividades)
    {
        $this->actividades->removeElement($actividades);
    }

    public function toArray()
    {
        return array(
            $this->id,
            $this->nombre,
            $this->referencia,
            $this->getActividades()->toArray()
        );
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