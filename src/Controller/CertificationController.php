<?php

namespace App\Controller;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Certification;
use App\Form\CertificationType;
use App\Repository\CertificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/certification')]
#[IsGranted('ROLE_ADMIN')]
class CertificationController extends AbstractController
{
    #[Route(name: 'app_certification_index', methods: ['GET'])]
    public function index(CertificationRepository $certificationRepository): Response
    {
        return $this->render('certification/index.html.twig', [
            'certifications' => $certificationRepository->findAll(),
        ]);
    }

   #[Route('/new', name: 'app_certification_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $certification = new Certification();
    $form = $this->createForm(CertificationType::class, $certification);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle image upload
        $imageFile = $form->get('imageFile')->getData();
        
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('kernel.project_dir').'/public/uploads/certifications',
                    $newFilename
                );
                $certification->setImage($newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Error uploading image');
            }
        }

        $entityManager->persist($certification);
        $entityManager->flush();

        $this->addFlash('success', 'Certification added successfully!');

        return $this->redirectToRoute('app_certification_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('certification/new.html.twig', [
        'certification' => $certification,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_certification_show', methods: ['GET'])]
    public function show(Certification $certification): Response
    {
        return $this->render('certification/show.html.twig', [
            'certification' => $certification,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_certification_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Certification $certification, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $form = $this->createForm(CertificationType::class, $certification);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle image upload
        $imageFile = $form->get('imageFile')->getData();
        
        if ($imageFile) {
            // Delete old image
            if ($certification->getImage()) {
                $oldImagePath = $this->getParameter('kernel.project_dir').'/public/uploads/certifications/'.$certification->getImage();
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('kernel.project_dir').'/public/uploads/certifications',
                    $newFilename
                );
                $certification->setImage($newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Error uploading image');
            }
        }

        $entityManager->flush();

        $this->addFlash('success', 'Certification updated successfully!');

        return $this->redirectToRoute('app_certification_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('certification/edit.html.twig', [
        'certification' => $certification,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_certification_delete', methods: ['POST'])]
    public function delete(Request $request, Certification $certification, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$certification->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($certification);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_certification_index', [], Response::HTTP_SEE_OTHER);
    }
}
