<?php

namespace App\Controller\Admin;

use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * A controller with useful debugging and development features to aid developers
 * @Route("/debug")
 */
class DebugController extends AbstractController
{
    /**
     * @Route("/pdf")
     */
    public function pdf(DocumentRepository $documentRepository): Response
    {
        return new Response("<html><body>hello</body></html>");
    }
}