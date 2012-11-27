<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\FinanceiroBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Fnx\FinanceiroBundle\Entity\Movimentacao;
use Fnx\FinanceiroBundle\Form\Type\MovimentacaoType;
use Fnx\FinanceiroBundle\Form\Type\FilterType;
use Symfony\Component\HttpFoundation\Response;
use Fnx\FinanceiroBundle\Entity\Parcela;
use Fnx\FinanceiroBundle\Entity\Registro;

/**
 * Description of MovimentacaoController
 *
 * @author bondcs
 * @Route("/financeiro")
 */
class MovimentacaoController extends Controller{
    
    /**
     * @Route("/{doc}", name="financeiroShow", defaults={"doc" = null})
     * @Template()
     *
     */
    function indexAction($doc){
        
        $formFilter = $this->createForm(new FilterType(), null, array(
                    'choices' => array(
                        'todas' => 'Todas',
                        'atraso' => 'Em atraso',
                        'aberto' => 'Em aberto',
                        'quitada' => 'Quitadas' 
                     )
        ));
        
        if ($doc != null){
            $formFilter["doc"]->setData($doc);
        }
        
        return array('formFilter' => $formFilter->createView());
    }

    /**
     * @Route("/new", name="movimentacaoNew", options={"expose" = true})
     * @Template()
     */
    function newAction(){
        
        $movimentacao = new Movimentacao();
        $form = $this->createForm(new MovimentacaoType(), $movimentacao);
        
        return array('form' => $form->createView());
        
    }
    
    /**
     * @Route("/create", name="movimentacaoCreate")
     * @Template("FnxFinanceiroBundle:Movimentacao:new.html.twig")
     */
    function createAction(){
        
        $movimentacao = new Movimentacao();
        $form = $this->createForm(new MovimentacaoType(), $movimentacao);
        $request = $this->getRequest();
        $form->bindRequest($request);
        if ($form->isValid()){

            $result = $movimentacao->atualizaCaixa();
            if ($result === false){
                $responseSuccess = array(
                 'dialogName' => '.simpleDialog',
                 'message' => 'erroSaldo');
                return $this->responseAjax($responseSuccess);
            }
                
            $movimentacao->getParcela()->setNumero(0);
            $registro = $movimentacao->getParcela()->getRegistro();
            $registro->setValor($movimentacao->getValor());
            $registro->setPrimeiraParcela($movimentacao->getParcela()->getDtVencimento());
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($movimentacao);
            $em->flush();
            $responseSuccess = array(
                 'dialogName' => '.simpleDialog',
                 'message' => 'add'
            );
            return $this->responseAjax($responseSuccess);
            
        }

        return array('form' => $form->createView());
        
    }
    
     /**
     * @Route("/edit-parcela/{id}", name="parcelaGeralEdit", options={"expose" = true})
     * @Template()
     */
    public function editAction($id){
        
        $movimentacao = $this->getDoctrine()->getEntityManager()->find("FnxFinanceiroBundle:Movimentacao", $id);
        $form = $this->createForm(new MovimentacaoType(),$movimentacao, array(
             'em' => $this->getDoctrine()->getEntityManager()
        ));
        
        
        return array('form' => $form->createView(),
                     'id' => $id);
        
    }
    
    /**
     * @Route("/update-parcela/{id}", name="parcelaGeralUpdate")
     * @Template("FnxFinanceiroBundle:Movimentacao:edit.html.twig")
     */
    function updateAction($id){
        
        $movimentacao = $this->getDoctrine()->getEntityManager()->find("FnxFinanceiroBundle:Movimentacao", $id);
        $valorCaixaOld = $movimentacao->getValorPago();
        
        $form = $this->createForm(new MovimentacaoType(), $movimentacao, array(
             'em' => $this->getDoctrine()->getEntityManager())); 
        
        $request = $this->getRequest();
        $form->bindRequest($request);
        if ($form->isValid()){
            
            if ($movimentacao->getParcela()->getFinalizado()){
                  $conta = $movimentacao->getParcela()->getRegistro()->getConta(); 
                  if ($movimentacao->getMovimentacao() == 'r'){
                    $diferenca = substr(str_replace(",", ".", $movimentacao->getValorPago()),3) - $valorCaixaOld;
                  }else{
                    $diferenca = $valorCaixaOld - substr(str_replace(",", ".", $movimentacao->getValorPago()),3);    
                  }
                  
                  if ($movimentacao->getMovimentacao() == 'p' && abs(($diferenca)) > $conta->getValor() && $diferenca < 0){
                        $responseSuccess = array(
                            'dialogName' => '.simpleDialog',
                            'message' => 'erroSaldo');
                        return $this->responseAjax($responseSuccess);
                  }
                  
                  $conta->deposita($diferenca);
            }
            
            $em = $this->getDoctrine()->getEntityManager();
            $em->merge($movimentacao);
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
     * @Route("/remove-parcela/{id}", name="removeParcelaGeral", options={"expose" = true})
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
     *
     * @Route("/ajaxTransacaoGeral/{inicio}//{fim}/{tipo}/{data}/{conta}/{categoria}/{doc}", name="ajaxTransacaoGeral", options={"expose" = true},requirements={"inicio" = ".+", "fim" = ".+"})
     */
    public function ajaxTransacaoGeralAction($inicio, $fim, $tipo, $data, $conta, $categoria, $doc){
        $movimentacoesBanco = $this->getDoctrine()->getRepository("FnxFinanceiroBundle:Movimentacao")->getMovimentacoesGerais($inicio, $fim, $tipo, $data, $conta, $categoria, $doc);
        //var_dump($movimentacoesBanco);die();
        $movimentacoes['aaData'] = array();
        
        foreach ($movimentacoesBanco as $key => $value) {
            $value['data']= $value['data']->format('d/m/Y H:s:i');
            $value['valorNumber'] = $value['valor'];
            $value['valor'] = number_format($value['valor'],2,',','.');
            $value['tipo'] = $value['movimentacao'] == 'r' ? "R" : "P";
            $numero =  $value['parcela']['numero'] != 0 ? " #".$value['parcela']['numero'] : "";
            $value['descricao'] = $value['parcela']['registro']['descricao'].$numero;
            $value['data_pagamento'] = $value['data_pagamento'] ? $value['data_pagamento']->format('d/m/Y H:s:i') : "-";
            $value['situacao'] = $this->verificaSituacao($value['parcela']['finalizado'], $value['parcela']['dt_vencimento']);
            $value['dt_vencimento'] = $value['parcela']['dt_vencimento'] ? $value['parcela']['dt_vencimento']->format('d/m/Y') : "-";
            
                
            $movimentacoes['aaData'][] = $value;
        }
        
        return $this->responseAjax($movimentacoes);
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
