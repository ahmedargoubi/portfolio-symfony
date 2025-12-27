<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SecurityHeadersSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $response = $event->getResponse();

        // 🔒 Content Security Policy
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
               "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; " .
               "img-src 'self' data: https:; " .
               "font-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.gstatic.com; " .
               "connect-src 'self'; " .
               "frame-ancestors 'none'; " .
               "base-uri 'self'; " .
               "form-action 'self'";

        $response->headers->set('Content-Security-Policy', $csp);
        
        // 🔒 Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // 🔒 X-Frame-Options (protection clickjacking)
        $response->headers->set('X-Frame-Options', 'DENY');
        
        // 🔒 X-Content-Type-Options
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // 🔒 X-XSS-Protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // 🔒 Permissions-Policy
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        // 🔒 Strict-Transport-Security (HTTPS uniquement)
        if ($event->getRequest()->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }
    }
}