<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Person;
use App\Entity\GroupOfPeople;
use App\Form\PersonType;

class PersonController extends AbstractController
{
    public function list()
    {
        $repository = $this->getDoctrine()->getRepository(Person::class);

        $persons = $repository->findAll();

        return $this->render('Person/list.html.twig', ['persons' => $persons]);
    }

    public function new(Request $request, ValidatorInterface $validator)
    {
        $person = new Person();

        $form = $this->createForm(PersonType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $data = $form->getData();
            $person->setLogin($data['login']);
            $person->setFirstname($data['firstname']);
            $person->setLastname($data['lastname']);
            $person->setCreatedAt(new \DateTime('now'));
            $person->setUpdatedAt(new \DateTime('now'));
            // $errors = $validator->validate($person);
            $people = $this->getDoctrine()
            ->getRepository(GroupOfPeople::class)
            ->findOneBy(['name' => $data['group']]);

            $person->setPersonGroup($people);

            $errors = $validator->validate($person);

            $errorsWithGroup = $validator->validatePropertyValue($person,'person_group',$data['group'] );

            if (count($errors) > 0 || count($errorsWithGroup) > 0) {
                $errorsString = (string) $errors.(string) $errorsWithGroup;
        
                return new Response($errorsString);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($person);
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('Person/new.html.twig', ['form' =>  $form->createView()]);
    }

    public function edit($id, Request $request, ValidatorInterface $validator)
    {
        $person = $this->getDoctrine()
        ->getRepository(Person::class)
        ->find($id);

        $form = $this->createForm(PersonType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            $person->setLogin($data['login']);
            $person->setFirstname($data['firstname']);
            $person->setLastname($data['lastname']);
            $person->setUpdatedAt(new \DateTime('now'));

            $people = $this->getDoctrine()
            ->getRepository(GroupOfPeople::class)
            ->findOneBy(['name' => $data['group']]);

            $person->setPersonGroup($people);
            $errors = $validator->validate($person);

            if (count($errors) > 0) {
                $errorsString = (string) $errors;
        
                return new Response($errorsString);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($person);
            $entityManager->flush();
            
            return $this->redirectToRoute('index');
        }

        return $this->render('Person/edit.html.twig', [
            'form' =>  $form->createView(),
            'person' => $person,
        ]);
    }

    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $person = $entityManager
        ->getRepository(Person::class)
        ->find($id);

        $entityManager->remove($person);
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }
}