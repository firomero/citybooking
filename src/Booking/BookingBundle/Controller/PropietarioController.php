<?php

namespace Booking\BookingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Booking\BookingBundle\Entity\Propietario;
use Booking\BookingBundle\Form\PropietarioType;

/**
 * Propietario controller.
 *
 */
class PropietarioController extends Controller
{
    /**
     * Lists all Propietario entities.
     *
     */
    public function indexAction()
    {
        $propietario = new Propietario();

        $formPropietario = $this->createCreateForm($propietario);

        return $this->render(
            'BookingBundle:Propietario:index.html.twig',
            array(
                'formPropietario' => $formPropietario->createView(),
            )
        );
    }

    /**
     * Creates a new Propietario entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Propietario();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('propietario', array('id' => $entity->getId())));
        }

        return $this->render(
            'BookingBundle:Propietario:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Propietario entity.
     *
     * @param Propietario $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Propietario $entity)
    {
        $form = $this->createForm(
            new PropietarioType(),
            $entity,
            array(
                'action' => $this->generateUrl('propietario_create'),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Propietario entity.
     *
     */
    public function newAction()
    {
        $entity = new Propietario();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'BookingBundle:Propietario:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Propietario entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BookingBundle:Propietario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Propietario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->redirect($this->generateUrl('propietario'));
    }

    /**
     * Displays a form to edit an existing Propietario entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BookingBundle:Propietario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Propietario entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->redirect($this->generateUrl('propietario'));
    }

    /**
     * Creates a form to edit a Propietario entity.
     *
     * @param Propietario $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Propietario $entity)
    {
        $form = $this->createForm(
            new PropietarioType(),
            $entity,
            array(
                'action' => $this->generateUrl('propietario_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Propietario entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BookingBundle:Propietario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Propietario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('propietario_edit', array('id' => $id)));
        }

        return $this->redirect($this->generateUrl('propietario'));
    }

    /**
     * Deletes a Propietario entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BookingBundle:Propietario')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Propietario entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('propietario'));
    }

    /**
     * Creates a form to delete a Propietario entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('propietario_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }

    /*AJAX SOURCE*/

    public function listarAction(Request $request)
    {
        $options = $request->query->all();
        try {
            $propietarios = $this->getDoctrine()->getRepository('BookingBundle:Propietario')->queryEntity($options);
            $total = count($this->getDoctrine()->getRepository('BookingBundle:Propietario')->findAll());


            $sEcho = 1;

            if (array_key_exists('sEcho', $options)) {
                $sEcho = intval($options['sEcho']);
            }

            if (isset($options['iDisplayStart']) && $options['iDisplayLength'] != '-1') {
                $iLimit = abs($options['iDisplayLength'] - $options['iDisplayStart']);
            }


            return new Response(
                json_encode(
                    array(
                        'sEcho' => $sEcho,
                        'iTotalRecords' => $total,
                        'iTotalDisplayRecords' => $this->getDoctrine()->getRepository(
                            'BookingBundle:Propietario'
                        )->getFilteredCount($options),
                        'aaData' => $propietarios

                    )
                ), 200
            );
        } catch (\Exception $e) {
            return new Response(json_encode(array('message' => $e->getMessage())), 500);
        }
    }

    /**
     * Adiciona un nuevo propietario
     * @param Request $request
     * @return Response
     */
    public function adicionarAction(Request $request)
    {
        $name = $request->get('nombre');
        $ci = $request->get('ci');
        $validator = $this->get('validator');
        $propietario = new Propietario();
        $propietario->setNombre($name);
        $propietario->setCi($ci);

        $errors = $validator->validate($propietario);
        if (count($errors) > 0) {
            return new Response(json_encode(array('message' => $errors->__toString())), 400);
        }
        try {
            $em = $this->get('doctrine')->getManager();
            $em->persist($propietario);
            $em->flush();

            return new Response(json_encode(array()), 200);
        } catch (\Exception $e) {
            return new Response(json_encode(array('message' => $e->getMessage())), 500);
        }
    }

    /**
     * Edita un propietario
     * @param Request $request
     * @return Response
     */
    public function editarAction(Request $request)
    {
        $name = $request->get('nombre');
        $ci = $request->get('ci');
        $id = $request->get('id');
        $em = $this->get('doctrine')->getManager();
        $propietario = $em->getRepository('BookingBundle:Propietario')->find($id);

        if ($propietario == null) {
            return new Response(json_encode(array()), 404);
        }

        $propietario->setNombre($name);
        $propietario->setCi($ci);
        $validator = $this->get('validator');


        $errors = $validator->validate($propietario);
        if (count($errors) > 0) {
            return new Response(json_encode(array('message' => $errors->__toString())), 400);
        }
        try {
            $em->persist($propietario);
            $em->flush();

            return new Response(json_encode(array()), 200);
        } catch (\Exception $e) {
            return new Response(json_encode(array('message' => $e->getMessage())), 500);
        }
    }

    /**
     * Elimina un nuevo tipo de habitacion
     * @param Request $request
     * @return Response
     */
    public function eliminarAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->get('doctrine')->getManager();
        $propietario = $em->getRepository('BookingBundle:Propietario')->find($id);

        if ($propietario == null) {
            return new Response(json_encode(array()), 404);
        }
        try {
            $em->remove($propietario);
            $em->flush();

            return new Response(json_encode(array()), 204);
        } catch (\Exception $e) {
            return new Response(json_encode(array('message' => $e->getMessage())), 500);
        }
    }
}
