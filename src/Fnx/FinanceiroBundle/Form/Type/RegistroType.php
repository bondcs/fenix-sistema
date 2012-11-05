<?php

namespace Fnx\FinanceiroBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Validator\Constraints\NotBlank;
use Fnx\AdminBundle\Validator\Constraints\Dinheiro;
use Symfony\Component\Validator\Constraints\Collection;

class RegistroType extends AbstractType
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('conta', 'entity', array(
                  'label' => 'Conta:*',
                  'class' => 'FnxFinanceiroBundle:Conta',
            )) 
            ->add('valor', 'text', array(
                  'label' => 'Valor:*',
                  'attr' => array('class' => 'moeda zero'),
                  'data' => '0'
            ))
            ->add('formaPagamento', 'entity', array(
                  'label' => 'Pagamento:*',
                  'class' => 'FnxFinanceiroBundle:FormaPagamento',
                  'property_path' => false,
            ))
            ->add('primeiraParcela', 'date', array(
                        'label' => '1º Parcela:*',
                        'input' => 'datetime',
                        'widget' => 'single_text',
                        'format' => 'dd/MM/yyyy',
                        'data' => new \DateTime
             ))
            ->add('numeroParcela', 'text', array(
                  'label' => 'Nº Parcelas:*',
                  'attr' => array('class' => 'um'),
                  'data' => 1,
                  'property_path' => false,
            ))
        ;
    }
    
    public function getDefaultOptions(array $options)
    {
        $collectionConstraint = new Collection(array(
            //'descricao' => new NotBlank(),
            'formaPagamento' => new NotBlank(),
            'parcela' => new NotBlank(),
        ));
        
        return array(
                     'data_class' => "Fnx\FinanceiroBundle\Entity\Registro");
    }

    public function getName()
    {
        return 'fnx_financeirobundle_registrotype';
    }
}
