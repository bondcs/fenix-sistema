<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Fnx\AdminBundle\DataFixtures\ORM;
 
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Fnx\AdminBundle\Entity\Usuario;
use Fnx\AdminBundle\Entity\Role;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

/**
 * Description of UserFixtures
 *
 * @author bondcs
 */
class UserFixtures implements FixtureInterface{
    
    public function load(ObjectManager $manager){
        
        $role = new Role();
        $role->setNome("ROLE_ADMIN");
        $manager->persist($role);
        
        $role2 = new Role();
        $role2->setNome("ROLE_FUNCIONARIO");
        $manager->persist($role2);
        
        $usuario = new Usuario();
        $usuario->setUserName("admin");
        $usuario->setSalt(md5(time()));
        
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword('admin', $usuario->getSalt());
        $usuario->setPassword($password);
        
        $usuario->getUserRoles()->add($role);
        
        $manager->persist($usuario);
        $manager->flush();
    }
}

?>
