<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Person;
use App\Entity\Group;
use App\Form\PersonType;

class PersonController extends AbstractController
{
    public function list()
    {
        $repository = $this->getDoctrine()->getRepository(Person::class);

        $persons = $repository->findAll();

        return $this->render('Person/list.html.twig', ['persons' => $persons]);
    }

    public function new(Request $request)
    {
        $person = new Person();

        $form = $this->createForm(PersonType::class);

         
        $form->handleRequest($request);
        if ($form->isSubmitted()  && $form->isValid()) {
            $data = $form->getData();
            
            $person->setLogin($data['login']);
            $person->setFirstname($data['firstname']);
            $person->setLastname($data['lastname']);

            $group = $this->getDoctrine()
            ->getRepository(Group::class)
            ->find('1');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($person);
            $entityManager->flush();
            return $this->redirectToRoute('index');
        }
        $form->getErrors();
        return $this->render('Person/new.html.twig', ['form' =>  $form->createView()]);


    }
}