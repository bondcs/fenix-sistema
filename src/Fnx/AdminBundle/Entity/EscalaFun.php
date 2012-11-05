<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Fnx\AdminBundle\Entity\EscalaFun
 *
 * @ORM\Table(name="escalaFun")
 * @ORM\Entity(repositoryClass="Fnx\AdminBundle\Entity\EscalaFunRepository")
 */
class EscalaFun
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
     * @var datetime $inicio
     *
     * @ORM\Column(name="inicio", type="datetime")
     * @Assert\NotBlank()
     */
    private $inicio;

    /**
     * @var datetime $fim
     *
     * @ORM\Column(name="fim", type="datetime")
     * @Assert\NotBlank()
     */
    private $fim;

    /**
     * @var string $descricao
     *
     * @ORM\Column(name="descricao", type="string", length=80)
     * @Assert\NotBlank()
     * @Assert\MaxLength(60)
     */
    private $descricao;
    
    /**
     * @var string $local
     *
     * @ORM\Column(name="local", type="string", length=80)
     * @Assert\NotBlank(groups={"registration"})
     */
    private $local;
    
    /**
     *
     * @var object $categoria
     * 
     * @ORM\ManyToOne(targetEntity="ServicoEscala", cascade={"persist"})
     * @Assert\NotBlank(groups={"registration"})
     */
    private $servicoEscala;
    
    /**
     *
     * @ORM\ManyToMany(targetEntity="Funcionario", inversedBy="escalasEx", cascade={"persist"}, fetch="LAZY")
     * @Assert\NotBlank()
     * @ORM\OrderBy({"nome" = "ASC"})
     */
    private $funcionarios;
    
    /**
     * @var boolean $ativo
     *
     * @ORM\Column(name="ativo", type="boolean")
     */
    private $ativo;
    
    public function atualizarEscalaFim(){
        $diferença = $this->getInicio()->diff($this->getFim());
        $this->setFim($diferença->h);
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
     * Set inicio
     *
     * @param datetime $inicio
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
    }

    /**
     * Get inicio
     *
     * @return datetime 
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Set fim
     *
     * @param datetime $fim
     */
    public function setFim($fim)
    {
        $this->fim = $fim;
    }

    /**
     * Get fim
     *
     * @return datetime 
     */
    public function getFim()
    {
        return $this->fim;
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

    public function __construct()
    {
        $this->funcionarios = new \Doctrine\Common\Collections\ArrayCollection();
        return $this->ativo = true;
    }
    
    public function setFuncionarios($funcionarios){
        $this->funcionarios = $funcionarios;
    }

        /**
     * Add funcionarios
     *
     * @param Fnx\AdminBundle\Entity\Funcionario $funcionarios
     */
    public function addFuncionario(\Fnx\AdminBundle\Entity\Funcionario $funcionarios)
    {
        $this->funcionarios[] = $funcionarios;
    }

    /**
     * Get funcionarios
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFuncionarios()
    {
        return $this->funcionarios;
    }

    /**
     * Set ativo
     *
     * @param boolean $ativo
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
    }

    /**
     * Get ativo
     *
     * @return boolean 
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Set local
     *
     * @param string $local
     */
    public function setLocal($local)
    {
        $this->local = $local;
    }

    /**
     * Get local
     *
     * @return string 
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * Set servicoEscala
     *
     * @param Fnx\AdminBundle\Entity\ServicoEscala $servicoEscala
     */
    public function setServicoEscala(\Fnx\AdminBundle\Entity\ServicoEscala $servicoEscala)
    {
        $this->servicoEscala = $servicoEscala;
    }

    /**
     * Get servicoEscala
     *
     * @return Fnx\AdminBundle\Entity\ServicoEscala 
     */
    public function getServicoEscala()
    {
        return $this->servicoEscala;
    }
}