<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Source;
use App\Entity\Version;
use App\Form\UploadType;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File as FileConstraint;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function upload(Request $request, ValidatorInterface $validator, EntityManagerInterface $manager): Response
    {
        if ($file = $request->files->get('file')) {
            /** @var $file UploadedFile */
            // Here we should rename the file to a safer name and use the file upload bundle
            // $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            dump($file);

            // dump($filename);

            $errors = $validator->validate(
                $file,
                new FileConstraint([
                    'maxSize' => '20M',
                    'mimeTypes' => [
                        'application/pdf',
                        'application/x-pdf',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid PDF document'
                ])
            );

            if (count($errors) > 0) {
                return $this->json(array_map(function(ConstraintViolationInterface $error) {
                    return $error->getMessage();
                }, iterator_to_array($errors)), Response::HTTP_BAD_REQUEST);
            }

            $document = new Document();
            $version = new Version();
            $source = new Source();

            $document->setDocid('DOC-001');
            $document->setTitle($file->getClientOriginalName());

            $version->setDocument($document);
            $version->setNumber("1.0");

            $source->setVersion($version);
            $source->setType(Source::InternalType);
            $source->setFile($file);

            dump($source);

            $manager->persist($document);
            $manager->persist($version);
            $manager->persist($source);
            $manager->flush();

            $this->addFlash('success', 'New document has been uploaded and archived successfully');
            return new Response("Everything is good");
        }

        return $this->json([
            "No file provided"
        ], Response::HTTP_BAD_REQUEST);
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
