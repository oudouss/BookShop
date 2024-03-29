<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\UserOwnedInterface;
use App\Repository\UserAddressRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserAddressRepository::class)]
#[ApiResource(
    denormalizationContext:['groups' => ['adress:write']],
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
class UserAddress implements UserOwnedInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:read'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userAddresses')]
    #[ORM\JoinColumn(nullable: true)]
    private $user;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:read', 'user:write', 'adress:write'])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    private $adressline1;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['user:read', 'user:write', 'adress:write'])]
    private $adressline2;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:read', 'user:write', 'adress:write'])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    private $city;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:read', 'user:write', 'adress:write'])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    private $postalcode;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:read', 'user:write', 'adress:write'])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    private $country;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:read', 'user:write', 'adress:write'])]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    private $phone;

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

    public function getAdressline1(): ?string
    {
        return $this->adressline1;
    }

    public function setAdressline1(string $adressline1): self
    {
        $this->adressline1 = $adressline1;

        return $this;
    }

    public function getAdressline2(): ?string
    {
        return $this->adressline2;
    }

    public function setAdressline2(?string $adressline2): self
    {
        $this->adressline2 = $adressline2;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalcode(): ?string
    {
        return $this->postalcode;
    }

    public function setPostalcode(string $postalcode): self
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
    public function __toString()
    {
        return (string) 
        $this->getAdressline1().' - '
        .$this->getAdressline2().' - '
        .$this->getPostalcode().' - '
        .$this->getCity().' - '
        .$this->getCountry();
    }
}
