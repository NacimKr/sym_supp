<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('slug', null, [
                'required' => false
            ])
            ->add('recipes', EntityType::class, [
                'class' => Recipe::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false // pour modifier les cases a cocher (plusieurs) pour modifier l'objet
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('createdaAt', null, [
                'widget' => 'single_text',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer'
            ])
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                [$this, 'onPreSetData']
            )
        ;
    }

    public function onPreSetData(PreSubmitEvent $event): void
    {
        $category = $event->getData();
        
        if(empty($category["slug"])){
            $category['slug'] = strtolower($category["libelle"]);
            $event->setData($category);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
