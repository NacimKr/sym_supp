<?php

namespace App\Controller;

use App\Entity\Pokemon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DoctrineController extends AbstractController
{
    #[Route('/doctrine', name: 'app_doctrine')]
    public function index(EntityManagerInterface $em): Response
    {
        $pokemons_all = $em->getRepository(Pokemon::class)->findAll();
        dump($em->getRepository(Pokemon::class)->getNomPokemon());
        return $this->render('doctrine/index.html.twig', [
            'pokemons_all' => $pokemons_all,
        ]);
    }


    //EntityManagerInterface est chargé de mémoriser les entités de notre appli utile si on souhaite ne pas trop utiliser de repository
    //sont but est de gérer ce qui est en base
    // avec le persist() //generer les requete sql
    //si on veut enregistrer une modification uniquement on est pas oblliger de le mettre le persist
    // et le flush() // execute les requetes (et genere les requetes pour la modification et la suppression)
    #[Route('/doctrine/{name}', name: 'app_doctrine_details')]
    public function detail(Request $request, EntityManagerInterface $em): Response
    {
        $pokemons_details = $em->getRepository(Pokemon::class)->findBy(['name' => $request->attributes->get("name")]);

        if($pokemons_details === [] || $pokemons_details[0]->getName() !== $request->attributes->get("name")){
            return $this->redirectToRoute("app_doctrine");
        }
        return $this->render('doctrine/show.html.twig', [
            'pokemons_all' => $pokemons_details,
        ]);
    }
}
