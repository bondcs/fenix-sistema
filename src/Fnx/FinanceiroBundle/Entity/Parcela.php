<?php

namespace Fnx\FinanceiroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Fnx\FinanceiroBundle\Entity\Parcela
 *
 * @ORM\Table(name="parcela")
 * @ORM\Entity
 */
class Parcela
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
     * @var date $dt_vencimento
     *
     * @ORM\Column(name="dt_vencimento", type="date")
     * @Assert\NotBlank()
     */
    protected $dt_vencimento;
    
    /**
     * @var integer $numero
     *
     * @ORM\Column(name="numero", type="integer")
     */
    protected $numero;
    
    /**
     * @var date $finalizado
     *
     * @ORM\Column(name="finalizado", type="boolean")
     */
    protected $finalizado;
    
    /**
     *
     * @var object $registro
     * @ORM\ManyToOne(targetEntity="Registro", inversedBy="parcelas", cascade={"persist"}, fetch="LAZY")
     */
    protected $registro;
    
    /**
     *
     * @var object $movimentacao
     * @ORM\OneToOne(targetEntity="Movimentacao", mappedBy="parcela", cascade={"all"})
     */
    protected $movimentacao;
    
    public function __construct() {
        $this->finalizado = false;
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
     * Set dt_vencimento
     *
     * @param date $dtVencimento
     */
    public function setDtVencimento($dtVencimento)
    {
        $this->dt_vencimento = $dtVencimento;
    }

    /**
     * Get dt_vencimento
     *
     * @return date 
     */
    public function getDtVencimento()
    {
        return $this->dt_vencimento;
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
     * Set movimentacao
     *
     * @param Fnx\FinanceiroBundle\Entity\Movimentacao $movimentacao
     */
    public function setMovimentacao(\Fnx\FinanceiroBundle\Entity\Movimentacao $movimentacao)
    {
        $this->movimentacao = $movimentacao;
    }

    /**
     * Get movimentacao
     *
     * @return Fnx\FinanceiroBundle\Entity\Movimentacao 
     */
    public function getMovimentacao()
    {
        return $this->movimentacao;
    }

    /**
     * Set finalizado
     *
     * @param boolean $finalizado
     */
    public function setFinalizado($finalizado)
    {
        $this->finalizado = $finalizado;
    }

    /**
     * Get finalizado
     *
     * @return boolean 
     */
    public function getFinalizado()
    {
        return $this->finalizado;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }
}