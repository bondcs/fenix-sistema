<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Fnx\AdminBundle\Entity\Fornecedor
 *
 * @ORM\Table(name="fornecedor")
 * @ORM\Entity
 */
class Fornecedor
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
     * @var string $telefone
     *
     * @ORM\Column(name="telefone", type="string", length=45)
     * @Assert\NotBlank()
     */
    protected $telefone;
    
    /**
     * @var string $cep
     *
     * @ORM\Column(name="cep", type="string", length=9, nullable=true)
     * @Assert\MinLength(8)
     */
    protected $cep;
    
     /**
     * @var objeto $cidade
     * 
     * @ORM\ManyToOne(targetEntity="Cidade")
     * @ORM\JoinColumn(name="cidade_id", referencedColumnName="id") 
     */
    protected $cidade;

    /**
     * @var string $bairro
     *
     * @ORM\Column(name="bairro", type="string", length=80, nullable=true)
     */
    protected $bairro;

    /**
     * @var string $rua
     *
     * @ORM\Column(name="rua", type="string", length=80, nullable=true)
     */
    protected $rua;

    /**
     * @var string $numero
     *
     * @ORM\Column(name="numero", type="string", length=10, nullable=true)
     */
    protected $numero;

    /**
     * @var string $complemento
     *
     * @ORM\Column(name="complemento", type="string", length=80, nullable=true)
     */
    protected $complemento;


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
     * Set telefone
     *
     * @param string $telefone
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    /**
     * Get telefone
     *
     * @return string 
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * Set bairro
     *
     * @param string $bairro
     */
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
    }

    /**
     * Get bairro
     *
     * @return string 
     */
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * Set rua
     *
     * @param string $rua
     */
    public function setRua($rua)
    {
        $this->rua = $rua;
    }

    /**
     * Get rua
     *
     * @return string 
     */
    public function getRua()
    {
        return $this->rua;
    }

    /**
     * Set numero
     *
     * @param string $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set complemento
     *
     * @param string $complemento
     */
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
    }

    /**
     * Get complemento
     *
     * @return string 
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * Set cep
     *
     * @param string $cep
     */
    public function setCep($cep)
    {
        $this->cep = $cep;
    }

    /**
     * Get cep
     *
     * @return string 
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * Set cidade
     *
     * @param Fnx\AdminBundle\Entity\Cidade $cidade
     */
    public function setCidade(\Fnx\AdminBundle\Entity\Cidade $cidade)
    {
        $this->cidade = $cidade;
    }

    /**
     * Get cidade
     *
     * @return Fnx\AdminBundle\Entity\Cidade 
     */
    public function getCidade()
    {
        return $this->cidade;
    }
}