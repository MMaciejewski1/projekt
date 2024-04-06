<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SecIT\ImapBundle\Connection\ConnectionInterface;
use App\Service\MailConverterService;
class MainPageController extends AbstractController
{
    #[Route(path: '/', name: 'mainpage', methods: ['GET'])]
    public function index(ConnectionInterface $exampleConnection): Response
    {

        $mailbox = $exampleConnection->getMailbox();
        $mails = [];
        foreach($mailbox->searchMailbox('ALL') as $id){
            $text = ($mailbox->getMailMboxFormat($id));
            $text = substr($text,strpos($text,'Nadawca'));
            $text = strip_tags($text);

            $mail['sender'] = MailConverterService::getMailData($text,'Nadawca','Odbiorca');
            $mail['receiver'] = MailConverterService::getMailData($text,'Odbiorca','Treść ');
            $mail['message'] = MailConverterService::getMailData($text,'wiadomości','Data'); 
            $mail['data'] = MailConverterService::getMailData($text,'Data',''); 
            $mails[]=$mail;
        }
      
        return $this->render('index.html.twig', [
            'mails' => $mails,
        ]);
    }
}
