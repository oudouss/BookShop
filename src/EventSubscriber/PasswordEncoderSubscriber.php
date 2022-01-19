<?php
namespace App\EventSubscriber;


use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordEncoderSubscriber implements EventSubscriberInterface
{
    private UserPasswordHasherInterface $encoder;
    
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder= $encoder;

    }
    public static function getSubscribedEvents()
    {
        return[
            KernelEvents::VIEW => ['encodePassword', EventPriorities::PRE_WRITE]
        ];
    }
    public function encodePassword(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }
        $user->setPassword($this->encoder->hashPassword($user, $user->getPassword()));
    }
}