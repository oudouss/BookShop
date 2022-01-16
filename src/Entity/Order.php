<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\UserOwnedInterface;
use App\Repository\OrderRepository;
use App\Controller\PlaceOrderController;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[ApiResource(
    paginationEnabled: false,
    normalizationContext:['groups' => ['order:read']],
    denormalizationContext:['groups' => ['order:write']],
    collectionOperations: [
        'get' =>[
            'pagination_enabled' => false,
            'openapi_context' => [
                'security' =>['bearerAuth'=>['is_granted("ROLE_USER")']]
            ]
        ],
        'post' => [
            'pagination_enabled' => false,
            'openapi_context' => [
                'security' =>['bearerAuth'=>['is_granted("ROLE_USER")']]
            ]
        ]
    ],
    itemOperations: [
        'get' =>[
            'pagination_enabled' => false,
            'openapi_context' => [
                'security' =>['bearerAuth'=>['is_granted("ROLE_USER")']]
            ]
        ],
        'place' => [
            'method' => 'POST',
            'pagination_enabled' => false,
            'path' => '/orders/{id}/place',
            'controller' => PlaceOrderController::class,
            'openapi_context' => [
                'summary' => 'Place An Order',
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema' => [],
                            'example' =>'{}'
                        ]
                    ]
                ],
                'security' =>[
                    'bearerAuth'=>['is_granted("ROLE_USER")']
                ]
            ]
        ]
    ]
)]
class Order implements UserOwnedInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:read', 'order:read'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    private $user;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:read', 'order:read'])]
    private $status;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\OneToMany(mappedBy: 'itemOrder', targetEntity: OrderItem::class, cascade:['persist'], orphanRemoval: true)]    
    #[Groups(['user:read', 'user:write', 'order:read', 'order:write'])]
    #[Assert\Valid()]
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->status = $this::STATUS_CART;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): self
    {
        // The item doesnt exist, add it
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setItemOrder($this);

        // The item already exists, update the quantity
        }else {
            foreach ($this->items as $existingItem) {
                if ($existingItem->equals($item)) {
                    $existingItem->setQuantity(
                        $existingItem->getQuantity() + $item->getQuantity()
                    );
                }
            }
        }

        return $this;
    }

    public function removeItem(OrderItem $item): self
    {
        if ($this->items->removeElement($item) &&
         $item->getItemOrder() === $this
        ) {
            $item->setItemOrder(null);
        }

        return $this;
    }

    /**
     * Calculates the order total.
     *
     * @return float
     */
    #[Groups(['user:read', 'order:read'])]
    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->items as $item) {
            $total += $item->getTotal();
        }

        return $total;
    }
    /**
     * Calculates the item Count of the order.
     *
     * @return int
     */
    #[Groups(['user:read', 'order:read'])]
    public function getCount(): int
    {
        $count = 0;

        foreach ($this->items as $item) {
            $count = $count + ( ($item->getQuantity()==1) ? 1 : $item->getQuantity() );
        }

        return $count;
    }
    /**
     * An order that is in progress, not placed yet.
     *
     * @var string
     */
    const STATUS_CART = 'Cart';
    /**
     * An order that is in progress, not placed yet.
     *
     * @var string
     */
    const STATUS_WISHLIST = 'Wish List';

    /**
     * An order that is placed.
     *
     * @var string
     */
    const STATUS_PLACED = 'Placed';

    /**
     * An order that is In Progress.
     *
     * @var string
     */
    const STATUS_INPROGRESS = 'In Progress';

    /**
     * An order that is Delivered.
     *
     * @var string
     */
    const STATUS_DELIVERED = 'Delivered';

}
