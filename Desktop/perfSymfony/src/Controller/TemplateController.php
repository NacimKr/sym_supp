<?php

namespace App\Controller;

use App\Entity\Dresseur;
use App\Entity\Pokemon;
use App\Entity\Type;
use App\Entity\User;
use App\Form\PokemonType;
use App\Form\TypeType;
use App\Repository\PokemonRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Test;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TemplateController extends AbstractController
{

    #[Route("/demo")]
    public function demo(Test $test){
        dd($test);
    }


    #[Route('/template', name: 'app_template')]
    public function index(EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $repo = $em->getRepository(Pokemon::class)->findAll();
        // $dresseurs = $em->getRepository(Dresseur::class)->findAll();
        
        // $type = new Type();
        // $type->setLibelle("Herbe")
        //     ->setPokemon($repo[mt_rand(0, count($repo)-1)]);
        //     ->setPokemon($repo[mt_rand(0, count($repo)-1)]);
        // $em->persist($type);
        // $dresseur = new Dresseur();
        // $dresseur->setNom("Ondine");
        // $em->persist($dresseur);
        // $em->flush();

        // dump($this->container->get("form.factory"));

        //Ici ils 5 requetes pour afficher les type de pokemon pour economiser les requetes on va
        //passer par le queryBuilder il le demander de faire la liaison avec les types de pokemon
        //voir le DQL getAllPokemonWithType()

        // $user = new User();

        // $hashedPassword = $userPasswordHasherInterface->hashPassword(
        //     $user,
        //     "test123"
        // );
        
        // $user->setEmail('test@mail.com')
        //     ->setRoles(['ROLE_ADMIN'])
        //     ->setPassword($hashedPassword)
        // ;
        // $em->persist($user);
        // $em->flush();

        dump($repo);
        return $this->render('template/index.html.twig', [
            "title" => "Pokédex",
            "repo" => $repo,
            'all_pokemons' => $repo,
        ]);
    }


    #[Route('/template_type', name: 'app_template_type')]
    public function index_type(EntityManagerInterface $em): Response
    {
        $repo = $em->getRepository(Type::class)->findAll();
        // $dresseurs = $em->getRepository(Dresseur::class)->findAll();
        
        // $type = new Type();
        // $type->setLibelle("Herbe")
        //     ->setPokemon($repo[mt_rand(0, count($repo)-1)]);
        //     ->setPokemon($repo[mt_rand(0, count($repo)-1)]);
        // $em->persist($type);
        // $dresseur = new Dresseur();
        // $dresseur->setNom("Ondine");
        // $em->persist($dresseur);
        // $em->flush();

        // dump($this->container->get("form.factory"));

        //Ici ils 5 requetes pour afficher les type de pokemon pour economiser les requetes on va
        //passer par le queryBuilder il le demander de faire la liaison avec les types de pokemon
        //voir le DQL getAllPokemonWithType()
        dump($repo);
        return $this->render('template/type.html.twig', [
            "title" => "Pokédex",
            "repo" => $repo,
            'all_pokemons' => $repo,
        ]);
    }



    #[Route("/add", name:"app_template_add")]
    public function add(Request $request, EntityManagerInterface $em):Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $pokemon = new Pokemon();
        $form = $this->createForm(PokemonType::class, $pokemon);
        //Au lieu d'utiliser le createForm on peut utiliser le formFactoryInterface
        //$form = $formFactoryInterface(PokemonType::class, $pokemon);


        dump($this->container->get("twig"));
        //On gere la requete du formulaire
        //Il regarde si la requete est en POST 
        //S'il est soumis il modifie l'entité pokemon pour remplir avec les données provenant du formulaire
        $form->handleRequest($request);

        //On regarder si le formulaire à été soumis
        if($form->isSubmitted() && $form->isValid()){
            //Si c'est le cas on sauvegarde notre saisie
            $em->persist($pokemon);
            $em->flush();
            //Et on le redirige sur une aute page
            return $this->redirectToRoute("app_template");
        }

        //La methode render utiliser le service de twig via un container
        return $this->render('template/add.html.twig', [
            "form" => $form
        ]);
    }



    #[Route("/add_type", name:"app_template_add_type")]
    public function add_type(Request $request, EntityManagerInterface $em):Response
    {
        $pokemon = new Type();
        $form = $this->createForm(TypeType::class, $pokemon);
        //Au lieu d'utiliser le createForm on peut utiliser le formFactoryInterface
        //$form = $formFactoryInterface(PokemonType::class, $pokemon);


        dump($this->container->get("twig"));
        //On gere la requete du formulaire
        //Il regarde si la requete est en POST 
        //S'il est soumis il modifie l'entité pokemon pour remplir avec les données provenant du formulaire
        $form->handleRequest($request);

        //On regarder si le formulaire à été soumis
        if($form->isSubmitted() && $form->isValid()){
            //Si c'est le cas on sauvegarde notre saisie
            $em->persist($pokemon);
            $em->flush();
            //Et on le redirige sur une aute page
            return $this->redirectToRoute("app_template");
        }

        //La methode render utiliser le service de twig via un container
        return $this->render('template/add_type.html.twig', [
            "form" => $form
        ]);
    }



    #[Route("/template/{name}", name:"app_template_show")]
    public function show(Request $request):Response{
        return $this->render('template/show.html.twig', [
            'pokemon' => $request->attributes->get("name")
        ]);
    }

    #[Route("/edit/pokemon/{id}", name:"app_template_edit")]
    public function edit_pokemon(Request $request, PokemonRepository $pokemonRepository, EntityManagerInterface $em):Response
    {
        $getIdPokemon = (int)$request->attributes->get('id');
        $pokemonId = $pokemonRepository->find($getIdPokemon);

        $form = $this->createForm(PokemonType::class, $pokemonId);

        //On gere la requete du formulaire
        //Il regarde si la requete est en POST 
        //S'il est soumis il modifie l'entité pokemon pour remplir avec les données provenant du formulaire
        $form->handleRequest($request);
        // dd($pokemonId);

        //On regarder si le formulaire à été soumis
        if($form->isSubmitted() && $form->isValid()){
            //Si c'est le cas on sauvegarde notre saisie
            $em->flush();

            //On pourra l'affiche grace a app (superglobale de notre twig)
            $this->addFlash("success", "L'ajout a été modifié avec succés");

            //Et on le redirige sur une aute page
            return $this->redirectToRoute("app_template");
        }

        // dd($pokemonId);
        return $this->render("template/add.html.twig", [
            "form" => $form
        ]);
    }


    #[Route("/edit/type/{id}", name:"app_template_edit_type")]
    public function edit_type(Request $request, TypeRepository $typeRepository, EntityManagerInterface $em):Response
    {
        $getIdPokemon = (int)$request->attributes->get('id');
        $pokemonId = $typeRepository->find($getIdPokemon);

        $form = $this->createForm(TypeType::class, $pokemonId);

        //On gere la requete du formulaire
        //Il regarde si la requete est en POST 
        //S'il est soumis il modifie l'entité pokemon pour remplir avec les données provenant du formulaire
        $form->handleRequest($request);
        // dd($pokemonId);

        //On regarder si le formulaire à été soumis
        if($form->isSubmitted() && $form->isValid()){
            //Si c'est le cas on sauvegarde notre saisie
            $em->flush();

            //On pourra l'affiche grace a app (superglobale de notre twig)
            $this->addFlash("success", "L'ajout a été modifié avec succés");

            //Et on le redirige sur une aute page
            return $this->redirectToRoute("app_template");
        }

        // dd($pokemonId);
        return $this->render("template/add_type.html.twig", [
            "form" => $form
        ]);
    }


    /**
     * On utilise la methode DELETE pour resepcter les verbes HTTP
     * mais les navigateur ne support que le GET et le POST
     * donc pour la suppression on va créer un formulaire avec un champs cacher 
     * avec comme name _method et ce formulaire sera en POST avec un button type submit
     * Pour que cela fonction il faudrea aller dans la configuration du framework pour
     * activer cette configuration
     * dans config/packages/framework.yaml 
     * http_method_override  : true
     * pour activer la methode quand on met un champs 
     * cacher avec input hidden et name=_method
     */
    #[Route("remove/pokemon/{id}", name:"app_template_delete", methods:["DELETE"])]
    public function remove_pokemon(Request $request, PokemonRepository $pokemonRepository, EntityManagerInterface $em){
        $id_remove_pokemon = (int)$request->attributes->get("id");
        $pokemon_remove = $pokemonRepository->find($id_remove_pokemon);
        $em->remove($pokemon_remove);
        $em->flush();
        return $this->redirectToRoute("app_template");
    }


    #[Route("remove/type/{id}", name:"app_template_delete_type", methods:["DELETE"])]
    public function remove_type(Request $request, TypeRepository $typeRepository, EntityManagerInterface $em){
        $id_remove_pokemon = (int)$request->attributes->get("id");
        $pokemon_remove = $typeRepository->find($id_remove_pokemon);
        $em->remove($pokemon_remove);
        $em->flush();
        return $this->redirectToRoute("app_template");
    }
}
