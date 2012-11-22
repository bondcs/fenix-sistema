<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\AdminBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
/**
 * Description of ContratoType
 *
 * @author bondcs
 */
class ContratoType extends AbstractType{
    
    function buildForm(FormBuilder $builder, array $options) {
        
        $builder->add("file", "file", array(
                      "label" => "Contrato:",
                      "required" => false
        ));
        
    }


    function getName() {
        return "fnx_admin_contrato";
    }
}

?>
