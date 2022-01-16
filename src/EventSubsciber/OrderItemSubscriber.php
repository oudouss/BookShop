<?php
namespace App\EventSubsciber;

use App\Entity\OrderItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Authorizations\OrderItemAuthorizationChecker;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OrderItemSubscriber implements EventSubscriberInterface
{

    private array $methodnotAllowed=[
        Request::METHOD_PUT,
        Request::METHOD_GET
    ];
    public function __construct(OrderItemAuthorizationChecker $orderItemAuthorizationChecker)
    {
        $this->orderItemAuthorizationChecker = $orderItemAuthorizationChecker;
    }
    public static function getSubscribedEvents()
    {
        return[
            KernelEvents::VIEW => ['checkOrderItem', EventPriorities::PRE_VALIDATE]
        ];
    }
    public function checkOrderItem(ViewEvent $event): void
    {
        $orderItem = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if ($orderItem instanceof OrderItem && !in_array($method, $this->methodnotAllowed, true)
        ) {
            $this->orderItemAuthorizationChecker->check($orderItem, $method);
            $orderItem->getItemOrder()->setUpdatedAt(new \DateTime());
        }

    }
}