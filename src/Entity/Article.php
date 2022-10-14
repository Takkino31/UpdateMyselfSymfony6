<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\ArticleController;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use ApiPlatform\Metadata\ApiResource;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ApiFilter(
    SearchFilter::class,properties: ['id'=>'exact']
)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(
            uriTemplate: '/publish/{id}',
            controller: ArticleController::class,
            denormalizationContext: ['groups'=>'write:publish'],
            name: 'publish'
        ),
        new Get(
            uriTemplate: '/count',
            controller: ArticleController::class,
            name: 'count'
        ),
        ],
    normalizationContext: ['groups'=>'read:collection'],
    paginationItemsPerPage: 2,
)]
#[Get(normalizationContext: ['groups'=>'read:item'])]
#[Put(denormalizationContext: ['groups'=>'edit:item'])]
#[Post(denormalizationContext: ['groups'=>'post:item'])]
#[Delete()]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:collection','read:get'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['post:item','edit:item'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 5, minMessage: 'Le slug must be more than 5 Char')]
    #[Groups(['post:item','read:item'])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['post:item','read:item','edit:item'])]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['post:item','read:item'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['post:item','read:item'])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[Groups(['post:item','edit:item','read:collection'])]
    private ?Category $category = null;

    #[ORM\Column(type: Types::BOOLEAN ,options: ['default'=>0])]
    #[Groups(['write:publish'])]
    private ?bool $online = false;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function isOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): self
    {
        $this->online = $online;

        return $this;
    }
}
