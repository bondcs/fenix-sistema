<?php

namespace Fnx\PedidoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nome','text', array(
                  'label' => 'Nome:*'
            ))
            ->add('descricao','text', array(
                  'label' => 'Descrição:'
            ))
            ->add('total','text', array(
                  'label' => 'Total:*',
                  'property_path' => false,
            ))
            ->add('quantidade','text', array(
                  'label' => 'Quantidade:*'
            ))
            ->add('preco','text', array(
                  'label' => 'Preço:*'
            ));
    }

    public function getName()
    {
        return 'fnx_pedidobundle_itemtype';
    }
}

?>