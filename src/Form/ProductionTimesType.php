<?php

namespace App\Form;

use App\Entity\ProductionTimes;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductionTimesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('productionTime')
            ->add('idProject', EntityType::class, [
                'class' => Project::class,
                'query_builder' => function (ProjectRepository $pr) {
                    return $pr->currentProjects();
                },
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductionTimes::class,
        ]);
    }
}
