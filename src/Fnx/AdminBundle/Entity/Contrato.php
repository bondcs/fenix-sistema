<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fnx\AdminBundle\Entity\Cliente;
use Fnx\AdminBundle\Entity\Atividade;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

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
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    protected $file;

    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;
    
    public function __construct() {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
        $this->arquivado = false;
        $this->atividades = new ArrayCollection();
        
    }
   
    public function removeUpload()
    {
        if ($this->path) {
            unlink($this->getAbsolutePath());
            $this->path = null;
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

    /**
     * Set path
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * Set file
     *
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }
    
    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/contratos';
    }
    
    public function upload(){
        // the file property can be empty if the field is not required
        if (null === $this->file) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the target filename to move to
        $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName()."-".$this->atividades->first()->getNome()."-".$this->atividades->first()->getId());

        // set the path property to the filename where you'ved saved the file
        $this->path = $this->file->getClientOriginalName()."-".$this->atividades->first()->getNome()."-".$this->atividades->first()->getId();

        // clean up the file property as you won't need it anymore
        $this->file = null;
    
    }
    
    public function hasPath(){
        if ($this->path){
            return true;
        }else{
            return false;
        }
    }
    
    
}