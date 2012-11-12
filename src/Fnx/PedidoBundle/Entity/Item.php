<?php

namespace Fnx\PedidoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fnx\PedidoBundle\Entity\Pedido as Pedido;
use Symfony\Component\Validator\Constraints as Assert;
use Fnx\AdminBundle\Validator\Constraints as FnxAssert;

/**
 * Fnx\PedidoBundle\Entity\Item
 *
 * @ORM\Table(name="pedido_item")
 * @ORM\Entity(repositoryClass="Fnx\PedidoBundle\Entity\ItemRepository")
 */
class Item
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
     *
     * @ORM\ManyToOne(targetEntity="Pedido")
     * @ORM\JoinColumn(name="pedido_id", referencedColumnName="id")
     */
    protected $pedido;

    /**
     *
     * @ORM\Column(name="nome", type="string", length=50)
     * @Assert\NotBlank()
     */
    protected $nome;

    /**
     * @var string $descricao
     *
     * @ORM\Column(name="descricao", type="string", length=255, nullable=true)
     */
    protected $descricao;

    /**
     * @var decimal $quantidade
     *
     * @ORM\Column(name="quantidade", type="decimal")
     * @Assert\NotBlank()
     * @FnxAssert\ApenasNumero()
     */
    protected $quantidade;

    /**
     * @var float $preco
     *
     * @ORM\Column(name="preco", type="float")
     * @Assert\NotBlank()
     */
    protected $preco;
    
    public function popula($nome, $descricao, $quantidade, $preco){
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->quantidade = $quantidade;
        $this->preco = $preco;
        
    }
    
    public function __toString() {
        return $this->id."";
    }

    public function setId($id) {
	$this->id = $id;
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
     * Set quantidade
     *
     * @param decimal $quantidade
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    /**
     * Get quantidade
     *
     * @return decimal
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set preco
     *
     * @param float $preco
     */
    public function setPreco($preco)
    {
         $this->preco =is_string($preco) ? substr(str_replace(",", ".", $preco),3) : $preco;
    }

    /**
     * Get preco
     *
     * @return float
     */
    public function getPreco()
    {
        return $this->preco;
    }

    public function getTotal(){
        return $this->getPreco() * $this->getQuantidade();
    }

    public function getPedido() {
	return $this->pedido;
    }

    public function setPedido($pedido) {
	$this->pedido = $pedido;
    }

    public function getNome() {
	return $this->nome;
    }

    public function setNome($nome) {
	$this->nome = $nome;
    }

    public function getName()
    {
        return 'Item';
    }
}