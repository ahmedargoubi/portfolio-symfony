<?php
namespace App\Entity;

use App\Repository\AnalyticsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnalyticsRepository::class)]
class Analytics
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $metricName = null;

    #[ORM\Column]
    private ?int $value = 0;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->updatedAt = new \DateTimeImmutable();
        $this->value = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMetricName(): ?string
    {
        return $this->metricName;
    }

    public function setMetricName(string $metricName): static
    {
        $this->metricName = $metricName;
        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function increment(int $amount = 1): static
    {
        $this->value += $amount;
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }
}