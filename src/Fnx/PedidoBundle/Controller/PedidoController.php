<?php

namespace Fnx\PedidoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Fnx\AdminBundle\Entity\Cliente;
use Fnx\PedidoBundle\Entity\Pedido;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("pedidos/")
 */
class PedidoController extends Controller
{
    /**
     * @Route("listar/",name="PedidoListar")
     * @Template()
     */
    public function indexAction()
    {
        $session = $this->getRequest()->getSession();
        $session->set("itens", array());
        $pedidos = $this->getDoctrine()->getRepository("FnxPedidoBundle:Pedido")->findAll();

        return $this->render("FnxPedidoBundle:Pedido:index.html.twig",array('pedidos' => $pedidos));
    }

    /**
     * @Route("add/",name="PedidoCadastrar")
     * @Template()
     */
    public function cadastrarAction(Request $request)
    {
        $pedido = new Pedido;
	$form = $this->createForm(new \Fnx\PedidoBundle\Form\PedidoType(), $pedido);
        
        if($request->getMethod() == "POST"){
	    $form->bindRequest($request);
            $cliente = $this->getDoctrine()->getRepository("FnxAdminBundle:Cliente")->findOneBy(array("nome" => $form->get("cliente")->getData()));
            $cliente == null ? $form->get("cliente")->addError(new FormError("Cliente inválido")) : $pedido->setCliente($cliente);
            if ($this->get("session")->get("itens") == array()){
                $this->get("session")->setFlash("error", "Nenhum item foi adicionado ao pedido.");
		return $this->redirect($this->generateUrl('PedidoCadastrar')); 
            };
            
            if($form->isValid()){
                $em = $this->getDoctrine()->getEntityManager();
		$usuarioLogado = $this->get('security.context')->getToken()->getUser();
                $pedido->setItens($this->get("session")->get("itens"));
                $pedido->setResponsavel($usuarioLogado);
                $em->persist($pedido);
                $em->flush();
                
                $this->get("session")->setFlash("success", "Pedido registrado.");
		return $this->redirect($this->generateUrl('PedidoListar'));
            }
        }

	    return $this->render("FnxPedidoBundle:Pedido:Cadastrar.html.twig",array(
		    'form' => $form->createView(),
                    "pedido" => $pedido
            ));
    }
    /**
     * @Route("editar/{id}",name="PedidoEditar", options={ "expose" = true})
     * @Template()
     */
     public function editarAction($id){

         $pedido = $this->getDoctrine()->getRepository("FnxPedidoBundle:Pedido")->find($id);
         $form = $this->createForm(new \Fnx\PedidoBundle\Form\PedidoType(), $pedido);
         $form->get("cliente")->setData($pedido->getCliente()->getNome());
         $request = $this->getRequest();
         
         if($request->getMethod() == "POST"){
	    $form->bindRequest($request);
            $cliente = $this->getDoctrine()->getRepository("FnxAdminBundle:Cliente")->findOneBy(array("nome" => $form->get("cliente")->getData()));
            $cliente == null ? $form->get("cliente")->addError(new FormError("Cliente inválido")) : $pedido->setCliente($cliente);
            if ($this->get("session")->get("itens") == array()){
                $this->get("session")->setFlash("error", "Nenhum item foi adicionado ao pedido.");
		return $this->redirect($this->generateUrl('PedidoEditar', array("id" => $id))); 
            };
            
            
            if($form->isValid()){
                $em = $this->getDoctrine()->getEntityManager();
//                   $pedido->getItens()->clear();
//                   $pedido->setItens($this->get("session")->get("itens"));
////                
//                foreach ($this->get("session")->get("itens") as $item){
//                    $pedido->addItem($item);
//                }
//                //var_dump($pedido->getItens()->count());die();
                $em->merge($pedido);
                $em->flush();
                
                $this->get("session")->setFlash("success", "Pedido alterado.");
		return $this->redirect($this->generateUrl('PedidoListar'));
            }
         }else{
             $this->get("session")->set("itens", $pedido->getItens()->toArray()); 
         }
         

         return $this->render("FnxPedidoBundle:Pedido:Cadastrar.html.twig", array('pedido' => $pedido, 'form' => $form->createView()));
     }
     
     /**
     * @Route("delete/{id}", name="PedidoDeletar", options={"expose" = true})
     * @Template()
     */
    public function deleteAction($id){
        
        $pedido = $this->getDoctrine()->getRepository("FnxPedidoBundle:Pedido")->find($id);
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($pedido);
        $em->flush();
        
        $this->get("session")->setFlash("success", "Pedido excluído.");
        return $this->redirect($this->generateUrl("PedidoListar"));     
        
    }
    
    /**
     * @Route("fechar/{id}", name="PedidoFechar", options={"expose" = true})
     */
    public function fecharAction($id){
        
        $pedido = $this->getDoctrine()->getRepository("FnxPedidoBundle:Pedido")->find($id);
        $pedido->fechar();
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($pedido);
        $em->flush();
        
        $this->get("session")->setFlash("success", "Status alterado.");
	return $this->redirect($this->generateUrl('PedidoEditar', array("id" => $id)));
        
    }
    
    /**
     * @Route("pagamento/{id}", name="PedidoPagamento", options={"expose" = true})
     * @Template("FnxPedidoBundle:Pagamento:index.html.twig")
     */
    public function pagamentoAction($id){
        
        $pedido = $this->getDoctrine()->getRepository("FnxPedidoBundle:Pedido")->find($id);
        return array("pedido" => $pedido);
        if ($pedido->getRegistro() != null){
            return $this->forward("FnxPedidoBundle:Pagamento:edit", array("id" => $id));
        }else{
            return $this->forward("FnxPedidoBundle:Pagamento:new", array("id" => $id));
        }
    }
    
    /**
     * @Route("/ajaxPedido/{status}", name="ajaxPedido", options={"expose" = true})
     * @Template()
     */
    public function ajaxAction($status){
        
        $valuesBanco = $this->getDoctrine()->getRepository("FnxPedidoBundle:Pedido")->loadPedido($status);
        $values['aaData'] = array();
        
        foreach ($valuesBanco as $key => $value) {
            $pedido['cliente']['nome'] = $value->getCliente()->getNome();
            $pedido['data'] = $value->getData()->format('d/m/Y');
            $pedido['previsao'] = $value->getPrevisao()->format('d/m/Y');
            $pedido['data_fechamento'] = $value->getDataFechamento() != null ? $value->getDataFechamento()->format('d/m/Y') : "-";
            $pedido['valorNumber'] = $value->getValorTotal();
            $pedido['valor'] = number_format($value->getValorTotal(),2,',','.');
            $pedido['id'] = $value->getId();
            $values['aaData'][] = $pedido;
            $pedido = array();
        }
       
        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
    
}

