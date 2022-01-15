<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Security;

class ProfileController
{
    
    public function __construct(private Security $security)
    {
        $this->security= $security;

    }
    public function __invoke()
    {
        return $this->security->getUser();
    }

}
