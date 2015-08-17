<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 5/06/15
 * Time: 23:25
 */

namespace Booking\BookingBundle\Controller;

use Booking\BookingBundle\Entity\Habitacion;
use Booking\BookingBundle\Form\HabitacionType;
use General\NomencladorBundle\HttpCode;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HabitacionController extends Controller
{
    /**
     * Lists all Habitacion entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BookingBundle:Casa')->findAll();

        $form = $this->createForm(new HabitacionType());

        return $this->render('BookingBundle:Habitacion:index.html.twig', array(
            'entities' => $entities,
            'form'=>$form->createView()
        ));
    }

    /*PRIVATE*/
    public function listarAction(Request $request)
    {
        $options = $request->query->all();
        try {
            $casa = $this->getDoctrine()->getRepository('BookingBundle:Habitacion')->queryEntity($options);
            $total = count($this->getDoctrine()->getRepository('BookingBundle:Habitacion')->findAll());


            $sEcho = 1;

            if (array_key_exists('sEcho', $options)) {
                $sEcho = intval($options['sEcho']);
            }

            return new Response(json_encode(array(
                'sEcho' => $sEcho,
                'iTotalRecords' => $total,
                'iTotalDisplayRecords' => $this->getDoctrine()->getRepository('BookingBundle:Habitacion')->getFilteredCount($options),
                'aaData' => $casa

            )), 200);
        } catch (\Exception $e) {
            return new Response(json_encode(array('message'=> $e->getMessage())), 500);
        }
    }

    /**
     * Creates a new Habitacion entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Habitacion();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $entity->getCasa()->Increment();
            $em->flush();
            return $this->redirect($this->generateUrl('habitacion'));
        }

        $this->get('logger')->addCritical('no valido'.$form->getErrorsAsString());

        return $this->render('BookingBundle:Habitacion:index.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function editarAction(Request $request)
    {
        $id = $request->get('id');
        $entity = $this->get('doctrine')->getManager()->getRepository('BookingBundle:Habitacion')->find($id);
        $form = $this->createEditForm($entity);
        $response = new JsonResponse(
            array(
                'form' => $this->renderView('BookingBundle:Habitacion:edit_form.html.twig',
                    array(
                        'entity' => $entity,
                        'edit_form' => $form->createView(),
                    ))), 200);

        return $response;
    }

    /**
     * Creates a form to edit a Casa entity.
     *
     * @param Habitacion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Habitacion $entity)
    {
        $form = $this->createForm(new HabitacionType(), $entity, array(
            'action' => $this->generateUrl('habitacion_actualizar', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        // $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }


    /**
     * Edits an existing Casa entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BookingBundle:Habitacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Casa entity.');
        }


        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('habitacion'));
        }

        return new JsonResponse($editForm->getErrorsAsString(), 400);
    }



    /**
     * Creates a form to create a Casa entity.
     *
     * @param Habitacion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Habitacion $entity)
    {
        $form = $this->createForm(new HabitacionType(), $entity, array(
            'action' => $this->generateUrl('habitacion_crear'),
            'method' => 'POST',
        ));

     //   $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }


    /**
     * Deletes a Casa entity.
     *
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        try {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BookingBundle:Habitacion')->find($id);

            if (!$entity) {
                return new Response('La Habitación no existe', HttpCode::HTTP_RESOURCE_NOTFOUND);
            }

            $entity->getCasa()->Decrement();
            $em->remove($entity);
            $em->flush();

            return new Response(json_encode(array()), 200);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), 500);
        }
    }
}
