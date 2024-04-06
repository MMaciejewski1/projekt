<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SecIT\ImapBundle\Connection\ConnectionInterface;
use App\Service\MailConverterService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Sms;
class GetDataController extends AbstractController
{
    #[Route(path: '/getData', name: 'getData', methods: ['GET'])]
    public function index(ConnectionInterface $exampleConnection,EntityManagerInterface $entityManager): JsonResponse
    {

        $mailbox = $exampleConnection->getMailbox();
        $mails = [];
        foreach($mailbox->searchMailbox('ALL') as $id){
            $text = ($mailbox->getMailMboxFormat($id));
            $text = substr($text,strpos($text,'Nadawca'));
            $text = strip_tags($text);
            $mail['id'] = $id;
            $mail['sender'] = MailConverterService::getMailData($text,'Nadawca','Odbiorca');
            $mail['receiver'] = MailConverterService::getMailData($text,'Odbiorca','Treść ');
            $mail['message'] = MailConverterService::getMailData($text,'wiadomości','Data'); 
            $mail['data'] = MailConverterService::getMailData($text,'Data',''); 
            $check = $entityManager->getRepository(Sms::class)->find($id);
            if(!$check){
                $sms = new Sms();
                $sms->setFromArray($mail);
                $entityManager->persist($sms);
                $mails[]=$mail;
            }
        }
            $entityManager->flush();
        return  new JsonResponse($mails);
    }
}
