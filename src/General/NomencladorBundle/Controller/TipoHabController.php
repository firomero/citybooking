<?php

namespace General\NomencladorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use General\NomencladorBundle\Entity\TipoHab;
use General\NomencladorBundle\Form\TipoHabType;
use Symfony\Component\HttpFoundation\Response;

/**
 * TipoHab controller.
 *
 */
class TipoHabController extends Controller
{
    /**
     * Lists all TipoHab entities.
     *
     */
    public function indexAction()
    {
        $tipoHab = new TipoHab();

        $formTipoHab = $this->createCreateForm($tipoHab);

        return $this->render(
            'NomencladorBundle:TipoHab:index.html.twig',
            array(
                'formTipoHab' => $formTipoHab->createView()
            )
        );
    }

    /**
     * Creates a form to create a TipoHab entity.
     *
     * @param TipoHab $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TipoHab $entity)
    {
        $form = $this->createForm(
            new TipoHabType(),
            $entity,
            array(
                'action' => $this->generateUrl('tipohab_create'),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Creates a new TipoHab entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new TipoHab();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tipohab_show', array('id' => $entity->getId())));
        }

        return $this->render(
            'NomencladorBundle:TipoHab:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Displays a form to create a new TipoHab entity.
     *
     */
    public function newAction()
    {
        $entity = new TipoHab();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'NomencladorBundle:TipoHab:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a TipoHab entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NomencladorBundle:TipoHab')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoHab entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render(
            'NomencladorBundle:TipoHab:show.html.twig',
            array(
                'entity' => $entity,
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Creates a form to delete a TipoHab entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tipohab_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }

    /**
     * Displays a form to edit an existing TipoHab entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NomencladorBundle:TipoHab')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoHab entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render(
            'NomencladorBundle:TipoHab:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a TipoHab entity.
     *
     * @param TipoHab $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(TipoHab $entity)
    {
        $form = $this->createForm(
            new TipoHabType(),
            $entity,
            array(
                'action' => $this->generateUrl('tipohab_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing TipoHab entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NomencladorBundle:TipoHab')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoHab entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tipohab_edit', array('id' => $id)));
        }

        return $this->render(
            'NomencladorBundle:TipoHab:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Deletes a TipoHab entity.
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
            $entity = $em->getRepository('NomencladorBundle:TipoHab')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TipoHab entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tipohab'));
    }

    /*AJAX SOURCE*/

    public function listarAction(Request $request)
    {
        $options = $request->query->all();
        try {
            $tipohabs = $this->getDoctrine()->getRepository('NomencladorBundle:TipoHab')->queryEntity($options);
            $total = count($this->getDoctrine()->getRepository('NomencladorBundle:TipoHab')->findAll());


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
                            'NomencladorBundle:TipoHab'
                        )->getFilteredCount($options),
                        'aaData' => $tipohabs

                    )
                ), 200
            );
        } catch (\Exception $e) {
            return new Response(json_encode(array('message' => $e->getMessage())), 500);
        }
    }

    /**
     * Adiciona un nuevo tipo de habitacion
     * @param Request $request
     * @return Response
     */
    public function adicionarAction(Request $request)
    {
        $name = $request->get('name');
        $weight = $request->get('weight');
        $validator = $this->get('validator');
        $tipohab = new TipoHab();
        $tipohab->setNombre($name);
        $tipohab->setPeso(intval($weight));

        $errors = $validator->validate($tipohab);
        if (count($errors) > 0) {
            return new Response(json_encode(array('message' => $errors->__toString())), 400);
        }
        try {
            $em = $this->get('doctrine')->getManager();
            $em->persist($tipohab);
            $em->flush();

            return new Response(json_encode(array()), 200);
        } catch (\Exception $e) {
            return new Response(json_encode(array('message' => $e->getMessage())), 500);
        }
    }

    /**
     * Edita un nuevo tipo de habitacion
     * @param Request $request
     * @return Response
     */
    public function editarAction(Request $request)
    {
        $name = $request->get('name');
        $weight = $request->get('weight');
        $id = $request->get('id');
        $em = $this->get('doctrine')->getManager();
        $tipohab = $em->getRepository('NomencladorBundle:TipoHab')->find($id);

        if ($tipohab == null) {
            return new Response(json_encode(array()), 404);
        }

        $tipohab->setNombre($name);
        $tipohab->setPeso($weight);
        $validator = $this->get('validator');


        $errors = $validator->validate($tipohab);
        if (count($errors) > 0) {
            return new Response(json_encode(array('message' => $errors->__toString())), 400);
        }
        try {
            $em->persist($tipohab);
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
        $tipohab = $em->getRepository('NomencladorBundle:TipoHab')->find($id);

        if ($tipohab == null) {
            return new Response(json_encode(array()), 404);
        }
        try {
            $em->remove($tipohab);
            $em->flush();

            return new Response(json_encode(array()), 204);
        } catch (\Exception $e) {
            return new Response(json_encode(array('message' => $e->getMessage())), 500);
        }
    }
}
