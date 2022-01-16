<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\UserOwnedInterface;
use App\Repository\UserPaymentRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserPaymentRepository::class)]
#[ApiResource(
    denormalizationContext:['groups' => ['payment:write']],
    collectionOperations: [
        'get' =>[
            'pagination_enabled' => false,
            'openapi_context' => [
                'security' =>['bearerAuth'=>['is_granted("ROLE_USER")']]
            ]
        ],
        'post' =>[
            'pagination_enabled' => false,
            'openapi_context' => [
                'security' =>['bearerAuth'=>['is_granted("ROLE_USER")']]
            ]
        ],
    ],
    itemOperations: [
        'get' =>[
            'pagination_enabled' => false,
            'openapi_context' => [
                'security' =>['bearerAuth'=>['is_granted("ROLE_USER")']]
            ]
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
class UserPayment implements UserOwnedInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:read'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userPayments')]
    #[ORM\JoinColumn(nullable: true)]
    private $user;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:read', 'user:write', 'payment:write'])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    private $type;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:read', 'user:write', 'payment:write'])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    private $provider;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:read', 'user:write', 'payment:write'])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    private $account;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:read', 'user:write', 'payment:write'])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    private $expiry;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(string $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getAccount(): ?string
    {
        return $this->account;
    }

    public function setAccount(string $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getExpiry(): ?string
    {
        return $this->expiry;
    }

    public function setExpiry(string $expiry): self
    {
        $this->expiry = $expiry;

        return $this;
    }
}
