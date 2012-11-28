<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
/**
 * Description of AgendaController
 *
 * @author bondcs
 */
class AgendaController extends Controller{
    
    /**
     * @Route("/agenda/{data}", name="agendaIndex", defaults={"data" = null}, options={"expose" = true}, requirements={"data" = ".+"})
     * @Template()
     */
    public function indexAction($data){
        $data = $this->conv_data_to_us($data);
        $pedidos = $this->getDoctrine()->getRepository("FnxPedidoBundle:Pedido")->getByAgenda($data);
        $escalas = $this->getDoctrine()->getRepository("FnxAdminBundle:EscalaFun")->getByAgenda($data);
        $atividades = $this->getDoctrine()->getRepository("FnxAdminBundle:Atividade")->getByAgenda($data);
        $patrus = $this->getDoctrine()->getRepository("FnxAdminBundle:EscalaFun")->getByAgenda($data, true);
        $parcelas = $this->getDoctrine()->getRepository("FnxFinanceiroBundle:Movimentacao")->getByAgenda($data);
        
        return array(
            "pedidos" => $pedidos,
            "escalas" => $escalas,
            "patrus" => $patrus,
            "atividades" => $atividades,
            "data" => $data,
            "parcelas" => $parcelas
        );
    }
    
    public static function conv_data_to_us($date){
	$dia = substr($date,0,2);
	$mes = substr($date,3,2);
	$ano = substr($date,6,4);
	return "{$ano}-{$mes}-{$dia}";
    }
}

?>
