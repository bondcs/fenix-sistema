<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\AdminBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Role\Role as CoreRole;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Description of Role
 *
 * @author bondcs
 */

    /**
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class Role extends CoreRole implements RoleInterface{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer $id
     */
    protected $id;
 
    /**
     * @ORM\Column(type="string", length="255")
     *
     * @var string $name
     * @Assert\NotBlank()
     */
    protected $nome;
 
    /**
     * @ORM\Column(type="datetime", name="created_at")
     *
     * @var DateTime $createdAt
     */
    protected $createdAt;
 
    /**
     * Gets the id.
     *
     * @return integer The id.
     */
    
    /**
     * @ORM\ManyToMany(targetEntity="Usuario", mappedBy="userRoles")
     * @ORM\JoinTable(name="usuario_role",
     *     joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     * @var ArrayCollection $usuarios
     */
    protected $usuarios;

        public function getId()
    {
        return $this->id;
    }
 
    /**
     * Gets the role name.
     *
     * @return string The name.
     */
    public function getNome()
    {
        return $this->nome;
    }
 
    /**
     * Sets the role name.
     *
     * @param string $value The name.
     */
    public function setNome($value)
    {
        $this->nome = $value;
    }
 
    /**
     * Gets the DateTime the role was created.
     *
     * @return DateTime A DateTime object.
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
 
    /**
     * Consturcts a new instance of Role.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->usuarios = new ArrayCollection();
    }
 
    /**
     * Implementation of getRole for the RoleInterface.
     * 
     * @return string The role.
     */
    public function getRole()
    {
        return $this->getNome();
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
    
    public function __toString()
    {
        $role = explode("_", $this->nome);
        
        return $role[1] == "USUARIO" ? "USUÁRIO" : $role[1];
    }

    /**
     * Add users
     *
     * @param Acme\AdminBundle\Entity\User $users
     */
    public function addUser(\Fnx\AdminBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios[] = $usuarios;
    }

    /**
     * Get users
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    /**
     * Add usuarios
     *
     * @param Fnx\AdminBundle\Entity\Usuario $usuarios
     */
    public function addUsuario(\Fnx\AdminBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios[] = $usuarios;
    }
}