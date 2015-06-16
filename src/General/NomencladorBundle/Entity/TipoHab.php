<?php

namespace General\NomencladorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * TipoHab
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="General\NomencladorBundle\Entity\TipoHabRepository")
 * @DoctrineAssert\UniqueEntity("nombre")
 */
class TipoHab
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
     * @Assert\Regex(pattern="/[A-Za-z0-9]/")
     * @Assert\Length( min =3 )
     * @Assert\NotBlank(message = "Por favor, escriba el nombre." )
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;


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
     * @return TipoHab
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

    public function toArray()
    {
        return array(
            $this->id,
            $this->nombre
        );
    }

    public function __toString()
    {
        return $this->getNombre();
    }
}