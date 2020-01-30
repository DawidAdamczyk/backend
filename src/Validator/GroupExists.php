<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class GroupExists extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    // public $message = 'The value "{{ value }}" is not valid.';

    public $message = 'There is not group of name {{ value }}';
}
