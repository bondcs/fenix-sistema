<?php

namespace Fnx\PatrulhaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityRepository;
use Fnx\PatrulhaBundle\Form\Type\EscalaPatruListener;

class EscalaPatruType extends AbstractType
{
    private $data;
    public function __construct($data = null) {
        $this->data = $data;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('inicio', 'time', array(
                        'label' => 'Início:*',
                        'input' => 'datetime',
                        'widget' => 'choice',
                        'required' => true,
                ))
            ->add('fim', 'choice', array(
                        'label' => 'Duração:*',
                        'choices' => array(1 => "1",
                                           2 => "2",
                                           3 => "3",
                                           4 => "4",
                                           5 => "5",
                                           6 => "6",
                                           7 => "7",
                                           8 => "8",
                                           9 => "9",
                                           10 => "10",
                                           11 => "11",
                                           12 => "12"),
                        'required' => true,
                ))
            ->add('descricao','choice',array(
                        "empty_value" => "Selecione uma descrição",
                        'label' => 'Descrição:*',
                        'required' => false,
                        'choices' => array("Vigilante" => "Vigilante",
                                           "Piloto" => "Piloto",
                                           "Co-piloto" => "Co-piloto")
            ))
            ->add('funcionarios','entity',array(
                        'empty_value' => 'Selecione uma opcão',
                        'label' => 'Funcionário',
                        'multiple' => true,
                        'class' => 'FnxAdminBundle:Funcionario',
                        'query_builder' => function(EntityRepository $er) {
                            return $er->createQueryBuilder('f');
                        },
                        'property' => 'nome'
             ));
                        
            $subscriber = new EscalaPatruListener($builder->getFormFactory(),$this->data, $options['em']);
            $builder->addEventSubscriber($subscriber);
                        
    }

    public function getName()
    {
        return 'fnx_adminbundle_escalapatrutype';
    }
    
    public function getDefaultOptions(array $options) {
        return array("em" => null);
    }
    
}
