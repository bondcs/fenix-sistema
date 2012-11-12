<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fnx\AdminBundle\Entity\Cliente;
use Fnx\AdminBundle\Entity\Atividade;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Fnx\AdminBundle\Entity\Contrato
 *
 * @ORM\Table(name="contrato")
 * @ORM\Entity(repositoryClass="Fnx\AdminBundle\Entity\ContratoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Contrato
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
     * @var datetime $created
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var datetime $updated
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    protected $updated;
    
    /**
     * @ORM\Column(name="arquivado",type="boolean", nullable=false)
     * @var boolean $arquivado
     */
    protected $arquivado;
    
    /**
     * @var object $cliente
     * 
     * @ORM\ManyToOne(targetEntity="Cliente", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="cliente_id", referencedColumnName="id")
     */
    protected $cliente;
    
    /**
     * @var ArrayCollection $atividades
     * 
     * @ORM\OneToMany(targetEntity="Atividade", mappedBy="contrato", cascade={"all"})
     * 
     */
    protected $atividades;




    public function __construct() {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
        $this->arquivado = false;
        $this->atividades = new ArrayCollection();
        
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
     * Set created
     *
     * @param datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }
    
    /**
     * Get created
     *
     * @return datetime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param datetime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * Get updated
     *
     * @return datetime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedValue(){
        $this->updated = new \datetime();
        
    }
    
    /**
     * @ORM\PreRemove
     */
    public function setRemovedValue(){
        $this->arquivado = true;
        
    }

    /**
     * Set arquivado
     *
     * @param boolean $arquivado
     */
    public function setArquivado($arquivado)
    {
        $this->arquivado = $arquivado;
    }

    /**
     * Get arquivado
     *
     * @return boolean 
     */
    public function getArquivado()
    {
        return $this->arquivado;
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
     * Add atividades
     *
     * @param Fnx\AdminBundle\Entity\Atividade $atividades
     */
    public function addAtividade(\Fnx\AdminBundle\Entity\Atividade $atividades)
    {
        $this->atividades[] = $atividades;
    }

    /**
     * Get atividades
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getAtividades()
    {
        return $this->atividades;
    }
}