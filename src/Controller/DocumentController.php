<?php

namespace App\Controller;

use App\Entity\Document;
use App\Form\UploadType;
use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/document")
 */
class DocumentController extends AbstractController
{
    /**
     * @Route("/", name="document_index", methods={"GET"})
     */
    public function index(DocumentRepository $documentRepository): Response
    {
        return $this->render('document/index.html.twig', [
            'documents' => $documentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/upload", name="document_upload", methods={"POST"})
     */
    public function upload(Request $request): Response
    {
        $document = new Document();

        if ($file = $request->files->get('file')) {
            // Here we should rename the file to a safer name and use the file upload bundle
            $filename =  pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            dump($filename);

            // Move the file to the directory where brochures are stored
            try {
                $file->move(
                    $this->getParameter('upload_directory'),
                    $filename
                );
            } catch (FileException $e) {
                dump($e);
                die($e);
                // ... handle exception if something happens during file upload
            }
        }

        return $this->renderForm('document/new.html.twig', [
            'document' => null,
            'form' => null,
        ]);
    }

    /**
     * @Route("/{id}", name="document_show", methods={"GET"})
     */
    public function show(Document $document): Response
    {
        return $this->render('document/show.html.twig', [
            'document' => $document,
        ]);
    }
}
