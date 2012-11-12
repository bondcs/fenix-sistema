<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fnx\FinanceiroBundle\Entity\Conta;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Fnx\AdminBundle\Entity\Configuracao
 *
 * @ORM\Table(name="configuracao")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Configuracao
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
     * @var float $valorDependente
     *
     * @ORM\Column(name="valorDependente", type="float")
     * @Assert\NotBlank()
     */
    protected $valorDependente;
    
    /**
     *
     * @var object $contaSalario
     * 
     * @ORM\OneToOne(targetEntity="Fnx\FinanceiroBundle\Entity\Conta")
     * @ORM\JoinColumn(name="conta_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $contaSalario;
    
    /**
     *
     * @var object $contaSalario
     * 
     * @ORM\OneToOne(targetEntity="Fnx\FinanceiroBundle\Entity\FormaPagamento")
     * @ORM\JoinColumn(name="forma_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $formaPagamentoSalario;
    
    /**
     * @ORM\PrePersist @ORM\PreUpdate
     */
    public function formataDinheiro(){
        $this->valorDependente = is_string($this->valorDependente) ? substr(str_replace(",", ".", $this->valorDependente),3) : $this->valorDependente;
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
     * Set valorDependente
     *
     * @param float $valorDependente
     */
    public function setValorDependente($valorDependente)
    {
        $this->valorDependente = $valorDependente;
    }

    /**
     * Get valorDependente
     *
     * @return float 
     */
    public function getValorDependente()
    {
        return $this->valorDependente;
    }


    /**
     * Set contaSalario
     *
     * @param Fnx\FinanceiroBundle\Entity\Conta $contaSalario
     */
    public function setContaSalario(\Fnx\FinanceiroBundle\Entity\Conta $contaSalario)
    {
        $this->contaSalario = $contaSalario;
    }

    /**
     * Get contaSalario
     *
     * @return Fnx\AdminBundle\Entity\Conta 
     */
    public function getContaSalario()
    {
        return $this->contaSalario;
    }

    /**
     * Set formaPagamentoSalario
     *
     * @param Fnx\FinanceiroBundle\Entity\FormaPagamento $formaPagamentoSalario
     */
    public function setFormaPagamentoSalario(\Fnx\FinanceiroBundle\Entity\FormaPagamento $formaPagamentoSalario)
    {
        $this->formaPagamentoSalario = $formaPagamentoSalario;
    }

    /**
     * Get formaPagamentoSalario
     *
     * @return Fnx\FinanceiroBundle\Entity\FormaPagamento 
     */
    public function getFormaPagamentoSalario()
    {
        return $this->formaPagamentoSalario;
    }
}