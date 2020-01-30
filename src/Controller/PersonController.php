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
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function list()
    {
        $repository = $this->getDoctrine()->getRepository(Person::class);

        $people = $repository->findAll();

        return $this->render('Person/list.html.twig', ['people' => $people]);
    }

    public function new(Request $request)
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
            $people = $this->getDoctrine()
            ->getRepository(GroupOfPeople::class)
            ->findOneBy(['name' => $data['group']]);

            $person->setPersonGroup($people);

            $errors = $this->validator->validate($person);

            $errorsWithGroup = $this->validator->validatePropertyValue($person,'person_group',$data['group'] );

            if (count($errors) > 0 || count($errorsWithGroup) > 0) {

                $errorsString = (string) $errors.(string) $errorsWithGroup;

                $this->addFlash('fail', $errorsString);

                return $this->redirectToRoute('index');
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($person);
            $entityManager->flush();

            $this->addFlash('success', 'Person created');

            return $this->redirectToRoute('index');
        }

        return $this->render('Person/new.html.twig', ['form' =>  $form->createView()]);
    }

    public function edit($id, Request $request)
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
             $errors = $this->validator->validate($person);

            $errorsWithGroup = $this->validator->validatePropertyValue($person,'person_group',$data['group'] );

            if (count($errors) > 0 || count($errorsWithGroup) > 0) {

                $errorsString = (string) $errors.(string) $errorsWithGroup;

                $this->addFlash('fail', $errorsString);

                return $this->redirectToRoute('index');
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($person);
            $entityManager->flush();

            $this->addFlash('success', 'Person updated');
            
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

        $this->addFlash('success', 'Person deleted');

        return $this->redirectToRoute('index');
    }
}