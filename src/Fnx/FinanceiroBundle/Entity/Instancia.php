<?php

namespace Fnx\FinanceiroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Fnx\FinanceiroBundle\Entity\Instancia
 *
 * @ORM\Table(name="instancia")
 * @ORM\Entity
 */
class Instancia
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
     * @Assert\NotBlank()
     */
    protected $nome;

    /**
     * @var string $descricao
     *
     * @ORM\Column(name="descricao", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $descricao;

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
}