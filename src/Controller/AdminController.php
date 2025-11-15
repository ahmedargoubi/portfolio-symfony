<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function index(
        ProjectRepository $projectRepository,
        ContactRepository $contactRepository,
        UserRepository $userRepository
    ): Response
    {
        // Statistics
        $totalProjects = $projectRepository->count([]);
        $publishedProjects = $projectRepository->count(['isPublished' => true]);
        $totalMessages = $contactRepository->count([]);
        $unreadMessages = $contactRepository->count(['isRead' => false]);
        $totalUsers = $userRepository->count([]);
        
        // Recent projects
        $recentProjects = $projectRepository->findBy(
            [],
            ['createdAt' => 'DESC'],
            5
        );
        
        // Recent messages
        $recentMessages = $contactRepository->findBy(
            [],
            ['createdAt' => 'DESC'],
            5
        );

        return $this->render('admin/index.html.twig', [
            'totalProjects' => $totalProjects,
            'publishedProjects' => $publishedProjects,
            'totalMessages' => $totalMessages,
            'unreadMessages' => $unreadMessages,
            'totalUsers' => $totalUsers,
            'recentProjects' => $recentProjects,
            'recentMessages' => $recentMessages,
        ]);
    }
}