<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fnx\AdminBundle\Entity\Contrato;
use Fnx\AdminBundle\Entity\Servico;
use Fnx\AdminBundle\Entity\Galeria;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Fnx\FinanceiroBundle\Entity\Registro;

/**
 * Fnx\AdminBundle\Entity\Atividade
 *
 * @ORM\Table(name="atividade")
 * @ORM\Entity(repositoryClass="Fnx\AdminBundle\Entity\AtividadeRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Atividade
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
     * @Assert\NotBlank()
     */
    protected $nome;

    /**
     * @var string $descricao
     *
     * @ORM\Column(name="descricao", type="string", length=255)
     * @Assert\NotBlank()
     */
    //protected $descricao;

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
     * @var object $contrato
     *
     * @ORM\ManyToOne(targetEntity="Contrato", cascade={"all"}, fetch="LAZY")
     * @ORM\JoinColumn(name="contrato_id", referencedColumnName="id")
     */
    protected $contrato;


    /**
     * @var object $servico
     *
     * @ORM\ManyToOne(targetEntity="Servico", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="servico_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     */
    protected $servico;

    /**
     * @ORM\ManyToOne(targetEntity="Funcionario", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="responsavel_id", referencedColumnName="id")
     */
    protected $responsavel;

    /**
     * @ORM\ManyToMany(targetEntity="Categoria")
     * @ORM\JoinTable(name="atividade_categoria",
     *     joinColumns={@ORM\JoinColumn(name="atividade_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="categoria_id", referencedColumnName="id")}
     * )
     * )
     * @var ArrayCollection $categorias
     */
    protected $categorias;

    /**
     *
     * @ORM\OneToMany(targetEntity="Endereco", mappedBy="atividade", cascade={"all"})
     * @var ArrayCollection $enderecos
     */
    protected $enderecos;

    /**
     *
     * @ORM\OneToMany(targetEntity="Escala", mappedBy="atividade", cascade={"all"})
     * @var ArrayCollection $escalas
     */
    protected $escalas;

    /**
     *
     * @ORM\OneToMany(targetEntity="Local", mappedBy="atividade", cascade={"all"})
     * @var ArrayCollection $locais
     */
    protected $locais;

    /**
     *
     * @ORM\OneToOne(targetEntity="Galeria", mappedBy="atividade", cascade={"all"})
     * @var ArrayCollection $galerias
     */
    protected $galeria;

    /**
     *
     * @ORM\OneToMany(targetEntity="Propriedade", mappedBy="atividade", cascade={"all"})
     * @var ArrayCollection $propriedades
     */
    protected $propriedades;

    /**
     *
     * @var object $registro
     * @ORM\OneToOne(targetEntity="Fnx\FinanceiroBundle\Entity\Registro", cascade={"all"})
     * @ORM\JoinColumn(name="registro_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $registro;

    /**
     * @var string $cep
     *
     * @ORM\Column(name="cep", type="string", length=9, nullable=true)
     * @Assert\MinLength(8)
     */
    protected $cep;

     /**
     * @var objeto $cidade
     *
     * @ORM\ManyToOne(targetEntity="Cidade")
     * @ORM\JoinColumn(name="cidade_id", referencedColumnName="id")
     */
    protected $cidade;

    /**
     * @var string $bairro
     *
     * @ORM\Column(name="bairro", type="string", length=45, nullable=true)
     */
    protected $bairro;

    /**
     * @var string $rua
     *
     * @ORM\Column(name="rua", type="string", length=80, nullable=true)
     */
    protected $rua;

    /**
     * @var integer $numero
     *
     * @ORM\Column(name="numero", type="string", length=20, nullable=true)
     */
    protected $numero;

    /**
     * @var string $complemento
     *
     * @ORM\Column(name="complemento", type="string", length=80, nullable=true)
     */
    protected $complemento;

    /**
     * @var datetime $dtInicio
     *
     * @ORM\Column(name="dtInicio", type="datetime", nullable=true)
     */
    protected $dtInicio;

    /**
     * @var datetime $dtFim
     *
     * @ORM\Column(name="dtFim", type="datetime", nullable=true)
     */
    protected $dtFim;


    public function __construct() {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
        $this->arquivado = false;
        $this->categorias = new ArrayCollection();
        $this->escalas = new ArrayCollection();
        $this->enderecos = new ArrayCollection();
        $this->locais = new ArrayCollection();
        $this->propriedades = new ArrayCollection();

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
    public function getDescricao($length = null)
    {
        if ($length != null && $length > 0){
            return substr($this->descricao, 0, $length);
        }
        return $this->descricao;
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
     * @ORM\PreRemove
     */
    public function setRemovedValue(){
        $this->arquivado = true;

    }

    /**
     * Set contrato
     *
     * @param Fnx\AdminBundle\Entity\Contrato $contrato
     */
    public function setContrato(\Fnx\AdminBundle\Entity\Contrato $contrato)
    {
        $this->contrato = $contrato;
    }

    /**
     * Get contrato
     *
     * @return Fnx\AdminBundle\Entity\Contrato
     */
    public function getContrato()
    {
        return $this->contrato;
    }

    /**
     * Add categorias
     *
     * @param Fnx\AdminBundle\Entity\Categoria $categorias
     */
//    public function addCategoria(\Fnx\AdminBundle\Entity\Categoria $categorias)
//    {
//        $this->categorias[] = $categorias;
//    }

    /**
     * Get categorias
     *
     * @return Doctrine\Common\Collections\Collection
     */
//    public function getCategorias()
//    {
//        return $this->categorias;
//    }

    /**
     * Set servico
     *
     * @param Fnx\AdminBundle\Entity\Servico $servico
     */
    public function setServico(\Fnx\AdminBundle\Entity\Servico $servico)
    {
        $this->servico = $servico;
    }

    /**
     * Get servico
     *
     * @return Fnx\AdminBundle\Entity\Servico
     */
    public function getServico()
    {
        return $this->servico;
    }

    /**
     * Add categorias
     *
     * @param Fnx\AdminBundle\Entity\Categoria $categorias
     */
    public function addCategoria(\Fnx\AdminBundle\Entity\Categoria $categorias)
    {
        $this->categorias[] = $categorias;
    }

    /**
     * Get categorias
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getCategorias()
    {
        return $this->categorias;
    }

    /**
     * Add escalas
     *
     * @param Fnx\AdminBundle\Entity\Escala $escalas
     */
    public function addEscala(\Fnx\AdminBundle\Entity\Escala $escalas)
    {
        $this->escalas[] = $escalas;
    }

    /**
     * Get escalas
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getEscalas()
    {
        return $this->escalas;
    }

    /**
     * Add enderecos
     *
     * @param Fnx\AdminBundle\Entity\Endereco $enderecos
     */
    public function addEndereco(\Fnx\AdminBundle\Entity\Endereco $enderecos)
    {
        $this->enderecos[] = $enderecos;
    }

    /**
     * Get enderecos
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getEnderecos()
    {
        return $this->enderecos;
    }

    /**
     * Get galeria
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getGaleria()
    {
        return $this->galeria;
    }

    /**
     * Set galeria
     *
     * @param Fnx\AdminBundle\Entity\Galeria $galeria
     */
    public function setGaleria(\Fnx\AdminBundle\Entity\Galeria $galeria)
    {
        $this->galeria = $galeria;
    }

    /**
     * Add locais
     *
     * @param Fnx\AdminBundle\Entity\Local $locais
     */
    public function addLocal(\Fnx\AdminBundle\Entity\Local $locais)
    {
        $this->locais[] = $locais;
    }

    /**
     * Get locais
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getLocais()
    {
        return $this->locais;
    }

    /**
     * Add propriedades
     *
     * @param Fnx\AdminBundle\Entity\Propriedade $propriedades
     */
    public function addPropriedade(\Fnx\AdminBundle\Entity\Propriedade $propriedades)
    {
        $this->propriedades[] = $propriedades;
    }

    /**
     * Get propriedades
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getPropriedades()
    {
        return $this->propriedades;
    }

    /**
     * Set registro
     *
     * @param Fnx\FinanceiroBundle\Entity\Registro; $registro
     */
    public function setRegistro(\Fnx\FinanceiroBundle\Entity\Registro $registro)
    {
        $this->registro = $registro;
    }

    /**
     * Get registro
     *
     * @return Fnx\FinanceiroBundle\Entity\Registro;
     */
    public function getRegistro()
    {
        return $this->registro;
    }

    /**
     * Set bairro
     *
     * @param string $bairro
     */
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
    }

    /**
     * Get bairro
     *
     * @return string
     */
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * Set rua
     *
     * @param string $rua
     */
    public function setRua($rua)
    {
        $this->rua = $rua;
    }

    /**
     * Get rua
     *
     * @return string
     */
    public function getRua()
    {
        return $this->rua;
    }

    /**
     * Set numero
     *
     * @param string $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set complemento
     *
     * @param string $complemento
     */
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
    }

    /**
     * Get complemento
     *
     * @return string
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * Set cidade
     *
     * @param Fnx\AdminBundle\Entity\Cidade $cidade
     */
    public function setCidade(\Fnx\AdminBundle\Entity\Cidade $cidade)
    {
        $this->cidade = $cidade;
    }

    /**
     * Get cidade
     *
     * @return Fnx\AdminBundle\Entity\Cidade
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * Set cep
     *
     * @param string $cep
     */
    public function setCep($cep)
    {
        $this->cep = $cep;
    }

    /**
     * Get cep
     *
     * @return string
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * Add servicos
     *
     * @param Fnx\AdminBundle\Entity\Servico $servicos
     */
    public function addServico(\Fnx\AdminBundle\Entity\Servico $servicos)
    {
        $this->servicos[] = $servicos;
    }

    /**
     * Get servicos
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getServicos()
    {
        return $this->servicos;
    }

    /**
     * Set dtInicio
     *
     * @param datetime $dtInicio
     */
    public function setDtInicio($dtInicio)
    {
        $this->dtInicio = $dtInicio;
    }

    /**
     * Get dtInicio
     *
     * @return datetime
     */
    public function getDtInicio()
    {
        return $this->dtInicio;
    }

    /**
     * Set dtFim
     *
     * @param datetime $dtFim
     */
    public function setDtFim($dtFim)
    {
        $this->dtFim = $dtFim;
    }

    /**
     * Get dtFim
     *
     * @return datetime
     */
    public function getDtFim()
    {
        return $this->dtFim;
    }
}