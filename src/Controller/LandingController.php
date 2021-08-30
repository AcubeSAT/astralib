<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends AbstractController
{
     /**
      * @Route("/", name="landing")
      */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository('App\Entity\Category');
        $htmlTree = $repo->childrenHierarchy(
            null, /* starting from root nodes */
            false, /* true: load all children, false: only direct */
            array(
                'decorate' => true,
                'representationField' => 'slug',
                'html' => true
            )
        );

        return $this->render('landing/index.html.twig', [
            'categories' => $htmlTree
        ]);
    }
}
