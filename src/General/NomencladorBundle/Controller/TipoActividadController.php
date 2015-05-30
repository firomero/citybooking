<?php

namespace General\NomencladorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use General\NomencladorBundle\Entity\TipoActividad;
use General\NomencladorBundle\Form\TipoActividadType;

/**
 * TipoActividad controller.
 *
 */
class TipoActividadController extends Controller
{

    /**
     * Lists all TipoActividad entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('NomencladorBundle:TipoActividad')->findAll();

        return $this->render('NomencladorBundle:TipoActividad:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new TipoActividad entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new TipoActividad();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tipoactividad_show', array('id' => $entity->getId())));
        }

        return $this->render('NomencladorBundle:TipoActividad:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a TipoActividad entity.
     *
     * @param TipoActividad $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TipoActividad $entity)
    {
        $form = $this->createForm(new TipoActividadType(), $entity, array(
            'action' => $this->generateUrl('tipoactividad_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new TipoActividad entity.
     *
     */
    public function newAction()
    {
        $entity = new TipoActividad();
        $form   = $this->createCreateForm($entity);

        return $this->render('NomencladorBundle:TipoActividad:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a TipoActividad entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NomencladorBundle:TipoActividad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoActividad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('NomencladorBundle:TipoActividad:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing TipoActividad entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NomencladorBundle:TipoActividad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoActividad entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('NomencladorBundle:TipoActividad:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a TipoActividad entity.
    *
    * @param TipoActividad $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TipoActividad $entity)
    {
        $form = $this->createForm(new TipoActividadType(), $entity, array(
            'action' => $this->generateUrl('tipoactividad_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing TipoActividad entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NomencladorBundle:TipoActividad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoActividad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tipoactividad_edit', array('id' => $id)));
        }

        return $this->render('NomencladorBundle:TipoActividad:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a TipoActividad entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('NomencladorBundle:TipoActividad')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TipoActividad entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tipoactividad'));
    }

    /**
     * Creates a form to delete a TipoActividad entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tipoactividad_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }




    /*AJAX SOURCE*/
    public function listarAction(Request $request)
    {
        $options = $request->query->all();
        try {

            $tipoactividad = $this->getDoctrine()->getRepository('NomencladorBundle:TipoActividad')->queryEntity($options);
            $total = count($this->getDoctrine()->getRepository('NomencladorBundle:TipoActividad')->findAll());


            $sEcho = 1;

            if (array_key_exists('sEcho', $options)) {
                $sEcho = intval($options['sEcho']);
            }

            if (isset($options['iDisplayStart']) && $options['iDisplayLength'] != '-1') {
                $iLimit = abs($options['iDisplayLength'] - $options['iDisplayStart']);

            }

            return new Response(json_encode(array(
                'sEcho' => $sEcho,
                'iTotalRecords' => $total,
                'iTotalDisplayRecords' => $this->getDoctrine()->getRepository('NomencladorBundle:TipoActividad')->getFilteredCount($options),
                'aaData' => $tipoactividad

            )), 200);
        }

        catch(\Exception $e)
        {
            return new Response(json_encode(array('message'=> $e->getMessage())),500);
        }
    }

    public function adicionarAction(Request $request)
    {
        $name = $request->get('name');
        $validator = $this->get('validator');
        $tipoactividad = new TipoActividad();
        $tipoactividad->setNombre($name);

        $errors = $validator->validate($tipoactividad);
        if (count($errors)>0) {
            return new Response(json_encode(array('message'=>$errors->__toString())),400);
        }
        try{
            $em = $this->get('doctrine')->getManager();
            $em->persist($tipoactividad);
            $em->flush();
            return new Response(json_encode(array()),200);
        }
        catch(\Exception $e)
        {
            return new Response(json_encode(array('message'=>$e->getMessage())),500);
        }
    }

    public function editarAction(Request $request)
    {
        $name = $request->get('name');
        $id = $request->get('id');
        $em = $this->get('doctrine')->getManager();
        $tipoactividad = $em->getRepository('NomencladorBundle:TipoActividad')->find($id);

        if ($tipoactividad==null) {
            return new Response(json_encode(array()),404);
        }

        $tipoactividad->setNombre($name);
        $validator = $this->get('validator');


        $errors = $validator->validate($tipoactividad);
        if (count($errors)>0) {
            return new Response(json_encode(array('message'=>$errors->__toString())),400);
        }
        try{
            $em->persist($tipoactividad);
            $em->flush();
            return new Response(json_encode(array()),200);
        }
        catch(\Exception $e)
        {
            return new Response(json_encode(array('message'=>$e->getMessage())),500);
        }
    }

    public function eliminarAction(Request $request)
    {

        $id = $request->get('id');
        $em = $this->get('doctrine')->getManager();
        $tipoactividad = $em->getRepository('NomencladorBundle:TipoActividad')->find($id);

        if ($tipoactividad==null) {
            return new Response(json_encode(array()),404);
        }
        try{


            $em->remove($tipoactividad);
            $em->flush();
            return new Response(json_encode(array()),204);
        }
        catch(\Exception $e)
        {
            return new Response(json_encode(array('message'=>$e->getMessage())),500);
        }
    }
}
