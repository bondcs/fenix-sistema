<?php

namespace Fnx\AdminBundle\Entity;
use Fnx\AdminBundle\Entity\Atividade;
use Fnx\AdminBundle\Entity\Funcionario;
use Symfony\Component\Validator\Constraints as Assert;
use Fnx\AdminBundle\Validator\Constraints as FnxAssert;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fnx\AdminBundle\Entity\Escala
 *
 * @ORM\Table(name="escalaEvento")
 * @ORM\Entity(repositoryClass="Fnx\AdminBundle\Entity\EscalaRepository")
 * @ORM\HasLifecycleCallbacks();
 */
class Escala
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var datetime $dtInicio
     *
     * @ORM\Column(name="dtInicio", type="datetime")
     * @Assert\NotBlank()
     */
    protected $dtInicio;

    /**
     * @var datetime $dtFim
     *
     * @ORM\Column(name="dtFim", type="datetime")
     * @Assert\NotBlank()
     */
    protected $dtFim;
    
    /**
     * @var string $local
     * 
     * @ORM\Column(type="string", length=45, nullable=false)
     * @Assert\NotBlank()
     */
    protected $local;
    
    /**
     *
     * @var float $custoUnitario
     * @ORM\Column(type="float", length=10, nullable=false)
     * @Assert\NotBlank()
     * @FnxAssert\Dinheiro()
     */
    protected $custoUnitario;
    
    /**
     *
     * @var float $custoTotal
     * @ORM\Column(type="float", length=10, nullable=false)
     */
    protected $custoTotal;
    
    /**
     *
     * @var integer $qtdFun
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    protected $qtdFun;
    /**
     *
     * @ORM\ManyToOne(targetEntity="Atividade", inversedBy="escalas", cascade={"persist"}, fetch="LAZY")
     */
    protected $atividade;
    
    /**
     * 
     * @ORM\ManyToMany(targetEntity="Funcionario", inversedBy="escalas", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinTable(name="escala_funcionario",
     *     joinColumns={@ORM\JoinColumn(name="escala_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="funcionario_id", referencedColumnName="id")}
     * )
     * 
     */
    protected $funcionarios;
    
    public function __construct() {
        $this->funcionarios = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * @ORM\PrePersist @ORM\PreUpdate
     */
    public function formataDinheiro(){
        $this->custoUnitario = substr(str_replace(",", ".", $this->custoUnitario),3);
    }

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
     * Set dtInicio
     *
     * @param datetime $dtInicio
     */
    public function setDtInicio($dtInicio)
    {
        $this->dtInicio = $dtInicio;
    }

    /**
     * Get dtInicio
     *
     * @return datetime 
     */
    public function getDtInicio()
    {      
        return $this->dtInicio;
    }

    /**
     * Set dtFim
     *
     * @param datetime $dtFim
     */
    public function setDtFim($dtFim)
    {
        $this->dtFim = $dtFim;
    }

    /**
     * Get dtFim
     *
     * @return datetime 
     */
    public function getDtFim()
    {
        return $this->dtFim;
    }

    /**
     * Set atividade
     *
     * @param Fnx\AdminBundle\Entity\Atividade $atividade
     */
    public function setAtividade(\Fnx\AdminBundle\Entity\Atividade $atividade)
    {
        $this->atividade = $atividade;
    }

    /**
     * Get atividade
     *
     * @return Fnx\AdminBundle\Entity\Atividade 
     */
    public function getAtividade()
    {
        return $this->atividade;
    }

    /**
     * Add funcionarios
     *
     * @param Fnx\AdminBundle\Entity\Funcionario $funcionarios
     */
    public function addFuncionario(\Fnx\AdminBundle\Entity\Funcionario $funcionarios)
    {
        $this->funcionarios[] = $funcionarios;
    }

    /**
     * Get funcionarios
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFuncionarios()
    {
        return $this->funcionarios;
    }

    /**
     * Set local
     *
     * @param string $local
     */
    public function setLocal($local)
    {
        $this->local = $local;
    }

    /**
     * Get local
     *
     * @return string 
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * Set custoUnitario
     *
     * @param float $custoUnitario
     */
    public function setCustoUnitario($custoUnitario)
    {
        $this->custoUnitario = $custoUnitario;
       
    }

    /**
     * Get custoUnitario
     *
     * @return float 
     */
    public function getCustoUnitario()
    {
        return $this->custoUnitario;
    }

    /**
     * Set custoTotal
     *
     * @param float $custoTotal
     */
    public function setCustoTotal($custoTotal)
    {
        $this->custoTotal = $custoTotal;
    }

    /**
     * Get custoTotal
     *
     * @return float 
     */
    public function getCustoTotal()
    {
        return $this->custoTotal;
    }
    
    /**
     * @ORM\PrePersist @ORM\PreUpdate
     */
    public function calculaCustoTotal(){
        $total = $this->getCustoUnitario()*$this->getFuncionarios()->count();
        $this->setCustoTotal($total);
    }
   
    /**
     * Set qtdFun
     *
     * @param integer $qtdFun
     * @ORM\PrePersist @ORM\PreUpdate
     */
    public function setQtdFun()
    {
        $this->qtdFun = $this->getFuncionarios()->count();
    }

    /**
     * Get qtdFun
     *
     * @return integer 
     */
    public function getQtdFun()
    {
        return $this->qtdFun;
    }
}