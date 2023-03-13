<?php

namespace App\Form;

use App\RequestDataTemplate\HomeTemplate;
use \Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\StringToFloatTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\UuidToStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\UuidType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Uid\Uuid as UuidV4;

class HomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category',UuidType::class, [
                'required' => false,
                'empty_data' => null
            ])
            ->add('page', NumberType::class, [
                'input' => 'string',
                'required' => false,
                'empty_data' => '1',
                'constraints' => [
                    new Positive()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HomeTemplate::class,
            'csrf_protection' => false
        ]);
    }
}