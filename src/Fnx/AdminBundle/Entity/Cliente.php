<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fnx\AdminBundle\Entity\Cidade;
use Doctrine\Common\Collections\ArrayCollection;
use Fnx\AdminBundle\Entity\Responsavel;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;
use Fnx\AdminBundle\Validator\Constraints as FnxAssert;
use Fnx\PedidoBundle\Entity\Pedido;

/**
 * Fnx\AdminBundle\Entity\Cliente
 *
 * @ORM\Table(name="cliente")
 * @ORM\Entity(repositoryClass="Fnx\AdminBundle\Entity\ClienteRepository")
 * @Assert\Callback(methods={"validaPessoa"})
 */
class Cliente
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
     * @Assert\MinLength(5)
     */
    protected $nome;

    /**
     * @var string $telefone
     *
     * @ORM\Column(name="telefone", type="string", length=14)
     * @Assert\NotBlank()
     */
    protected $telefone;

    /**
     * @var string $cnpj
     *
     * @ORM\Column(name="cnpj", type="string", length=18, nullable=true)
     * @Assert\NotBlank(groups="juridico")
     * @Assert\MinLength(limit=14,groups="juridico")
     */
    protected $cnpj;

    /**
     * @var string $cnpj
     *
     * @ORM\Column(name="cpf", type="string", length=15, nullable=true)
     * @Assert\NotBlank(groups="fisico")
     * @Assert\MinLength(limit=11,groups="fisico")
     */
    protected $cpf;

    /**
     * @var string $cep
     *
     * @ORM\Column(name="cep", type="string", length=9, nullable=true)
     * @Assert\MinLength(8)
     */
    protected $cep;

    /**
     * @var string $cep
     *
     * @ORM\Column(name="descricao", type="string", length=150, nullable=true)
     * @Assert\Regex(pattern="/[[:alnum:]]{0,}/",message="a descricao deve conter apenas letras e numeros")
     */
    protected $descricao;

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
     * @ORM\Column(name="bairro", type="string", length=45, nullable=true)
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
     * @var string $pessoa
     *
     * @ORM\Column(name="pessoa", type="string")
     */
    protected $pessoa;

    /**
     * @var ArrayCollection $responsaveis
     *
     * @ORM\OneToMany(targetEntity="Responsavel", mappedBy="cliente", cascade={"persist", "remove"})
     *
     */
    protected $responsaveis;
    /**
     * @var ArrayCollection $contratos
     *
     * @ORM\OneToMany(targetEntity="Contrato", mappedBy="cliente", cascade={"remove"})
     *
     */
    protected $contratos;

    /**
     * @var ArrayCollection $contratos
     *
     * @ORM\OneToMany(targetEntity="\Fnx\PedidoBundle\Entity\Pedido", mappedBy="cliente", cascade={"persist"})
     *
     */
    protected $pedidos;

    public function __construct() {
        $this->responsaveis = new ArrayCollection();
        $this->contratos = new ArrayCollection();
	$this->pedidos = new ArrayCollection();
    }


    public function validaPessoa(ExecutionContext $ec){

        if ($this->ehJuridica()) {
          $ec->getGraphWalker()->walkReference($this, 'juridico', $ec->getPropertyPath(), true);
        }else{
          $ec->getGraphWalker()->walkReference($this, 'fisico', $ec->getPropertyPath(), true);
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

    public function setId($id) {
	$this->id = $id;
    }

    public function setResponsaveis(ArrayCollection $responsaveis) {
	$this->responsaveis = $responsaveis;
    }

    public function setContratos(ArrayCollection $contratos) {
	$this->contratos = $contratos;
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
     * Set cnpj
     *
     * @param string $cnpj
     */
    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;
    }

    /**
     * Get cnpj
     *
     * @return string
     */
    public function getCnpj()
    {
        return $this->cnpj;
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
     * Add responsaveis
     *
     * @param Fnx\AdminBundle\Entity\Responsavel $responsaveis
     */
    public function addResponsavel(\Fnx\AdminBundle\Entity\Responsavel $responsaveis)
    {
        $this->responsaveis[] = $responsaveis;
    }

    /**
     * Get responsaveis
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getResponsaveis()
    {
        return $this->responsaveis;
    }

    /**
     * Set cpf
     *
     * @param string $cpf
     */
    public function setCpf($cpf){
        $this->cpf = $cpf;
    }

    /**
     * Get cpf
     *
     * @return string
     */
    public function getCpf(){
        return $this->cpf;
    }

    /**
     * Set pessoa
     *
     * @param string $pessoa
     */
    public function setPessoa($pessoa){
        $this->pessoa = $pessoa;
    }

    /**
     * Get pessoa
     *
     * @return boolean
     */
    public function getPessoa(){
        return $this->pessoa;
    }

    public function getDescricao() {
	return $this->descricao;
    }

    public function setDescricao($descricao) {
	$this->descricao = $descricao;
    }

    /**
     * Add contratos
     *
     * @param Fnx\AdminBundle\Entity\Contrato $contratos
     */
    public function addContrato(\Fnx\AdminBundle\Entity\Contrato $contratos){
        $this->contratos[] = $contratos;
    }

    /**
     * Get contratos
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getContratos(){
        return $this->contratos;
    }

    public function getPedidos() {
	return $this->pedidos;
    }

    public function setPedidos(ArrayCollection $pedidos) {
	$this->pedidos = $pedidos;
    }

    public function ehJuridica(){
	return $this->getPessoa() == 'j';
    }

    /**
     * Add pedidos
     *
     * @param Fnx\PedidoBundle\Entity\Pedido $pedidos
     */
    public function addPedido(\Fnx\PedidoBundle\Entity\Pedido $pedidos)
    {
        $this->pedidos[] = $pedidos;
    }
}