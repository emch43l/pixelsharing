<?php

namespace App\Form\Request;

use App\Request\HomeRequest;
use App\Request\PaginationRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\UuidType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HomeRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category',UuidType::class, [
                'required' => false,
                'empty_data' => null
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HomeRequest::class,
            'csrf_protection' => false
        ]);
    }

    public function getParent() : string
    {
        return PaginationRequestType::class;
    }

}