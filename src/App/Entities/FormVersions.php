<?php

namespace App\Entities;


use \Doctrine\ORM\Mapping as ORM;
use \App\Entity as Entity;
use \Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
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
     * @ORM\OneToMany(targetEntity="Questions", mappedBy="form", cascade={"persist","remove"})
     */
    private $questions;


    public function __construct()
    {
        parent::__construct();
        $this->questions = new ArrayCollection();
    }



    public function addQuestion(Questions $qestion){
        $this->qestions[] = $qestion;
        $qestion->setForm($this);
    }

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

    /**
     * @return mixed
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param mixed $questions
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;
    }




    public function toArray(){
        $questions = $this->getQuestions()->toArray();
        $questionsParsedArray = [];
        foreach ($questions as $q){
            $questionsParsedArray[$q->getId()]=$q->toArray();
        }

        return array_merge(parent::toArray(),[
          "id"=> $this->getId(),
            "titre"=>$this->getTitre(),
            "creator"=>$this->getCreator()->getEmail(),
            "questions"=>$questionsParsedArray
        ]);
    }

}