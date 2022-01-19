<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\OrderItemRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
#[ApiResource(
    denormalizationContext:['groups' => ['item:write']],
    collectionOperations: [
        'get' =>[
            'controller' => NotFoundAction::class,
            'openapi_context' => ['summary' => 'hidden'],
            'read' => false,
            'output' => false
        ],
        'post' =>[
            'denormalization_context'=>['groups' => ['item:write','items:write']],
            'pagination_enabled' => false,
            'openapi_context' => [
                'security' =>['bearerAuth'=>['is_granted("ROLE_USER")']]
            ]
        ],
    ],
    itemOperations: [
        'get' =>[
            'controller' => NotFoundAction::class,
            'openapi_context' => ['summary' => 'hidden'],
            'read' => false,
            'output' => false
        ],
        'patch' =>[
            'pagination_enabled' => false,
            'openapi_context' => [
                'security' =>['bearerAuth'=>['is_granted("ROLE_USER")']]
            ]
        ],
        'delete' =>[
            'pagination_enabled' => false,
            'openapi_context' => [
                'security' =>['bearerAuth'=>['is_granted("ROLE_USER")']]
            ]
        ],
    ]
)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:read', 'order:read'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: Book::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user:read', 'user:write', 'order:read', 'order:write', 'items:write'])]
    private $book;

    #[ORM\Column(type: 'integer')]
    #[Groups(['user:read', 'user:write', 'order:read', 'order:write', 'item:write'])]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    #[Assert\GreaterThanOrEqual(1)]
    private $quantity;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['items:write'])]
    private $itemOrder;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getItemOrder(): ?Order
    {
        return $this->itemOrder;
    }

    public function setItemOrder(?Order $itemOrder): self
    {
        $this->itemOrder = $itemOrder;

        return $this;
    }
    /**
     * Tests if the given item given corresponds to the same order item.
     *
     * @param OrderItem $item
     *
     * @return bool
     */
    public function equals(OrderItem $item): bool
    {
        return $this->getBook()->getId() === $item->getBook()->getId();
    }

    /**
     * Calculates the item total.
     *
     * @return float|int
     */
    
    #[Groups(['order:read'])]
    public function getTotal(): float
    {
        return $this->getBook()->getPrice() * $this->getQuantity();
    }
    
    public function __toString()
    {
        return $this->book.' - Qty:'.$this->quantity;
    }
}
