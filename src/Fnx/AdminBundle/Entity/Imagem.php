<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fnx\AdminBundle\Entity\Galeria;

/**
 * Fnx\AdminBundle\Entity\Imagem
 *
 * @ORM\Table(name="imagem")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Imagem
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
     * @var string $caminho
     *
     * @ORM\Column(name="caminho", type="string", length=255)
     */
    protected $caminho;
    
    protected $filenameForRemove;

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
     * Set caminho
     *
     * @param string $caminho
     */
    public function setCaminho($caminho)
    {
        $this->caminho = $caminho;
    }
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Galeria", inversedBy="imagens", cascade={"persist"}, fetch="LAZY")
     */
    protected $galeria;
    
       /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->filenameForRemove = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($this->filenameForRemove) {
            unlink($this->filenameForRemove);
        }
    }


    /**
     * Get caminho
     *
     * @return string 
     */
    public function getCaminho()
    {
        return $this->caminho;
    }
    
     public function getAbsolutePath()
    {
        return null === $this->caminho ? null : $this->getUploadRootDir().'/'.$this->caminho;
    }

    public function getWebPath()
    {
        return null === $this->caminho ? null : $this->getUploadDir().'/'.$this->caminho;
    }

    public function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    public function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/images';
    }

    /**
     * Set galeria
     *
     * @param Fnx\AdminBundle\Entity\Imagem $galeria
     */
    public function setGaleria(\Fnx\AdminBundle\Entity\Galeria $galeria)
    {
        $this->galeria = $galeria;
    }

    /**
     * Get galeria
     *
     * @return Fnx\AdminBundle\Entity\Imagem 
     */
    public function getGaleria()
    {
        return $this->galeria;
    }
}