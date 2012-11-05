<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityRepository;
/**
 * Description of LocalEnderecoType
 *
 * @author bondcs
 */
class LocalType extends AbstractType {
    
    function getName() {
        return "local";
    }
    
    function buildForm(FormBuilder $builder, array $options) {
         
        $builder
            ->add('custo','text', array(
                'label' => 'Custo:*'
            ))
            ->add('descricao','text', array(
                'label' => 'Nome:'
            ))
            ->add('bairro','text', array(
                'label' => 'Bairro:*'
            ))
            ->add('rua', 'text', array(
                'label' => 'Rua:*'
            ))
            ->add('numero', 'text', array(
                'label' => 'Número:'
            ))
            ->add('complemento', 'text', array(
                'label' => 'Complemento:'
            ))
            ->add('cidade', 'entity', array(
                'empty_value' => 'Selecione um estado antes',
                'class' => 'FnxAdminBundle:Cidade',
                'property' => 'nome',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                           ->join('c.estado', 'e')
                           ->where('e.uf = ?1')
                           ->setParameter(1,"SC");
                },
                'label' => 'Cidade:*'
            ));
            
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Fnx\AdminBundle\Entity\Local',
        );
    }

}

?>
