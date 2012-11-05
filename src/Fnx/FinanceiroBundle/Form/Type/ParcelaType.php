<?php

namespace Fnx\FinanceiroBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ParcelaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('dt_vencimento', 'date', array(
                        'label' => 'Vencimento:*',
                        'input' => 'datetime',
                        'widget' => 'single_text',
                        'format' => 'dd/MM/yyyy'
             ))  
            ->add('finalizado', "checkbox", array(
                        'label' => 'Finalizado:*'
              ))
            ->add('registro', new RegistroMovType(), array(
            ))
        ;
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Fnx\FinanceiroBundle\Entity\Parcela',
        );
    }

    public function getName()
    {
        return 'fnx_financeirobundle_parcelatype';
    }
}
