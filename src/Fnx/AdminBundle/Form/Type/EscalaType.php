<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Fnx\AdminBundle\Entity\Funcionario;
use Fnx\AdminBundle\Form\Listener\EscalaListener;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
/**
 * Description of ResponsavelType
 *
 * @author bondcs
 */
class EscalaType extends AbstractType{
    
    private $em;
    
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function getName() {
        return 'fnx_admin_escala';
    }
    
    function buildForm(FormBuilder $builder, array $options) {
            
         $builder->add('dtInicio', 'date', array(
                        'label' => 'Início:*',
                        'input' => 'datetime',
                        'widget' => 'single_text',
                        'required' => true,
                        'format' => 'dd/MM/yyyy HH:mm:ss'
                ))
                 ->add('dtFim', 'date', array(
                        'label' => 'Fim:*',
                        'input' => 'datetime',
                        'widget' => 'single_text',
                        'required' => true,
                        'format' => 'dd/MM/yyyy HH:mm:ss'    
                ))
                 ->add('funcionarios','entity',array(
                        'label' => 'Agentes',
                        'class' => 'FnxAdminBundle:Funcionario',
                        'expanded' => false,
                        'multiple' => true, 
                     
                ))
                 ->add('local','text', array(
                        'label' => 'Posto:*'
                ))
                 ->add('custoUnitario','text', array(
                        'label' => 'Custo:*'
                 ));
         
             $subscriber = new EscalaListener($builder->getFormFactory(), $this->em);
             $builder->addEventSubscriber($subscriber);
        
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => "Fnx\AdminBundle\Entity\Escala",
        );
    }
    
}

?>
