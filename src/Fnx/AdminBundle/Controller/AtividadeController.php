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
use Fnx\AdminBundle\Entity\Atividade;
use Fnx\AdminBundle\Entity\Contrato;
use Fnx\AdminBundle\Form\Type\AtividadeType;
use Fnx\AdminBundle\Form\Type\ContratoType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of AtividadeController
 *
 * @author bondcs
 * @Route("/adm/atividade")
 */
class AtividadeController extends Controller{
    
    
    /**
     * @Route("/", name="atividadeHome")
     * @Template()
     */
    public function indexAction(){
        
//        $dbal = $this->get('database_connection');
//        $stmt = $dbal->query('SELECT * FROM atividade');
//        var_dump($stmt->fetchAll());die();
        $entities = $this->getDoctrine()->getRepository("FnxAdminBundle:Atividade")->findBy(array("arquivado" => false));
        return array("entities" => $entities);
        
    }
    
    /**
     * @Route("/add/", name="atividadeAdd")
     * @Template()
     */
    public function addAction(){
        
        $atividade = new Atividade();
        $form =  $this->createForm(new AtividadeType($atividade), $atividade);
        
        return array(
            'atividade' => $atividade,
            'form' => $form->createView()
        );
        
    }
    
    /**
     * @Route("/create", name="atividadeCreate")
     * @Method({"POST"})
     * @Template("FnxAdminBundle:Atividade:add.html.twig")
     */
    public function createAction(){
        
        $atividade = new Atividade();
        $atividade->setContrato(new Contrato());
        $cidades = $this->getDoctrine()->getRepository("FnxAdminBundle:Cidade")->loadCidadeObject($_POST['fnx_admin_atividade']['estado']);
        $form =  $this->createForm(new AtividadeType($atividade,$cidades), $atividade);
        
        $form->bindRequest($this->getRequest());   
        $cliente = $this->getDoctrine()->getRepository("FnxAdminBundle:Cliente")
                                            ->findOneBy(array("nome" => $form->get("cliente")->getData()));
        
        $cliente == null ? $form->get("cliente")->addError(new FormError("Cliente inválido")) : $atividade->getContrato()->setCliente($cliente);
               
        if($form->isValid()){
           $this->onSuccess($atividade);
           $this->get("session")->setFlash("success", "Atividade cadastrada.");
           return $this->redirect($this->generateUrl("atividadeHome"));
        }
           
        return array(
           "form" => $form->createView(),
           "atividade" => $atividade
        );     
    }
    
    /**
     * @Route("/edit/{id}", name="atividadeEdit", options={"expose" = true})
     * @Template()
     */
    public function editAction($id){
        
        $atividade = $this->getDoctrine()->getRepository("FnxAdminBundle:Atividade")->find($id);
        if (!$atividade){
            throw $this->createNotFoundException("Atividade não encontrada.");
        }
        
        if ($atividade->getCidade()){
            $cidades = $this->getDoctrine()->getRepository("FnxAdminBundle:Cidade")->loadCidadeObject($atividade->getCidade()->getEstado()->getId());
        }else{
            $cidades = array();
        }
        
        $form =  $this->createForm(new AtividadeType($atividade, $cidades), $atividade);
        $form->get("cliente")->setData($atividade->getContrato()->getCliente()->getNome());
        
        return array(
            'atividade' => $atividade,
            'form' => $form->createView()
        );
        
    }
    
    /**
     * @Route("/update/{id}", name="atividadeUpdate")
     * @Method({"POST"})
     * @Template("FnxAdminBundle:Atividade:edit.html.twig")
     */
    public function updateAction($id){
            
        $atividade = $this->getDoctrine()->getRepository("FnxAdminBundle:Atividade")->find($id);
        if (!$atividade){
            throw $this->createNotFoundException("Atividade não encontrada.");
        }
        $cidades = $this->getDoctrine()->getRepository("FnxAdminBundle:Cidade")->loadCidadeObject($_POST['fnx_admin_atividade']['estado']);
        $form =  $this->createForm(new AtividadeType($atividade,$cidades), $atividade);
        $servicoOld = $atividade->getServico();
        $form->bindRequest($this->getRequest());   
        $cliente = $this->getDoctrine()->getRepository("FnxAdminBundle:Cliente")
                                            ->findOneBy(array("nome" => $form->get("cliente")->getData()));
        
        $cliente == null ? $form->get("cliente")->addError(new FormError("Cliente inválido")) : $atividade->getContrato()->setCliente($cliente);
               
        if($form->isValid()){
           if ($atividade->getServico() != $servicoOld){
             $this->changeEvento($atividade);
               
           }
           
           $this->onSuccess($atividade);
           $this->get("session")->setFlash("success", "Atividade alterada.");
           return $this->redirect($this->generateUrl("atividadeHome"));
        }
           
        return array(
           "form" => $form->createView(),
           "atividade" => $atividade
        );  
        
    }
    
    /**
     * @Route("/delete/{id}", name="atividadeDelete", options={"expose" = true})
     * @Template()
     */
    public function deleteAction($id){
        
        $atividade = $this->getDoctrine()->getRepository("FnxAdminBundle:Atividade")->find($id);
        if (!$atividade){
            throw $this->createNotFoundException("Atividade não encontrada.");
        }
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($atividade);
        $em->flush();
        
        $this->get("session")->setFlash("success", "Atividade excluída.");
        return $this->redirect($this->generateUrl("atividadeHome"));
        
        
    }
    
