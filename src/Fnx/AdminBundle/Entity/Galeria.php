<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Fnx\AdminBundle\Entity\Imagem;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Fnx\AdminBundle\Entity\Galeria
 *
 * @ORM\Table(name="galeria")
 * @ORM\Entity(repositoryClass="Fnx\AdminBundle\Entity\GaleriaRepository")
 */
class Galeria
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
     * 
     */
    protected $nome;

    /**
     * @var string $descricao
     *
     * @ORM\Column(name="descricao", type="string", length=200, nullable=true)
     */
    protected $descricao;
      
    /**
     * @var array $files
     * 
     */
    protected $files;
    
    /**
     *
     * @ORM\OneToMany(targetEntity="Imagem", mappedBy="galeria", cascade={"all"}, orphanRemoval=true)
     */
    protected $imagens;
    
    /**
     *
     * @ORM\OneToOne(targetEntity="Atividade", inversedBy="galeria")
     */
    protected $atividade;


    public function __construct() {
        $this->files = array();
        $this->imagens = new ArrayCollection();
    }
    
    public function upload(){
        
        if ($this->files[0] == null){
            return;
        }
        
        $j = 0;
        foreach($this->getImagens()->toArray() as $imagem){
            $j += $imagem->getCaminho() != null ? 1 : 0;
        }

        for ($i=0; $i < count($this->files); $i++ ){
            $randomNumber = sha1(uniqid(mt_rand(), true));
            
            $file = $this->files[$i];
            $imagem = $this->getImagens()->get($j);
            $file->move($imagem->getUploadRootDir(), $randomNumber.".".$this->nome.".".$j.".".$file->getClientOriginalName());
            $imagem->setCaminho($randomNumber.".".$this->nome.".".$j.".".$file->getClientOriginalName());
            $imagem->setGaleria($this);
            $j++;
        }
        
        $this->files = null;

    }
    
//    function removeUpload(){
//        
//        foreach($this->getImagens()->toArray() as $imagem){
//            if ($imagemNome = $imagem->getAbsolutePath()){
//                unlink($imagemNome);
//            }
//        }
//    }


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
     * Add files
     *
     * @param $files
     */
    public function setFiles( $files)
    {
        $this->files = $files;
    }

    /**
     * Get files
     *
     * @return 
     */
    public function getFiles()
    {
        return $this->files;
    }
   

    /**
     * Add imagens
     *
     * @param Fnx\AdminBundle\Entity\File $imagens
     */
    public function addImagem(\Fnx\AdminBundle\Entity\Imagem $imagens)
    {
        $this->imagens[] = $imagens;
    }

    /**
     * Get imagens
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getImagens()
    {
        return $this->imagens;
    }

    /**
     * Set atividade
     *
     * @param Fnx\AdminBundle\Entity\Atividade $atividade
     */
    public function setAtividade(\Fnx\AdminBundle\Entity\Atividade $atividade)
    {
        $this->atividade = $atividade;
    }

    /**
     * Get atividade
     *
     * @return Fnx\AdminBundle\Entity\Atividade 
     */
    public function getAtividade()
    {
        return $this->atividade;
    }
}