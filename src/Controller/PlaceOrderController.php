<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Component\Security\Core\Security;

class PlaceOrderController
{
    
    public function __construct(private Security $security)
    {
        $this->security= $security;

    }
    public function __invoke(Order $data)
    {
        if($data->getCount()>=1 && 
            ($data->getStatus(Order::STATUS_CART) || $data->getStatus(Order::STATUS_WISHLIST)) &&
            $this->security->getUser()->getId() === $data->getUser()->getId()
        ){
            $data->setStatus(Order::STATUS_PLACED)
            ->setUpdatedAt(new \DateTime());
        }
        return $data;

    }

}
