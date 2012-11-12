<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fnx\AdminBundle\Entity\Endereco;
use Fnx\AdminBundle\Entity\Galeria;
use Symfony\Component\Validator\Constraints as Assert;
use Fnx\AdminBundle\Validator\Constraints as FnxAssert;

/**
 * Fnx\AdminBundle\Entity\Local
 *
 * @ORM\Table(name="local")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks();
 */
class Local
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
     * @var string $bairro
     *
     * @ORM\Column(name="descricao", type="string", length=45, nullable=true)
     */
    protected $descricao;
    
    /**
     * @var objeto $cidade
     * 
     * @ORM\ManyToOne(targetEntity="Cidade")
     * @ORM\JoinColumn(name="cidade_id", referencedColumnName="id") 
     * @Assert\NotBlank()
     */
    protected $cidade;

    /**
     * @var string $bairro
     *
     * @ORM\Column(name="bairro", type="string", length=45)
     * @Assert\NotBlank()
     */
    protected $bairro;

    /**
     * @var string $rua
     *
     * @ORM\Column(name="rua", type="string", length=80)
     * @Assert\NotBlank()
     */
    protected $rua;

    /**
     * @var integer $numero
     *
     * @ORM\Column(name="numero", type="string", length=20, nullable=true)
     */
    protected $numero;

    /**
     * @var string $complemento
     *
     * @ORM\Column(name="complemento", type="string", length=80, nullable=true)
     */
    protected $complemento;
    
    /**
     *
     * @var Objetc $atividade
     * 
     * @ORM\ManyToOne(targetEntity="Atividade", inversedBy="escalas", cascade={"persist"}, fetch="LAZY")
     */
    protected $atividade;

    /**
     * @var decimal $custo
     *
     * @ORM\Column(name="custo", type="float")
     * @Assert\NotBlank()
     * @FnxAssert\Dinheiro()
     */
    protected $custo;

    
    /**
     * @ORM\PrePersist @ORM\PreUpdate
     */
    public function formataDinheiro(){
        $this->custo = substr(str_replace(",", ".", $this->custo),3);
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
     * Set custo
     *
     * @param decimal $custo
     */
    public function setCusto($custo)
    {
        $this->custo = $custo;
    }

    /**
     * Get custo
     *
     * @return decimal 
     */
    public function getCusto()
    {
        return $this->custo;
    }

    /**
     * Set endereco
     *
     * @param Fnx\AdminBundle\Entity\Endereco $endereco
     */
    public function setEndereco(\Fnx\AdminBundle\Entity\Endereco $endereco)
    {
        $this->endereco = $endereco;
    }

    /**
     * Get endereco
     *
     * @return Fnx\AdminBundle\Entity\Endereco 
     */
    public function getEndereco()
    {
        return $this->endereco;
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