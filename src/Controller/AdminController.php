<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SecIT\ImapBundle\Connection\ConnectionInterface;
use App\Service\MailConverterService;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Sms;

class AdminController extends AbstractController
{
    #[Route(path: '/admin', name: 'admin', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $mails = $entityManager->getRepository(Sms::class)->findAll();
      
        return $this->render('table.html.twig', [
            'mails' => $mails,
        ]);
    }
}
