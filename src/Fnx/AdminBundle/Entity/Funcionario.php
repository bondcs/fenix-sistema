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
     * @ORM\Column(name="nome", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nome;
    
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
     * @ORM\ManyToOne(targetEntity="TipoFun", fetch="LAZY")
     * 
     * @Assert\NotBlank()
     */
    private $tipo;
    
    /**
     *
     * @ORM\Column(type="time", nullable=true)
     */
    private $escalaDiariaInicio;
    
    /**
     *
     * @ORM\Column(type="time", nullable=true)
     */
    private $escalaDiariaFinal;
    
    /**
     * Escalas excepcionais.
     * @ORM\OneToMany(targetEntity="EscalaFun", mappedBy="funcionario", cascade={"persist, remove"}, orphanRemoval=true)
     */
    private $escalasEx;
    
    public function __construct() {
        
        $this->escalas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->escalasEx = new \Doctrine\Common\Collections\ArrayCollection();
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
        
        switch ($this->tipo->getNome()){
            case "FreeLancer":
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
     * @param Fnx\AdminBundle\Entity\TipoFun $tipo
     */
    public function setTipo(\Fnx\AdminBundle\Entity\TipoFun $tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Get tipo
     *
     * @return Fnx\AdminBundle\Entity\TipoFun 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set escalaDiariaInicio
     *
     * @param time $escalaDiariaInicio
     */
    public function setEscalaDiariaInicio($escalaDiariaInicio)
    {
        $this->escalaDiariaInicio = $escalaDiariaInicio;
    }

    /**
     * Get escalaDiariaInicio
     *
     * @return time 
     */
    public function getEscalaDiariaInicio()
    {
        return $this->escalaDiariaInicio;
    }

    /**
     * Set escalaDiariaFinal
     *
     * @param time $escalaDiariaFinal
     */
    public function setEscalaDiariaFinal($escalaDiariaFinal)
    {
        $this->escalaDiariaFinal = $escalaDiariaFinal;
    }

    /**
     * Get escalaDiariaFinal
     *
     * @return time 
     */
    public function getEscalaDiariaFinal()
    {
        return $this->escalaDiariaFinal;
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
}