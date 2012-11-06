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
        $servico->setCor("Teal");
        
        
        $servico2 = new Servico();
        $servico2->setNome("Verificação de Propriedade");
        $servico2->setCor("Brown");
        
        $servico3 = new Servico();
        $servico3->setNome("Monitoramento");
        $servico3->setCor("");
        
        $servico4 = new Servico();
        $servico4->setNome("Administração");
        $servico4->setCor("");
        
        $manager->persist($servico);
        $manager->persist($servico2);
        $manager->persist($servico3);
        $manager->persist($servico4);
        $manager->flush();
             
        
    }
}

?>
