<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Fnx\AdminBundle\Entity\Servico;

/**
 * Description of ServicoFixture
 *
 * @author bondcs
 */
class ServicoFixture implements FixtureInterface{
    
    public function load(ObjectManager $manager){
        
        $servico = new Servico();
        $servico->setNome("Segurança");
        
        $servico2 = new Servico();
        $servico2->setNome("Verificação de Propriedade");
        
        $servico3 = new Servico();
        $servico3->setNome("Patrulhamento");
        
        $servico4 = new Servico();
        $servico4->setNome("Administração");
        
        $manager->persist($servico);
        $manager->persist($servico2);
        $manager->persist($servico3);
        $manager->persist($servico4);
        $manager->flush();
             
        
    }
}

?>
