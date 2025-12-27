<?php
namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ProjectRepository;
use App\Repository\CertificationRepository;
use App\Repository\AnalyticsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        Request $request, 
        EntityManagerInterface $entityManager,
        ProjectRepository $projectRepository,
        CertificationRepository $certificationRepository,
        AnalyticsRepository $analyticsRepository
    ): Response
    {
        // Track page view - REAL ANALYTICS
        $session = $request->getSession();
        if (!$session->has('page_viewed')) {
            $analyticsRepository->incrementMetric('total_views', 1);
            $session->set('page_viewed', true);
        }
        
        // Track unique visitors
        if (!$session->has('visitor_counted')) {
            $analyticsRepository->incrementMetric('total_visitors', 1);
            $session->set('visitor_counted', true);
        }

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash('success', 'Thank you! Your message has been sent successfully. I will get back to you soon.');

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        // Fetch only published projects
        $projects = $projectRepository->findBy(
            ['isPublished' => true], 
            ['createdAt' => 'DESC']
        );

        // Fetch only active certifications
        $certifications = $certificationRepository->findBy(
            ['isActive' => true], 
            ['displayOrder' => 'ASC']
        );

        return $this->render('home/index.html.twig', [
            'contactForm' => $form,
            'projects' => $projects,
            'certifications' => $certifications,
        ]);
    }
}
