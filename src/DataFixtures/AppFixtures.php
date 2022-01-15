<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\UserAddress;
use App\Entity\UserPayment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $encoder;
    
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder= $encoder;

    }
    public function load(ObjectManager $manager): void
    {
        $books=[];
        for ($i = 1; $i <= 50; $i++) {
            $book = new Book();
            $faker = Factory::create();
            $book
            ->setTitle("Book #".$i)
            ->setDescription($faker->text(250))
            ->setAuthor($faker->name())
            ->setPrice(mt_rand(10, 600))
            ->setImage("https://dummyimage.com/450x300/dee2e6/6c757d.jpg")
            ->setStock(mt_rand(10, 600));
            
            $books[]=$book;
            $manager->persist($book);
        }
        $manager->flush();

        $user = new User();
        $passHash = $this->encoder->hashPassword($user,"password");

        $user
        ->setFirstName($faker->firstName())
        ->setLastName($faker->lastName())
        ->setPassword($passHash)
        ->setEmail("user@app.com");
        
        $manager->persist($user);
        
        $order= new Order();
        $order
        ->setUser($user)
        ->setStatus(Order::STATUS_CART)
        ->setUpdatedAt(new \DateTime());
        $manager->persist($order);
        
        for ($i=1; $i < 10 ; $i++) { 
            $orderitem= new OrderItem();
            $orderitem
            ->setBook($books[$i])
            ->setItemOrder($order)
            ->setQuantity(mt_rand(1, 10));
            $manager->persist($orderitem);
        }
                
        $useradmin = new User();
        $passAdminHash = $this->encoder->hashPassword($useradmin,"password");
        $useradmin
        ->setFirstName("Sys")
        ->setLastName("Admin")
        ->setEmail("admin@app.com")
        ->setRoles(["ROLE_ADMIN"])
        ->setPassword($passAdminHash);

        $manager->persist($useradmin);
        $adress = new UserAddress();
        $adress->setUser($user)
        ->setAdressline1($faker->streetAddress())
        ->setAdressline2($faker->streetAddress())
        ->setCity($faker->city())
        ->setPostalcode($faker->postcode())
        ->setCountry($faker->country())
        ->setPhone("0772032448");
        $manager->persist($adress);

        $payment = new UserPayment();
        $payment->setUser($user)
        ->setType('MasterCard')
        ->setProvider('BANK OF AFRICA')
        ->setAccount('5555555555554444')
        ->setExpiry('09/28');
        $manager->persist($payment);

        $manager->flush();
    }
}
