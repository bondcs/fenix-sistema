<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\PedidoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Fnx\FinanceiroBundle\Entity\Registro;
use Fnx\FinanceiroBundle\Entity\Movimentacao;
use Fnx\FinanceiroBundle\Form\Type\RegistroType;
use Fnx\FinanceiroBundle\Form\Type\ParcelaType;
use Fnx\FinanceiroBundle\Entity\Parcela;
use Fnx\FinanceiroBundle\Form\Type\MovimentacaoType;

/**
 * Description of PagamentoController
 *
 * @author bondcs
 * @Route("/pedido/pagamento")
 */
class PagamentoController extends Controller{
    
    /**
     * @Route("/new/{id}", name="pagamentoPedidoNew")
     * @Template()
     */
    function newAction($id){
        
        $pedido = $this->getDoctrine()->getRepository("FnxPedidoBundle:Pedido")->find($id);
        $form = $this->get("fnx_financeiro.registro.form");
        $form["valor"]->setData($pedido->getValorTotal());
        return array('form' => $form->createView(),
                     'id' => $id);
        
    }
    
    /**
     * @Route("/create/{id}", name="pagamentoPedidoCreate")
     * @Template("FnxPedidoBundle:Pagamento:new.html.twig")
     */
    function createAction($id){
        $form = $this->get("fnx_financeiro.registro.form");
        $formHandler = $this->get("fnx_financeiro.registro.form.handler");
        $em = $this->getDoctrine()->getEntityManager();
        $pedido = $this->getDoctrine()->getRepository("FnxPedidoBundle:Pedido")->find($id);
        
        $registro = new Registro();
        $categoria = $this->em->createQuery("SELECT c FROM FnxAdminBundle:Categoria c WHERE c.nome = :param")->setParameter("param", "Pedido")->getSingleResult();
        $registro->setCategoria($categoria);
        $pedido->setRegistro($registro);
        $registro->setDescricao($pedido->getCliente()->getNome()." - pedido: ". $pedido->getData()->format("d/m/Y"));
        $process = $formHandler->process($registro);
        if ($process){
            $this->get('session')->setFlash("success","Registro efetuado.");
            return $this->redirect($this->generateUrl("PedidoPagamento", array('id' => $id)));
        }

        return array('form' => $form->createView(),
                     'id' => $id);
        
    }
    
    /**
     * @Route("/edit/{id}", name="PagamentoPedidoEdit")
     * @Template()
     */
    function editAction($id){
        $pedido = $this->getDoctrine()->getRepository("FnxPedidoBundle:Pedido")->find($id);
        $form = $this->createForm(new RegistroType());
        
        return array('form' => $form->createView(),
                     'pedido' => $pedido);
        
    }
    
    /**
     * @Route("/remove/{id}", name="PagamentoPedidoRemove")
     * @Template()
     */
    function removeAction($id){
        $pedido = $this->getDoctrine()->getRepository("FnxPedidoBundle:Pedido")->find($id);
        
        $em = $this->getDoctrine()->getEntityManager();
        $registro = $pedido->getRegistro();
        $em->remove($registro);
        $em->flush();
        $this->get("session")->setFlash("success","Pagamento excluído");
        return $this->redirect($this->generateUrl("PedidoPagamento", array("id" => $id)));
        
    }
    
    
    
    /**
     * @Route("/ajaxParcelaPedido/{id}", name="ajaxParcelaPedido", options={"expose" = true})
     * @return 
     */
    public function ajaxParcelaAction($id){
        
        $parcelasBanco = $this->getDoctrine()->getRepository("FnxFinanceiroBundle:Movimentacao")->loadParcelaPedido($id);
        //var_dump($parcelasBanco);die();
        $parcelas['aaData'] = array();
        if ($parcelasBanco != null){
            foreach ($parcelasBanco[0]['registro']['parcelas'] as $key => $value) {
                $value['situacao'] = $this->verificaSituacao($value['finalizado'], $value['dt_vencimento']);
                $value['dt_vencimento']= $value['dt_vencimento']->format('d/m/Y');
                $value['movimentacao']['data'] = $value['movimentacao']['data']->format('d/m/Y H:i:s');
                $value['movimentacao']['valorNumber'] = $value['movimentacao']['valor'];
                $value['movimentacao']['valor'] = number_format($value['movimentacao']['valor'],2,',','.');
                $value['movimentacao']['data_pagamento'] = $value['movimentacao']['data_pagamento'] ? $value['movimentacao']['data_pagamento']->format('d/m/Y H:i:s') : '-';
                $value['movimentacao']['valor_pagoNumber'] = $value['movimentacao']['valor_pago'];
                $value['movimentacao']['valor_pago'] = number_format($value['movimentacao']['valor_pago'],2,',','.');

                $parcelas['aaData'][] = $value;
            }
        }
       
        
        return $this->responseAjax($parcelas);
    }
    
    /**
     * @Route("/new-parcela/{id}", name="parcelaPedidoNew", options={"expose" = true})
     * @Template()
     */
    public function parcelaNewAction($id){
        
        $movimentacao = new Movimentacao;
        $form = $this->createForm(new MovimentacaoType(), $movimentacao, array(
            'em' => $this->getDoctrine()->getEntityManager()
        ));
        
        return array('form' => $form->createView(),
                     'id' => $id);
        
    }
    
