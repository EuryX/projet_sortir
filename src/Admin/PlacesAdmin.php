<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class PlacesAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('namePlace', TextType::class)
                    ->add('city')
                    ->add('latitude')
                    ->add('longitude')
                    ->add('street');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('namePlace')
                    ->add('city')
                    ->add('latitude')
                    ->add('longitude')
                    ->add('street');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('namePlace')
                    ->add('city')
                    ->add('latitude')
                    ->add('longitude')
                    ->add('street');
    }
}