<?php

namespace Fnx\FinanceiroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fnx\FinanceiroBundle\Entity\FormaPagamento
 *
 * @ORM\Table(name="forma_pagamento")
 * @ORM\Entity
 */
class FormaPagamento
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
     * @var string $nome
     *
     * @ORM\Column(name="nome", type="string", length=45)
     */
    protected $nome;

    /**
     * @var boolean $validado
     *
     * @ORM\Column(name="validado", type="boolean")
     */
    protected $validado;

    /**
     * @var boolean $lembrar
     *
     * @ORM\Column(name="lembrar", type="boolean")
     */
    protected $lembrar;
    
    public function __construct() {
        $this->validado = false;
        $this->lembrar = false;
    }

    public function __toString() {
        return $this->nome;
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
}