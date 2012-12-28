<?php

namespace Fnx\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CategoriaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nome','text',array(
                  'label' => 'Nome:*'
            ))
            ->add('descricao','textarea', array(
                  'label' => 'Descrição:*'
            ))
        ;
    }

    public function getName()
    {
        return 'fnx_adminbundle_categoriatype';
    }
}
