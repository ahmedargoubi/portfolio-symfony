<?php
// src/Repository/AnalyticsRepository.php
namespace App\Repository;

use App\Entity\Analytics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AnalyticsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Analytics::class);
    }

    /**
     * Get or create a metric
     */
    public function getMetric(string $metricName): Analytics
    {
        $metric = $this->findOneBy(['metricName' => $metricName]);
        
        if (!$metric) {
            $metric = new Analytics();
            $metric->setMetricName($metricName);
            $metric->setValue(0);
            $this->getEntityManager()->persist($metric);
            $this->getEntityManager()->flush();
        }
        
        return $metric;
    }

    /**
     * Increment a metric value
     */
    public function incrementMetric(string $metricName, int $amount = 1): void
    {
        $metric = $this->getMetric($metricName);
        $metric->increment($amount);
        $this->getEntityManager()->flush();
    }

    /**
     * Get metric value
     */
    public function getMetricValue(string $metricName): int
    {
        $metric = $this->findOneBy(['metricName' => $metricName]);
        return $metric ? $metric->getValue() : 0;
    }

    /**
     * Set metric value directly
     */
    public function setMetricValue(string $metricName, int $value): void
    {
        $metric = $this->getMetric($metricName);
        $metric->setValue($value);
        $this->getEntityManager()->flush();
    }
}