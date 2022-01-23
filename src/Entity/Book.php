<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\BookRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

/**
 * @Vich\Uploadable
 */
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
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    private $title;

    #[ORM\Column(type: 'text')]
    #[Groups(['book:read'])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Assert\Length(max:250)]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['books:read', 'category:read'])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    private $author;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Groups(['books:read', 'user:read'])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    private $price;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['books:read', 'category:read', 'order:read', 'user:read'])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    public $image;

    /**
     * @Vich\UploadableField(mapping="book_images", fileNameProperty="image")
     * @var File|null
     */
    private $imageFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['books:read', 'category:read', 'order:read', 'user:read'])]
    public $smallimage;

    #[ORM\Column(type: 'integer')]
    #[Groups(['books:read', 'category:read'])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    private $stock;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'books', cascade:['persist'])]
    #[Groups(['books:read'])]
    private $category;

    /**
     * @Gedmo\Timestampable(on="create")
     */
    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     */
    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();
    }

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
    
    public function setImage(string $image = null): self
    {
        $this->image = $image;

        return $this;
    }
    /**
     * @param File|null $image
     * @return void
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
    public function __toString()
    {
        return $this->title;
    }

}
