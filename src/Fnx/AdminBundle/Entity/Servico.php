<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fnx\AdminBundle\Entity\Servico
 *
 * @ORM\Table(name="servico")
 * @ORM\Entity(repositoryClass="Fnx\AdminBundle\Entity\ServicoRepository")
 */
class Servico
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $nome
     *
     * @ORM\Column(name="nome", type="string", length=45)
     */
    private $nome;
    
    /**
     * @var string $cor
     *
     * @ORM\Column(name="cor", type="string", length=20)
     */
    private $cor;

    /**
     * @var time $descanso
     *
     * @ORM\Column(name="descanso", type="time", nullable=true)
     */
    private $descanso;

    /**
     * @var time $descansoNoturno
     *
     * @ORM\Column(name="descansoNoturno", type="time", nullable=true)
     */
    private $descansoNoturno;

    /**
     * @var integer $horasSemanais
     *
     * @ORM\Column(name="horasSemanais", type="integer", nullable=true)
     */
    private $horasSemanais;

    /**
     * @var integer $horasContinuas
     *
     * @ORM\Column(name="horasContinuas", type="integer", nullable=true)
     */
    private $horasContinuas;

    /**
     * @var integer $maxHoraExtra
     *
     * @ORM\Column(name="maxHoraExtra", type="integer", nullable=true)
     */
    private $maxHoraExtra;

    /**
     * @var float $valorHoraExtra
     *
     * @ORM\Column(name="valorHoraExtra", type="float", nullable=true)
     */
    private $valorHoraExtra;

    /**
     * @var float $multiHoraExtra
     *
     * @ORM\Column(name="multiHoraExtra", type="float", nullable=true)
     */
    private $multiHoraExtra;


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
     * Set nome
     *
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * Get nome
     *
     * @return string 
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set descanso
     *
     * @param time $descanso
     */
    public function setDescanso($descanso)
    {
        $this->descanso = $descanso;
    }

    /**
     * Get descanso
     *
     * @return time 
     */
    public function getDescanso()
    {
        return $this->descanso;
    }

    /**
     * Set descansoNoturno
     *
     * @param time $descansoNoturno
     */
    public function setDescansoNoturno($descansoNoturno)
    {
        $this->descansoNoturno = $descansoNoturno;
    }

    /**
     * Get descansoNoturno
     *
     * @return time 
     */
    public function getDescansoNoturno()
    {
        return $this->descansoNoturno;
    }

    /**
     * Set horasSemanais
     *
     * @param integer $horasSemanais
     */
    public function setHorasSemanais($horasSemanais)
    {
        $this->horasSemanais = $horasSemanais;
    }

    /**
     * Get horasSemanais
     *
     * @return integer 
     */
    public function getHorasSemanais()
    {
        return $this->horasSemanais;
    }

    /**
     * Set horasContinuas
     *
     * @param integer $horasContinuas
     */
    public function setHorasContinuas($horasContinuas)
    {
        $this->horasContinuas = $horasContinuas;
    }

    /**
     * Get horasContinuas
     *
     * @return integer 
     */
    public function getHorasContinuas()
    {
        return $this->horasContinuas;
    }

    /**
     * Set maxHoraExtra
     *
     * @param integer $maxHoraExtra
     */
    public function setMaxHoraExtra($maxHoraExtra)
    {
        $this->maxHoraExtra = $maxHoraExtra;
    }

    /**
     * Get maxHoraExtra
     *
     * @return integer 
     */
    public function getMaxHoraExtra()
    {
        return $this->maxHoraExtra;
    }

    /**
     * Set valorHoraExtra
     *
     * @param float $valorHoraExtra
     */
    public function setValorHoraExtra($valorHoraExtra)
    {
        $this->valorHoraExtra = $valorHoraExtra;
    }

    /**
     * Get valorHoraExtra
     *
     * @return float 
     */
    public function getValorHoraExtra()
    {
        return $this->valorHoraExtra;
    }

    /**
     * Set multiHoraExtra
     *
     * @param float $multiHoraExtra
     */
    public function setMultiHoraExtra($multiHoraExtra)
    {
        $this->multiHoraExtra = $multiHoraExtra;
    }

    /**
     * Get multiHoraExtra
     *
     * @return float 
     */
    public function getMultiHoraExtra()
    {
        return $this->multiHoraExtra;
    }

    /**
     * Set cor
     *
     * @param string $cor
     */
    public function setCor($cor)
    {
        $this->cor = $cor;
    }

    /**
     * Get cor
     *
     * @return string 
     */
    public function getCor()
    {
        return $this->cor;
    }
}