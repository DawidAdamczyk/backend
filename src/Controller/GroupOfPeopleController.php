<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Person;
use App\Entity\GroupOfPeople;
use App\Form\GroupType;

class GroupOfPeopleController extends AbstractController
{
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($group);
            $entityManager->flush();
            
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($group);
            $entityManager->flush();
            
            return $this->redirectToRoute('index');
        }

        return $this->render('Group/new.html.twig', ['form' =>  $form->createView()]);
    }

    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $group = $entityManager
        ->getRepository(GroupOfPeople::class)
        ->find($id);

        $entityManager->remove($group);
        $entityManager->flush();

        return $this->redirectToRoute('index');


    }
}