<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\GroupOfPeopleRepository;
// use App\Entity\GroupOfPeople;

class GroupExistsValidator extends ConstraintValidator
{
    private $groupRepository;

    public function __construct(GroupOfPeopleRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        //obsługuje przypadek, dla braku grupy (użytkownik nie musi być przypisany)
         if (null === $value || '' === $value || 'string' !== \gettype($value)) {
            return;
        }

        $group = $this->groupRepository->findOneBy(['name' => $value]);
        
        //obsługuje przypadek dla nieistniejącej nazwy grupy
        if (!$group) {
            $this->context->buildViolation($constraint->message)
             ->setParameter('{{ value }}', $value)
             ->addViolation();
        }
    }
}
