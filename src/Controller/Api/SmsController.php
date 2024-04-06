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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
class SmsController extends AbstractController
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
            $check = $entityManager->getRepository(Sms::class)->findBy(['idMail' => $id]);
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
    #[Route(path: '/setData', name: 'setData', methods: ['GET'])]
    public function setData(Request $request,EntityManagerInterface $entityManager): RedirectResponse 
    {
        $id = $request->query->get('id', 1);
        $sms = $entityManager->getRepository(Sms::class)->find($id);
        $sms->setSender($request->query->get('sender', 1));
        $sms->setReceiver($request->query->get('receiver', 1));
        $sms->setMessage($request->query->get('message', 1));
        $sms->setData(date_create($request->query->get('data', 1)));
        $entityManager->persist($sms);
        $entityManager->flush();
        return  $this->redirectToRoute('admin');
    }
    #[Route(path: '/addSms', name: 'addData', methods: ['GET'])]
    public function addData(Request $request,EntityManagerInterface $entityManager): RedirectResponse 
    {
        $sms = new Sms();
        
        $sms->setSender($request->query->get('sender', 1));
        $sms->setReceiver($request->query->get('receiver', 1));
        $sms->setMessage($request->query->get('message', 1));
        $sms->setData(date_create($request->query->get('data', 1)));
        $sms->setIdMail(0);
        $entityManager->persist($sms);
        $entityManager->flush();
        return  $this->redirectToRoute('admin');
    }
    #[Route(path: '/deleteSms', name: 'deleteData', methods: ['GET'])]
    public function deleteData(Request $request,EntityManagerInterface $entityManager): RedirectResponse 
    {
        $id = $request->query->get('id', 1);
        $sms = $entityManager->getRepository(Sms::class)->find($id);
        $entityManager->remove($sms);
        $entityManager->flush();
        return  $this->redirectToRoute('admin');
    }
}
