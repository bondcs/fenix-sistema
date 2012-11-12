<?php

namespace Fnx\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Fnx\AdminBundle\Entity\Categoria;
use Fnx\AdminBundle\Form\Type\CategoriaType;
use Symfony\Component\Form\FormError;

/**
 * Categoria controller
 *
 * @Route("/financeiro/categoria")
 */
class CategoriaController extends Controller
{
    /**
     * Lists all Categoria entities.
     *
     * @Route("/", name="categoriaHome")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('FnxAdminBundle:Categoria')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Categoria entity.
     *
     * @Route("/{id}/show", name="adm_categoria_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FnxAdminBundle:Categoria')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Categoria não encontrado.');
        }

        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Displays a form to create a new Categoria entity.
     *
     * @Route("/new", name="categoriaNew")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Categoria();
        $form   = $this->createForm(new CategoriaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Categoria entity.
     *
     * @Route("/create", name="adm_categoria_create")
     * @Method("post")
     * @Template("FnxAdminBundle:Categoria:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Categoria();
        $request = $this->getRequest();
        $form    = $this->createForm(new CategoriaType(), $entity);
        $form->bindRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();
            $this->get("session")->setFlash('success',"Categoria registrada.");
            return $this->redirect($this->generateUrl('categoriaHome'));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Categoria entity.
     *
     * @Route("/{id}/edit", name="categoriaEdit", options={"expose" = true})
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FnxAdminBundle:Categoria')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Categoria não encontrada.');
        }
        
        if ($entity->getEditavel() == false){
           $this->get("session")->setFlash('error', "Este registro não pode ser editado.");
           return $this->redirect($this->generateUrl('categoriaHome'));
        }

        $editForm = $this->createForm(new CategoriaType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
     * Edits an existing Categoria entity.
     *
     * @Route("/{id}/update", name="adm_categoria_update")
     * @Method("post")
     * @Template("FnxAdminBundle:Categoria:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FnxAdminBundle:Categoria')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Categoria  não encontrada.');
        }

        $editForm   = $this->createForm(new CategoriaType(), $entity);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
          
           $this->get("session")->setFlash('success', "Categoria alterada.");
           return $this->redirect($this->generateUrl('categoriaHome'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
     * Deletes a Categoria entity.
     *
     * @Route("/{id}/delete", name="categoriaDelete", options={"expose" = true} )
     */
    public function deleteAction($id)
    {

       $em = $this->getDoctrine()->getEntityManager();
       $entity = $em->getRepository('FnxAdminBundle:Categoria')->find($id);

       if (!$entity) {
                throw $this->createNotFoundException('Categoria  não encontrada.');
       }

       $em->remove($entity);
       $em->flush();
       $this->get("session")->setFlash('success', "Categoria  excluída.");
       return $this->redirect($this->generateUrl('categoriaHome'));
    }

}
