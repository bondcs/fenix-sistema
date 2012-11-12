<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fnx\AdminBundle\Entity\Cliente;
use Fnx\AdminBundle\Entity\Usuario;
use Symfony\Component\Validator\Constraints as Assert;
use Fnx\AdminBundle\Validator\Constraints as FnxAssert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * Fnx\AdminBundle\Entity\Responsavel
 *
 * @ORM\Table(name="responsavel")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Fnx\AdminBundle\Entity\ResponsavelRepository")
 * @Assert\Callback(methods={"addGroup"})
 * 
 */
class Responsavel
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
     * 
     * @Assert\NotBlank(groups={"juridico","register"})
     * @Assert\MinLength(limit=5,groups={"juridico","register"})
     */
    protected $nome;

    /**
     * @var string $telefone
     *
     * @ORM\Column(name="telefone", type="string", length=14)
     * @Assert\NotBlank(groups={"juridico", "register"})
     * @Assert\MaxLength(limit=14,groups={"juridico","register"})
     */
    protected $telefone;
    
    /**
     * @var string $cpf
     * 
     * @ORM\Column(name="cpf", type="string", length="15", nullable=true)
     * 
     */
    protected $cpf;
    
    /**
     * @var objeto $cliente
     * 
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="responsaveis")
     * @ORM\JoinColumn(name="cliente_id", referencedColumnName="id")
     */
    protected $cliente;
    
    /**
     * @var objeto $usuario
     * 
     * @ORM\OneToOne(targetEntity="Usuario", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $usuario;
    
    /**
     * @var boolean principal
     * 
     * @ORM\Column(name="principal", type="boolean")
     * 
     */
    protected $principal;
    
    
    
    public function __construct() {
        $this->principal = false;
    }

    

    public function addGroup(ExecutionContext $ec){
          
          if ($this->cliente->getPessoa() == "j"){
            $ec->getGraphWalker()->walkReference($this, 'juridico', $ec->getPropertyPath(), true);
          }
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
     * Set cliente
     *
     * @param Fnx\AdminBundle\Entity\Cliente $cliente
     */
    public function setCliente(\Fnx\AdminBundle\Entity\Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    /**
     * Get cliente
     *
     * @return Fnx\AdminBundle\Entity\Cliente 
     */
    public function getCliente()
    {
        return $this->cliente;
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
     * Set principal
     *
     * @param boolean $principal
     */
    public function setPrincipal($principal)
    {
        $this->principal = $principal;
    }

    /**
     * Get principal
     *
     * @return boolean 
     */
    public function getPrincipal()
    {
        return $this->principal;
    }
    
    /**
     * Is principal
     *
     * @return boolean 
     */
    public function isPrincipal(){
        
        if ($this->principal){
            return true;
        }else{
            return false;
        }
    }
}