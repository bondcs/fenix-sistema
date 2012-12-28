<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fnx\AdminBundle\Entity\Chamado
 *
 * @ORM\Table(name="chamado")
 * @ORM\Entity(repositoryClass="Fnx\AdminBundle\Entity\ChamadoRepository")
 */
class Chamado
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
     * @var string $assunto
     * @ORM\ManyToOne(targetEntity="Cliente", cascade={"all"}, fetch="LAZY")
     */
    private $autor;

    /**
     * @var string $assunto
     *
     * @ORM\Column(name="assunto", type="string", length=40)
     */
    private $assunto;

    /**
     * @var string $conteudo
     *
     * @ORM\Column(name="conteudo", type="string", length=500)
     */
    private $conteudo;





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
     * Set assunto
     *
     * @param string $assunto
     */
    public function setAssunto($assunto)
    {
        $this->assunto = $assunto;
    }

    /**
     * Get assunto
     *
     * @return string
     */
    public function getAssunto()
    {
        return $this->assunto;
    }

    /**
     * Set conteudo
     *
     * @param string $conteudo
     */
    public function setConteudo($conteudo)
    {
        $this->conteudo = $conteudo;
    }

    /**
     * Get conteudo
     *
     * @return string
     */
    public function getConteudo()
    {
        return $this->conteudo;
    }

    public function getAutor() {
	return $this->autor;
    }

    public function setAutor($autor) {
	$this->autor = $autor;
    }


}