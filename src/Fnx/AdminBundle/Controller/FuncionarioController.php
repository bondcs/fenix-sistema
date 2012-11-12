<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Fnx\AdminBundle\Entity\Funcionario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Fnx\AdminBundle\Form\Type\FuncionarioType;
use Fnx\AdminBundle\Entity\Usuario;
use Fnx\AdminBundle\Form\Type\UsuarioType;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Fnx\AdminBundle\Form\Type\ChangePasswordType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of FuncionarioController
 *
 * @author bondcs
 */
class FuncionarioController extends Controller{
    
    /**
     * @Route("adm/funcionario", name="funcionarioHome")
     * @Template()
     */
    public function indexAction(){
       $funcionarios = $this->getDoctrine()->getRepository("FnxAdminBundle:Funcionario")->findAll(); 
       return array("funcionarios" => $funcionarios);
    }
    
    /**
     * @Route("adm/funcionario/add", name="funcionarioAdd", options={"expose" = true})
     * @Template()
     */
    public function addAction(){
        
        $funcionario = new Funcionario();
        $form = $this->createForm(new FuncionarioType, $funcionario);
        $request = $this->getRequest();
        if ($request->getMethod() == "POST"){
            $form->bindRequest($request);
            if ($form->isValid()){
                $em = $this->getDoctrine()->getEntityManager();
//                $config = $em->getRepository('FnxAdminBundle:Configuracao')->findOneBy(array("id" => $this->container->getParameter("configuracaoId")));
//                $funcionario->getSalario()->setSalarioPago(substr(str_replace(",", ".", $funcionario->getSalario()->getSalario()),3) + $config->getValorDependente()*$funcionario->getDependentes());
                $em->persist($funcionario);
                $em->flush();
                
                $this->get("session")->setFlash("success","Cadastro concluído.");
                return $this->redirect($this->generateUrl("funcionarioHome"));
            }
        }
        
        return $this->render("FnxAdminBundle:Funcionario:form.html.twig", array(
                    "form" => $form->createView(),
                    "funcionario" => $funcionario));
    }
    
    /**
     * @Route("adm/funcionario/edit/{id}", name="funcionarioEdit", options={"expose" = true})
     * @Template()
     */
    public function editAction($id){
        $funcionario = $this->getDoctrine()->getRepository("FnxAdminBundle:Funcionario")->find($id);
        if (!$funcionario){
            throw $this->createNotFoundException("Funcionário não encontrado.");
        }
        
        $form = $this->createForm(new FuncionarioType, $funcionario);
        $request = $this->getRequest();
        if ($request->getMethod() == "POST"){
            $form->bindRequest($request);
            if ($form->isValid()){
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($funcionario);
                $em->flush();
                
                $this->get("session")->setFlash("success","Alteração concluída.");
                return $this->redirect($this->generateUrl("funcionarioHome"));
            }
        }
        
        return $this->render("FnxAdminBundle:Funcionario:form.html.twig", array(
                    "form" => $form->createView(),
                    "funcionario" => $funcionario));
    }
    
    /**
     * @Route("adm/funcionario/remove/{id}", name="funcionarioRemove", options={"expose" = true})
     */
    public function removeAction($id){
       $funcionario = $this->getDoctrine()->getRepository("FnxAdminBundle:Funcionario")->find($id);
       if (!$funcionario){
            throw $this->createNotFoundException("Funcionário não encontrado.");
        }
       $em = $this->getDoctrine()->getEntityManager();
       $em->remove($funcionario);
       $em->flush();
       $this->get('session')->setFlash('success', 'Funcionário excluído.');  
       return $this->redirect($this->generateUrl("funcionarioHome"));
    }
    
    /**
     * @Route("adm/funcionario/show/{id}", name="funcionarioShow", options={"expose" = true})
     * @Template()
     */
    public function showProfileAction($id){
        $funcionario = $this->getDoctrine()
                    ->getEntityManager()
                    ->getRepository("FnxAdminBundle:Funcionario")
                    ->find($id);
        
        $escalas = $this->getDoctrine()->getRepository("FnxAdminBundle:Escala")->loadEscalaByFuncionario($id);
        $escalasFun = $this->getDoctrine()->getRepository("FnxAdminBundle:EscalaFun")->loadEscalaFunByFuncionario($id);
        if (!$funcionario){
            throw $this->createNotFoundException("Funcionário não encontrado.");
        }
        
        
        return array("funcionario" => $funcionario,
                     "escalas" => $escalas,
                     "escalaFun" => $escalasFun);
    }
    
     /**
     * @Route("/adm/funcionario/usuario/{id}", name="usuarioManagerFuncionario", options={"expose" = true})
     * @Template()
     * @param type $id
     */
    public function usuarioAction($id){
        
        $funcionario = $this->getDoctrine()->getRepository("FnxAdminBundle:Funcionario")->find($id);
        if (!$funcionario){
            throw $this->createNotFoundException("Funcionário não encontrado.");
        }
        
        $flag = true;
        if (!$funcionario->getUsuario()){
            $funcionario->setUsuario(new Usuario);
            $flag = false;
        }
        
        $form = $this->get("fnx_admin.usuario.form");
        $formHandler = $this->get("fnx_admin.usuario.form.handler");
        $process = $formHandler->process($funcionario->getUsuario(), $funcionario,$form );
        
        if ($process){
           $message = $flag == true ? "edit" : "add";
            
            $responseSuccess = array(
                  'dialogName' => '.simpleDialog',
                  'message' => $message
                );
            
            $response = new Response(json_encode($responseSuccess));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        
        return $this->render("FnxAdminBundle:Usuario:form.html.twig",array(
            "form" => $form->createView(),
            "father" => $funcionario,
            "responsavel" => false,
        ));
        
    }
    
    /**
     * @Route("/adm/funcionario/usuario/remove/{id}", name="usuarioRemoveFuncionario", options={"expose" = true})
     * @Template()
     * @param type $id
     */
    public function usuarioRemoveAction($id){
        
        $funcionario = $this->getDoctrine()->getRepository("FnxAdminBundle:Funcionario")->find($id);
        if (!$funcionario){
            throw $this->createNotFoundException("Funcionario não encontrado.");
        }
        
        $usuarioLogado = $this->get('security.context')->getToken()->getUser();
        
        if ($funcionario->getUsuario()->getId() == $usuarioLogado->getId()){
            throw new \Exception("Não pode excluir seu própio usuário.");
        }
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($funcionario->getUsuario());
        $em->flush();
        $this->get("session")->setFlash("success","Usuário excluído.");
        return $this->redirect($this->generateUrl("funcionarioShow", array("id" => $funcionario->getId())));


 
    }

}

?>
