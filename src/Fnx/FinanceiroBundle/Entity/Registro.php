<?php

namespace Fnx\FinanceiroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Fnx\AdminBundle\Validator\Constraints as FnxAssert;

/**
 * Fnx\FinanceiroBundle\Entity\Registro
 *
 * @ORM\Table(name="registro")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Registro
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
     * @var boolean $ativo
     *
     * @ORM\Column(name="ativo", type="boolean")
     */
    private $ativo;

    /**
     * @var datetime $data
     *
     * @ORM\Column(name="data", type="datetime")
     */
    private $data;

    /**
     * @var string $descricao
     *
     * @ORM\Column(name="descricao", type="string", length=255)
     */
    private $descricao;
    
    /**
     *
     * @var object $conta
     * @ORM\ManyToOne(targetEntity="Conta", inversedBy="registros", cascade={"persist"}, fetch="LAZY")
     * @Assert\NotBlank()
     */
    private $conta;
    
    /**
     * @var array collection $parcelas
     * @ORM\OneToMany(targetEntity="Parcela", mappedBy="registro", cascade={"all"}, orphanRemoval=true)
     * 
     */
    private $parcelas;
    
    /**
     * @var object $categoria
     * @ORM\ManyToOne(targetEntity="Fnx\AdminBundle\Entity\Categoria", cascade={"persist"}, fetch="LAZY")
     */
    private $categoria;
    
    /**
     * @var date $primeriaParcela
     *
     * @ORM\Column(name="dt_primeiraParcela", type="date")
     */
    private $primeiraParcela;
    
    /**
     * @var date $valor
     *
     * @ORM\Column(name="valorTotal", type="float")
     */
    private $valor;


    public function __construct() {
        $this->parcelas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->data = new \DateTime();
        $this->ativo = true;
    }
    
    /**
     * @ORM\PrePersist @ORM\PreUpdate
     */
    public function formataDinheiroDb(){
        if (is_string($this->valor)){
            $this->valor = substr(str_replace(",", ".", $this->valor),3);
        }
        
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
     * Set data
     *
     * @param datetime $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Get data
     *
     * @return datetime 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set descricao
     *
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * Get descricao
     *
     * @return string 
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set conta
     *
     * @param Fnx\FinanceiroBundle\Entity\Instancia $conta
     */
    public function setConta(\Fnx\FinanceiroBundle\Entity\Conta $conta)
    {
        $this->conta = $conta;
    }

    /**
     * Get conta
     *
     * @return Fnx\FinanceiroBundle\Entity\Instancia 
     */
    public function getConta()
    {
        return $this->conta;
    }

    /**
     * Add parcelas
     *
     * @param Fnx\FinanceiroBundle\Entity\Parcela $parcelas
     */
    public function addParcela(\Fnx\FinanceiroBundle\Entity\Parcela $parcelas)
    {
        $this->parcelas[] = $parcelas;
    }

    /**
     * Get parcelas
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getParcelas()
    {
        return $this->parcelas;
    }
    
    public function calculaParcela($parcelas, $valor){
        return floatval($this->formataDinheiro($valor))/(int)$parcelas;
        
    }
    
    public function formataDinheiro($valor){
        return substr(str_replace(",", ".", $valor),3);
        
    }
    
    public function getValorTotal(){
        $total = 0;
        foreach ($this->parcelas as $parcela){
            $total += $parcela->getMovimentacao()->getValor();
        }
        return $total;
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
     * Set categoria
     *
     * @param Fnx\AdminBundle\Entity\Categoria $categoria
     */
    public function setCategoria(\Fnx\AdminBundle\Entity\Categoria $categoria)
    {
        $this->categoria = $categoria;
    }

    /**
     * Get categoria
     *
     * @return Fnx\AdminBundle\Entity\Categoria 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set primeiraParcela
     *
     * @param date $primeiraParcela
     */
    public function setPrimeiraParcela($primeiraParcela)
    {
        $this->primeiraParcela = $primeiraParcela;
    }

    /**
     * Get primeiraParcela
     *
     * @return date 
     */
    public function getPrimeiraParcela()
    {
        return $this->primeiraParcela;
    }

    /**
     * Set valor
     *
     * @param float $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Get valor
     *
     * @return float 
     */
    public function getValor()
    {
        return $this->valor;
    }
}