    /**
     * @Route("/create-parcela/{id}", name="parcelaPedidoCreate")
     * @Template("FnxFinanceiroBundle:Pagamento:parcelaNew.html.twig")
     */
    function parcelaCreateAction($id){
        
        $movimentacao = new Movimentacao;
        $form = $this->createForm(new MovimentacaoType(), $movimentacao, array(
            'em' => $this->getDoctrine()->getEntityManager()
        ));
        $request = $this->getRequest();
        $form->bindRequest($request);
        if ($form->isValid()){
            
            $em = $this->getDoctrine()->getEntityManager();
            $pedido = $em->find("FnxPedidoBundle:Pedido", $id);
            $registro = $pedido->getRegistro();
            $parcela = $movimentacao->getParcela();
            $registro->addParcela($parcela);
            $parcela->setNumero($registro->getParcelas()->count());
            $movimentacao->getParcela()->setRegistro($registro);
            
            
            $movimentacao->setMovimentacao('r');
            $em->persist($movimentacao);
            $em->flush();
            $responseSuccess = array(
                  'dialogName' => '.simpleDialog',
                  'message' => 'add'
                );
            return $this->responseAjax($responseSuccess);
        }

        return array('form' => $form->createView(),
                     'id' => $id);
        
    }
    
    /**
     * @Route("/edit-parcela/{id}", name="parcelaPedidoEdit", options={"expose" = true})
     * @Template()
     */
    public function parcelaEditAction($id){
        
        $movimentacao = $this->getDoctrine()->getEntityManager()->find("FnxFinanceiroBundle:Movimentacao", $id);
        //$movimentacao->setDataPagamento(new \DateTime());
        $form = $this->createForm(new MovimentacaoType(),$movimentacao, array(
//             'validation_groups' => array('Default','edit'),
             'em' => $this->getDoctrine()->getEntityManager()
        ));
        
//        if ($movimentacao->getParcela()->getFinalizado()){
//            return $this->responseAjax(array('erro' => 'erro'));
//        }
        
        return array('form' => $form->createView(),
                     'id' => $id);
        
    }
    
    /**
     * @Route("/update-parcela/{id}", name="parcelaPedidoUpdate")
     * @Template("FnxFinanceiroBundle:Pagamento:parcelaEdit.html.twig")
     */
    function parcelaUpdateAction($id){
        
        $movimentacao = $this->getDoctrine()->getEntityManager()->find("FnxFinanceiroBundle:Movimentacao", $id);
        $valorCaixaOld = $movimentacao->getValorPago();
        
        $form = $this->createForm(new MovimentacaoType(), $movimentacao, array(
             'em' => $this->getDoctrine()->getEntityManager())); 
        
        $request = $this->getRequest();
        $form->bindRequest($request);
        if ($form->isValid()){
            
            if ($movimentacao->getParcela()->getFinalizado()){
                  $diferenca = substr(str_replace(",", ".", $movimentacao->getValorPago()),3) - $valorCaixaOld;
                  $registro = $movimentacao->getParcela()->getRegistro();
                  $registro->getConta()->deposita($diferenca);
            }
            
            
            $movimentacao->setMovimentacao('r');
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($movimentacao);
            $em->flush();
            $responseSuccess = array(
                  'dialogName' => '.simpleDialog',
                  'message' => 'edit'
            );
            return $this->responseAjax($responseSuccess);
        }

        return array('form' => $form->createView(),
                     'id' => $id);
        
    }
    
    /**
     * @Route("/remove-parcela/{id}", name="removeParcelaPedido", options={"expose" = true})
     */
    public function removeParcelaAction($id){
        
        $em = $this->getDoctrine()->getEntityManager();
        $movimentacao = $em->find("FnxFinanceiroBundle:Movimentacao", $id);
        if (!$movimentacao){
            throw $this->createNotFoundException("Movimentação não encontrada.");
        }
        
        $em->remove($movimentacao);
        $em->flush();
        
        return $this->responseAjax(array());
    }
    
    /**
     * @Route("/finalizar-parcela/{id}", name="finalizarParcelaPedido", options={"expose" = true})
     */
    public function finalizarParcelaAction($id){
        
        $em = $this->getDoctrine()->getEntityManager();
        $movimentacao = $em->find("FnxFinanceiroBundle:Movimentacao", $id);
        $conta = $movimentacao->getParcela()->getRegistro()->getConta();
        if (!$movimentacao){
            throw $this->createNotFoundException("Movimentação não encontrada.");
        }
        
        $erros = array();
        if ($movimentacao->getValorPago() == "0.00"){ 
            $erros[] = 'erro01';   
        }
        
        if ($movimentacao->getParcela()->getFinalizado()){ 
            $erros[] = 'erro02';      
        }

        if ($movimentacao->getDataPagamento() == null){ 
            $erros[] = 'erro03';      
        }
        if ($movimentacao->getMovimentacao() == 'p' && $movimentacao->getValor() > $conta->getValor()){
            $erros[] = 'erro04';
        }
        
        if(count($erros) > 0){
            return $this->responseAjax($erros);
        }else{
            $movimentacao->getParcela()->setFinalizado(true);
            $valorPago = $movimentacao->getValorPago();
            
            if ($movimentacao->getMovimentacao() == 'r'){
                $conta->deposita($valorPago);
            }else{
                $conta->saque($valorPago);
            }
            
            $em->persist($movimentacao);
            $em->flush();
            
            return $this->responseAjax(array('finalizar' => 'success'));
            
        }
    }
    
    public function responseAjax($json){
        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    public function verificaSituacao($param, $vencimento){
        
        if ($param){
            return "Finalizado";
        }elseif ($vencimento < new \DateTime("-1 days")) {
            return "Em atraso";
        }else{
            return "Em aberto";
        }
        
    }
}

?>
