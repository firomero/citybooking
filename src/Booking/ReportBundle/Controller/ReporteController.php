<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 20/06/15
 * Time: 21:19
 */

namespace Booking\ReportBundle\Controller;
use General\NomencladorBundle\HttpCode;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ReporteController extends  Controller{
   const DISPONIBLE='disponible';
   const RESERVADA='reservada';
    /**
     * Dada una casa devuelve las reservaciones asociadas.
     * @param Request $request
     * @return JsonResponse
     */
    public function reporteCasaAction(Request $request){
        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        try{
            $rManager = $this->get('reportbundle.manager.reportmanager');
            $casa = $em->getRepository('BookingBundle:Casa')->find($id);
            $reporte = $rManager->reporteCasa($casa);
            return new JsonResponse($reporte,HttpCode::HTTP_OK);
        }
        catch(\Exception $e)
        {
            return new Response($e->getMessage(),HttpCode::HTTP_SERVER_ERROR);
        }
    }
    /**
     * Dada una agencia devuelve las reservaciones asociadas
     * @param Request $request
     * @return JsonResponse
     */
    public function reporteAgenciaAction(Request $request)
    {
        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        try{
            $rManager = $this->get('reportbundle.manager.reportmanager');
            $agencia = $em->getRepository('NomencladorBundle:Agencia')->find($id);
            $reporte = $rManager->reporteAgencia($agencia);
            return new JsonResponse($reporte,HttpCode::HTTP_OK);
        }
        catch(\Exception $e)
        {
            return new Response($e->getMessage(),HttpCode::HTTP_SERVER_ERROR);
        }
    }
    /**
     * Dada una reservación devuelve las actividades  asociadas
     * @param Request $request
     * @return JsonResponse
     */
    public function invoiceActivityAction(Request $request)
    {
        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        try{
            $rManager = $this->get('reportbundle.manager.reportmanager');
            $reservacion = $em->getRepository('BookingBundle:Reservacion')->find($id);
            $reporte = $rManager->reporteAgencia($reservacion);
            return new JsonResponse($reporte,HttpCode::HTTP_OK);
        }
        catch(\Exception $e)
        {
            return new Response($e->getMessage(),HttpCode::HTTP_SERVER_ERROR);
        }
    }
    //html exporter
    public function facturasTourAction(Request $request){
        $manager = $this->get('reportbundle.manager.reportmanager');
        $filter = array();
        $casa = $request->query->get('casa');
        if (isset($casa)) {
            $filter ['casa']= $casa;
        }
        //$data = $manager->invoiceTour($filter);
        //print_r($data);die;
        $data['list'] = $manager->invoiceTour($filter);
        $data['date'] = date('now');
        return $this->render('ReportBundle:Default:facturastour.html.twig', $data);
    }
    public function listReservAction(Request $request){
        $manager = $this->get('reportbundle.manager.reportmanager');
        $filter = array();
        $casa = $request->query->get('casa');
        $agencia = $request->query->get('agencia');
        if (isset($casa)) {
            $filter ['casa']= $casa;
        }
        if (isset($casa)) {
            $filter ['agencia']= $agencia;
        }
        $data = $manager->invoiceBooking($filter);
        $data['date'] = date_format(new \DateTime('now'),'d/m/Y');
        return $this->render('ReportBundle:Default:listreservas.html.twig', $data);
    }
    //... exportar a pdf ...
    public function pdfFacturasTourAction(Request $request){
        $view = $this->facturasTourAction($request);
        $exporter = $this->get('booking_reportbundle.exporter.pdfexporter');
        return $exporter->export($view, 'Boooking Tour Invoice');
    }
    public function pdfListReservAction(Request $request){
        $view = $this->listReservAction($request);
        $exporter = $this->get('booking_reportbundle.exporter.pdfexporter');
        return $exporter->export($view, 'Boooking List Reserv');
    }

    //... exportar a html ......................................
    public function viewFacturasTourAction(Request $request){
        $manager = $this->get('reportbundle.manager.reportmanager');
        $filter = array();
        $casa = $request->query->get('casa');
        if (isset($casa)) {
            $filter ['casa']= $casa;
        }
        $data = $manager->invoiceTour($filter);
        return $this->render('ReportBundle:Default:viewfacturastour.html.twig', array('list'=>$data));
    }
    public function viewListReservAction(Request $request){
        $manager = $this->get('reportbundle.manager.reportmanager');
        $filter = array();
        $casa = $request->query->get('casa');
        $agencia = $request->query->get('agencia');
        if (isset($casa)) {
            $filter ['casa']= $casa;
        }
        if (isset($casa)) {
            $filter ['agencia']= $agencia;
        }
        $data = $manager->invoiceBooking($filter);
        $data['date'] = date_format(new \DateTime('now'),'d/m/Y');
        return $this->render('ReportBundle:Default:viewlistreservas.html.twig', $data);
    }
    //Options
    public function optionsAction(){
        return $this->render('ReportBundle:Default:options.html.twig');

    }

    public function dateSeekAction(Request $request){

        $date = $request->query->get('date');
        $query = $request->query->get('state');
        $manager = $this->get('reportbundle.manager.reportmanager');
        try{
            return new JsonResponse(array('casas'=>$manager->seekDate(new \DateTime($date),$query)),HttpCode::HTTP_OK);
        }
        catch(\Exception $e){

            $this->get('logger')->addCritical($e->getMessage());
            return new JsonResponse(array('Ha ocurrido un error procesando los datos. Revise sus datos de entrada.'),HttpCode::HTTP_SERVER_ERROR);
        }
    }

    public function homeBookAction(Request $request){

        $date = $request->query->get('date');
        $casa = $request->query->get('casa');
        $manager = $this->get('reportbundle.manager.reportmanager');
        $home = $this->getDoctrine()->getManager()->getRepository('BookingBundle:Casa')->findOneBy(array('nombre'=>$casa));
        try{
            return new JsonResponse(array('booking'=>$manager->getReservaciones($home,date_create_from_format('m/Y',$date)),HttpCode::HTTP_OK));
        }
        catch(\Exception $e){

            $this->get('logger')->addCritical($e->getMessage());
            return new JsonResponse(array('Ha ocurrido un error procesando los datos. Revise sus datos de entrada.'),HttpCode::HTTP_SERVER_ERROR);
        }
    }
} 