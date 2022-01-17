<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    paginationEnabled:false,
    normalizationContext:['groups' => ['books:read']],
    collectionOperations:[
        'get' => [
            'openapi_context' => [
                'security' => []
            ]
        ],
    ],
    itemOperations: [
        'get' => [
            'normalization_context' =>['groups' => ['books:read', 'book:read']],
            'openapi_context' => [
                'security' => []
            ]
        ]

    ]
)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['books:read', 'category:read', 'user:read'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['books:read', 'category:read', 'user:read'])]
    private $title;

    #[ORM\Column(type: 'text')]
    #[Groups(['book:read'])]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['books:read', 'category:read'])]
    private $author;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Groups(['books:read', 'user:read'])]
    private $price;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['books:read'])]
    private $image;

    #[ORM\Column(type: 'integer')]
    #[Groups(['books:read', 'category:read'])]
    private $stock;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'books', cascade:['persist'])]
    #[Groups(['books:read'])]
    private $category;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $smallimage;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

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

    public function getSmallimage(): ?string
    {
        return $this->smallimage;
    }

    public function setSmallimage(?string $smallimage): self
    {
        $this->smallimage = $smallimage;

        return $this;
    }

}
