<?php

namespace App\Entities;


use Doctrine\ORM\Mapping as ORM;
use \App\Entity as Entity;

/**
 * @ORM\Entity
 * @ORM\Table(name="formVersions")
 */
class FormVersions extends Entity{

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $titre;

    /**
     * @var integer
     ** @ORM\Column(name="version", type="integer", options={"default":0})
     */
    protected $version;

    /**
     * @ORM\ManyToOne(targetEntity="\App\Entities\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;






    /**
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param string $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param mixed $creator
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
    }




}