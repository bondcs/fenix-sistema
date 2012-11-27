<?php

namespace Fnx\CalenderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/calendario")
 */
class CalenderController extends Controller
{
    /**
     * @Route("/", name="calenderHome")
     * @Template()
     */
    public function indexAction()
    {
    \YsJQuery::useComponent(\YsJQueryConstant::COMPONENT_JQFULL_CALENDAR);
    
    $calendar = new \YsFullCalendar('myCalendarId');
    $calendar->setAllDaySlot(true);
    $dias = array("Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab");
    $meses = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
    $calendar->setDayNamesShort($dias);
    $calendar->setMonthNames($meses);
    $calendar->setButtonText(array("today" => "Hoje"));
    $calendar->setTheme(true);
    $calendar->setFirstDay(0);
    
    $escalasFun = $this->getDoctrine()->getRepository("FnxAdminBundle:EscalaFun")->findBy(array("ativo" => true));
    
    foreach ($escalasFun as $key => $value) {
        $nome = "";
        
        foreach ($value->getFuncionarios() as $keyFun => $funcionario){
             $nome = $keyFun == 0 ? $funcionario->getNome() : $nome." - ".$funcionario->getNome();
        }
        
        $event = new \YsCalendarEvent($key, $nome."(".$value->getDescricao().")");
        $event->setStart($value->getInicio());
        $event->setColor($value->getServicoEscala()->getCor());
        if ($value->getServicoEscala()->getNome() == "Patrulhamento"){
            $event->setUrl($this->generateUrl("escalaPatruIndex", array("data" => $value->getInicio()->format("Y-m-d"))));
        }else{
             $event->setUrl($this->generateUrl("escalaFunIndex", array("data" => $value->getInicio()->format("Y-m-d"))));
        }
        
        $calendar->addEvent($event);
    }
    
    $atividades = $this->getDoctrine()->getRepository("FnxAdminBundle:Atividade")->loadAtividade();

    foreach ($atividades as $key => $value) {
       $event = new \YsCalendarEvent($key, $value->getNome()." (".$value->getContrato()->getCliente()->getNome().")");
       $event->setStart($value->getdtInicio());
       $event->setEnd($value->getdtFim());
       $event->setColor($value->getServico()->getCor());
       $event->setUrl($this->generateUrl("atividadeShow", array("id" => $value->getId())));
       $calendar->addEvent($event);
    }
   
    $pedidos = $this->getDoctrine()->getRepository("FnxPedidoBundle:Pedido")->findAll();

    foreach ($pedidos as $key => $value) {
       $event = new \YsCalendarEvent($key, $value->getCliente()->getNome()." - Valor: ".number_format($value->getValorTotal(),2,',','.')." - Status: ".$value->getStatusToStr());
       $event->setStart($value->getData());
       $event->setEnd($value->getPrevisao());
       $event->setColor("Purple");
       $event->setUrl($this->generateUrl("PedidoEditar", array("id" => $value->getId())));
       $event->setClassName("pedido");
       $calendar->addEvent($event);
    }
    
    $servicos = $this->getDoctrine()->getRepository("FnxAdminBundle:ServicoEscala")->findAll();

    return array("calendar" => $calendar,
                 "servicos" => $servicos,
                 "atividades" => $atividades);
    }
}
