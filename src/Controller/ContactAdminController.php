<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Contact;

#[Route('/admin/messages')]
#[IsGranted('ROLE_ADMIN')]
class ContactAdminController extends AbstractController
{
    #[Route('/', name: 'admin_contact_index')]
    public function index(ContactRepository $contactRepository): Response
    {
        $messages = $contactRepository->findBy([], ['createdAt' => 'DESC']);
        
        return $this->render('admin/contact/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route('/{id}', name: 'admin_contact_show', methods: ['GET'])]
    public function show(Contact $contact, EntityManagerInterface $entityManager): Response
    {
        // Marquer comme lu
        if (!$contact->isRead()) {
            $contact->setIsRead(true);
            $entityManager->flush();
        }

        return $this->render('admin/contact/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_contact_delete', methods: ['POST'])]
    public function delete(Contact $contact, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($contact);
        $entityManager->flush();

        $this->addFlash('success', 'Message deleted successfully');

        return $this->redirectToRoute('admin_contact_index');
    }
}