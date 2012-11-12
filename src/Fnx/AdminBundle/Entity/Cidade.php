<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fnx\AdminBundle\Entity\Estado;

/**
 * Fnx\AdminBundle\Entity\Cidade
 *
 * @ORM\Table(name="cidade")
 * @ORM\Entity(repositoryClass="Fnx\AdminBundle\Entity\CidadeRepository")
 */
class Cidade
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
     * @ORM\Column(name="nome", type="string", length=255)
     */
    protected $nome;
    
    /**
     * @var objeto $estado
     * 
     * @ORM\ManyToOne(targetEntity="Estado")
     * @ORM\JoinColumn(name="estado", referencedColumnName="id") 
     */
    protected $estado;


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
     * Set estado
     *
     * @param Fnx\AdminBundle\Entity\Estado $estado
     */
    public function setEstado(\Fnx\AdminBundle\Entity\Estado $estado)
    {
        $this->estado = $estado;
    }

    /**
     * Get estado
     *
     * @return Fnx\AdminBundle\Entity\Estado 
     */
    public function getEstado()
    {
        return $this->estado;
    }
}