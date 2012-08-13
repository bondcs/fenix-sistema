<?php

namespace Fnx\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PageController extends Controller
{
    
    /**
     * @Route("/", name="frontPage")
     * @Template()
     */
    public function frontAction(){
        
        return array();
    }

    /**
     * @Route("/adm", name="adminHome")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
