<?php

namespace Booking\ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ReportBundle:Default:index.html.twig', array('name' => $name));
    }
    //... funciones utiles ...
	protected function driver($title='Boooking', $author='Bycod Teams', $subject='Reposts', $keywords='TCPDF, PDF, example, test, guide'){
		$path = __DIR__ . "\../../../../vendor/";
		require_once($path.'tcpdf/config/tcpdf_config.php');
		require_once($path.'tcpdf/tcpdf.php');

		$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($author);
		$pdf->SetTitle($title);
		$pdf->SetSubject($subject);
		$pdf->SetKeywords($keywords);

		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title, PDF_HEADER_STRING, array(0,0,0), array(0,0,0));
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->setFooterData(array(0,0,0), array(0,0,0));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setFontSubsetting(true);
		$pdf->SetFont('dejavusans', '', 14, '', true);
		$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
		return $pdf;
	}
    protected function exportPDF($view, $title='Boooking'){
        $pdf = $this->driver($title);
        $html = $view->getContent();
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf->Output('tmp.pdf', 'I');
        $response = new Response('');
        $response->headers->set('Content-Type', 'application/pdf');
        return $response;
    }
    //... exportar a html ...
    public function facturasTourAction(){
        //... IN ... simulacion de datos ........................
        $data = $this->dataFacturasTour();
        //... OUT ... simulacion de datos ........................
        return $this->render('ReportBundle:Default:facturastour.html.twig', array('list'=>$data));
    }
    public function facturasHouseAction(){
        //... IN ... simulacion de datos ........................
        $data = $this->dataFacturasHouse();
        //... OUT ... simulacion de datos ........................
        return $this->render('ReportBundle:Default:facturashouse.html.twig', $data);
    }
    public function listReservAction(){
        $data = $this->dataListReserv();
        //... OUT ... simulacion de datos ........................
        return $this->render('ReportBundle:Default:listreservas.html.twig', $data);
    }
    public function listSummerAction(){
        $data = $this->dataListSummer();
        //... OUT ... simulacion de datos ........................
        return $this->render('ReportBundle:Default:listsummer.html.twig', $data);
    }
    //... exportar a pdf ...
    public function pdfFacturasTourAction(){
        $view = $this->facturasTourAction();
        return $this->exportPDF($view, 'Boooking Tour Facture');
    }
    public function pdfFacturasHouseAction(){
        $view = $this->facturasHouseAction();
        return $this->exportPDF($view, 'Boooking House Facture');
    }
    public function pdfListReservAction(){
        $view = $this->listReservAction();
        return $this->exportPDF($view, 'Boooking List Reserv');
    }
    public function pdfListSummerAction(){
        $view = $this->listSummerAction();
        return $this->exportPDF($view, 'Boooking List Summer');
    }
    //... simulacion de la fuente de datos--done
    public function dataFacturasTour(){
        //... IN ... simulacion de datos ........................
        $data = array();
        for($i=0; $i<5; $i++){
            $data[$i] = array(
                'date'=> '08/04/2015',
                'supplier_agency'=> 'MiCuba Local',
                'supplier_name'=> 'Fatima Elena',
                'supplier_mobile'=> '0053 52906540',
                'supplier_phone'=> '0053 4199 6686',
                'supplier_email'=> 'bookingcuba.micubalocal@gmail.com',
                'client_agency'=> 'MiCuba - Reisspecialist in    Cuba&nbsp;',
                'client_address'=> 'Veembroederhof 173, 1019 HD in Amsterdam, Nl',
                'client_mobile'=> '0031 6 442135578',
                'client_web'=> 'www.micuba.nl',
                'client_email'=> 'julio@micuba.nl',
                'booking_name'=>'Renate A. Vogt ',
                'booking_pax'=>'2548',
                'booking_number'=>'115150178',
                'total' => '35,00',
                'services'=>array()
            );
            for($j=0; $j<15; $j++) {
                $data[$i]['services'][] = array(
                    'services_checkIn' => '09/04/2015',
                    'services_checkOut' => '09/04/2015',
                    'services_description' => 'CENA+BEBIDA-',
                    'services_price' => '23.00',
                    'services_total' => '25.00'
                );
            }
        }
        //... OUT ... simulacion de datos ........................
        return $data;
    }
    public function dataFacturasHouse(){
        //... IN ... simulacion de datos ........................
        $data = array(
            'date'=>    'Abril 2015',
            'from'=>    'Veembroederhof 173, 1019 HD in Amsterdam, Nl',
            'address'=> 'Calle Sim&oacute;n Bolivar # 309 e/ Calle Maceo y Calle Jose Mart&iacute;, Trinidad, Cuba',
            'phone'=>   '5987548263',
            'cell'=>    '0053 5248 9772',
            'email'=>   'bookingcuba.micubalocal@gmail.com',
            'web' =>    'www.tatakosa.com',
            'field' =>  array()
        );
        for($i=0; $i<100; $i++)
            $data['field'][] = array( 'fdate'=>'02/04/2015', 'fproduct'=>'ELECTRICIDAD (MARZO)', 'fcount'=>'25,001', 'fprice'=>'50,00', 'ftotal'=>'139,00' );
        //... OUT ... simulacion de datos ........................
        return $data;
    }
    public function dataListReserv(){
        $data = array('list'=>array(), 'date'=>'ABRIL 2016');
        for($i=0; $i<100; $i++)
            $data['list'][] = array(
                'referencia' => '91515016',
                'cliente'=>'Dawn Jenkins',
                'entrada'=>'27/04/2015',
                'salida'=>'30/04/2015',
                'n'=>'3',
                'servicio'=>'HOSTAL GIROUD',
                'p'=>'4',
                'hab'=>'1DOUBLE 2TWIN',
                'fact'=>'198,00',
                'pagar'=>'120,00',
                'com'=>'20,00',
                'observaciones'=>'BICITOUR DIA 29 9:00 CMC 27CUC'
            );
        //... OUT ... simulacion de datos ........................
        return $data;
    }
    public function dataListSummer(){
        $data = array('list'=>array());
        for($i=0; $i<100; $i++)
            $data['list'][] = array(
                'snummer' => '815150098',
                'contact'=>'#006638&nbsp; MartinMentzel',
                'omschrijving'=>'Casa Belen 1850    double room',

                'startdate'=>'19/04/2015',
                'aantal'=>'3',
                'kostprijs'=>'120',
                'leveranciers'=>'8'
            );
        //... OUT ... simulacion de datos ........................
        return $data;
    }
}
