<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PokemonController extends AbstractController
{
    //
    //requirements pour typer les parametres de notre URL
    //defaults pour definir une valeur par default d'un parametre si il est pas renseigne dans url
    #[Route(
        path:'/pokemon/{name}/{type}/{pv}', 
        name:'app_pokemon', 
        defaults:["pv"=>"10", "name"=>"pas de pokemon", "type"=>"pas de type"],
        requirements:["name" => '[a-z0-9]+', "type"=> '[a-z0-9]+']
    )]
    public function pokemon(Request $request): Response
    {
        //STEP 1 
        //Si je passe dans ma routes de la data alors c'est des parameters sinon c'est des query
        //Les objets de $request sont de type parameterBag qui est utile pour recuperer ou faire des traitements sur des parameters
        dump($request, $request->attributes->get("name"), $request->attributes->get("type"), $request->attributes->getInt("pv"));
        return new Response("<h1>Voici le pokemons ".
         $request->attributes->get("name")." ".$request->attributes->get("type")
        ."</h1>");

        //STEP 2
        //On peut aussi recuperer le nom des parametres comme je fais d'habitudes
    }
}
