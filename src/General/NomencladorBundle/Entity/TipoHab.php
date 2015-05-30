<?php

namespace General\NomencladorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoHab
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="General\NomencladorBundle\Entity\TipoHabRepository")
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
     *
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
}