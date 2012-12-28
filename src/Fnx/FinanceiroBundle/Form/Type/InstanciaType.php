<?php

namespace Fnx\FinanceiroBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class InstanciaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nome', 'text', array(
                'label' => 'Nome:*'
            ))
            ->add('descricao','textarea', array(
                'label' => 'Descrição:*',
                'required' => false
            ))
        ;
    }

    public function getName()
    {
        return 'fnx_financeirobundle_instanciatype';
    }
}
