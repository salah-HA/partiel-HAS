<?php

namespace App\Form;

use App\Entity\Editor;
use App\Entity\VideoGame;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoGameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class)
            ->add('support',TextType::class)
            ->add('description',TextAreaType::class)
            ->add('releaseAt', DateType::class,[
                'years'=>range(date('Y')-100,date('Y'))
            ])
            ->add('editor',EntityType::class, [
                'class'=> Editor::class,
                'choice_label'=> 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VideoGame::class,
        ]);
    }
}
