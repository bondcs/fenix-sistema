<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\PatrulhaBundle\Form\Type;
use Symfony\Component\Form\Event\DataEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManager;

/**
 * Description of EscalaPatruListener
 *
 * @author bondcs
 */
class EscalaPatruListener implements EventSubscriberInterface{
    
    private $form;
    private $em;
    private $data;


    public function __construct(FormFactoryInterface $form, $data, EntityManager $em = null) {
        $this->form = $form;
        $this->em = $em;
        $this->data = $data;
    }
    
    public static function getSubscribedEvents() {
        return array(FormEvents::POST_BIND => 'postBindData');
    }
    
    public function postBindData(DataEvent $event){
         
         $resp = $this->em->getRepository("FnxAdminBundle:EscalaFun");
         $form = $event->getForm();
         $data = $event->getData();
         $inicio = $data->getInicio();
         $this->data = substr($this->data, 0, 10);
         
         $funcionarios = $form->get("funcionarios")->getData()->toArray();
         if ($funcionarios == array()){
             $form->get('funcionarios')->addError(new FormError("Selecione um funcionário"));
             return;
         }
         
         $hoje = new \DateTime($this->data);
         $data->setInicio($hoje->setTime($inicio->format("H"), $inicio->format("i"), 0));
         
         if (($inicio->format("H") + $data->getFim()) < 24){
             $hoje = new \DateTime($this->data);
             $data->setFim($hoje->setTime($inicio->format("H") + $data->getFim(), 00, 00));
         }else{
             $interval = new \DateInterval("P1D");
             $amanha = new \DateTime($this->data);
             $amanha->add($interval);
             $data->setFim($amanha->setTime(($inicio->format("H") + $data->getFim()) - 24, 00, 00));
         }
         
         $data->setLocal("Não definido");
         $data->setServicoEscala($this->em->createQuery("Select s FROM FnxAdminBundle:ServicoEscala s where s.nome = :param")
                                                        ->setParameter("param", "Patrulhamento")
                                                        ->getSingleResult());
         
         
//         foreach ($funcionarios as $value) {
//             $flag = true;
//             $escalas = $resp->verificaEscala($value->getId(), $data->getInicio(), $data->getFim());
//             if ($escalas != null){
//                  foreach ($escalas as $escala){
//                          if ($value->getEscalasEx()->contains($escala) && $escala->getId() == $data->getId()){
//                              $flag = false;
//                          }
//                  }
//                  if ($flag){
//                    $form->get('funcionarios')->addError(new FormError($value->getNome() . " não é válido nesta escala."));
//                  }
//             }
//             
//         }
    }
    
//    public function preSetData(DataEvent $event){
//         
//         $form = $event->getForm();
//         $data = $event->getData();
//         $hoje = new \DateTime;
//         var_dump($data->getInicio());
//         
//         $data->setFim(10);
//        
//         $data->setInicio($hoje->setTime($inicio->format("H"), $inicio->format("i"), 0));
//         
//         if (($inicio->format("H") + $data->getFim()) < 24){
//             $data->setFim($hoje->setTime($inicio->format("H") + $data->getFim(), 00, 00));
//         }else{
//             $amanha = new \DateTime("1 day");
//             $data->setFim($amanha->setTime(($inicio->format("H") + $data->getFim()) - 24, 00, 00));
//         }
//   
//    }
    
    
}

?>
