<?php


namespace App\Entities;


use App\Entity;
use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection as ArrayCollection;


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


    /**
     * @ORM\OneToMany(targetEntity="Answers", mappedBy="formAnswered", cascade={"persist","remove"})
     */
    private $answers;

    public function __construct()
    {
        parent::__construct();
        $this->answers = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getFormlinked()
    {
        return $this->formlinked;
    }

    /**
     * @param mixed $formlinked
     */
    public function setFormlinked($formlinked)
    {
        $this->formlinked = $formlinked;
    }

    /**
     * @return mixed
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param mixed $answers
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;
    }



    public function toArray(){
        $answers = $this->getAnswers()->toArray();
        $answersParsedArray = [];
        foreach ($answers as $a){
            $answersParsedArray[$a->getQuestion()->getQuestion()] = $a->getAnswer();
        }
        return array_merge(parent::toArray(),[
            "created_by"=>$this->getUser()->getEmail(),
            "linked_form"=>["titre"=>$this->getFormlinked()->getTitre(), "version"=>$this->getFormlinked()->getVersion()],
            "content"=>$answersParsedArray

        ]);

    }

}