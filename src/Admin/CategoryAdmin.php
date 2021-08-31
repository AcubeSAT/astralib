<?php

namespace App\Admin;

use App\Entity\Category;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\BooleanType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

final class CategoryAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('name', TextType::class);
        $form->add('abstract', CheckboxType::class, [
            'required' => false,
        ]);
        $form->add('parent', EntityType::class, [
            'class' => Category::class,
            'required' => false,
        ]);
        $form->add('colour', FormType::class, [
            'attr' => [
                'class' => 'row'
            ],
            'help' => 'Visit https://material.io/resources/color to experiment with the Material palette.'
        ]);

        $colour = $form->get('colour');
        $colour->add('hue', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, [
            'choices' => [
                'Default' => 'default-color',
                'Red' => 'red',
                'Pink' => 'pink',
                'Purple' => 'purple',
                'Deep Purple' => 'deep-purple',
                'Indigo' => 'indigo',
                'Blue' => 'blue',
                'Light Blue' => 'light-blue',
                'Cyan' => 'cyan',
                'Teal' => 'teal',
                'Green' => 'green',
                'Light Green' => 'light-green',
                'Lime' => 'lime',
                'Yellow' => 'yellow',
                'Amber' => 'amber',
                'Orange' => 'orange',
                'Deep Orange' => 'deep-orange',
                'Brown' => 'brown',
                'Grey' => 'grey',
                'Blue-Grey' => 'blue-grey',
                'Black' => 'black',
                'White' => 'white',
                'Transparent' => 'transparent',
            ],
            'row_attr' => [
                'class' => 'col-sm-4'
            ]
        ]);
        $colour->add('lightness', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, [
            'choices' => [
                'Lighten 5 (0)' => 'lighten-5',
                'Lighten 4 (100)' => 'lighten-4',
                'Lighten 3 (200)' => 'lighten-3',
                'Lighten 2 (300)' => 'lighten-2',
                'Lighten 1 (400)' => 'lighten-1',
                'Default (500)' => 'default-color',
                'Darken 1 (600)' => 'darken-1',
                'Darken 2 (700)' => 'darken-2',
                'Darken 3 (800)' => 'darken-3',
                'Darken 4 (900)' => 'darken-4',
                'Accent 1 (A100)' => 'accent-1',
                'Accent 2 (A200)' => 'accent-2',
                'Accent 3 (A400)' => 'accent-3',
                'Accent 4 (A700)' => 'accent-4',
            ],
            'row_attr' => [
                'class' => 'col-sm-4'
            ]
        ]);
        $colour->add('light_text', CheckboxType::class, [
            'required' => false,
            'row_attr' => [
                'class' => 'col-sm-4',
                'style' => 'margin-top: 20px'
            ]
        ]);

        $colour->addModelTransformer(new CallbackTransformer(
            function ($colourString) {
                // Model to Form
                $parts = explode(' ', $colourString);

                $lightnessClass = 'default-color';
                if (isset($parts[2]) && !$parts[2]) {
                    $lightnessClass = 'uk-text-secondary';
                }

                return [
                    'hue' => $parts[0],
                    'lightness' => $parts[1] ?? 'default-color',
                    'light_text' => (!isset($parts[2]) || $parts[2] != 'uk-text-secondary')
                ];
            },
            function ($colourArray) {
                // Form to Model
                $lightnessClass = $colourArray['light_text'] ? 'default-color' : 'uk-text-secondary';
                return "{$colourArray['hue']} {$colourArray['lightness']} {$lightnessClass}";
            }
        ));
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('name');
        $datagrid->add('abstract');
        $datagrid->add('parent');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('id');
        $list->addIdentifier('name');
        $list->addIdentifier('abstract');
        $list->addIdentifier('parent');
        $list->add(ListMapper::NAME_ACTIONS, null, [
            'actions' => [
                'show' => [],
                'edit' => [],
                'delete' => [],
                'propagate_colours' => [
                    'template' => 'CRUD/list__action_propagate_colours.html.twig',
                ]
            ],
        ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('id');
        $show->add('name');
        $show->add('abstract');
        $show->add('parent');
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->add('propagate_colours', $this->getRouterIdParameter().'/propagate_colours');
    }


}
