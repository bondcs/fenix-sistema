<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\AdminBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Fnx\AdminBundle\Entity\Cliente;
/**
 * Description of ClienteType
 *
 * @author bondcs
 */
class ClienteType extends AbstractType{
    
    protected $cliente;
    protected $cidades;


    public function __construct(Cliente $cliente, $cidades = array()) {
        
        $this->cliente = $cliente;
        $this->cidades = $cidades;
    }   

    public function buildForm(FormBuilder $builder, array $options) {

        $estado = $this->cliente->getCidade() ? $this->cliente->getCidade()->getEstado() : null;

        $builder
            ->add('nome','text', array(
                'label' => 'Nome:*'
            ))
            ->add('telefone','text', array(
                'max_length' => 10,
                'label' => 'Telefone:*'
            ))
            ->add('cpf','text', array(
                'max_length' => 11,
                'label' => 'Cpf:*'
            ))
            ->add('cnpj','text', array(
                'max_length' => 14,
                'label' => 'Cnpj:*'
            ))
            ->add('cep','text', array(
                'max_length' => 8,
                'label' => 'Cep:'
            ))
            ->add('estado', 'entity', array(
                'empty_value' => 'Selecione uma opcão',
                'property' => 'nome',
                'class' => 'FnxAdminBundle:Estado',
                'property_path' => false,
                'data' => $estado,
                'label' => 'Estado:'
            ))
            ->add('cidade', 'entity', array(
                'empty_value' => 'Selecione um estado antes',
                'class' => 'FnxAdminBundle:Cidade',
                'property' => 'nome',
                'choices' => $this->cidades,
                'label' => 'Cidade:'

            ))
            ->add('pessoa', 'choice', array(
                'choices'   => array('f' => 'Física', 'j' => 'Jurídica'),
                'required'  => false,
                'expanded'  => true,
                'multiple'  => false,
            ))
            ->add('bairro','text', array(
                'label' => 'Bairro:'
            ))
            ->add('rua','text', array(
                'label' => 'Rua:'
            ))
            ->add('numero','text', array(
                'label' => 'Numero:'
            ))
            ->add('responsaveis', 'collection', array('type'=> new ResponsavelType()));
        
    }
     
    
    
    
    public function getName() {
        return "cliente";
    }
    //put your code here
}

?>
