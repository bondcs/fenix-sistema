<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\FinanceiroBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Fnx\FinanceiroBundle\Entity\Conta;
use Fnx\FinanceiroBundle\Entity\Transferencia;
use Fnx\FinanceiroBundle\Form\Type\TransferenciaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Description of TransferenciaController
 *
 * @Route("/financeiro/transferencia")
 */
class TransferenciaController extends Controller{
    
    /**
     * Lists all Transferencia entities.
     *
     * @Route("/", name="financeiro_transferencia")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('FnxFinanceiroBundle:Transferencia')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Displays a form to create a new Transferencia entity.
     *
     * @Route("/new", name="financeiro_transferencia_new", options={"expose" = true})
     * @Template()
     */
    public function newAction()
    {
        $entity = new Transferencia();
        $form   = $this->createForm(new TransferenciaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Transferencia entity.
     *
     * @Route("/create", name="financeiro_transferencia_create")
     * @Method("post")
     * @Template("FnxFinanceiroBundle:Transferencia:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Transferencia();
        $request = $this->getRequest();
        $form    = $this->createForm(new TransferenciaType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
           $em = $this->getDoctrine()->getEntityManager();
           $entity->efetuaTransferencia();
           
           $em->persist($entity);
           $em->persist($entity->getContaSaque());
           $em->persist($entity->getContaRecebimento());
           $em->flush();

           $this->get("session")->setFlash("success","Cadastro concluído.");
           return $this->redirect($this->generateUrl("financeiro_transferencia"));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    
    /**
     * Deletes a Conta entity.
     *
     * @Route("/{id}/delete", name="financeiro_transferencia_delete", options={"expose" = true})
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('FnxFinanceiroBundle:Transferencia')->find($id);

        if (!$entity) {
             throw $this->createNotFoundException('Transferência não encontrada.');
        }
        
        if($entity->getContaRecebimento()->getValor() < $entity->getValor()){
            $this->get("session")->setFlash("error","Conta depósito não possu saldo suficiente.");
            return $this->redirect($this->generateUrl("financeiro_transferencia"));
        }
        
        $entity->desfazTransferencia();
        $em->persist($entity->getContaSaque());
        $em->persist($entity->getContaRecebimento());
        $em->remove($entity);
        $em->flush();
            
        $this->get("session")->setFlash("success","Tranferência desfeita.");
        return $this->redirect($this->generateUrl("financeiro_transferencia"));
    }
}

?>
