<?php

namespace Booking\BookingBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Booking\BookingBundle\Entity\Actividad;
use Booking\BookingBundle\Form\ActividadType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Actividad controller.
 *
 */
class ActividadController extends Controller
{

    /**
     * Lists all Actividad entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BookingBundle:Actividad')->findAll();

        return $this->render('BookingBundle:Actividad:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Actividad entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Actividad();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('actividad'));
        }


    }

    /**
     * Creates a form to create a Actividad entity.
     *
     * @param Actividad $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Actividad $entity)
    {
        $form = $this->createForm(new ActividadType(), $entity, array(
            'action' => $this->generateUrl('actividad_create'),
            'method' => 'POST',
        ));

//        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Actividad entity.
     *
     */
    public function newAction()
    {
        $entity = new Actividad();
        $form   = $this->createCreateForm($entity);

        return $this->render('BookingBundle:Actividad:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Actividad entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BookingBundle:Actividad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BookingBundle:Actividad:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Actividad entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BookingBundle:Actividad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividad entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BookingBundle:Actividad:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Actividad entity.
    *
    * @param Actividad $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Actividad $entity)
    {
        $form = $this->createForm(new ActividadType(), $entity, array(
            'action' => $this->generateUrl('actividad_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Actividad entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BookingBundle:Actividad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Actividad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('actividad_edit', array('id' => $id)));
        }

        return $this->render('BookingBundle:Actividad:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Actividad entity.
     *
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        try{

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BookingBundle:Casa')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Casa entity.');
            }

            $em->remove($entity);
            $em->flush();

            return new Response(json_encode(array()), 200);
        }catch (\Exception $e){
            return new Response($e->getMessage(),500);
        }
    }

    /**
     * Creates a form to delete a Actividad entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('actividad_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }


    //Ajax Management

    /**
     * @param Request $request
     * @return Response
     */
    public function listarAction(Request $request)
    {
        $options = $request->query->all();
        try {

            $casa = $this->getDoctrine()->getRepository('BookingBundle:Actividad')->queryEntity($options);
            $total = count($this->getDoctrine()->getRepository('BookingBundle:Actividad')->findAll());


            $sEcho = 1;

            if (array_key_exists('sEcho', $options)) {
                $sEcho = intval($options['sEcho']);
            }

            return new Response(json_encode(array(
                'sEcho' => $sEcho,
                'iTotalRecords' => $total,
                'iTotalDisplayRecords' => $this->getDoctrine()->getRepository('BookingBundle:Actividad')->getFilteredCount($options),
                'aaData' => $casa

            )), 200);
        }

        catch(\Exception $e)
        {
            return new Response($e->getMessage(),500);
        }


    }



    /**
     *
     * @return JsonResponse
     */
    public function addformAction(){

        $entity = new Actividad();
        $form = $this->createCreateForm($entity);
        $response = new JsonResponse(
            array(
                'form' => $this->renderView('BookingBundle:Actividad:new.html.twig',
                    array(
                        'form' => $form->createView(),
                    ))), 200);

        return $response;

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function editformAction(Request $request)
    {

        $id = $request->get('id');
        $entity = $this->get('doctrine')->getManager()->getRepository('BookingBundle:Actividad')->find($id);
        $form = $this->createEditForm($entity);
        $response = new JsonResponse(
            array(
                'form' => $this->renderView('BookingBundle:Actividad:edit.html.twig',
                    array(
                        'entity' => $entity,
                        'form' => $form->createView(),
                    ))), 200);

        return $response;
    }
}
