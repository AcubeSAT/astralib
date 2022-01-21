<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\CallbackTransformer;

final class MetadataAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('name')
            ->add('data')
            ->add('locked')
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->addIdentifier('name')
            ->addIdentifier('data')
            ->add('locked')
            ->add('version')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('name')
            ->add('data')
            ->add('locked')
            ;

        // Add transformer to handle data as text
        $form->get('data')->addModelTransformer(new CallbackTransformer(
            function ($tagsAsArray) {
                //o bject stdclass json, need to be transform as string for render form
                return json_encode($tagsAsArray);
            },
            function ($tagsAsString) {
                // string, need to be transform as stdClass for json type for persist in DB
                return json_decode($tagsAsString) ?: $tagsAsString;
            }
        ));
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('name')
            ->add('data')
            ->add('locked')
            ;
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        if (!$this->isChild()) {
            // Clear route configuration on parent to prevent this from showing up as primary
            $collection->clear();
        }
    }
}
