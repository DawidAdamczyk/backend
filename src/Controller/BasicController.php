<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BasicController extends AbstractController
{
    public function index()
    {
        return  $this->render('base.html.twig');
    }
}