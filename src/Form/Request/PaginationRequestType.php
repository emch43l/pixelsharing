<?php

namespace App\Form\Request;

use App\Request\PaginationRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaginationRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('page', NumberType::class, [
                'input' => 'string',
                'required' => false,
                'empty_data' => '1',
                'data' => '1'
            ])
            ->get('page')
                ->addModelTransformer(new CallbackTransformer(
                    function (mixed $var) {
                        return $var;
                    },
                    function (mixed $var) {
                        return max($var, 1);
                    })
                );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'allow_extra_fields' => true,
            'data_class' => PaginationRequest::class,
        ]);
    }
}