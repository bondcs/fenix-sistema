<?php

namespace Fnx\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ChamadoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('assunto', 'text', array(
		'label' => 'Título: '
	    ))
            ->add('conteudo', 'textarea', array(
		'label' => 'Conteudo: '
	    ))
        ;
    }

    public function getName()
    {
        return 'fnx_adminbundle_chamadotype';
    }
}
