<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fnx\FinanceiroBundle\Entity\Registro;
use Fnx\FinanceiroBundle\Entity\Parcela;
use Fnx\FinanceiroBundle\Entity\Movimentacao;

/**
 * Fnx\AdminBundle\Entity\SalarioPagamento
 *
 * @ORM\Table(name="salarioPagamento")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class SalarioPagamento
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
     * @var float $valorPago
     *
     * @ORM\Column(name="valorPago", type="float", nullable=true)
     */
    protected $valorPago;

    /**
     * @var datetime $dataPagamento
     *
     * @ORM\Column(name="dataPagamento", type="datetime", nullable=true)
     */
    protected $dataPagamento;
    
    /**
     * @var datetime $data
     *
     * @ORM\Column(name="data", type="date")
     */
    protected $data;
    
    /**
     * @var float $bonus
     *
     * @ORM\Column(name="bonus", type="float", nullable=true)
     */
    protected $bonus;
    

    /**
     * @var boolean $pago
     *
     * @ORM\Column(name="pago", type="boolean")
     */
    protected $pago;
    
    /**
     * @var object $salario
     * 
     * @ORM\ManyToOne(targetEntity="Salario", inversedBy="pagamentos", cascade={"all"}, fetch="LAZY")
     */
    protected $salario;
    
    /**
     *
     * @ORM\OneToOne(targetEntity="Fnx\FinanceiroBundle\Entity\Registro")
     * @ORM\JoinColumn(name="registro_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $registro;


    public function __construct() {
        $this->pago = false;
        $this->bonus = 0;
    }
    
    public function calculaSalario($valorDependente){
        return $this->salario->getSalario()+ $this->bonus + $this->salario->getFuncionario()->getDependentes()*$valorDependente;
    }
    
    public function efetuaPagamento($contaSalario, Categoria $categoria, $valorDependente, $formaPagamento, $mes ){
        
        $valorPago = $this->calculaSalario($valorDependente);
        
        if ($contaSalario->getValor() < $valorPago){
            return false;
        }else{

            $this->registro = new Registro();
            $this->registro->setConta($contaSalario);
            $this->registro->setCategoria($categoria);
            $this->registro->setDescricao($this->salario->getFuncionario()->getNome()." recebeu ".$valorPago." R$ - Mês: ".$mes);
            $this->registro->setPrimeiraParcela(new \DateTime);
            $this->registro->setValor($valorPago);
            $contaSalario->addRegistro($this->registro);

            $parcela = new Parcela();
            $parcela->setDtVencimento(new \DateTime);
            $parcela->setFinalizado(true);
            $parcela->setNumero(1);
            $parcela->setRegistro($this->registro);
            $this->registro->addParcela($parcela);

            $movimentacao = new Movimentacao;
            $movimentacao->setDataPagamento(new \DateTime);
            $movimentacao->setFormaPagamento($formaPagamento);
            $movimentacao->setMovimentacao("P");
            $movimentacao->setValor($valorPago);
            $movimentacao->setValorPago($valorPago);
            $movimentacao->setParcela($parcela);
            $parcela->setMovimentacao($movimentacao);
            
            $contaSalario->saque($valorPago);
            
            return true;
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
     * Set valorPago
     *
     * @param float $valorPago
     */
    public function setValorPago($valorPago)
    {
        $this->valorPago = $valorPago;
    }

    /**
     * Get valorPago
     *
     * @return float 
     */
    public function getValorPago()
    {
        return $this->valorPago;
    }

    /**
     * Set dataPagamento
     *
     * @param datetime $dataPagamento
     */
    public function setDataPagamento($dataPagamento)
    {
        $this->dataPagamento = $dataPagamento;
    }

    /**
     * Get dataPagamento
     *
     * @return datetime 
     */
    public function getDataPagamento()
    {
        return $this->dataPagamento;
    }

    /**
     * Set pago
     *
     * @param boolean $pago
     */
    public function setPago($pago)
    {
        $this->pago = $pago;
    }

    /**
     * Get pago
     *
     * @return boolean 
     */
    public function getPago()
    {
        return $this->pago;
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
     * Set data
     *
     * @param date $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Get data
     *
     * @return date 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set bonus
     *
     * @param float $bonus
     */
    public function setBonus($bonus)
    {
        $this->bonus = $bonus;
    }

    /**
     * Get bonus
     *
     * @return float 
     */
    public function getBonus()
    {
        return $this->bonus;
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
}