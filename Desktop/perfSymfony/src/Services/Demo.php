<?php

//Constitue l'emplacement de là ou est situer le fichier NE SURTOUT PAS L'OUBLIER
namespace App\Services;

use Symfony\Component\Form\FormFactoryInterface;


//Ci dessous avec les services fourni avec la commande
//php bin/console debug:autowiring 
//Il injecte de cette façon tout nos services lorsqu'on fait une injection de dépendances
class Demo{

  //Lorsqu'on debug notre Demo on aura les attribut sans le besoin de les construire
  //Symfony se chargera pour nous de l'instancie meme si les attribut ont besoin de dependances
  /* En php classique
  class Demo{
    public int $num;
    public string $string;
    public function __construct($num, $string){}
    public function showDemo(string $value) 
  }

  Symfony le fera pour nous
  $demo1 = new Demo(42,"blabla");
  print_r($demo1);
  */
  public function __construct(public FormFactoryInterface $formFactor, public string $string){
  }

  public function showDemo(string $value)
  {
    return "My value is ".$value;
  }

}