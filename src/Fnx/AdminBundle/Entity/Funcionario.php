<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Fnx\AdminBundle\Validator\Constraints as FnxAssert;
use Fnx\AdminBundle\Entity\Escala;

/**
 * Fnx\AdminBundle\Entity\Funcionario
 *
 * @ORM\Table(name="funcionario")
 * @ORM\Entity(repositoryClass="Fnx\AdminBundle\Entity\FuncionarioRepository")
 * @ORM\HasLifecycleCallbacks();
 */
class Funcionario
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
     * @ORM\Column(name="nome", type="string", length=45)
     * @Assert\NotBlank()
     */
    private $nome;
    
    /**
     * @var string $cpf
     *
     * @ORM\Column(name="cpf", type="string", length=45, nullable=true)
     */
    private $cpf;
    
    /**
     * @var string $rg
     *
     * @ORM\Column(name="rg", type="string", length=45, nullable=true)
     * @FnxAssert\ApenasNumero()
     */
    private $rg;
    
    /**
     * @var string $nome
     *
     * @ORM\Column(name="dependentes", type="integer")
     * @FnxAssert\ApenasNumero()
     */
    private $dependentes;
    
    /**
     *
     * @var array collection $servicos
     * 
     * @ORM\ManyToMany(targetEntity="ServicoEscala")
     */
    private $servicos;


    /**
     * @var object Usuario
     * 
     * @ORM\OneToOne(targetEntity="Fnx\AdminBundle\Entity\Usuario", cascade={"persist"})
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id", onDelete="SET NULL")   
     */
    private $usuario;

    /**
     * @var string $telefone
     *
     * @ORM\Column(name="telefone", type="string", length=14)
     * @Assert\NotBlank()
     */
    private $telefone;
    
    /**
     *
     * @ORM\ManyToMany(targetEntity="Escala", mappedBy="funcionarios", cascade={"persist"})
     * @ORM\JoinTable(name="escala_funcionario",
     *     joinColumns={@ORM\JoinColumn(name="escala_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="funcionario_id", referencedColumnName="id")}
     * )
     * 
     */
    private $escalas;
    
    /**
     *
     * @var type object
     * @ORM\Column(name="tipo", type="string", length=5)
     * 
     */
    private $tipo;
       
    /**
     * Escalas excepcionais.
     * @ORM\ManyToMany(targetEntity="EscalaFun", mappedBy="funcionarios", cascade={"persist"})
     */
    private $escalasEx;
    
    /**
     *
     * @var object $salario
     * @ORM\OneToOne(targetEntity="Salario", inversedBy="funcionario", cascade={"all"})
     */
    private $salario;
    
    /**
     * 
     * @var object $registro
     * @ORM\Column(name="registro", type="datetime")
     */
    private $registro;
    
    public function __construct() {
        
        $this->escalas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->escalasEx = new \Doctrine\Common\Collections\ArrayCollection();
        $this->servicos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->registro = new \DateTime();
        $this->dependentes = 0;
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
     * Set usuario
     *
     * @param Fnx\AdminBundle\Entity\Usuario $usuario
     */
    public function setUsuario(\Fnx\AdminBundle\Entity\Usuario $usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Get usuario
     *
     * @return Fnx\AdminBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
    
    public function __toString() {
        
        switch ($this->tipo){
            case "free":
                return $this->nome." (FreeLancer)";
                break;
            
            default:
                return $this->nome;
                
        }
       
    }

    /**
     * Add escalas
     *
     * @param Fnx\AdminBundle\Entity\Escala $escalas
     */
    public function addEscala(\Fnx\AdminBundle\Entity\Escala $escalas)
    {
        $this->escalas[] = $escalas;
    }

    /**
     * Get escalas
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getEscalas()
    {
        return $this->escalas;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }
 

    /**
     * Add escalasEx
     *
     * @param Fnx\AdminBundle\Entity\EscalaFun $escalasEx
     */
    public function addEscalaFun(\Fnx\AdminBundle\Entity\EscalaFun $escalasEx)
    {
        $this->escalasEx[] = $escalasEx;
    }

    /**
     * Get escalasEx
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getEscalasEx()
    {
        return $this->escalasEx;
    }

    /**
     * Set cpf
     *
     * @param string $cpf
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    /**
     * Get cpf
     *
     * @return string 
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * Set rg
     *
     * @param string $rg
     */
    public function setRg($rg)
    {
        $this->rg = $rg;
    }

    /**
     * Get rg
     *
     * @return string 
     */
    public function getRg()
    {
        return $this->rg;
    }

    /**
     * Set dependentes
     *
     * @param integer $dependentes
     */
    public function setDependentes($dependentes)
    {
        $this->dependentes = $dependentes;
    }

    /**
     * Get dependentes
     *
     * @return integer 
     */
    public function getDependentes()
    {
        return $this->dependentes;
    }

    /**
     * Add categorias
     *
     * @param Fnx\AdminBundle\Entity\Categoria $categorias
     */
    public function addCategoria(\Fnx\AdminBundle\Entity\Categoria $categorias)
    {
        $this->categorias[] = $categorias;
    }

    /**
     * Get categorias
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCategorias()
    {
        return $this->categorias;
    }


    /**
     * Set salario
     *
     * @param Fnx\AdminBundle\Entity\Salario $salario
     */
    public function setSalario(\Fnx\AdminBundle\Entity\Salario $salario)
    {
        $this->salario = $salario;
    }

    /**
     * Get salario
     *
     * @return Fnx\AdminBundle\Entity\Salario 
     */
    public function getSalario()
    {
        return $this->salario;
    }

    /**
     * Set registro
     *
     * @param datetime $registro
     */
    public function setRegistro($registro)
    {
        $this->registro = $registro;
    }

    /**
     * Get registro
     *
     * @return datetime 
     */
    public function getRegistro()
    {
        return $this->registro;
    }

    /**
     * Add servicos
     *
     * @param Fnx\AdminBundle\Entity\ServicoEscala $servicos
     */
    public function addServicoEscala(\Fnx\AdminBundle\Entity\ServicoEscala $servicos)
    {
        $this->servicos[] = $servicos;
    }

    /**
     * Get servicos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getServicos()
    {
        return $this->servicos;
    }
}