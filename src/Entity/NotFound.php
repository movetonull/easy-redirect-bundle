<?php

namespace Adeliom\EasyRedirectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[UniqueEntity('source')]
#[ORM\MappedSuperclass]
class NotFound
{
    /**
     * @var mixed
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
    protected $id;

    #[Groups('main')]
    #[ORM\Column(name: 'path', type: \Doctrine\DBAL\Types\Types::STRING, length: 500)]
    protected string $path;

    #[Groups('main')]
    #[ORM\Column(name: 'host', type: \Doctrine\DBAL\Types\Types::STRING, length: 255, options: ["default" => ""])]
    protected ?string $host = "";

    #[Groups('main')]
    #[ORM\Column(name: 'full_url', type: \Doctrine\DBAL\Types\Types::STRING, length: 500)]
    protected string $fullUrl;

    #[Groups('main')]
    #[ORM\Column(name: 'referer', type: \Doctrine\DBAL\Types\Types::STRING, length: 500, nullable: true)]
    protected ?string $referer;

    #[Groups('main')]
    #[ORM\Column(name: 'timestamp', type: \Doctrine\DBAL\Types\Types::DATETIME_MUTABLE)]
    protected ?\DateTimeInterface $timestamp;

    public function __construct(string $path, string $fullUrl, ?string $referer = null, ?\DateTimeInterface $timestamp = null)
    {
        if (null === $timestamp) {
            $timestamp = new \DateTime('now');
        }

        $path = \trim($path);
        $path = empty($path) ? null : $path;
        $host = null;

        if (null !== $path) {
            $path = '/'.\ltrim(\parse_url($path, \PHP_URL_PATH), '/');
        }

        if (null !== $fullUrl) {
            $host = \ltrim(\parse_url($fullUrl, \PHP_URL_HOST), '/');
        }


        $this->path = $path;
        $this->host = $host;
        $this->fullUrl = $fullUrl;
        $this->referer = $referer;
        $this->timestamp = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function getFullUrl(): string
    {
        return $this->fullUrl;
    }

    public function getTimestamp(): \DateTimeInterface
    {
        return $this->timestamp;
    }

    public function getReferer(): ?string
    {
        return $this->referer;
    }
}
