<?php

namespace Fnx\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Fnx\AdminBundle\Entity\Fornecedor;
use Fnx\AdminBundle\Form\Type\FornecedorType;

/**
 * Fornecedor controller.
 *
 * @Route("/adm/fornecedor")
 */
class FornecedorController extends Controller
{
    /**
     * Lists all Fornecedor entities.
     *
     * @Route("/", name="adm_fornecedor")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('FnxAdminBundle:Fornecedor')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Fornecedor entity.
     *
     * @Route("/{id}/show", name="adm_fornecedor_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FnxAdminBundle:Fornecedor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fornecedor entity.');
        }

        return array(
            'entity'      => $entity  );
    }

    /**
     * Displays a form to create a new Fornecedor entity.
     *
     * @Route("/new", name="adm_fornecedor_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Fornecedor();
        $form   = $this->createForm(new FornecedorType($entity), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Fornecedor entity.
     *
     * @Route("/create", name="adm_fornecedor_create")
     * @Method("post")
     * @Template("FnxAdminBundle:Fornecedor:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Fornecedor();
        $request = $this->getRequest();
        $cidades = $this->getDoctrine()->getRepository("FnxAdminBundle:Cidade")->loadCidadeObject($_POST['fnx_adminbundle_fornecedortype']['estado']);
        $form    = $this->createForm(new FornecedorType($entity,$cidades), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();
            
            $this->get("session")->setFlash("success", "Fornecedor cadastrado.");
            return $this->redirect($this->generateUrl('adm_fornecedor'));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Fornecedor entity.
     *
     * @Route("/{id}/edit", name="adm_fornecedor_edit", options={"expose" = true})
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FnxAdminBundle:Fornecedor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fornecedor entity.');
        }
        
        if ($entity->getCidade()){
            $cidades = $this->getDoctrine()->getRepository("FnxAdminBundle:Cidade")->loadCidadeObject($entity->getCidade()->getEstado()->getId());
        }else{
            $cidades = array();
        }

        $editForm = $this->createForm(new FornecedorType($entity, $cidades), $entity);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
     * Edits an existing Fornecedor entity.
     *
     * @Route("/{id}/update", name="adm_fornecedor_update")
     * @Method("post")
     * @Template("FnxAdminBundle:Fornecedor:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('FnxAdminBundle:Fornecedor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fornecedor entity.');
        }

        $cidades = $this->getDoctrine()->getRepository("FnxAdminBundle:Cidade")->loadCidadeObject($_POST['fnx_adminbundle_fornecedortype']['estado']);
        $editForm = $this->createForm(new FornecedorType($entity,$cidades), $entity);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get("session")->setFlash("success", "Fornecedor alterado.");
            return $this->redirect($this->generateUrl('adm_fornecedor'));
        }

        return array(
            'entity'      => $entity,
            'edit'   => $editForm->createView(),
        );
    }

    /**
     * Deletes a Fornecedor entity.
     *
     * @Route("/{id}/delete", name="adm_fornecedor_delete", options={"expose" = true})
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('FnxAdminBundle:Fornecedor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fornecedor entity.');
        }

        $em->remove($entity);
        $em->flush();

        $this->get("session")->setFlash("success", "Fornecedor excluído.");
        return $this->redirect($this->generateUrl('adm_fornecedor'));
    }

}
