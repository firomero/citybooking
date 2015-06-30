<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 8/06/15
 * Time: 23:39
 */

namespace Booking\BookingBundle\Controller;



use Booking\BookingBundle\Entity\Reservacion;
use Booking\BookingBundle\Form\ReservacionType;
use Booking\BookingBundle\Manager\ReservacionManager;
use Doctrine\ORM\NoResultException;
use General\NomencladorBundle\HttpCode;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReservacionController extends Controller{

    /**
     * Devuelve la página principal con el formulario de nuevo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(){

        $form = $this->createForm(new ReservacionType());

        return $this->render('BookingBundle:Reservacion:index.html.twig', array(

            'form'=>$form->createView()
        ));

    }


    /**
     * Creates a new Reervacion entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Reservacion();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var Reservacion $entity
             *@var ReservacionManager $model
             */
            $model = $this->get('booking.reservacionmanager');
            $model->setPrecio($entity);
            $em->persist($entity);
            $em->flush();


            return $this->redirect($this->generateUrl('reservacion_index'));
        }

        $this->get('logger')->addCritical('no valido'.$form->getErrorsAsString());

        return $this->render('BookingBundle:Reservacion:index.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));


    }


    /**
     * Creates a form to create a Reservacion entity.
     *
     * @param Reservacion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Reservacion $entity)
    {
        $form = $this->createForm(new ReservacionType(), $entity, array(
            'action' => $this->generateUrl('reservacion_crear'),
            'method' => 'POST',
        ));
        return $form;
    }


    public function editFormAction(Request $request)
    {

        $id = $request->get('id');
        $entity = $this->get('doctrine')->getManager()->getRepository('BookingBundle:Casa')->findOneBy(array('nombre'=>$id));
        $form = $this->createEditForm($entity);
        $response = new JsonResponse(
            array(
                'form' => $this->renderView('BookingBundle:Casa:edit.html.twig',
                    array(
                        'entity' => $entity,
                        'edit_form' => $form->createView(),
                    ))), 200);

        return $response;
    }

    /**
     * Creates a form to edit a Casa entity.
     *
     * @param Reservacion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Reservacion $entity)
    {
        $form = $this->createForm(new ReservacionType(), $entity, array(
            'action' => $this->generateUrl('reservacion_actualizar', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Edits an existing Reservacion entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BookingBundle:Reservacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Reservacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('reservacion_index'));
        }


        return $this->render('BookingBundle:Casa:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
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
            ->setAction($this->generateUrl('reservacion_cancelar', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
    }


    /**
     * Deletes a Reservacion entity.
     *
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        try{

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BookingBundle:Reservacion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Reservacion entity.');
            }

            $em->getRepository('BookingBundle:Reservacion')->delete($entity);
            $em->flush();

            return new Response(json_encode(array()), 200);
        }catch (\Exception $e){
            return new Response($e->getMessage(),500);
        }
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function listarAction(Request $request)
    {
        $options = $request->query->all();
        try {

            $casa = $this->getDoctrine()->getRepository('BookingBundle:Reservacion')->queryEntity($options);
            $total = count($this->getDoctrine()->getRepository('BookingBundle:Reservacion')->findAll());


            $sEcho = 1;

            if (array_key_exists('sEcho', $options)) {
                $sEcho = intval($options['sEcho']);
            }

            return new Response(json_encode(array(
                'sEcho' => $sEcho,
                'iTotalRecords' => $total,
                'iTotalDisplayRecords' => $this->getDoctrine()->getRepository('BookingBundle:Reservacion')->getFilteredCount($options),
                'aaData' => $casa

            )), 200);
        }

        catch(\Exception $e)
        {
            return new Response(json_encode(array('message'=> $e->getMessage())),500);
        }


    }

    public function mostrarAction(Request $request)
    {
        $id = $request->query->get('id');
        $em= $this->getDoctrine()->getManager();
        try{
            $entity = $em->getRepository('BookingBundle:Reservacion')->find($id);
            return new JsonResponse($entity->toArray(),HttpCode::HTTP_OK);

        }catch (NoResultException $not)
        {
            return new Response('No se encontró la reservación solicitada', HttpCode::HTTP_RESOURCE_NOTFOUND);
        }
        catch(\Exception $e)
        {
            return new Response($e->getMessage(),HttpCode::HTTP_SERVER_ERROR);
        }
    }


} 