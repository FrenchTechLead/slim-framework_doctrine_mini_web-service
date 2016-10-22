<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use \App\Entity as Entity;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends Entity
{
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=32)
     * @var string
     */
    protected $password;

    /**
     * @var integer
     ** @ORM\Column(name="is_superuser", type="integer", options={"default":0})
     */
    protected $is_superuser;




// les fonctions

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = htmlspecialchars(strtolower($email));
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = sha1($password);
    }

    public function getIs_superuser(){
        return $this->is_superuser;
    }

    public function setIs_superuser($is_superuser){
        $this->is_superuser = $is_superuser;
    }

    public function toArray(){
        return array_merge(parent::toArray(),[
            "email"=>$this->getEmail(),
            "is_superuser"=>$this->getIs_superuser()
        ]);
    }
}