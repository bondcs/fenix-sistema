<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\AdminBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Fnx\AdminBundle\Form\Listener\CategoriaListener;

/**
 * Description of AtividadeType
 *
 * @author bondcs
 */
class AtividadeType extends AbstractType{
    
    protected $atividade;
    protected $cidades;


    public function __construct($atividade, $cidades = array()) {
        
        $this->atividade = $atividade;
        $this->cidades = $cidades;
    } 
    
    function buildForm(FormBuilder $builder, array $options) {
        
        $estado = $this->atividade->getCidade() ? $this->atividade->getCidade()->getEstado() : null;
        
        $builder
            ->add("nome","text", array(
                "label" => "Nome:*"
            ))
//            ->add("descricao","textarea", array(
//                "label" => "Descrição:*"
//            ))
            ->add("servico", "entity", array(
                "property" => "nome",
                "class" => "FnxAdminBundle:Servico",
                "label" => "Serviço:*"
            ))
            ->add('cliente','text', array(
                'property_path' => false,
                'label' => 'Cliente:*'
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
            ->add('bairro','text', array(
                'label' => 'Bairro:'
            ))
            ->add('rua','text', array(
                'label' => 'Rua:'
            ))
            ->add('numero','text', array(
                'label' => 'Numero:'
            ))
            ->add('dtInicio', 'date', array(
                        'label' => 'Início:',
                        'input' => 'datetime',
                        'widget' => 'single_text',
                        'required' => true,
                        'format' => 'dd/MM/yyyy HH:mm:ss'
             ))
            ->add('dtFim', 'date', array(
                        'label' => 'Fim:',
                        'input' => 'datetime',
                        'widget' => 'single_text',
                        'required' => true,
                        'format' => 'dd/MM/yyyy HH:mm:ss'    
            ));
         
    }

    function getName() {
        return "fnx_admin_atividade";
    }
}

?>
