<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\FinanceiroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Transferencia
 *
 * @author bondcs
 * @ORM\Table(name="transferencia")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity()
 */
class Transferencia {
    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var decimal $valor
     *
     * @ORM\Column(name="valor", type="decimal")
     * @Assert\NotBlank()
     */
    protected $valor;
    
    /**
     *
     * @var object $conta
     * @ORM\ManyToOne(targetEntity="Conta", cascade={"persist"}, fetch="LAZY")
     * @Assert\NotBlank()
     */
    protected $contaSaque;
    
    /**
     *
     * @var object $conta
     * @ORM\ManyToOne(targetEntity="Conta", cascade={"persist"}, fetch="LAZY")
     * @Assert\NotBlank()
     */
    protected $contaRecebimento;
    
    /**
     * @ORM\PrePersist @ORM\PreUpdate
     */
    public function formataDinheiroDb(){
        if (is_string($this->valor)){
            $this->valor = substr(str_replace(",", ".", $this->valor),3);
        }
        
    }
    
    public function ehIgual(){
        
        if (is_object($this->contaSaque) & is_object($this->contaRecebimento)){
            if($this->contaSaque->getId() == $this->contaRecebimento->getId()){
                return true;
            }
        }
    }
    
    public function efetuaTransferencia(){
        $this->contaSaque->saque(substr(str_replace(",", ".", $this->valor),3));
        $this->contaRecebimento->deposita(substr(str_replace(",", ".", $this->valor),3));  
    }
    
    public function desfazTransferencia(){
        $this->contaSaque->deposita($this->valor);
        $this->contaRecebimento->saque($this->valor);  
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
     * Set contaSaque
     *
     * @param object $contaSaque
     */
    public function setContaSaque($contaSaque)
    {
        $this->contaSaque = $contaSaque;
    }

    /**
     * Get contaSaque
     *
     * @return object 
     */
    public function getContaSaque()
    {
        return $this->contaSaque;
    }
    
    /**
     * Set contaRecebimento
     *
     * @param object $contaRecebimento
     */
    public function setContaRecebimento($contaRecebimento)
    {
        $this->contaRecebimento = $contaRecebimento;
    }

    /**
     * Get contaRecebimento
     *
     * @return object 
     */
    public function getContaRecebimento()
    {
        return $this->contaRecebimento;
    }
}

?>
