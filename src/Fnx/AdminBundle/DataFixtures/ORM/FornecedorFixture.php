<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Fnx\AdminBundle\Entity\Fornecedor;

/**
 * Description of FornecedorFixture
 *
 * @author bondcs
 */
class FornecedorFixture implements FixtureInterface{
    
    public function load(ObjectManager $manager){
        
        $fornecedor = new Fornecedor;
        $fornecedor->setNome("Fenix");
        $fornecedor->setTelefone("(00) 0000-0000");
        
        $manager->persist($fornecedor);
        $manager->flush();

    }
}

?>
