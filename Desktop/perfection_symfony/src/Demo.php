<?php

namespace App;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class Demo{
  public string $nom = "Toto";
  public  RequestStack $request;

  public function __construct(string $nom, RequestStack $request) {
    $this->nom = $nom;
    $this->request = $request;
  }
}