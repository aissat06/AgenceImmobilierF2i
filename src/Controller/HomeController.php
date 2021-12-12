<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
        $home = 'home';
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Agence Immobilier',
            'admin' => 'Youcef',
            'actif' => $home,
        ]);
    }
}
