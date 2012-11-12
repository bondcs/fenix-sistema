<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Fnx\AdminBundle\Entity\Categoria
 *
 * @ORM\Table(name="categoria")
 * @ORM\Entity(repositoryClass="Fnx\AdminBundle\Entity\CategoriaRepository")
 */
class Categoria
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
     * @ORM\Column(name="nome", type="string", length=50)
     * @Assert\NotBlank()
     */
    protected $nome;

    /**
     * @var string $descricao
     *
     * @ORM\Column(name="descricao", type="string", length=255)
     * 
     * @Assert\NotBlank()
     */
    protected $descricao;
    
    /**
     *  
     * @var boolean $editavel
     * @ORM\Column(name="editavel", type="boolean")
     */
    protected $editavel;

    public function __construct() {
        $this->editavel = true;
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
    public function getDescricao($length = null)
    {
        if ($length != null && $length > 0){
            return substr($this->descricao, 0, $length);
        }
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
}