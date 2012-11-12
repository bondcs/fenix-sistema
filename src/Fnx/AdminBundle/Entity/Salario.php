<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Fnx\AdminBundle\Entity\Salario
 *
 * @ORM\Table(name="salario")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Salario
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
     * @var float $salario
     *
     * @ORM\Column(name="salario", type="float")
     * @Assert\NotBlank()
     */
    protected $salario;

    /**
     * @var float $valorHora
     *
     * @ORM\Column(name="valorHora", type="float", nullable=true)
     */
    protected $valorHora;
    
    /**
     *
     * @ORM\Column(name="ativo", type="boolean")
     */
    protected $ativo;
    
    /**
     * @var datetime $ultimoPagamento
     *
     * @ORM\Column(name="ultimoPagamento", type="datetime", nullable=true)
     */
    protected $ultimoPagamento;
    
    /**
     *
     * @var object $salario
     * @ORM\OneToOne(targetEntity="Funcionario", mappedBy="salario", cascade={"persist"})
     */
    protected $funcionario;
    
    /**
     *
     * @var Array Collection
     * 
     * @ORM\OneToMany(targetEntity="SalarioPagamento", mappedBy="salario", cascade={"persist"}, orphanRemoval=true) 
     */
    protected $pagamentos;

    public function __construct() {
        $this->pagamentos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ativo = true;
    }
    
    public function verificaPagamentos($mes, $ano){
        
        $inicio = new \DateTime($ano . "-" . $mes . "-01");
        $hoje = new \DateTime();
        $fimInicial = new \DateTime($ano . "-" . $mes);
        $fim = new \DateTime($fimInicial->format("Y-m-t"));
     
        foreach ($this->pagamentos as $pagamento){
            //var_dump($pagamento->getData() >= $inicio && $pagamento->getData());
            //var_dump($pagamento->getData() >= $inicio && $pagamento->getData() || $fim && $this->funcionario->getRegistro() >= $inicio);
            //var_dump($inicio);
            
            if ($pagamento->getData() >= $inicio && $pagamento->getData() || $fim && $this->funcionario->getRegistro() >= $inicio){
                return false;
            }
            
        }

        return true;
    }
    
   

    /**
     * @ORM\PrePersist @ORM\PreUpdate
     */
    public function formataDinheiro(){
        $this->salario =is_string($this->salario) ? substr(str_replace(",", ".", $this->salario),3) : $this->salario;
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
     * Set salario
     *
     * @param float $salario
     */
    public function setSalario($salario)
    {
        $this->salario = $salario;
    }

    /**
     * Get salario
     *
     * @return float 
     */
    public function getSalario()
    {
        return $this->salario;
    }

    /**
     * Set valorHora
     *
     * @param float $valorHora
     */
    public function setValorHora($valorHora)
    {
        $this->valorHora = $valorHora;
    }

    /**
     * Get valorHora
     *
     * @return float 
     */
    public function getValorHora()
    {
        return $this->valorHora;
    }

    /**
     * Set funcionario
     *
     * @param Fnx\AdminBundle\Entity\Funcionario $funcionario
     */
    public function setFuncionario(\Fnx\AdminBundle\Entity\Funcionario $funcionario)
    {
        $this->funcionario = $funcionario;
    }

    /**
     * Get funcionario
     *
     * @return Fnx\AdminBundle\Entity\Funcionario 
     */
    public function getFuncionario()
    {
        return $this->funcionario;
    }


    /**
     * Add pagamentos
     *
     * @param Fnx\AdminBundle\Entity\SalarioPagamento $pagamentos
     */
    public function addSalarioPagamento(\Fnx\AdminBundle\Entity\SalarioPagamento $pagamentos)
    {
        $this->pagamentos[] = $pagamentos;
    }

    /**
     * Get pagamentos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPagamentos()
    {
        return $this->pagamentos;
    }

    /**
     * Set ativo
     *
     * @param boolean $ativo
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
    }

    /**
     * Get ativo
     *
     * @return boolean 
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Set ultimoPagamento
     *
     * @param datetime $ultimoPagamento
     */
    public function setUltimoPagamento($ultimoPagamento)
    {
        $this->ultimoPagamento = $ultimoPagamento;
    }

    /**
     * Get ultimoPagamento
     *
     * @return datetime 
     */
    public function getUltimoPagamento()
    {
        return $this->ultimoPagamento;
    }
}