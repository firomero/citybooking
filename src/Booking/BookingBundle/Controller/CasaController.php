<?php

namespace Booking\BookingBundle\Controller;

use Booking\BookingBundle\Entity\Habitacion;
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
     * @param Request $request
     * @return JsonResponse
     */
    public function editformAction(Request $request)
    {

        $id = $request->get('id');
        $entity = $this->get('doctrine')->getManager()->getRepository('BookingBundle:Casa')->find($id);
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
     * Creates a new Casa entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Casa();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
//        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setCantidadHab(0);
            $em->persist($entity);
            $em->flush();


            return $this->redirect($this->generateUrl('casa'));
        }

        $this->get('logger')->addCritical('no valido'.$form->getErrorsAsString());

        return $this->render('BookingBundle:Default:index.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));


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

        $entity = $em->getRepository('BookingBundle:Casa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Casa entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('casa'));
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
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        try{

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BookingBundle:Casa')->findOneBy($id);

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

    /**
     * @return JsonResponse
     */
    public function tabServeAction(){

        $response = new JsonResponse(
            array(
                'tabs' => $this->renderView('BookingBundle:Casa:tabstpl.html.twig'
                ), 200
            ));

        return $response;
    }

    /**
     * @return JsonResponse
     */
    public function habsTipoAction(){
        $em = $this->getDoctrine()->getManager();
        try
        {
            $th = $em->getRepository('NomencladorBundle:TipoHab')->findAll();
            $dataResult =  array_map(function($object){ return array('id'=>$object->getId(),'tipo'=>$object->getNombre());},$th);
            $response = new JsonResponse(
                array(
                    'tipo' => $dataResult, 200
                ));

            return $response;
        }
        catch(\Exception $e){
            return new JsonResponse(array($e->getMessage()),500);
        }



    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function habsByCasaAction(Request $request){
        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();
       try{
           $casa = $em->getRepository('BookingBundle:Casa')->find($id);
           $habsCasa = $em->getRepository('BookingBundle:Habitacion')->findBy(array('casa'=>$casa));
           $dataResult =  array_map(function($object){ return array('id'=>$object->getId(),'tipo'=>$object->getTipo()->getNombre());},$habsCasa);
           $response = new JsonResponse(
               array(
                   'habs' => $dataResult, 200
               ));

           return $response;
       }
       catch(\Exception $e){
           return new JsonResponse(array($e->getMessage()),500);
       }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addRoomAction(Request $request){
        $tipo = $request->query->get('type');
        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        try{
            $casa = $em->getRepository('BookingBundle:Casa')->find($id);
            $type = $em->getRepository('NomencladorBundle:TipoHab')->find($tipo);
            $hab = new Habitacion();
            $hab->setCasa($casa);
            $hab->setTipo($type);
            $casa->Increment();
            $em->persist($hab);
            $em->persist($casa);
            $em->flush();
            $response = new JsonResponse(
                array(
                    'habs' => array(
                        'tipo'=> $type->getNombre(),
                        'id'=>$hab->getId()
                    ), 200
                ));

            return $response;

        }
        catch(\Exception $e)
        {
            return new JsonResponse(array($e->getMessage()),500);
        }

    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteRoomAction(Request $request){
        $hb = $request->query->get('hab');
        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        try{
            $casa = $em->getRepository('BookingBundle:Casa')->find($id);
            $hab = $em->getRepository('BookingBundle:Habitacion')->find($hb);
            $casa->Decrement();
            $em->remove($hab);
            $em->persist($casa);
            $em->flush();
            $response = new JsonResponse(
                array(
                    'habs' => $casa->getNombre(), 204
                ));

            return $response;

        }
        catch(\Exception $e)
        {
            return new JsonResponse(array($e->getMessage()),500);
        }

    }




}
