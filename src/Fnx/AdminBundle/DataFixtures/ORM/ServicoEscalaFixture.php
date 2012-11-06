<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Fnx\AdminBundle\Entity\ServicoEscala;

/**
 * Description of ServicoEscalaFixture
 *
 * @author bondcs
 */
class ServicoEscalaFixture implements FixtureInterface{
    
    public function load(ObjectManager $manager){
        
        $servico = new ServicoEscala();
        $servico->setNome("Segurança");
        $servico->setDescricao("Responsável por serviços de segurança em geral");
        $servico->setEditavel(false);
        
        $servico2 = new ServicoEscala();
        $servico2->setNome("Administração");
        $servico2->setDescricao("Responsável por tarefas administrativas");
        $servico2->setEditavel(false);
        
        $servico3 = new ServicoEscala();
        $servico3->setNome("Patrulhamento");
        $servico3->setDescricao("Responsável por realizar patrulhas");
        $servico3->setEditavel(false);
        
        $manager->persist($servico);
        $manager->persist($servico2);
        $manager->persist($servico3);
        $manager->flush();     
    }
}

?>
