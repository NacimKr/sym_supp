<?php

namespace App;

use Symfony\Component\Form\FormFactoryInterface;

class Test{
  public function __construct(
    public FormFactoryInterface $formInterface,
    private string $name, 
    private int $level
  )
  {
  }
}