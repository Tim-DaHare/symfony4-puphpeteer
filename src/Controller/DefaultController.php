<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PdfService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DefaultController extends AbstractController {

    /**
    * @Route("/")
    */
    public function index(PdfService $pdfService) {
        $pdf = $pdfService->generatePdfFromUrl('https://google.com/');

        if (!$pdf) {
            throw new $this->createNotFoundException();
        }

        return new BinaryFileResponse($pdf);
    }
}