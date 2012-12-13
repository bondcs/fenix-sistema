<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\FinanceiroBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Fnx\FinanceiroBundle\Form\Listener\TransferenciaListener;
/**
 * Description of TransferenciaType
 *
 * @author bondcs
 */
class TransferenciaType extends AbstractType{
    public function getName() {
        return "form_tranferencia";
    }
    
    public function buildForm(FormBuilder $builder, array $options) {
         
        $builder->add('contaSaque', 'entity', array(
                    'empty_value' => "Selecione uma conta",
                    'label' => 'Saque:*',
                    'class' => 'FnxFinanceiroBundle:Conta',
                    'required' => false
                 ))
                 ->add('contaRecebimento', 'entity', array(
                    'empty_value' => "Selecione uma conta",
                    'label' => 'Depósito:*',
                    'class' => 'FnxFinanceiroBundle:Conta',
                     'required' => false
                 ))
                 ->add("valor", "text", array(
                    'label' => 'Valor:*',
                    'required' => false
        ));
        
        $subscriber = new TransferenciaListener();
        $builder->addEventSubscriber($subscriber);
                 
    }   
    
}

?>
