<?php

namespace Fnx\PedidoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PedidoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('cliente','text', array(
                'property_path' => false,
                'required' => false,
                'label' => 'Cliente:*'
                ))
                ->add('previsao', 'date', array(
                        'label' => 'Previsão:',
                        'input' => 'datetime',
                        'widget' => 'single_text',
                        'format' => 'dd/MM/yyyy',
             ));
    }

    public function getName()
    {
        return 'fnx_pedidobundle_pedidotype';
    }
}
