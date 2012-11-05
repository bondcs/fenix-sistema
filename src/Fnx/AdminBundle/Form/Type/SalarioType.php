<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
/**
 * Description of SalarioType
 *
 * @author bondcs
 */
class SalarioType extends AbstractType{
    
    public function getName() {
        return "salario";
    }
    
    public function buildForm(FormBuilder $builder, array $options) {
        
        $builder->add('salario', 'text', array(
               'label' => 'Salário:*',
            ))  
            ->add('valor', 'text', array(
               'label' => 'Valor:',
               'property_path' => false
            ))
            ->add('valorHora', 'text', array(
               'label' => 'Valor/Hora:*',
            ));
    }
    
    public function getDefaultOptions(array $options) {
        return array(
             'data_class' => 'Fnx\AdminBundle\Entity\Salario',
        );
    }
}

?>
