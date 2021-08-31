<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryAdminController extends CRUDController
{
    /**
     * @param $id
     */
    public function propagateColoursAction(Request $request): Response
    {
        $this->assertObjectExists($request, true);

        /** @var Category $object */
        $object = $this->admin->getSubject();

        $this->admin->checkAccess('edit', $object);

        if ($request->getMethod() === Request::METHOD_POST) {
            $this->validateCsrfToken($request, 'sonata.propagate_colours');

            $this->getDoctrine()->getRepository(Category::class)
                ->propagateColours($object);
            $this->getDoctrine()->getManager()->flush();

            if ($this->isXmlHttpRequest($request)) {
                return $this->renderJson(['result' => 'ok']);
            }

            $this->addFlash('sonata_flash_success', 'Colours propagated to children successfully');

            return $this->redirectTo($request, $object);
        }

        return $this->renderWithExtraParams('CRUD/propagate_colours.html.twig', [
            'object' => $object,
            'action' => 'propagate_colours',
            'csrf_token' => $this->getCsrfToken('sonata.propagate_colours'),
        ]);
    }
}