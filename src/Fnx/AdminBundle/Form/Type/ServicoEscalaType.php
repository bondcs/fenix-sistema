<?php

namespace Fnx\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ServicoEscalaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nome','text',array(
                  'label' => 'Nome:*'
            ))
            ->add('descricao','textarea',array(
                  'label' => 'Descrição:*'
            ))
            ->add('cor','choice',array(
                  'label' => 'Cor:*',
                  'choices' => array('red' => 'Vermelho',
                                     'green' => 'Verde',
                                     'blue' => 'Azul',
                                     'orange' => 'Laranjado',
                                     'silver' => 'Prata',
                                     'gray' => 'Cinza') 
            ))
        ;
    }

    public function getName()
    {
        return 'fnx_adminbundle_servicoescalatype';
    }
}
