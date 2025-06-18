<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

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
                'expanded' => true,
            ])
            ->add('duration')
            ->add('brochure', FileType::class, [
                'label' => 'Brochure (PDF file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '1024m',
                        extensions: ['pdf'],
                        extensionsMessage: 'Please upload a valid PDF document',
                    )
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer'
            ])
            ->add('quantities', CollectionType::class, [
                'entry_type' => QuantityTypeForm::class,
                'by_reference' => false,
                'entry_options' => ['label' => false]
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
