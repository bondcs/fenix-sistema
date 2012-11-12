<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Fnx\AdminBundle\Entity\EscalaFun;
use Fnx\AdminBundle\Entity\Funcionario;
use Fnx\AdminBundle\Form\Type\EscalaFunType;
use Fnx\FinanceiroBundle\Form\Type\FilterType;

/**
 * Description of EscalaFunController
 * 
 * @author bondcs
 * @Route("/adm/funcionario/escala")
 */
class EscalaFunController extends Controller{
    
    
    /**
     * @Route("/index/{data}", name="escalaFunIndex", defaults={"data" = null})
     * @Template()
     */
    public function indexAction($data){
        $formFilter = $this->createForm(new FilterType());
        if ($data != null){
            $data = new \DateTime($data);
            $formFilter["inicio"]->setData($data);
        }else{
            $formFilter["inicio"]->setData(new \Datetime("2012-01-01"));
        }
        return array("formFilter" => $formFilter->createView());
    }
    
    /**
     * @Route("/add", name="escalaFunAdd", options={"expose" = true})
     * @Template()
     */
    public function addAction(){
        $escala = new EscalaFun();
        $form = $this->createForm(new EscalaFunType(), $escala);
        
        return array(
            "form" => $form->createView(),
        );
    }
    
     /**
     * @Route("/create", name="escalaFunCreate", options={"expose" = true})
     * @Template("FnxAdminBundle:EscalaFun:add.html.twig")
     */
    public function createAction(){
        
        $escala = new EscalaFun();
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new EscalaFunType(),$escala, array("em" => $em));
        $form->bindRequest($this->getRequest());
        
        if ($form->isValid()){
            
            $em->persist($escala);
            $em->flush();
            $responseSuccess = array(
                  'dialogName' => '.simpleDialog',
                  'message' => 'add'
                );
            $response = new Response(json_encode($responseSuccess));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        
        return array(
            "form" => $form->createView(),
        );
    }
    
    /**
     * @Route("/edit/{id}", name="escalaFunEdit", options={"expose" = true})
     * @Template()
     */
    public function editAction($id){
        $em = $this->getDoctrine()->getEntityManager();
        $escala = $em->find("FnxAdminBundle:EscalaFun", $id);
        $form = $this->createForm(new EscalaFunType(), $escala);
        
        return array(
            "form" => $form->createView(),
            "id" => $id
        );
    }
    
     /**
     * @Route("/update/{id}", name="escalaFunUpdate", options={"expose" = true})
     * @Template("FnxAdminBundle:EscalaFun:edit.html.twig")
     */
    public function updateAction($id){
        
        $em = $this->getDoctrine()->getEntityManager();
        $escala = $em->find("FnxAdminBundle:EscalaFun", $id);
        $form = $this->createForm(new EscalaFunType(),$escala, array("em" => $em));

        $form->bindRequest($this->getRequest());
        if ($form->isValid()){
            
            $em->merge($escala);
            $em->flush();
            $responseSuccess = array(
                  'dialogName' => '.simpleDialog',
                  'message' => 'add'
                );
            $response = new Response(json_encode($responseSuccess));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        
        return array(
            "form" => $form->createView(),
            "id" => $id
        );
    }
    
    /**
     * @Route("/check/{id}", name="escalaFunCheck", options={"expose" = true})
     * @Method({"POST"})
     */
    function checkAction($id){
        
        $em = $this->getDoctrine()->getEntityManager();
        $escala = $em->find("FnxAdminBundle:EscalaFun", $id);
        if (!$escala){
            throw $this->createNotFoundException("Escala não encontrada.");
        }
        
        $valor =  $escala->getAtivo() ? false : true;
        $escala->setAtivo($valor);
        
        $em->merge($escala);
        $em->flush();

        $response = new Response(json_encode(array()));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        
    }
    
    /**
     * @Route("/remove/{id}", name="escalaFunRemove", options={"expose" = true})
     */
    public function removeAction($id){
       $escala = $this->getDoctrine()->getRepository("FnxAdminBundle:EscalaFun")->find($id);
       if (!$escala ){
            throw $this->createNotFoundException("Escala não encontrada.");
        }
       $em = $this->getDoctrine()->getEntityManager();
       $em->remove($escala);
       $em->flush();
       
       $this->get("session")->setFlash("success","Escala excluída.");
        return $this->redirect($this->generateUrl("escalaFunIndex"));
    }

    /**
     * @Route("/ajaxEscalaFun/{status}/{servico}/{inicio}", name="escalaFunAjax", options={"expose" = true},requirements={"inicio" = ".+", "fim" = ".+"})
     * @Template()
     */
    public function ajaxAction($status, $servico, $inicio){
        
        $escalasBanco = $this->getDoctrine()->getRepository("FnxAdminBundle:EscalaFun")->loadEscalaFun($status, $servico, $inicio);
        $escalas['aaData'] = array();
        
        foreach ($escalasBanco as $key => $value) {
            $value['funcionariosString'] = "";
            foreach ($value['funcionarios'] as $keyFun => $funcionario ){
                $value['funcionariosString'] = $keyFun == 0 ? $funcionario['nome'] : $value['funcionariosString']." - ".$funcionario['nome'];
                
            }
//          $value['escalaN'] = $value['funcionario']['escalaDiariaInicio']->format('H:i:s')." - ".$value['funcionario']['escalaDiariaFinal']->format('H:i:s');
            $value['escalaEx'] = $value['inicio']->format('d/m/Y H:i')." - ". $value['fim'] = $value['fim']->format('d/m/Y H:i');
            $escalas['aaData'][] = $value;
        }
       
        $response = new Response(json_encode($escalas));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
    
    public function verificaEscala(){
        
    }

}

?>
