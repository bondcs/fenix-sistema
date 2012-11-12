<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Fnx\AdminBundle\Validator\Constraints as FnxAssert;

/**
 * Fnx\AdminBundle\Entity\Propriedade
 *
 * @ORM\Table(name="propriedade")
 * @ORM\Entity(repositoryClass="Fnx\AdminBundle\Entity\PropriedadeRepository")
 */
class Propriedade
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
     * @var float $quantidade
     *
     * @ORM\Column(name="quantidade", type="float")
     * @Assert\NotBlank()
     * @FnxAssert\ApenasNumero()
     */
    protected $quantidade;

    /**
     * @var string $descricao
     *
     * @ORM\Column(name="descricao", type="string", length=255, nullable=true)
     */
    protected $descricao;
    
    /**
     *
     * @var Objetc $atividade
     * 
     * @ORM\ManyToOne(targetEntity="Atividade", inversedBy="escalas", cascade={"persist"}, fetch="LAZY")
     */
    protected $atividade;
    
    /**
     * @var boolean $checado
     * 
     * @ORM\Column(name="checado", type="boolean")
     */
    protected $checado;

    public function __construct() {
        $this->checado = false;
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
     * Set quantidade
     *
     * @param float $quantidade
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    /**
     * Get quantidade
     *
     * @return float 
     */
    public function getQuantidade()
    {
        return $this->quantidade;
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
     * Set checado
     *
     * @param boolean $checado
     */
    public function setChecado($checado)
    {
        $this->checado = $checado;
    }

    /**
     * Get checado
     *
     * @return boolean 
     */
    public function getChecado()
    {
        return $this->checado;
    }
}