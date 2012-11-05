<?php

namespace Fnx\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * @Route("/admin")
 */
class PageController extends Controller
{
    /**
     * @Route("/", name="adminHome")
     * @Template()
     */
    public function indexAction()
    {
        $usuarioLogado = $this->get('security.context')->getToken()->getUser();
        $funcionario = $this->getDoctrine()->getRepository("FnxAdminBundle:Funcionario")->loadFuncionarioByUsuario($usuarioLogado->getId());
        if($funcionario == array()){
            $funcionario[0] = null;
        }
        return array("funcionario" => $funcionario[0]);
    }
}