    /**
     * @Route("/show/{id}", name="atividadeShow", options={"expose" = true})
     * @Template()
     */
    public function showAction($id){
        
        $atividade = $this->getDoctrine()->getRepository("FnxAdminBundle:Atividade")->find($id);
        if (!$atividade){
            throw $this->createNotFoundException("Atividade não encontrada.");
        }
        
//        if ($atividade->getArquivado()){
//            $this->get("session")->setFlash("error", "Esta atividade está arquivada.");
//            return $this->redirect($this->generateUrl("atividadeHome"));
//            
//        }
        
        return array("atividade" => $atividade);
        
    }
    
    public function onSuccess(Atividade $atividade){
        
        $em = $this->getDoctrine()->getEntityManager();
        $atividade->getId() ? $em->merge($atividade) : $em->persist($atividade);  
        $em->flush();
        
    }
    
    /**
     * @Route("/arquivar/{id}", name="atividadeArquivar", options={"expose" = true})
     * @Template()
     */
    public function arquivarAction($id){
        
        $atividade = $this->getDoctrine()->getRepository("FnxAdminBundle:Atividade")->find($id);
        if (!$atividade){
            throw $this->createNotFoundException("Atividade não encontrada.");
        }
        
        $em = $this->getDoctrine()->getEntityManager();
        $atividade->setArquivado(true);
        if ($atividade->getRegistro()){
            $atividade->getRegistro()->setAtivo(false);
        }
        $em->persist($atividade);
        $em->flush();
        $this->get("session")->setFlash("success", "Atividade arquivada.");
        return $this->redirect($this->generateUrl("atividadeShow", array("id" => $atividade->getId())));
    }
    
    /**
     * @Route("/arquivado", name="atividadeArquivado")
     * @Template()
     */
    public function arquivadoAction(){
        
        $entities = $this->getDoctrine()->getRepository("FnxAdminBundle:Atividade")->findBy(array("arquivado" => true));
        return array("entities" => $entities);
        
    }
    
    /**
     * @Route("/ativar/{id}", name="atividadeAtivar", options={"expose" = true})
     * @Template()
     */
    public function ativarAction($id){
        
        $atividade = $this->getDoctrine()->getRepository("FnxAdminBundle:Atividade")->find($id);
        if (!$atividade){
            throw $this->createNotFoundException("Atividade não encontrada.");
        }
        
        $atividade->setArquivado(false);
        if ($atividade->getRegistro()){
            $atividade->getRegistro()->setAtivo(true);
        }
        $em = $this->getDoctrine()->getEntityManager();
        
        foreach ($atividade->getEscalas() as $escala){
            $em->remove($escala);
        }
        
        $atividade->getEscalas()->clear();
        
        $em->persist($atividade);
        $em->flush();
        $this->get("session")->setFlash("success", "Atividade ativada.");
        return $this->redirect($this->generateUrl("atividadeShow", array("id" => $atividade->getId())));
    }
    
    public function changeEvento($atividade){
        
        $em = $this->getDoctrine()->getEntityManager();
            
        foreach ($atividade->getEscalas() as $escala){
              $em->remove($escala);
        }
        
        $atividade->getEscalas()->clear();

        $em->flush();
               
        
    }
    
    /**
     * @Route("/upload/{id}", name="uploadContrato", options={"expose" = true})
     * @Template()
     */
    public function uploadAction($id){
        $atividade = $this->getDoctrine()->getRepository("FnxAdminBundle:Atividade")->find($id);
        $form = $this->createForm(new ContratoType, $atividade->getContrato());
        
        $request = $this->getRequest();
        if ($request->getMethod() == "POST"){
            $form->bindRequest($request);
            if ($form->isValid()){
                $em = $this->getDoctrine()->getEntityManager();
                $atividade->getContrato()->upload();
                $em->persist($atividade);
                $em->flush();
                
                $this->get("session")->setFlash("success", "Contrato registrado");
                return $this->redirect($this->generateUrl("atividadeShow", array("id" => $id)));
            }
        }
        
        return $this->render("FnxAdminBundle:Atividade:addContrato.html.twig", array(
               "form" => $form->createView(),
               "atividade" => $atividade));
    }
    
    /**
     * @Route("/download/{id}", name="downloadContrato", options={"expose" = true})
     * @Template()
     */
    public function downloadAction($id){
        
        $atividade = $this->getDoctrine()->getRepository("FnxAdminBundle:Atividade")->find($id);
        $content = file_get_contents($atividade->getContrato()->getAbsolutePath());

        $response = new Response();

        //set headers
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$atividade->getContrato()->getPath());

        $response->setContent($content);
        return $response;
   }
   
   /**
     * @Route("/remove-upload/{id}", name="removeUpload", options={"expose" = true})
     * @Template()
     */
    public function removeUploadAction($id){
        $atividade = $this->getDoctrine()->getRepository("FnxAdminBundle:Atividade")->find($id);
        
        $em = $this->getDoctrine()->getEntityManager();
        $atividade->getContrato()->removeUpload();
        $em->persist($atividade);
        $em->flush();
                
        $this->get("session")->setFlash("success", "Contrato excluído");
        return $this->redirect($this->generateUrl("atividadeShow", array("id" => $id)));
    }
    
}

?>
