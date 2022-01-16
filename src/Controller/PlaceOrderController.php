<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PlaceOrderController
{
    
    public function __construct(private Security $security, private EntityManagerInterface $manager)
    {
        $this->security= $security;
        $this->manager= $manager;

    }
    public function __invoke(Order $data)
    {
        
        
        if($data->getCount()>=1 && 
            ($data->getStatus()===Order::STATUS_CART || $data->getStatus()===Order::STATUS_WISHLIST) &&
            $this->security->getUser()->getId() === $data->getUser()->getId()
        ){
            $data->setStatus(Order::STATUS_PLACED)
            ->setUpdatedAt(new \DateTime());
            if ($data->getUser()->getCart()===null) {
                $cart = (new Order())->setStatus(Order::STATUS_CART)->setUser($this->security->getUser());
                $this->manager->persist($cart);
                $this->manager->flush();
            }
            if ($data->getUser()->getWishList()===null) {
                $wichlist = (new Order())->setStatus(Order::STATUS_WISHLIST)->setUser($this->security->getUser());
                $this->manager->persist($wichlist);
                $this->manager->flush();
            }

        }
        return $data;

    }

}
