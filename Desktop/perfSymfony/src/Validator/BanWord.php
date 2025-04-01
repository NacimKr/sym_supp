<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BanWord extends Constraint
{

    public function __construct(
        public string $message = 'The value "{{ value }}" is not valid.',
        public array $banWord = ["spam", "gueule"],
    ){
        parent::__construct();
    }
}
