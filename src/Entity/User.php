<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Action\NotFoundAction;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\ProfileController;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    normalizationContext:['groups' => ['user:read']],
    denormalizationContext:['groups' => ['user:write']],
    collectionOperations: [
        'get' =>[
            'controller' => NotFoundAction::class,
            'openapi_context' => ['summary' => 'hidden'],
            'read' => false,
            'output' => false
        ],
        'post' => [
            'pagination_enabled' => false,
            'path' => '/register',
            'method' => 'post',
            'read' => false,
            'openapi_context' => [
                'security' =>['bearerAuth'=>[]]
            ]
        ]
    ],
    itemOperations: [
        'get' =>[
            'controller' => NotFoundAction::class,
            'openapi_context' => ['summary' => 'hidden'],
            'read' => false,
            'output' => false
        ],
        'profile' => [
            'pagination_enabled' => false,
            'path' => '/profile',
            'method' => 'get',
            'controller' => ProfileController::class,
            'read' => false,
            'openapi_context' => [
                'security' =>[
                    'bearerAuth'=>['is_granted("ROLE_USER")']
                ]
            ]
        ]
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:read','user:write'])]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:read','user:write'])]
    private $lastname;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['user:read','user:write'])]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    #[Groups(['user:write'])]
    private $password;
    
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserAddress::class, cascade:['persist'], orphanRemoval: true)]
    #[Groups(['user:read','user:write'])]
    private $userAddresses;
    
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserPayment::class, cascade:['persist'], orphanRemoval: true)]
    #[Groups(['user:read','user:write'])]
    private $userPayments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Order::class, cascade:['persist'])]
    #[Groups(['user:read','user:write'])]
    private $orders;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->userAddresses = new ArrayCollection();
        $this->userPayments = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }
    public function getUsername(): string
    {
        return (string) $this->email;
    }
    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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
     * @return Collection|UserAddress[]
     */
    public function getUserAddresses(): Collection
    {
        return $this->userAddresses;
    }

    public function addUserAddress(UserAddress $userAddress): self
    {
        if (!$this->userAddresses->contains($userAddress)) {
            $this->userAddresses[] = $userAddress;
            $userAddress->setUser($this);
        }

        return $this;
    }

    public function removeUserAddress(UserAddress $userAddress): self
    {
        if ($this->userAddresses->removeElement($userAddress)) {
            // set the owning side to null (unless already changed)
            if ($userAddress->getUser() === $this) {
                $userAddress->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserPayment[]
     */
    public function getUserPayments(): Collection
    {
        return $this->userPayments;
    }

    public function addUserPayment(UserPayment $userPayment): self
    {
        if (!$this->userPayments->contains($userPayment)) {
            $this->userPayments[] = $userPayment;
            $userPayment->setUser($this);
        }

        return $this;
    }

    public function removeUserPayment(UserPayment $userPayment): self
    {
        if ($this->userPayments->removeElement($userPayment)) {
            // set the owning side to null (unless already changed)
            if ($userPayment->getUser() === $this) {
                $userPayment->setUser(null);
            }
        }

        return $this;
    }
    /**
     *
     * @return Order|null
     * @Groups({ "user:read" })
     */
    public function getCart(): ?Order
    {
        foreach ($this->orders as $order) {
            if ($order->getStatus() == Order::STATUS_CART){
                return $order;
            }
        }
        return null;
    }

    /**
     *
     * @return Order|null
     * @Groups({ "user:read" })
     */
    public function getWishList(): ?Order
    {
        foreach ($this->orders as $order) {
            if ($order->getStatus() == Order::STATUS_WISHLIST){
                return $order;
            }
        }
        return null;
    }
}
