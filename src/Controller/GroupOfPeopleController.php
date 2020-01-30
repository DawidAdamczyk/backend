<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Validator\DeleteGroup;
use App\Entity\GroupOfPeople;
use App\Form\GroupType;

class GroupOfPeopleController extends AbstractController
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
    public function list()
    {
        $repository = $this->getDoctrine()->getRepository(GroupOfPeople::class);

        $groups = $repository->findAll();

        return $this->render('Group/list.html.twig', ['groups' => $groups]);
    }

    public function new(Request $request)
    {
        $group = new GroupOfPeople();

        $form = $this->createForm(GroupType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $group->setName($data['name']);
            $group->setInfo($data['info']);
            $group->setCreatedAt(new \DateTime('now'));
            $group->setUpdatedAt(new \DateTime('now'));

            $errors = $this->validator->validate($group);

            if (count($errors) > 0) {
                $errorsString = (string) $errors;
        
                $this->addFlash('fail', $errorsString);
            
                return $this->redirectToRoute('index');
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($group);
            $entityManager->flush();

            $this->addFlash('success', 'Group created');
            
            return $this->redirectToRoute('index');
        }

        return $this->render('Group/new.html.twig', ['form' =>  $form->createView()]);
    }

    public function edit($id, Request $request)
    {
        $group = $this->getDoctrine()
        ->getRepository(GroupOfPeople::class)
        ->find($id);

        $form = $this->createForm(GroupType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            $group->setName($data['name']);
            $group->setInfo($data['info']);
            $group->setCreatedAt(new \DateTime('now'));
            $group->setUpdatedAt(new \DateTime('now'));

            $errors = $this->validator->validate($group);

            if (count($errors) > 0) {
                $errorsString = (string) $errors;
        
                $this->addFlash('fail', $errorsString);
            
                return $this->redirectToRoute('index');
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($group);
            $entityManager->flush();

            $this->addFlash('success', 'Group updated');
            
            return $this->redirectToRoute('index');
        }

        return $this->render('Group/edit.html.twig', [
            'form' =>  $form->createView(),
            'group' => $group
        ]);
    }

    public function delete($id)
     {
        $constraint = new DeleteGroup();

        $violations = $this->validator->validate($id, $constraint);
        $errors =  $violations;
        
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
    
            $this->addFlash('fail', $errorsString);
            
            return $this->redirectToRoute('index');
        }
        $entityManager = $this->getDoctrine()->getManager();

        $group = $entityManager
        ->getRepository(GroupOfPeople::class)
        ->find($id);

        $entityManager->remove($group);
        $entityManager->flush();

        $this->addFlash('success', 'Group deleted');

        return $this->redirectToRoute('index');


    }
}