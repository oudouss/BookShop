<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\Category;
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
        $booksArray=[
            [
              "title"=> "Magical messages from the fairies",
              "price"=> 30,
              "stock"=> 1,
              "image"=> "/img/product/product-1.jpg",
              "smallimage"=> "/img/cart/cart-1.jpg",
            ],
            [
              "title"=> "Healing with the Angels",
              "price"=> 15,
              "stock"=> 1,
              "image"=> "/img/product/product-5.jpg",
              "smallimage"=> "/img/cart/cart-5.jpg",
            ],
            [
              "title"=> "Healing with the Angels II",
              "price"=> 25,
              "stock"=> 1,
              "image"=> "/img/product/product-2.jpg",
              "smallimage"=> "/img/cart/cart-2.jpg",
            ],
            [
              "title"=> "Healing with the Angels III",
              "price"=> 12,
              "stock"=> 2,
              "image"=> "/img/product/product-3.jpg",
              "smallimage"=> "/img/cart/cart-3.jpg",
            ],
            [
              "title"=> "Oracle of wisdom",
              "price"=> 17,
              "stock"=> 5,
              "image"=> "/img/product/product-4.jpg",
              "smallimage"=> "/img/cart/cart-4.jpg",
            ],
            [
              "title"=> "The Light Seer's Tarot",
              "price"=> 20,
              "stock"=> 30,
              "image"=> "/img/product/product-6.jpg",
              "smallimage"=> "/img/cart/cart-6.jpg",
            ],
            [
              "title"=> "Watermelon 1kg",
              "price"=> 10,
              "stock"=> 25,
              "image"=> "/img/product/product-7.jpg",
              "smallimage"=> "/img/cart/cart-7.jpg",
            ],
            [
              "title"=> "Ethereal Visions",
              "price"=> 8,
              "stock"=> 10,
              "image"=> "/img/product/product-8.jpg",
              "smallimage"=> "/img/cart/cart-8.jpg",
            ],
            [
              "title"=> "The Fountain Tarot",
              "price"=> 25,
              "stock"=> 14,
              "image"=> "/img/product/product-9.jpg",
              "smallimage"=> "/img/cart/cart-9.jpg",
            ],
            [
              "title"=> "Moonology",
              "price"=> 20,
              "stock"=> 5,
              "image"=> "/img/product/product-10.jpg",
              "smallimage"=> "/img/cart/cart-10.jpg",
            ],
            [
              "title"=> "Seventy-Eight Degrees of Wisdom",
              "price"=> 2.5,
              "stock"=> 27,
              "image"=> "/img/product/product-11.jpg",
              "smallimage"=> "/img/cart/cart-11.jpg",
            ],
            [
              "title"=> "Simplicity Tarot",
              "price"=> 15,
              "stock"=> 35,
              "image"=> "/img/product/product-12.jpg",
              "smallimage"=> "/img/cart/cart-11.jpg",
            ],
        ];
        $faker = Factory::create();
        $id=0;
        foreach($booksArray as $bookItem) {
            $id++;
            $category = new Category();
            $category->setName("Category #".$id);
            $manager->persist($category);
            $book = new Book();
            $book
            ->setTitle($bookItem["title"])
            ->setDescription($faker->text(150))
            ->setAuthor($faker->name())
            ->setPrice($bookItem["price"])
            ->setCategory($category)
            ->setImage($bookItem["image"])
            ->setSmallimage($bookItem["smallimage"])
            ->setStock($bookItem["stock"]);
            
            $manager->persist($book);
            $books[]=$book;
        }

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
