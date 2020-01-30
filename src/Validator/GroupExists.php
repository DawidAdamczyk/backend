<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class GroupExists extends Constraint
{
    public $message = 'There is not group of name {{ value }}';
}
