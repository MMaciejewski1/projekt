<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    #[Route(path: '/articles', name: 'articles', methods: ['GET'])]
    public function list(): Response
    {
        return $this->render('base.html.twig', [
            'number' => 1,
        ]);
    }
}
