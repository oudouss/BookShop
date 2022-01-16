<?php
namespace App\Authorizations;

use App\Entity\Order;
use App\Entity\OrderItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class OrderItemAuthorizationChecker
{
    private array $methodAllowed = [
        Request::METHOD_POST,
        Request::METHOD_PATCH,
        Request::METHOD_DELETE
    ];
    private array $statusAllowed = [
        Order::STATUS_CART,
        Order::STATUS_WISHLIST
    ];

    private ?UserInterface $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }

    public function check(OrderItem $orderItem, string $method): void
    {
        $this->isAuthenticated();
        if ($orderItem->getItemOrder()->getUser()->getId() !== $this->user->getId() ||
             !$this->isStatusAllowed($orderItem->getItemOrder()->getStatus()) ||
             ($this->isMethodAllowed($method) &&  $orderItem->getQuantity()<1)
        ) {
            $errorMessage = "Not Authorized";
            throw new UnauthorizedHttpException($errorMessage, $errorMessage);
        }
        
    }

    public function isAuthenticated(): void
    {
        if ( null === $this->user ) {
            $errorMessage = "Not Authenticated";
            throw new UnauthorizedHttpException($errorMessage, $errorMessage);
        }
    }
    
    public function isMethodAllowed(string $method): bool
    {
        return in_array($method, $this->methodAllowed, true);
    }
    public function isStatusAllowed(string $status): bool
    {
        return in_array($status, $this->statusAllowed, true);
    }
}
