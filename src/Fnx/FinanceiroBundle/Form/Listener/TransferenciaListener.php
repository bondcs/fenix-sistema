<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\FinanceiroBundle\Form\Listener;

use Symfony\Component\Form\Event\DataEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Fnx\FinanceiroBundle\Entity\Parcela;
use Fnx\FinanceiroBundle\Entity\Movimentacao;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManager;

/**
 * Description of TransferenciaListener
 *
 * @author bondcs
 */
class TransferenciaListener implements EventSubscriberInterface{
    
//    protected $em;
//
//
//    public function __construct($em) {
//        $this->em = $em;
//    }
    
    public static function getSubscribedEvents() {
        return array(FormEvents::POST_BIND => 'postBindData');
    }
    
    public function postBindData(DataEvent $event){
         
         $form = $event->getForm();
         $data = $event->getData();

         if($data->ehIgual() === true){
             $form->addError(new FormError("Não pode transferir para mesma conta."));
             return;
         }
         
         if(is_object($data->getContaSaque())){
            if(substr(str_replace(",", ".", $data->getValor()),3) > $data->getContaSaque()->getValor()){
                $form->addError(new FormError("Conta saque com saldo insuficiente."));
                return;
            }
         }
    
    }
}

?>
