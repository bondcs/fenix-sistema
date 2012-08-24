<?php

namespace Fnx\PedidoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fnx\PedidoBundle\Entity\Pedido as Pedido;

/**
 * Fnx\PedidoBundle\Entity\Item
 *
 * @ORM\Table()
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
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Pedido")
     * @ORM\JoinColumn(name="pedido_id", referencedColumnName="id")
     */
    private $pedido;


    /**
     * @var string $descricao
     *
     * @ORM\Column(name="descricao", type="string", length=255)
     */
    private $descricao;

    /**
     * @var decimal $quantidade
     *
     * @ORM\Column(name="quantidade", type="decimal")
     */
    private $quantidade;

    /**
     * @var float $preco
     *
     * @ORM\Column(name="preco", type="float")
     */
    private $preco;


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
        $this->preco = $preco;
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
}