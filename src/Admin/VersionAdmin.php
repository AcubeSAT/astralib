<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Source;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionFactoryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\CollectionType;

final class VersionAdmin extends AbstractAdmin
{
    public function __construct(string $code, string $class, string $baseControllerName)
    {
        parent::__construct($code, $class, $baseControllerName);
    }


    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('document')
            ->add('number')
            ->add('variant')
            ->add('public')
            ->add('date')
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('document')
            ->addIdentifier('number')
            ->add('variant')
            ->add('public')
            ->add('date')
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
            ->with('Document')
                ->add('document', ModelAutocompleteType::class, [
                    'property' => 'title',
                    'btn_add' => false
                ])
            ->end()
            ->with('General', ['class' => 'col-md-6'])
                ->add('number')
                ->add('variant', \Sonata\AdminBundle\Form\Type\CollectionType::class, [
                    'allow_add' => true,
                    'allow_delete' => true
                ])
                ->add('public')
                ->add('date')
            ->end()
            ->with('Metadata', ['class' => 'col-md-6'])
                ->add('metadata', CollectionType::class, [
                ], [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'position',
                ])
            ->end()
            ->with('Sources')
//                ->add('sources', ModelType::class, [
//                    'multiple' => true
//                ])
            ->end()
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('document')
            ->add('number')
            ->add('variant')
            ->add('public')
            ->add('date')
            ->add('sources')
            ->add('metadata', null, [
                'admin_code' => 'App\Admin\DocumentAdmin|App\Admin\VersionAdmin|App\Admin\MetadataAdmin'
            ])
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
