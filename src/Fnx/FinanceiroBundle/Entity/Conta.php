<?php

namespace Fnx\FinanceiroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Fnx\FinanceiroBundle\Entity\Conta
 *
 * @ORM\Table(name="conta")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Conta
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
     */
    protected $nome;

    /**
     * @var string $descricao
     *
     * @ORM\Column(name="descricao", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $descricao;

    /**
     * @var decimal $valor
     *
     * @ORM\Column(name="valor", type="decimal")
     */
    protected $valor;
    
    /**
     *
     * @var object $instancia
     * @ORM\ManyToOne(targetEntity="Instancia", cascade={"persist"}, fetch="LAZY")
     * @Assert\NotBlank()
     */
    protected $instancia;
    
    /**
     *
     * @var array collection $registros
     * @ORM\OneToMany(targetEntity="Registro", mappedBy="conta", cascade={"all"}, orphanRemoval=true)
     */
    protected $registros;
    
    /**
     * @ORM\PrePersist @ORM\PreUpdate
     */
    public function formataDinheiro(){
        
        if (is_string($this->valor)){
            $this->valor = substr(str_replace(",", ".", $this->valor),3);
        }
    }
    
    public function __construct() {
        $this->registros = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->nome;
    }
    
    public function deposita($valor){
        $this->valor += $valor;
        
    }
    
    public function saque($valor){
        $this->valor -= $valor;
        
    }
    
    public function getValorFuturo(){
        $total = 0;
        foreach ($this->getRegistros() as $registro){
            foreach ($registro->getParcelas() as $parcela){
                if($parcela->getMovimentacao() != null){
                    $total += $parcela->getMovimentacao()->getValor();
                }
            }
        }
        return $total;
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

    /**
     * Set valor
     *
     * @param decimal $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Get valor
     *
     * @return decimal 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set instancia
     *
     * @param Fnx\FinanceiroBundle\Entity\Instancia $instancia
     */
    public function setInstancia(\Fnx\FinanceiroBundle\Entity\Instancia $instancia)
    {
        $this->instancia = $instancia;
    }

    /**
     * Get instancia
     *
     * @return Fnx\FinanceiroBundle\Entity\Instancia 
     */
    public function getInstancia()
    {
        return $this->instancia;
    }

    /**
     * Add registros
     *
     * @param Fnx\FinanceiroBundle\Entity\Registro $registros
     */
    public function addRegistro(\Fnx\FinanceiroBundle\Entity\Registro $registros)
    {
        $this->registros[] = $registros;
    }

    /**
     * Get registros
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRegistros()
    {
        return $this->registros;
    }
}