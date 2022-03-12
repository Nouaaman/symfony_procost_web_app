<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Job;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2]),
                ],
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2]),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 10]),
                ],
            ])
            ->add('dailyCost', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2]),
                ],
            ])
            ->add('hiringDate', DateType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('idJob', EntityType::class, [
                'class' => Job::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
