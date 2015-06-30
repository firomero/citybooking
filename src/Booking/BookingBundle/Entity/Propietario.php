<?php

namespace Booking\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Propietario
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Booking\BookingBundle\Entity\PropietarioRepository")
 * @DoctrineAssert\UniqueEntity("nombre")
 * @DoctrineAssert\UniqueEntity("ci")
 */
class Propietario
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
     * @Assert\Regex(pattern="/[A-Za-z0-9]/")
     * @Assert\Length( min =3 )
     * @Assert\NotBlank(message = "Por favor, escriba el nombre." )
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     * @Assert\Length( min =3 )
     * @Assert\NotBlank(message = "Por favor, escriba el número de identificación." )
     * @Assert\Regex(pattern="/[0-9]/")
     * @ORM\Column(name="ci", type="string", length=255)
     */
    private $ci;


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
     * @return Propietario
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
     * Set ci
     *
     * @param string $ci
     * @return Propietario
     */
    public function setCi($ci)
    {
        $this->ci = $ci;
    
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            $this->id,
            $this->nombre,
            $this->ci
        );
    }

    /**
     * Get ci
     *
     * @return string 
     */
    public function getCi()
    {
        return $this->ci;
    }

    public function __toString(){
        return $this->nombre;
    }
}