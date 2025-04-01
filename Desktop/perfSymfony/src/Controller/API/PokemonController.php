<?php

namespace App\Controller\API;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PokemonController extends AbstractController {

  #[Route('/api/pokemon', name:"api_pokemon")]
  public function api_pokemon(PokemonRepository $pokemonRepository):JsonResponse
  {
    $pokemons = $pokemonRepository->findAll();
    return $this->json($pokemons, 200, [], [
      'groups' => ['pokemons.index']
    ]);
  } 


  #[Route("/api/pokemon/details/{id}", name:"api_pokemons_details", requirements:['id'=> '[0-9]'])]
  public function show_api(Pokemon $pokemon):JsonResponse
  {
    // $this->denyAccessUnlessGranted('ROLE_USER');
    return $this->json($pokemon, 200, [], [
      'groups' => ['pokemons.index', 'pokemons.show']
    ]);
  }
}