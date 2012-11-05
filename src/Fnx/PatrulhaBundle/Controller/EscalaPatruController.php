<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\PatrulhaBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Fnx\AdminBundle\Entity\EscalaFun;
use Fnx\AdminBundle\Entity\Funcionario;
use Fnx\PatrulhaBundle\Form\Type\EscalaPatruType;
use Fnx\FinanceiroBundle\Form\Type\FilterType;

/**
 * Description of EscalaPatruController
 * 
 * @author bondcs
 * @Route("/adm/funcionario/escala/patrulha")
 */
class EscalaPatruController extends Controller{
    
    
    /**
     * @Route("/", name="escalaPatruIndex")
     * @Template()
     */
    public function indexAction(){
        $locais = $this->getDoctrine()->getRepository("FnxAdminBundle:Atividade")->loadLocal();
        $formFilter = $this->createForm(new FilterType());
        
        return array("formFilter" => $formFilter->createView(),
                     "entities" => $locais);
    }
    
    /**
     * @Route("/add/{data}", name="escalaPatruAdd", options={"expose" = true},requirements={"data" = ".+"})
     * @Template()
     */
    public function addAction($data){
        $escala = new EscalaFun();
        $form = $this->createForm(new EscalaPatruType(), $escala);
        
        return array(
            "form" => $form->createView(),
            "data" => $this->conv_data_to_us($data)
        );
    }
    
     /**
     * @Route("/create/{data}", name="escalaPatruCreate", options={"expose" = true})
     * @Template("FnxPatrulhaBundle:EscalaPatru:add.html.twig")
     */
    public function createAction($data){
        
        $escala = new EscalaFun();
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new EscalaPatruType($data),$escala, array("em" => $em));
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
            "data" => $data,
        );
    }
    
    /**
     * @Route("/edit/{id}/{data}", name="escalaPatruEdit", options={"expose" = true},requirements={"data" = ".+"})
     * @Template()
     */
    public function editAction($id, $data){
        $em = $this->getDoctrine()->getEntityManager();
        $escala = $em->find("FnxAdminBundle:EscalaFun", $id);
        $escala->atualizarEscalaFim();
        $form = $this->createForm(new EscalaPatruType(), $escala);
        
        return array(
            "form" => $form->createView(),
            "id" => $id,
            "data" => $this->conv_data_to_us($data)
        );
    }
    
     /**
     * @Route("/update/{id}/{data}", name="escalaPatruUpdate", options={"expose" = true},requirements={"data" = ".+"})
     * @Template("FnxAdminBundle:EscalaPatru:edit.html.twig")
     */
    public function updateAction($id, $data){
        
        $em = $this->getDoctrine()->getEntityManager();
        $escala = $em->find("FnxAdminBundle:EscalaFun", $id);
        $escala->atualizarEscalaFim();
        $form = $this->createForm(new EscalaPatruType($data),$escala, array("em" => $em,));

        $form->bindRequest($this->getRequest());
        if ($form->isValid()){

            $em->persist($escala);
            $em->flush();
            $responseSuccess = array(
                  'dialogName' => '.simpleDialog',
                  'message' => 'edit'
                );
            $response = new Response(json_encode($responseSuccess));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        
        return array(
            "form" => $form->createView(),
            "id" => $id,
            "data" => $data
        );
    }
    
    /**
     * @Route("/ultima/{data}", name="escalaPatruUltima", options={"expose" = true},requirements={"data" = ".+"})
     * @Method({"POST"})
     */
    function ultimaAction($data){
        
        $resp = $this->getDoctrine()->getRepository("FnxAdminBundle:EscalaFun");
        $escalas = $resp->locaEscalaByData($data);
        $escalasData = $resp->loadEscalaPatru($data);
        
        if ($escalasData != null){
            $response = new Response(json_encode(array("notifity" => "erroEscala01")));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        
        if ($escalas == null){
            throw $this->createNotFoundException("Escalas não encontradas.");
        }
        
        $em = $this->getDoctrine()->getEntityManager();
        $data = new \DateTime(substr($this->conv_data_to_us($data), 0 , 10));
        foreach ($escalas as $escala){
            $dia1 = $data->format("d");
            $dia2 = $escala->getInicio()->format("d");
            $interval = $dia1 - $dia2;
            
            $inicio = clone $escala->getInicio();
            $fim = clone $escala->getFim();
            
            $newEscala = new EscalaFun;
            $newEscala->setDescricao($escala->getDescricao());
            $newEscala->setInicio($inicio->modify($interval." days"));
            $newEscala->setFim($fim->modify($interval." days"));
            $newEscala->setLocal($escala->getLocal());
            $newEscala->setServicoEscala($escala->getServicoEscala());
            
            foreach ($escala->getFuncionarios() as $value) {
                $value->addEscalaFun($newEscala);
                $newEscala->addFuncionario($value);
                $em->persist($value);
            }
            $em->persist($newEscala);

        }
        $em->flush();
        $response = new Response(json_encode(array()));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        
    }
    
    /**
     * @Route("/check/{id}", name="escalaPatruCheck", options={"expose" = true})
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
     * @Route("/remove/{id}", name="escalaPatruRemove", options={"expose" = true})
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
     * @Route("/ajaxEscalaFun/{data}", name="escalaPatruAjax", options={"expose" = true},requirements={"data" = ".+"})
     * @Template()
     */
    public function ajaxAction($data){
        
        $escalasBanco = $this->getDoctrine()->getRepository("FnxAdminBundle:EscalaFun")->loadEscalaPatru($data);
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
    
    public static function conv_data_to_us($date){
	$dia = substr($date,0,2);
	$mes = substr($date,3,2);
	$ano = substr($date,6,4);
        $hora = substr($date, 11,2);
        $min = substr($date, 14,2);
	return "{$ano}-{$mes}-{$dia} {$hora}:{$min} ";
     }

}

?>
