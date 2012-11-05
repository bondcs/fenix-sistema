<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Fnx\AdminBundle\Form\Type\ChangePasswordType;
use Fnx\AdminBundle\Entity\Usuario;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
/**
 * Description of SecurityController
 *
 * @author bondcs
 */
class SecurityController extends Controller{
   
    /**
     * @Route("/adm/login", name="_security_login_a")
     * @return type 
     */
    public function loginAction(){
        
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }
 
        return $this->render('FnxAdminBundle:Security:login.html.twig', array(
            'last_username' => $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME),
            'error' => $error
        ));
    }
    
    /**
     * @Route("/adm/login_check", name="_security_check_a")
     */
    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/adm/logout", name="_security_logout_a")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }
    
    /**
     * @Route("/adm/change-password", name="_security_change_password_a")
     * @Template()
     */
    public function senhaEditAction(){  
        $usuarioLogado = $this->get('security.context')->getToken()->getUser();
        $form = $this->createForm(new ChangePasswordType);
        
        $request = $this->getRequest();
        if ($request->getMethod() == "POST"){
            $form->bindRequest($request);
            if ($form->isValid()){
                $em = $this->getDoctrine()->getEntityManager();
                $data = $form->getData();
                $usuarioLogado->setSalt(md5(time()));
                $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
                $password = $encoder->encodePassword($data['password'], $usuarioLogado->getSalt());
                $usuarioLogado->setPassword($password);
                $em->persist($usuarioLogado);
                $em->flush();
                
                $this->get("session")->setFlash("success", "Senha alterada.");
                return $this->redirect($this->generateUrl("adminHome"));
            }
        }
        
        return array("form" => $form->createView());
        
    }
}

?>
