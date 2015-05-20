<?php

namespace General\NomencladorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use General\NomencladorBundle\Entity\Agencia;
use General\NomencladorBundle\Form\AgenciaType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Agencia controller.
 *
 */
class AgenciaController extends Controller
{

    /**
     * Lists all Agencia entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('NomencladorBundle:Agencia')->findAll();

        return $this->render('NomencladorBundle:Agencia:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Agencia entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Agencia();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('agencia_show', array('id' => $entity->getId())));
        }

        return $this->render('NomencladorBundle:Agencia:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Agencia entity.
    *
    * @param Agencia $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Agencia $entity)
    {
        $form = $this->createForm(new AgenciaType(), $entity, array(
            'action' => $this->generateUrl('agencia_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Agencia entity.
     *
     */
    public function newAction()
    {
        $entity = new Agencia();
        $form   = $this->createCreateForm($entity);

        return $this->render('NomencladorBundle:Agencia:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Agencia entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NomencladorBundle:Agencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Agencia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('NomencladorBundle:Agencia:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Agencia entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NomencladorBundle:Agencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Agencia entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('NomencladorBundle:Agencia:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Agencia entity.
    *
    * @param Agencia $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Agencia $entity)
    {
        $form = $this->createForm(new AgenciaType(), $entity, array(
            'action' => $this->generateUrl('agencia_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Agencia entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NomencladorBundle:Agencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Agencia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('agencia_edit', array('id' => $id)));
        }

        return $this->render('NomencladorBundle:Agencia:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Agencia entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('NomencladorBundle:Agencia')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Agencia entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('agencia'));
    }

    /**
     * Creates a form to delete a Agencia entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('agencia_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /*AJAX SOURCE*/
    public function listarAction(Request $request)
    {
        $options = $request->query->all();
        try{

            $agencias = $this->getDoctrine()->getRepository('NomencladorBundle:Agencia')->queryEntity($options);

            $iLimit = 10;

            if ( isset( $options['$iDisplayStart']) && $options['iDisplayLength'] != '-1' )
            {
                $iLimit = abs($options['iDisplayLength']-$options['$iDisplayStart']);
            }


            return new Response(json_encode(array(
                "sEcho" => intval($options['sEcho']),
                'iTotalRecords'=>$iLimit,
                'iTotalDisplayRecords'=>sizeof($agencias),
                'aaData'=>$agencias

            )),200);
        }
        catch(\Exception $e)
        {
            return new Response(json_encode(array('message'=> $e->getMessage())),500);
        }


    }

    /**
     * Adiciona una nueva agencia
     * @param Request $request
     * @return Response
     */
    public function adicionarAction(Request $request)
    {
        $name = $request->get('name');
        $validator = $this->get('validator');
        $agencia = new Agencia();
        $agencia->setNombre($name);

        $errors = $validator->validate($agencia);
        if (count($errors)>0) {
           return new Response(json_encode(array('message'=>$errors->__toString())),400);
        }
        try{

            $em = $this->get('doctrine')->getManager();
            $em->persist($agencia);
            $em->flush();
            return new Response(json_encode(array()),200);
        }
        catch(\Exception $e)
        {
            return new Response(json_encode(array('message'=>$e->getMessage())),500);
        }
    }

    /**
     * Edita una agencia
     * @param Request $request
     * @return Response
     */
    public function editarAction(Request $request)
    {
        $name = $request->get('name');
        $id = $request->get('id');
        $em = $this->get('doctrine')->getManager();
        $agencia = $em->getRepository('NomencladorBundle:Agencia')->find($id);

        if ($agencia==null) {
            return new Response(json_encode(array()),404);
        }

        $agencia->setNombre($name);
        $validator = $this->get('validator');


        $errors = $validator->validate($agencia);
        if (count($errors)>0) {
            return new Response(json_encode(array('message'=>$errors->__toString())),400);
        }
        try{


            $em->persist($agencia);
            $em->flush();
            return new Response(json_encode(array()),200);
        }
        catch(\Exception $e)
        {
            return new Response(json_encode(array('message'=>$e->getMessage())),500);
        }
    }

    /**
     * Elimina una agencia
     * @param Request $request
     * @return Response
     */
    public function eliminarAction(Request $request)
    {

        $id = $request->get('id');
        $em = $this->get('doctrine')->getManager();
        $agencia = $em->getRepository('NomencladorBundle:Agencia')->find($id);

        if ($agencia==null) {
            return new Response(json_encode(array()),404);
        }
        try{


            $em->remove($agencia);
            $em->flush();
            return new Response(json_encode(array()),204);
        }
        catch(\Exception $e)
        {
            return new Response(json_encode(array('message'=>$e->getMessage())),500);
        }
    }
}
