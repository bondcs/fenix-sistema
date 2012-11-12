<?php

namespace Fnx\PedidoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fnx\PedidoBundle\Entity\Item as Item;
use Fnx\AdminBundle\Entity\Cliente;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Fnx\PedidoBundle\Entity\Pedido
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Fnx\PedidoBundle\Entity\PedidoRepository")
 */
class Pedido
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
     * @ORM\ManyToOne(targetEntity="\Fnx\AdminBundle\Entity\Cliente")
     */
    protected $cliente;

    /**
     * @var datetime $data
     *
     * @ORM\Column(name="data", type="date", nullable=true)
     */
    protected $data;

    /**
     * @var date $previsao
     *
     * @ORM\Column(name="previsao", type="date", nullable=true)
     */
    protected $previsao;

    /**
     * @var date $dataPagamento
     *
     * @ORM\Column(name="data_fechamento", type="date", nullable=true)
     */
    protected $dataFechamento;

    /**
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="pedido",fetch="LAZY", cascade={"all"})
     */
    protected $itens;

    /**
     * @ORM\ManyToOne(targetEntity="\Fnx\AdminBundle\Entity\Usuario")
     */
    protected $responsavel;

    /**
     * @ORM\Column(type="string", length=1, unique=false, options={"default" = "r"})
     * @var string
     */
    protected $status;
    
    /**
     *
     * @var object $registro
     * @ORM\OneToOne(targetEntity="Fnx\FinanceiroBundle\Entity\Registro", cascade={"all"})
     * @ORM\JoinColumn(name="registro_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $registro;

    public function __construct() {
        $this->itens = new \Doctrine\Common\Collections\ArrayCollection();
        $this->data = new \DateTime;
        $this->status = "a";
        $this->previsao = new \Datetime;
    }


    public function getResponsavel() {
        return $this->responsavel;
    }

    public function setResponsavel($responsavel) {
        $this->responsavel = $responsavel;
    }
    
    public function getItensArray(){
        $itensArray = array();
        foreach ($this->itens as $i){
            $itensArray[] = $i;
        }
        return $itensArray;
    }
    
    public function fechar(){
        if ($this->status == "a"){
            $this->status = "f";
            $this->dataFechamento = new \DateTime();
        }elseif ($this->status == "f"){
            $this->status = "a";
            $this->dataFechamento = null;    
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
     * Set data
     *
     * @param datetime $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }


    public function getCliente() {
        return $this->cliente;
    }

    public function setCliente(\Fnx\AdminBundle\Entity\Cliente $cliente) {
        $this->cliente = $cliente;
    }



    public function getItens() {
        return $this->itens;
    }

    public function setItens($itens, $object = true) {
        if ($object){
            foreach ($itens as $item){
                $item->setPedido($this);
            }
        }
        $this->itens = $itens;
    }

    /**
     * Get data
     *
     * @return datetime
     */
    public function getData(){
            return $this->data;
    }

    public function getDataPagamento() {
        return $this->dataPagamento;
    }

    public function setDataPagamento($dataPagamento) {
        $this->dataPagamento = $dataPagamento;
    }
    public function setId($id) {
	$this->id = $id;
    }

        /**
     * Set previsao
     *
     * @param date $previsao
     */
    public function setPrevisao(\DateTime $previsao)
    {
        $this->previsao = $previsao;
    }

    /**
     * Get previsao
     *
     * @return date
     */
    public function getPrevisao()
    {
        return $this->previsao;
    }

    public function getStatus() {
	return $this->status;
    }

    public function getStatusToStr() {
	switch ($this->status){
	    case "r": return "rascunho";
	    case "a": return "em aberto";
	    case 'f': return 'fechado';
	}
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * Retorna a soma do valor dos itens
     *
     * @return float
     */
    public function getValorTotal(){
        $sum = 0;
        foreach($this->itens as $i):
            $sum += $i->getPreco() * $i->getQuantidade();
        endforeach;

        return $sum;
    }

    /**
     * Add itens
     *
     * @param Fnx\PedidoBundle\Entity\Item $itens
     */
    public function addItem(\Fnx\PedidoBundle\Entity\Item $item)
    {
        $item->setPedido($this);
        $this->itens[] = $item;
    }

    /**
     * Set registro
     *
     * @param Fnx\FinanceiroBundle\Entity\Registro $registro
     */
    public function setRegistro(\Fnx\FinanceiroBundle\Entity\Registro $registro)
    {
        $this->registro = $registro;
    }

    /**
     * Get registro
     *
     * @return Fnx\FinanceiroBundle\Entity\Registro 
     */
    public function getRegistro()
    {
        return $this->registro;
    }

    /**
     * Set dataFechamento
     *
     * @param date $dataFechamento
     */
    public function setDataFechamento($dataFechamento)
    {
        $this->dataFechamento = $dataFechamento;
    }

    /**
     * Get dataFechamento
     *
     * @return date 
     */
    public function getDataFechamento()
    {
        return $this->dataFechamento;
    }
}