<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Fnx\AdminBundle\Entity\ServicoEscala
 *
 * @ORM\Table(name="servicoEscala")
 * @ORM\Entity(repositoryClass="Fnx\AdminBundle\Entity\ServicoEscalaRepository")
 */
class ServicoEscala
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
     * @ORM\Column(name="nome", type="string", length=80)
     * @Assert\NotBlank()
     */
    private $nome;

    /**
     * @var string $descricao
     *
     * @ORM\Column(name="descricao", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $descricao;
    
    /**
     * @var string $cor
     *
     * @ORM\Column(name="cor", type="string", length=20)
     * @Assert\NotBlank()
     */
    private $cor;
    
    /**
     *  
     * @var boolean $editavel
     * @ORM\Column(name="editavel", type="boolean")
     */
    private $editavel;

    public function __construct() {
        return $this->editavel = false;
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
     * Set editavel
     *
     * @param boolean $editavel
     */
    public function setEditavel($editavel)
    {
        $this->editavel = $editavel;
    }

    /**
     * Get editavel
     *
     * @return boolean 
     */
    public function getEditavel()
    {
        return $this->editavel;
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