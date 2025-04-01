<?php

namespace App\Form;

use App\Entity\Dresseur;
use App\Entity\Pokemon;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Sequentially;

/**
 * A la soumission du formulaire on a 3 evenement qui se declenche 
 * PRE_SUBMIT
 * SUBMIT
 * POST_SUBMOT
 * 
 * Lorsqu'on créer un ecouteur d'evenemnt on va pouvoir preciser ou PRE_SUBMIT ce qu'il doit
 * faire avant de soumettre le formulaire ça nous permet d'avoir un controle sur les données
 * 
 * Avantages: ça eviter de trop surcharger le controlleurs
 */
class PokemonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                //Pour verfier si l'ensemble des contraintes sont bien pris en compte sinon il affiche la premiere erreur afficher les erreurs un par un
                "constraints" =>new Sequentially( 
                    [                    
                    new Length(
                        min: 5,
                        max: 50,
                        minMessage : "Le nom est trop court 2 minimum", 
                        maxMessage : "Le nom est trop long 10 maximum",
                    ),
                    new Regex(
                        '/[a-z]/',
                        message: 'Le nom ne doit pas comporter de chiffre'
                    )
                    ]
                )
            ])
             //Si on souhaite voir si un champs est vide (exemple un simple espace au lieu d'un caractere complet)
            ->add('content', TextareaType::class, [
                'empty_data' => "Non renseigné" //ou une chaine vide
            ])
            ->add('slug', TextType::class, [
                'required' => false 
            ])
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('updatedAt', null, [
            //     'widget' => 'single_text', 
            // ])
            ->add('pv')
            ->add("type", EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'libelle',
                // 'multiple' => true
                // 'expanded' => !false,
                
                // S'il est a false il rajouter avec la methode add et non le set 
                //comme il fait par défaut voir tuto 12 17:10
                // 'by_reference' => false
            ])

            ->add("dresseur", EntityType::class, [
                'class' => Dresseur::class,
                'choice_label' => 'nom',
            ])

            //On peut rajouter un ecouteurEveneemnt
            ->addEventListener(FormEvents::PRE_SUBMIT,$this->autoFilSlug(...))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->setUpdatedAtForm(...))
            ->add("Add", SubmitType::class, [
                'attr' => [
                    "class" => 'btn btn-danger'
                ]
            ])
        ;
    }

    //Fonction qui va s'executer avant la soumission du formulaire
    public function autoFilSlug(PreSubmitEvent $event){
        //On a regarder ce qu'on a à la soumission du formulaire
        //dd($event->getData())

        $data = $event->getData();
        //On a juste a remplir le slug par la meme valeur que le nom
        $data["slug"] = $data["name"];

        //On fait un setData pour mettre a jour
        $event->setData($data);
    }


    //On met a jour le updatedAt
    public function setUpdatedAtForm(PostSubmitEvent $postSubmitEvent){
        $date_data = $postSubmitEvent->getData();
        $date_updated_at = new \DateTimeImmutable();
        $date_data->setUpdatedAt($date_updated_at);

        if(!$date_data->getId()){
            $date_data->setCreatedAt($date_updated_at);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pokemon::class,
        ]);
    }
}
