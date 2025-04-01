<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController {

    //On utilise les parametre nommée afin de mieux savoir quel parametre sont renseignés
    //Car on peut en mettre plusieurs

    //L'objet Request en parametre
    #[Route(path:"/", name:"main_home", methods:["GET"])]
    function index(Request $request):Response
    {
        //On peut utiliser les super-globales en PHP avec Symfony
        //return new Response("<h1>Bonjour les pokemons ".$_GET['name']."</h1>", 200);
        //OU avec l'objet Request qui permet d'intéragir avec la requete (fini les variables super-globales en symfony avec l'objet Request)
        dump($request);

        //$request->query->get('name', "inconnu") veut dire que si le nom dans l'url existe pas alors on met inconnu
        return new Response("<h1>Bonjour les pokemons ".$request->query->get('name', "inconnu")."</h1>", 200);
    }
}