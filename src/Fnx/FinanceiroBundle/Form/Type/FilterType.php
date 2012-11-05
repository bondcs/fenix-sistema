<?php

namespace Fnx\FinanceiroBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityRepository;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $hoje = new \DateTime("now");
        $ano = $hoje->format("Y");
        for ($i = 2012; $i <= ($ano+1); $i++){
             $anos[$i] = $i;
             
        }
        
        $builder
            ->add('inicio', 'date', array(
                        'label' => 'Início:',
                        'input' => 'datetime',
                        'widget' => 'single_text',
                        'format' => 'dd/MM/yyyy HH:mm:ss',
                        'data' => new \Datetime('-1 days'),
             ))
            ->add('inicioTime', 'date', array(
                        'label' => 'Início:*',
                        'input' => 'datetime',
                        'widget' => 'single_text',
                        'required' => true,
                        'format' => 'dd/MM/yyyy HH:mm:ss',
                        'data' => new \Datetime('-1 days'),
                ))
            ->add('fim', 'date', array(
                        'label' => 'Fim:',
                        'input' => 'datetime',
                        'widget' => 'single_text',
                        'format' => 'dd/MM/yyyy',
                        'data' => new \Datetime(),
                        
             ))
            ->add('fimTime', 'date', array(
                        'label' => 'Início:*',
                        'input' => 'datetime',
                        'widget' => 'single_text',
                        'required' => true,
                        'format' => 'dd/MM/yyyy HH:mm:ss',
                        'data' => new \Datetime('-1 days')
                ))
            ->add('tipo', 'choice', array(
                'label' => 'Tipo:',
                'choices' => $options['choices']
             ))
            ->add('tipo_data', 'choice', array(
                'label' => 'Tipo de data:',
                'expanded' => true,
                'multiple' => false,
                "required" => false,
                'choices' => array('r' => 'Registrado', 'p' => 'Pagamento', 'v' => 'Vencimento'),
                'attr' => array('class' => 'tipoData'),
                'data' => 'r'
             ))
           ->add('conta', 'entity', array(
                'label' => 'Conta:',
                'class' => 'FnxFinanceiroBundle:Conta',
                'property' => 'nome',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nome', 'ASC');
                },
             ))
           ->add('servico', 'entity', array(
                'empty_value' => "Todos os serviços",
                'label' => 'Serviço:',
                "required" => false,
                'class' => 'FnxAdminBundle:ServicoEscala',
                'property' => 'nome',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nome', 'ASC');
                },
             ))
            ->add('categoria', 'entity', array(
                'empty_value' => "Todas",
                'label' => 'Categoria:',
                "required" => false,
                'class' => 'FnxAdminBundle:Categoria',
                'property' => 'nome',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nome', 'ASC');
                },
             ))
            ->add('status', 'choice', array(
                'label' => 'Status:',
                'choices' => array("t" => "Todos",
                                   "a" => "Em andamento",
                                   "c" => "Concluído"),
                'data' => "a"
             ))
            ->add('meses', 'choice', array(
                'label' => 'Mes:',
                "required" => false,
                'choices' => array( 1 => "Janeiro",
                                    2 => "Fevereiro",
                                    3 => "Março",
                                    4 => "Abril",
                                    5 => "Maio",
                                    6 => "Junho",
                                    7 => "Julho",
                                    8 => "Agosto",
                                    9 => "Setembro",
                                    10 => "Outubro",
                                    11 => "Novembro",
                                    12 => "Dezembro"),
                'data' => $hoje->format("m")
             ))
             ->add("ano","choice", array(
                 "label" => "Ano:",
                 "choices" => $anos
             ))
             ->add("doc","text", array(
                 "label" => "Doc:",
                 "required" => false,
             ))
             ->add("data","date", array(
                 "label" => "Data:",
                 "format" => "dd-MM-yyy",
                 'input' => 'datetime',
                 'widget' => 'single_text',
                 "data" => $hoje,
                 "required" => false
             ));
    }

    public function getName()
    {
        return 'fnx_financeirobundle_filtertype';
    }
    
    public function getDefaultOptions(array $options) {
        return array('choices' => array());
    }
}
