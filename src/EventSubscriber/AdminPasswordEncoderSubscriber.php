<?php

namespace App\EventSubscriber;

use App\Entity\Admin;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminPasswordEncoderSubscriber implements EventSubscriberInterface
{
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder= $encoder;

    }
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => 'encodePassword',
        ];
    }
    public function encodePassword(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Admin)) {
            return;
        }
        $entity->setPassword($this->encoder->hashPassword($entity, $entity->getPassword()));

        
    }

}
