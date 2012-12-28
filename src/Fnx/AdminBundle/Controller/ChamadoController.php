<?php

namespace Fnx\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Fnx\AdminBundle\Entity\Chamado;
use Fnx\AdminBundle\Form\Type\ChamadoType;

/**
 * Chamado controller.
 *
 * @Route("/chamado")
 */
class ChamadoController extends Controller
{
    /**
     * Lists all Chamado entities.
     *
     * @Route("/", name="chamado")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('FnxAdminBundle:Chamado')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Chamado entity.
     *
     * @Route("/{id}/show", name="chamado_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FnxAdminBundle:Chamado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chamado entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
	);
    }

    /**
     * Displays a form to create a new Chamado entity.
     *
     * @Route("/new", name="chamado_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Chamado();
        $form   = $this->createForm(new ChamadoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Chamado entity.
     *
     * @Route("/create", name="chamado_create")
     * @Method("post")
     * @Template("FnxAdminBundle:Chamado:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Chamado();
        $request = $this->getRequest();
        $form    = $this->createForm(new ChamadoType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('chamado_show', array('id' => $entity->getId())));

        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Chamado entity.
     *
     * @Route("/{id}/edit", name="chamado_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FnxAdminBundle:Chamado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chamado entity.');
        }

        $editForm = $this->createForm(new ChamadoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Chamado entity.
     *
     * @Route("/{id}/update", name="chamado_update")
     * @Method("post")
     * @Template("FnxAdminBundle:Chamado:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FnxAdminBundle:Chamado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chamado entity.');
        }

        $editForm   = $this->createForm(new ChamadoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('chamado_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Chamado entity.
     *
     * @Route("/{id}/delete", name="chamado_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('FnxAdminBundle:Chamado')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Chamado entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('chamado'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
