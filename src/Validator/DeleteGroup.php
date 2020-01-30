<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class DeleteGroup extends Constraint
{
    public $message = 'This group have people';
}
