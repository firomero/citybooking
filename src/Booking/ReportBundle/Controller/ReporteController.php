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
    /**
     * @param Request $request
     * @return Response
     */
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

    /**
     * @param Request $request
     * @return Response
     */
    public function listReservAction(Request $request){
        $manager = $this->get('reportbundle.manager.reportmanager');
        $filter = array();
        $casa = $request->query->get('casa');
        $agencia = $request->query->get('agencia');
        if (isset($casa)) {
            $filter ['casa']= $casa;
        }
        if (isset($agencia)) {
            $filter ['agencia']= $agencia;
        }
        $data = $manager->invoiceBooking($filter);
        $data['date'] = date_format(new \DateTime('now'),'d/m/Y');
        return $this->render('ReportBundle:Default:listreservas.html.twig', $data);
    }
    //... exportar a pdf ...
    /**
     * Tour PDF
     * @param Request $request
     * @return Response
     */
    public function pdfFacturasTourAction(Request $request){
        $view = $this->facturasTourAction($request);
        $exporter = $this->get('booking_reportbundle.exporter.pdfexporter');
        return $exporter->export($view, 'Boooking Tour Invoice');
    }

    /**
     * Booking PDF
     * @param Request $request
     * @return Response
     */
    public function pdfListReservAction(Request $request){
        $view = $this->listReservAction($request);
        $exporter = $this->get('booking_reportbundle.exporter.pdfexporter');
        return $exporter->export($view, 'Boooking List Reserv',date('Ymd-His'));
    }

    //... exportar a html ......................................
    /**
     * Facturas HTML
     * @param Request $request
     * @return Response
     */
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

    /**
     * Booking HTML
     * @param Request $request
     * @return Response
     */
    public function viewListReservAction(Request $request){
        $manager = $this->get('reportbundle.manager.reportmanager');
        $filter = array();
        $casa = $request->query->get('casa');
        $agencia = $request->query->get('agencia');
        if (isset($casa)) {
            $filter ['casa']= $casa;
        }
        if (isset($agencia)) {
            $filter ['agencia']= $agencia;
        }
        $data = $manager->invoiceBooking($filter);
        $data['date'] = date_format(new \DateTime('now'),'d/m/Y');
        return $this->render('ReportBundle:Default:viewlistreservas.html.twig', $data);
    }

    //Options
    /**
     * Página principal de las operaciones de los reportes
     * @return Response
     */
    public function optionsAction(){
        return $this->render('ReportBundle:Default:options.html.twig');

    }

    /**
     * Busqueda por fecha
     * @param Request $request
     * @return JsonResponse
     */
    public function dateSeekAction(Request $request){

        $date = $request->query->get('date');
        $date=array_map(function($value){
            return trim($value);
        },explode('-',$date));

        $in = date_create_from_format('d/m/Y',$date[0]);
        $out = date_create_from_format('d/m/Y',$date[1]);
        $query = $request->query->get('state');
        $manager = $this->get('reportbundle.manager.reportmanager');
        try{
            return new JsonResponse(array('casas'=>$manager->seekDate($in,$out,$query)),HttpCode::HTTP_OK);
        }
        catch(\Exception $e){

            $this->get('logger')->addCritical($e->getMessage());
            return new JsonResponse(array('Ha ocurrido un error procesando los datos. Revise sus datos de entrada.'),HttpCode::HTTP_SERVER_ERROR);
        }
    }

    /**
     * Facturas Booking General
     * @param Request $request
     * @return JsonResponse
     */
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

    /**
     * Facturas Booking por mes
     * @param Request $request
     * @return Response
     */
    public function homeBookMonthAction(Request $request){

        $date = $request->query->get('date');
        $layout = $request->query->get('layout');
        $manager = $this->get('reportbundle.manager.reportmanager');
        $data = $manager->getMonth(date_create_from_format('m/Y',$date));
        $data['date'] = date_format(new \DateTime('now'),'d/m/Y');
        $f = date_create_from_format('m/Y',$date);
        $data['mes'] = $f->format('m-Y');
        $template = 'ReportBundle:Default:viewmonthlistreservas.html.twig';
        if (!is_null($layout)) {
            $template = 'ReportBundle:Default:listreservas.html.twig';
        }
        return $this->render($template, $data);
    }

    /**
     * Booking PDF
     * @param Request $request
     * @return Response
     */
    public function pdfhomeBookMonthAction(Request $request){
        $date = $request->get('mes');
        $data =date_create_from_format('m-Y',$date);
        $request->query->add(
            array(
                'date'=>$data->format('m/Y'),
                'layout'=>'false'

        ));
        $view = $this->homeBookMonthAction($request);
        $exporter = $this->get('booking_reportbundle.exporter.pdfexporter');
        return $exporter->export($view, 'Boooking for '.date('F'),date('Ymd-His'));
    }

    /**
     * Facturas Tour por Mes
     * @param Request $request
     * @return Response
     */
    public function facturasMonthTourAction(Request $request){
        $manager = $this->get('reportbundle.manager.reportmanager');
        $date = $request->query->get('date');
        $data = $manager->invoiceTour(array(),date_create_from_format('m/Y',$date));
        return $this->render('ReportBundle:Default:viewfacturastour.html.twig', array('list'=>$data));
    }

    /**
     * Invoice for a custom booking
     * @param Request $request
     * @return Response
     */
    public function customInvoiceAction(Request $request){
        $manager = $this->get('reportbundle.manager.reportmanager');
        $id = $request->query->get('id');
        $data = $manager->customActivityInvoice($this->getDoctrine()->getManager()->getRepository('BookingBundle:Reservacion')->find($id));
        return $this->render('ReportBundle:Default:customInvoice.html.twig', array('list'=>$data, 'booking'=>$id));
    }

    /**
     * Invoice for a custom bookingPDF
     * @param Request $request
     * @return Response
     */
    public function customInvoicePDFAction(Request $request){
        $view = $this->customInvoicePlaneAction($request);
        $booking = $request->get('id');
        /**
         * @var Reservacion $reservacion
         */
        $reservacion = $this->getDoctrine()->getManager()->getRepository('BookingBundle:Reservacion')->find($booking);
        $exporter = $this->get('booking_reportbundle.exporter.pdfexporter');
        $result =  $exporter->export($view, 'Boooking Tour Invoice', $reservacion->getCliente()->getReferencia(),"D");
        return $result;

    }

    /**
     * Invoice for a custom booking
     * @param Request $request
     * @return Response
     */
    public function customInvoicePlaneAction(Request $request){
        $manager = $this->get('reportbundle.manager.reportmanager');
        $id = $request->query->get('id');
        if ($id==null)
        {
            $id = $request->get('id');

        }
        $data = $manager->customActivityInvoice($this->getDoctrine()->getManager()->getRepository('BookingBundle:Reservacion')->find($id));
        return $this->render('ReportBundle:Default:customInvoicePlane.html.twig', array('list'=>$data));
    }
} 