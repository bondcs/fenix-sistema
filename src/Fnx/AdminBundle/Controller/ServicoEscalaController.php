<?php

namespace Fnx\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Fnx\AdminBundle\Entity\ServicoEscala;
use Fnx\AdminBundle\Form\Type\ServicoEscalaType;

/**
 * ServicoEscala controller.
 *
 * @Route("/adm/funcionario/categoria")
 */
class ServicoEscalaController extends Controller
{
    /**
     * Lists all ServicoEscala entities.
     *
     * @Route("/", name="funcionario_servico_escala")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('FnxAdminBundle:ServicoEscala')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Displays a form to create a new ServicoEscala entity.
     *
     * @Route("/new", name="funcionario_servico_escala_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ServicoEscala();
        $form   = $this->createForm(new ServicoEscalaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new ServicoEscala entity.
     *
     * @Route("/create", name="funcionario_servico_escala_create")
     * @Method("post")
     * @Template("FnxAdminBundle:ServicoEscala:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new ServicoEscala();
        $request = $this->getRequest();
        $form    = $this->createForm(new ServicoEscalaType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();
            
            $this->get("session")->setFlash('success',"Serviço registrado.");
            return $this->redirect($this->generateUrl('funcionario_servico_escala'));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing ServicoEscala entity.
     *
     * @Route("/{id}/edit", name="funcionario_servico_escala_edit", options={"expose" = true})
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FnxAdminBundle:ServicoEscala')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Serviço não encontrado.');
        }
        
        if ($entity->getEditavel() == false){
           $this->get("session")->setFlash('error', "Este registro não pode ser editado.");
           return $this->redirect($this->generateUrl('funcionario_servico_escala'));
        }

        $editForm = $this->createForm(new ServicoEscalaType(), $entity);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
     * Edits an existing ServicoEscala entity.
     *
     * @Route("/{id}/update", name="funcionario_servico_escala_update")
     * @Method("post")
     * @Template("FnxAdminBundle:ServicoEscala:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FnxAdminBundle:ServicoEscala')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Serviço não encontrado.');
        }

        $editForm   = $this->createForm(new ServicoEscalaType(), $entity);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->get("session")->setFlash('success',"Serviço alterado.");
            return $this->redirect($this->generateUrl('funcionario_servico_escala'));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
     * Deletes a ServicoEscala entity.
     *
     * @Route("/{id}/delete", name="funcionario_servico_escala_delete", options={"expose" = true})
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('FnxAdminBundle:ServicoEscala')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Serviço não encontrado.');
        }

        $em->remove($entity);
        $em->flush();
        
        $this->get("session")->setFlash('success',"Serviço excluído.");
        return $this->redirect($this->generateUrl('funcionario_servico_escala'));
    }
    
}
