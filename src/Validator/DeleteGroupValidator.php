<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\PersonRepository;

class DeleteGroupValidator extends ConstraintValidator
{
    private $personRepository;

    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        $people = $this->personRepository->findOneBy(['person_group' => $value]);

        if ($people) {
            $this->context->buildViolation($constraint->message)
            ->addViolation();
        }
        
        if (null === $value || '' === $value) {
            return;
        }
    }
}
