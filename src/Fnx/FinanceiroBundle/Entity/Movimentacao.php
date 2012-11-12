<?php

namespace Fnx\FinanceiroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Fnx\AdminBundle\Validator\Constraints as FnxAssert;

/**
 * Fnx\FinanceiroBundle\Entity\Movimentacao
 *
 * @ORM\Table(name="movimentacao")
 * @ORM\Entity(repositoryClass="Fnx\FinanceiroBundle\Entity\MovimentacaoRepository")
 * @ORM\HasLifecycleCallbacks();
 */
class Movimentacao
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
     * @var datetime $data
     *
     * @ORM\Column(name="data", type="datetime")
     */
    protected $data;

    /**
     * @var decimal $valor
     *
     * @ORM\Column(name="valor", type="decimal", precision=8, scale=2)
     * @Assert\NotBlank()
     * @FnxAssert\Dinheiro()
     */
    protected $valor;

    /**
     * @var string $movimentacao
     *
     * @ORM\Column(name="movimentacao", type="string", length=1)
     */
    protected $movimentacao;

    /**
     * @var decimal $valor_pago
     *
     * @ORM\Column(name="valor_pago", type="decimal", nullable=true, precision=8, scale=2)
     * @FnxAssert\Dinheiro(groups={"edit"})
     */
    protected $valor_pago;

    /**
     * @var datetime $data_pagamento
     *
     * @ORM\Column(name="data_pagamento", type="datetime", nullable=true)
     */
    protected $data_pagamento;

    /**
     * @var boolean $lembrar
     *
     * @ORM\Column(name="lembrar", type="boolean")
     */
    protected $lembrar;

    /**
     * @var boolean $validado
     *
     * @ORM\Column(name="validado", type="boolean")
     */
    protected $validado;
    
    /**
     *
     * @var object $parcela
     * @ORM\OneToOne(targetEntity="Parcela", inversedBy="movimentacao", cascade={"all"})
     */
    protected $parcela;
    
    /**
     *
     * @var object $formaPagamento
     * @ORM\ManyToOne(targetEntity="FormaPagamento", cascade={"persist"}, fetch="LAZY")
     * @Assert\NotBlank()
     */
    protected $formaPagamento;
    
    function __construct() {
        $this->data = new \DateTime();
        $this->lembrar = false;
        $this->validado = false;
        $this->valor_pago = 0;
    }
    
    public function atualizaCaixa(){
        $conta = $this->getParcela()->getRegistro()->getConta();
        if ($this->getParcela()->getFinalizado()){
            $this->data_pagamento = new \DateTime();

            if ($this->movimentacao == 'p' && substr(str_replace(",", ".", $this->valor),3) > $conta->getValor()){
                return false;
            }
            
            if ($this->valor_pago == 0){
                $this->setValorPago($this->valor);
            }
            
            $valorPago = substr(str_replace(",", ".", $this->valor_pago),3);
            if ($this->movimentacao == 'r'){
                $conta->deposita($valorPago);
            }else{
                $conta->saque($valorPago);
            }
        }
        
    }


    /**
     * @ORM\PrePersist @ORM\PreUpdate
     */
    public function formataDinheiro(){
        if (is_string($this->valor)){
            $this->valor = substr(str_replace(",", ".", $this->valor),3);
        }
        
        if (is_string($this->valor)){
            $this->valor_pago = substr(str_replace(",", ".", $this->valor_pago),3);
        }
    }
    
    /**
     * @ORM\PreRemove
     */
    public function removeDinheiroConta(){
        if ($this->getParcela()->getFinalizado()){
            $conta = $this->getParcela()->getRegistro()->getConta();
            if ($this->movimentacao == 'r'){
                $conta->saque($this->valor_pago);
            }else{
                $conta->deposita($this->valor_pago);
            }
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
     * Set valor
     *
     * @param decimal $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Get valor
     *
     * @return decimal 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set movimentacao
     *
     * @param string $movimentacao
     */
    public function setMovimentacao($movimentacao)
    {
        $this->movimentacao = $movimentacao;
    }

    /**
     * Get movimentacao
     *
     * @return string 
     */
    public function getMovimentacao()
    {
        return $this->movimentacao;
    }

    /**
     * Set valor_pago
     *
     * @param decimal $valorPago
     */
    public function setValorPago($valorPago)
    {
        $this->valor_pago = $valorPago;
    }

    /**
     * Get valor_pago
     *
     * @return decimal 
     */
    public function getValorPago()
    {
        return $this->valor_pago;
    }

    /**
     * Set data_pagamento
     *
     * @param datetime $dataPagamento
     */
    public function setDataPagamento($dataPagamento)
    {
        $this->data_pagamento = $dataPagamento;
    }

    /**
     * Get data_pagamento
     *
     * @return datetime 
     */
    public function getDataPagamento()
    {
        return $this->data_pagamento;
    }

    /**
     * Set lembrar
     *
     * @param boolean $lembrar
     */
    public function setLembrar($lembrar)
    {
        $this->lembrar = $lembrar;
    }

    /**
     * Get lembrar
     *
     * @return boolean 
     */
    public function getLembrar()
    {
        return $this->lembrar;
    }

    /**
     * Set validado
     *
     * @param boolean $validado
     */
    public function setValidado($validado)
    {
        $this->validado = $validado;
    }

    /**
     * Get validado
     *
     * @return boolean 
     */
    public function getValidado()
    {
        return $this->validado;
    }

    /**
     * Set parcela
     *
     * @param Fnx\FinanceiroBundle\Entity\Parcela $parcela
     */
    public function setParcela(\Fnx\FinanceiroBundle\Entity\Parcela $parcela)
    {
        $this->parcela = $parcela;
    }

    /**
     * Get parcela
     *
     * @return Fnx\FinanceiroBundle\Entity\Parcela 
     */
    public function getParcela()
    {
        return $this->parcela;
    }

    /**
     * Set formaPagamento
     *
     * @param Fnx\FinanceiroBundle\Entity\FormaPagamento $formaPagamento
     */
    public function setFormaPagamento(\Fnx\FinanceiroBundle\Entity\FormaPagamento $formaPagamento)
    {
        $this->formaPagamento = $formaPagamento;
    }

    /**
     * Get formaPagamento
     *
     * @return Fnx\FinanceiroBundle\Entity\FormaPagamento 
     */
    public function getFormaPagamento()
    {
        return $this->formaPagamento;
    }
}