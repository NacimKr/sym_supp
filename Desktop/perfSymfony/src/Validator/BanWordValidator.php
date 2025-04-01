<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


//Cette classe recoit une valeur et la contrainte liés a l'affichage de cette valeur
class BanWordValidator extends ConstraintValidator
{ 
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var BanWord $constraint */

        //Si la valeur est vide ou null alors on sort car elle est considérée 
        //comme etant valide
        if (null === $value || '' === $value) {
            return;
        }

        //notre contrainte est le BanWord qu'on recuperer via l'injection de dependances Constraint

        foreach($constraint->banWord as $banWord){
            //On regarde si notre valeur contient un mot banni alors on affiche l'erreur
            if(str_contains($value, $banWord)){                
                //Le code en cas d'erreur
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $banWord)
                    ->addViolation();
            }
        }

    }
}
