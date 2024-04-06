<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SecIT\ImapBundle\Connection\ConnectionInterface;
use App\Service\MailConverterService;
use Symfony\Component\HttpFoundation\JsonResponse;
class GetDataController extends AbstractController
{
    #[Route(path: '/getData', name: 'getData', methods: ['GET'])]
    public function index(ConnectionInterface $exampleConnection): JsonResponse
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
      
        return  new JsonResponse($mails);
    }
}
