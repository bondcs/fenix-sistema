<?php

namespace Fnx\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityRepository;
use Fnx\AdminBundle\Form\Listener\EscalaFunListener;

class EscalaFunType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('inicio', 'date', array(
                        'label' => 'Início:*',
                        'input' => 'datetime',
                        'widget' => 'single_text',
                        'required' => true,
                        'format' => 'dd/MM/yyyy HH:mm:ss'
                ))
            ->add('fim', 'date', array(
                        'label' => 'Fim:*',
                        'input' => 'datetime',
                        'widget' => 'single_text',
                        'required' => true,
                        'format' => 'dd/MM/yyyy HH:mm:ss'
                ))
            ->add('local','text',array(
                        'label' => 'Local:*',
                        'required' => false,
                 ))
            ->add('descricao','text',array(
                        'label' => 'Descrição:*',
                        'required' => false,
                        'max_length' => 60
                 ))
            ->add('servicoEscala','entity',array(
                        'empty_value' => 'Selecione uma opcão',
                        'label' => 'Serviço:*',
                        'class' => 'FnxAdminBundle:ServicoEscala',
                        'property' => 'nome'
            ))
            ->add('funcionarios','entity',array(
                        'empty_value' => 'Selecione uma opcão',
                        'label' => 'Funcionário',
                        'multiple' => true,
                        'class' => 'FnxAdminBundle:Funcionario',
                        'query_builder' => function(EntityRepository $er) {
                            return $er->createQueryBuilder('f');
                        },
                        'property' => 'nome'
                ));
                        
            $subscriber = new EscalaFunListener($builder->getFormFactory(), $options['em']);
            $builder->addEventSubscriber($subscriber);
    }

    public function getName()
    {
        return 'fnx_adminbundle_escalafuntype';
    }
    
    public function getDefaultOptions(array $options) {
        return array("em" => null,
                     "validation_groups" => array(
                            "registration",
                            "Default"
                     ));
    }
}
