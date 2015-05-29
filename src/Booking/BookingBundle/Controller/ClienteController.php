<?php

namespace Booking\BookingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Booking\BookingBundle\Entity\Cliente;
use Booking\BookingBundle\Form\ClienteType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cliente controller.
 *
 */
class ClienteController extends Controller
{

    /**
     * Lists all Cliente entities.
     *
     */
    public function indexAction()
    {
        $cliente = new Cliente();

        $formCliente = $this->createCreateForm($cliente);

        return $this->render(
            'BookingBundle:Cliente:index.html.twig',
            array(
                'formCliente' => $formCliente->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Cliente entity.
     *
     * @param Cliente $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Cliente $entity)
    {
        $form = $this->createForm(
            new ClienteType(),
            $entity,
            array(
                'action' => $this->generateUrl('cliente_create'),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Creates a new Cliente entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $entity = new Cliente();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('cliente_show', array('id' => $entity->getId())));
        }

        return $this->render(
            'BookingBundle:Cliente:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Displays a form to create a new Cliente entity.
     *
     */
    public function newAction()
    {
        $entity = new Cliente();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'BookingBundle:Cliente:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Cliente entity.
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BookingBundle:Cliente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cliente entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render(
            'BookingBundle:Cliente:show.html.twig',
            array(
                'entity' => $entity,
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Creates a form to delete a Cliente entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cliente_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }

    /**
     * Displays a form to edit an existing Cliente entity.
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BookingBundle:Cliente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cliente entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render(
            'BookingBundle:Cliente:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Cliente entity.
     *
     * @param Cliente $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Cliente $entity)
    {
        $form = $this->createForm(
            new ClienteType(),
            $entity,
            array(
                'action' => $this->generateUrl('cliente_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Cliente entity.
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BookingBundle:Cliente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cliente entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('cliente_edit', array('id' => $id)));
        }

        return $this->render(
            'BookingBundle:Cliente:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Deletes a Cliente entity.
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BookingBundle:Cliente')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Cliente entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('cliente'));
    }

    /*AJAX SOURCE*/

    public function listarAction(Request $request)
    {
        $options = $request->query->all();
        try {

            $clientes = $this->getDoctrine()->getRepository('BookingBundle:Cliente')->queryEntity($options);
            $total = count($this->getDoctrine()->getRepository('BookingBundle:Cliente')->findAll());


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
                            'BookingBundle:Cliente'
                        )->getFilteredCount($options),
                        'aaData' => $clientes

                    )
                ), 200
            );
        } catch (\Exception $e) {
            return new Response(json_encode(array('message' => $e->getMessage())), 500);
        }


    }

    /**
     * Adiciona un nuevo cliente
     * @param Request $request
     * @return Response
     */
    public function adicionarAction(Request $request)
    {
        $name = $request->get('nombre');
        $reference = $request->get('referencia');
        $validator = $this->get('validator');
        $cliente = new Cliente();
        $cliente->setNombre($name);
        $cliente->setReferencia($reference);

        $errors = $validator->validate($cliente);
        if (count($errors) > 0) {
            return new Response(json_encode(array('message' => $errors->__toString())), 400);
        }
        try {

            $em = $this->get('doctrine')->getManager();
            $em->persist($cliente);
            $em->flush();

            return new Response(json_encode(array()), 200);
        } catch (\Exception $e) {
            return new Response(json_encode(array('message' => $e->getMessage())), 500);
        }
    }

    /**
     * Edita un cliente
     * @param Request $request
     * @return Response
     */
    public function editarAction(Request $request)
    {
        $name = $request->get('nombre');
        $reference = $request->get('referencia');
        $id = $request->get('id');
        $em = $this->get('doctrine')->getManager();
        $cliente = $em->getRepository('BookingBundle:Cliente')->find($id);

        if ($cliente == null) {
            return new Response(json_encode(array()), 404);
        }

        $cliente->setNombre($name);
        $cliente->setReferencia($reference);
        $validator = $this->get('validator');


        $errors = $validator->validate($cliente);
        if (count($errors) > 0) {
            return new Response(json_encode(array('message' => $errors->__toString())), 400);
        }
        try {


            $em->persist($cliente);
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
        $cliente = $em->getRepository('BookingBundle:Cliente')->find($id);

        if ($cliente == null) {
            return new Response(json_encode(array()), 404);
        }
        try {


            $em->remove($cliente);
            $em->flush();

            return new Response(json_encode(array()), 204);
        } catch (\Exception $e) {
            return new Response(json_encode(array('message' => $e->getMessage())), 500);
        }
    }
}
