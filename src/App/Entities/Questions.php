<?php


namespace App\Entities;


use App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="questions")
 */
class Questions extends Entity{
    /**
     * @ORM\ManyToOne(targetEntity="\App\Entities\FormVersions", inversedBy="questions")
     * @ORM\JoinColumn(name="form_id", nullable=false, referencedColumnName="id", onDelete="CASCADE")
     */
    private $form;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $question;




    /**
     * @return mixed
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param mixed $form
     */
    public function setForm( FormVersions $form)
    {
        $this->form = $form;
    }

    /**
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param string $question
     */
    public function setQuestion($question)
    {
        $this->question = htmlspecialchars($question);
    }


    public function toArray(){

        return array_merge(parent::toArray(),[
        "related_form"=>$this->getForm()->getTitre(),
        "question"=>$this->getQuestion(),

        ]);
    }

}