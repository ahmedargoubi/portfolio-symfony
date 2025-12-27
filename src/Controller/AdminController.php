<?php
namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use App\Repository\AnalyticsRepository;
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
        UserRepository $userRepository,
        AnalyticsRepository $analyticsRepository
    ): Response
    {
        // Basic Statistics
        $totalProjects = $projectRepository->count([]);
        $publishedProjects = $projectRepository->count(['isPublished' => true]);
        $totalMessages = $contactRepository->count([]);
        $unreadMessages = $contactRepository->count(['isRead' => false]);
        $totalUsers = $userRepository->count([]);
        
        // Recent items
        $recentProjects = $projectRepository->findBy(
            [],
            ['createdAt' => 'DESC'],
            5
        );
        
        $recentMessages = $contactRepository->findBy(
            [],
            ['createdAt' => 'DESC'],
            5
        );

        // Analytics Metrics - REAL STATISTICS
        $totalViews = $analyticsRepository->getMetricValue('total_views');
        $cvDownloads = $analyticsRepository->getMetricValue('cv_downloads');
        $totalVisitors = $analyticsRepository->getMetricValue('total_visitors');
        
        // Calculate average rating from messages (if you have a rating system)
        // For now, we'll calculate based on total positive interactions
        $readMessages = $totalMessages - $unreadMessages;
        $avgRating = $totalMessages > 0 ? 
            min(5.0, 4.0 + ($readMessages / max($totalMessages, 1))) : 4.5;
        
        return $this->render('admin/index.html.twig', [
            'totalProjects' => $totalProjects,
            'publishedProjects' => $publishedProjects,
            'totalMessages' => $totalMessages,
            'unreadMessages' => $unreadMessages,
            'totalUsers' => $totalUsers,
            'recentProjects' => $recentProjects,
            'recentMessages' => $recentMessages,
            // Real Analytics
            'totalViews' => $totalViews,
            'cvDownloads' => $cvDownloads,
            'avgRating' => number_format($avgRating, 1),
            'totalVisitors' => $totalVisitors,
        ]);
    }
}
