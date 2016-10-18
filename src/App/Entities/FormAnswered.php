<?php


namespace App\Entities;


use App\Entity;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="formAnswerd")
 */
class FormAnswered extends Entity{

    /**
     * @ORM\ManyToOne(targetEntity="\App\Entities\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="\App\Entities\FormVersions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formlinked;


}