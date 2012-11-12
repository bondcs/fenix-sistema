<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\PedidoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Fnx\PedidoBundle\Entity\Item;
use Fnx\PedidoBundle\Form\ItemType;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
/**
 * Description of ItemController
 *
 * @author bondcs
 * @Route("adm/pedido/item")
 */
class ItemController extends Controller{
    
    /**
     * @Route("/new/{pedido}", name="itemAdd", options={"expose" = true})
     * @Template()
     * @return 
     */
    function newAction($pedido){
        
        $item = new Item();
        $form = $this->createForm(new ItemType(),$item);
        
        return array("form" => $form->createView(),
                     "pedido" => $pedido);
        
    }
    
    /**
     * @Route("/create/{pedido}", name="itemCreate")
     * @Template("FnxPedidoBundle:Item:new.html.twig")
     */
    function createAction($pedido){
        
        $item = new Item;
        $form = $this->createForm(new ItemType(),$item);
        $request = $this->getRequest();
        
        $form->bindRequest($request);
        if ($form->isValid()){
            $this->atualizarSessaoItem($item);
            if ($pedido != 0){
                $em = $this->getDoctrine()->getEntityManager();
                $pedido = $em->find("FnxPedidoBundle:Pedido", $pedido);
                $pedido->addItem($item);
                $em->persist($pedido);
                $em->flush();
            }
            $responseSuccess = array(
                  'dialogName' => '.simpleDialog',
                  'message' => 'add'
                );
            
            return $this->responseAjax($responseSuccess);
        }

        return array('form' => $form->createView(),
                     "pedido" => $pedido);
        
    }
    
    /**
     * @Route("/edit/{id}", name="itemEdit", options={"expose" = true})
     * @Template()
     */
    public function editAction($id){
        
        $sessao = $this->getRequest()->getSession();
        $itens = $sessao->get("itens");
        $item = $itens[$id];
        
        $form = $this->createForm(new ItemType(),$item);
        
        return array(
            "form" => $form->createView(),
            "id" => $id,
        );
    }
    
    /**
     * @Route("/update/{id}", name="itemUpdate", options={"expose" = true})
     * @Method({"POST"})
     * @Template("FnxAdminBundle:Pedido:edit.html.twig")
     */
    public function updateAction($id){
        
        $sessao = $this->getRequest()->getSession();
        $itens = $sessao->get("itens");
        $item = $itens[$id];
        
        $form = $this->createForm(new ItemType(),$item);
        $request = $this->getRequest();
        
        $form->bindRequest($request);
        if ($form->isValid()){
            $this->atualizarSessaoItem($item,$id);
            if ($item->getId() != null){
                $em = $this->getDoctrine()->getEntityManager();
                $em->merge($item);
                $em->flush();
            }
            $responseSuccess = array(
                  'dialogName' => '.simpleDialog',
                  'message' => 'edit'
            );
            
            return $this->responseAjax($responseSuccess);
        }
 
        return array(
            "form" => $form->createView(),
            "id" => $id
        );
    }
    
    /**
     * @Route("/delete/{id}", name="itemDelete", options={"expose" = true})
     * @Method({"POST"})
     */
    function deleteAction($id){
        
        $sessao = $this->getRequest()->getSession();
        $itens = $sessao->get("itens");
        $em = $this->getDoctrine()->getEntityManager();
//        if (count($itens) <= 1){
//            return 
//        }
        
        if ($itens[$id]->getId() != null){
            $item = $em->find("FnxPedidoBundle:Item", $itens[$id]->getId());
            $em->remove($item);
            $em->flush();
        }
        
        unset($itens[$id]);
        $sessao->set("itens", $itens);
        
        return $this->responseAjax(array());
    }
    
    /**
     * @Route("/ajaxItem", name="ajaxItem", options={"expose" = true})
     * @return 
     */
    public function ajaxItemAction(){
        
        $sessao = $this->getRequest()->getSession();
        $itensSessao = $sessao->get("itens");
        $itens['aaData'] = array();
        foreach ($itensSessao as $key => $itemSessao){
            $item['nome'] = $itemSessao->getNome();
            $item['descricao'] = $itemSessao->getDescricao();
            $item['quantidade'] = $itemSessao->getQuantidade();
            $item['preco'] = number_format($itemSessao->getPreco(),2,',','.');
            $item['total'] = number_format($itemSessao->getTotal(),2,',','.');
            $item['totalNumber'] = $itemSessao->getTotal();
            $item['id'] = $key;
            $itens['aaData'][] = $item;
        }
        
        return $this->responseAjax($itens);
    }
    
    public function atualizarSessaoItem($item, $id = null){
        
        $sessao = $this->getRequest()->getSession();
        $itens = $sessao->get("itens");
        if ($id == null){
            $itens[] = $item;
        }else{
            $itens[$id] = $item;
            
        };
        $sessao->set("itens", $itens); 
    }
    
    public function responseAjax($json){
        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}

?>
