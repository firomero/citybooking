<?php

namespace Booking\BookingBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Booking\BookingBundle\Entity\Casa;
use Booking\BookingBundle\Form\CasaType;

/**
 * Casa controller.
 *
 */
class CasaController extends Controller
{

    /**
     * Lists all Casa entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BookingBundle:Casa')->findAll();

        $form = $this->createForm(new CasaType());

        return $this->render('BookingBundle:Casa:index.html.twig', array(
            'entities' => $entities,
            'form'=>$form->createView()
        ));
    }
    /**
     * Creates a new Casa entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Casa();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return new JsonResponse(array('message' => 'Exitoso'), 200);
        }

        $this->get('logger')->addCritical('no valido'.$form->getErrorsAsString());

        $response = new JsonResponse(
            array(
                'message' => 'Error',
                'form' => $this->renderView('BookingBundle:Casa:casaform.html.twig',
                    array(
                        'entity' => $entity,
                        'form' => $form->createView(),
                    ))), 400);

        return $response;
    }

    /**
     * Creates a form to create a Casa entity.
     *
     * @param Casa $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Casa $entity)
    {
        $form = $this->createForm(new CasaType(), $entity, array(
            'action' => $this->generateUrl('casa_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Casa entity.
     *
     */
    public function newAction()
    {
        $entity = new Casa();
        $form   = $this->createCreateForm($entity);

        return $this->render('BookingBundle:Casa:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Casa entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BookingBundle:Casa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Casa entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BookingBundle:Casa:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Casa entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BookingBundle:Casa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Casa entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BookingBundle:Casa:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Casa entity.
    *
    * @param Casa $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Casa $entity)
    {
        $form = $this->createForm(new CasaType(), $entity, array(
            'action' => $this->generateUrl('casa_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Casa entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BookingBundle:Casa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Casa entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('casa_edit', array('id' => $id)));
        }

        return $this->render('BookingBundle:Casa:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Casa entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BookingBundle:Casa')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Casa entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('casa'));
    }

    /**
     * Creates a form to delete a Casa entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('casa_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /*AJAX SOURCE*/
    /**
     * @param Request $request
     * @return Response
     */
    public function listarAction(Request $request)
    {
        $options = $request->query->all();
        try {

            $casa = $this->getDoctrine()->getRepository('BookingBundle:Casa')->queryEntity($options);
            $total = count($this->getDoctrine()->getRepository('BookingBundle:Casa')->findAll());


            $sEcho = 1;

            if (array_key_exists('sEcho', $options)) {
                $sEcho = intval($options['sEcho']);
            }

            return new Response(json_encode(array(
                'sEcho' => $sEcho,
                'iTotalRecords' => $total,
                'iTotalDisplayRecords' => $this->getDoctrine()->getRepository('BookingBundle:Casa')->getFilteredCount($options),
                'aaData' => $casa

            )), 200);
        }

        catch(\Exception $e)
        {
            return new Response(json_encode(array('message'=> $e->getMessage())),500);
        }


    }
}
