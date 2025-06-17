<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RecipeTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('slug', TextType::class, [
                'required' => false
            ])
            ->add('content')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'libelle',
                'multiple' => true
            ])
            ->add('duration')
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer'
            ])
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                [$this, 'autoSlug']
            )
        ;
    }

    public function autoSlug(PreSubmitEvent $pre_submit){
        $recipe = $pre_submit->getData();
        $form = $pre_submit->getForm();

        if(empty($recipe['slug'])){
            $recipe['slug'] = strtolower($recipe['title']);
            $pre_submit->setData($recipe);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
