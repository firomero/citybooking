<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 19/07/15
 * Time: 10:31
 */

namespace Booking\ReportBundle\Exporter;


use Symfony\Component\HttpFoundation\Response;

interface ExporterInterface {

    /**
     * Returns the exporter Manager
     * @param string $title
     * @param string $author
     * @param string $subject
     * @param string $keywords
     * @return mixed
     */
    public function driver($title='Boooking', $author='Bycod Teams', $subject='Reposts', $keywords='TCPDF, PDF, example, test, guide');

    /**
     * Returns the View
     * @param $view
     * @param string $title
     * @return Response
     */
    public function export($view, $title='Boooking');

} 