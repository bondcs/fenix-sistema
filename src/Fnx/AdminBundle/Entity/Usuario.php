<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="usuario")
 * @ORM\Entity(repositoryClass="Fnx\AdminBundle\Entity\UsuarioRepository")
 * @Assert\Callback(methods={"passwordCondition"})
 * @UniqueEntity("username")
 *
 */
class Usuario implements UserInterface
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
     * @ORM\Column(name="username", type="string", length=255)
     * @Assert\NotBlank(groups={"register","edit"})
     * @Assert\MaxLength(limit=20,groups={"register","edit"})
     * @Assert\MinLength(limit=4,groups={"register","edit"})
     */
    protected $username;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=255)
     *
     * @Assert\NotBlank(groups={"register"})
     * @Assert\MaxLength(limit=15,groups={"register"})
     * @Assert\MinLength(limit=4,groups={"register"})
     */
    protected $password;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;

    /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="string", length="255")
     *
     * @var string salt
     */
    protected $salt;

    /**
     *
     * @ORM\OneToMany(targetEntity="\Fnx\PedidoBundle\Entity\Pedido", mappedBy="Usuario")
     * @var \Fnx\PedidoBundle\Entity\Pedido
     */
    protected $pedidos;


    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="usuarios")
     * @ORM\JoinTable(name="usuario_role",
     *     joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     *
     * @Assert\Type(type="object", message="The value.",groups={"register","edit"})
     * @var ArrayCollection $userRoles
     */
    protected $userRoles;

    public function __construct(){

        $this->userRoles = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }


    public function passwordCondition(\Symfony\Component\Validator\ExecutionContext $context){

        if ($this->getId() != null){
             $context->getGraphWalker()->walkReference($this, 'edit', $context->getPropertyPath(), true);
        }else{
             $context->getGraphWalker()->walkReference($this, 'register', $context->getPropertyPath(), true);
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
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Gets the user salt.
     *
     * @return string The salt.
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Sets the user salt.
     *
     * @param string $value The salt.
     */
    public function setSalt($value)
    {
        $this->salt = $value;
    }

    /**
     * Gets the user roles.
     *
     * @return ArrayCollection A Doctrine ArrayCollection
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * Erases the user credentials.
     */
    public function eraseCredentials()
    {

    }

    /**
     * Gets an array of roles.
     *
     * @return array An array of Role objects
     */
    public function getRoles()
    {
        return $this->getUserRoles()->toArray();
    }

    /**
     * Compares this user to another to determine if they are the same.
     *
     * @param UserInterface $user The user
     * @return boolean True if equal, false othwerwise.
     */
    public function equals(UserInterface $user)
    {
        return md5($this->getUsername()) == md5($user->getUsername());
    }

    /**
     * Add userRoles
     *
     * @param Acme\BlogBundle\Entity\Role $userRoles
     */
    public function addRole(\Fnx\AdminBundle\Entity\Role $userRoles)
    {
        $this->userRoles[] = $userRoles;
    }


    /**
     * Add pedidos
     *
     * @param Fnx\PedidoBundle\Entity\Pedido $pedidos
     */
    public function addPedido(\Fnx\PedidoBundle\Entity\Pedido $pedidos)
    {
        $this->pedidos[] = $pedidos;
    }

    /**
     * Get pedidos
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getPedidos()
    {
        return $this->pedidos;
    }
}