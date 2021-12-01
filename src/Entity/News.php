<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NewsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=NewsRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *      attributes={
 *          "order"={"publishedAt":"DESC"}
 *      },
 *      paginationItemsPerPage=10,
 *      normalizationContext={"groups" = {"read"}},
 *      collectionOperations={"get"},
 *      itemOperations={"get"}
 * )
 */
class News
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $slug;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read"})
     */
    private ?string $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true))
     * @Groups({"read"})
     */
    private ?string $author;

    /**
     * @ORM\Column(type="text", nullable=true))
     * @Groups({"read"})
     */
    private ?string $description;

    /**
     * @ORM\Column(type="text", nullable=true))
     * @Groups({"read"})
     */
    private ?string $content;

    /**
     * @ORM\Column(type="text", nullable=true))
     * @Groups({"read"})
     */
    private ?string $url;

    /**
     * @ORM\Column(type="text", nullable=true))
     * @Groups({"read"})
     */
    private ?string $imageUrl;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true))
     * @Groups({"read"})
     */
    private ?\DateTimeImmutable $publishedAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private ?\DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author = null): self
    {
        $this->author = $author;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description = null): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content = null): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url = null): self
    {
        $this->url = $url;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl = null): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt = null): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
