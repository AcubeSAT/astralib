<?php

namespace App\Controller\Admin;

use App\Entity\Document;
use App\Entity\Source;
use App\Entity\Version;
use App\Event\FileUpdatedEvent;
use App\Form\UploadType;
use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * A controller with useful debugging and development features to aid developers
 * @Route("/debug")
 */
class DebugController extends AbstractController
{
    /**
     * @Route("/pdf")
     */
    public function pdf(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        $form = $this->createFormBuilder()
            ->add('file', FileType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form['file']->getData();
            $source = $this->handleFile($file, $dispatcher);

            return $this->render('Admin/debug/pdf.html.twig', [
                'form' => $form->createView(),
                'source' => $source
            ]);
        }

        return $this->render('Admin/debug/pdf.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function handleFile(UploadedFile $file, EventDispatcherInterface $dispatcher) {
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

        $event = new FileUpdatedEvent($source);
        $dispatcher->dispatch($event);

        return $source;
    }
}