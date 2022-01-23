<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Order;
use App\Entity\Category;
use App\Entity\OrderItem;
use App\Entity\UserAddress;
use App\Entity\UserPayment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $encoder;
    private Filesystem $filesystem;
    
    public function __construct(UserPasswordHasherInterface $encoder, Filesystem $filesystem)
    {
        $this->encoder= $encoder;
        $this->filesystem= $filesystem;

    }
    public function load(ObjectManager $manager): void
    {
      $this->filesystem->remove(['public/img/cart']);
      $this->filesystem->mkdir('public/img/cart');

      $this->filesystem->remove(['public/img/product']);
      $this->filesystem->mkdir('public/img/product');

      $this->filesystem->mirror('dummy/cart', 'public/img/cart');
      $this->filesystem->mirror('dummy/product', 'public/img/product');

      $books=[];
      $booksArray=[
        [
          "title"=> "CROSSROADS",
          "author"=> "JONATHAN FRANZEN",
          "price"=> 300,
          "stock"=> 10,
          "image"=> "product-1.jpg",
          "smallimage"=> "cart-1.jpg",
        ],
        [
          "title"=> "THE WITCHER BLOOD OF ELVES",
          "author"=> "ANDRZEJ SAPKOWSKI",
          "price"=> 150,
          "stock"=> 10,
          "image"=> "product-5.jpg",
          "smallimage"=> "cart-5.jpg",
        ],
        [
          "title"=> "THE MAN WHO DIED TWICE",
          "author"=> "RICHARD OSMAN",
          "price"=> 250,
          "stock"=> 10,
          "image"=> "product-2.jpg",
          "smallimage"=> "cart-2.jpg",
        ],
        [
          "title"=> "OH WILLIAM",
          "author"=> "ELIZABETH STROUT",
          "price"=> 120,
          "stock"=> 20,
          "image"=> "product-3.jpg",
          "smallimage"=> "cart-3.jpg",
        ],
        [
          "title"=> "THE CHRISTMAS PIG",
          "author"=> "J.K. ROWLING",
          "price"=> 170,
          "stock"=> 50,
          "image"=> "product-4.jpg",
          "smallimage"=> "cart-4.jpg",
        ],
        [
          "title"=> "THE BOY, THE MOLE, THE FOX AND THE HORSE",
          "author"=> "CHARLIE MACKESY",
          "price"=> 200,
          "stock"=> 30,
          "image"=> "product-6.jpg",
          "smallimage"=> "cart-6.jpg",
        ],
        [
          "title"=> "THE POWER OF GEOGRAPHY",
          "author"=> "TIM MARSHALl",
          "price"=> 100,
          "stock"=> 25,
          "image"=> "product-7.jpg",
          "smallimage"=> "cart-7.jpg",
        ],
        [
          "title"=> "DOPESICK",
          "author"=> "BETH MACY",
          "price"=> 89.67,
          "stock"=> 20,
          "image"=> "product-8.jpg",
          "smallimage"=> "cart-8.jpg",
        ],
        [
          "title"=> "LOS ÁNGELES DEL AMOR",
          "author"=> "DOREEN VIRTUE",
          "price"=> 250,
          "stock"=> 14,
          "image"=> "product-9.jpg",
          "smallimage"=> "cart-9.jpg",
        ],
        [
          "title"=> "LAS CARTAS DEL ORÁCULO DEL DIOSAS",
          "author"=> "DRA. DOREEN VIRTUE",
          "price"=> 189.56,
          "stock"=> 50,
          "image"=> "product-10.jpg",
          "smallimage"=> "cart-10.jpg",
        ],
        [
          "title"=> "ANTES DE DICIEMBRE",
          "author"=> "JOANA MARCUS",
          "price"=> 200,
          "stock"=> 27,
          "image"=> "product-11.jpg",
          "smallimage"=> "cart-11.jpg",
        ],
        [
          "title"=> "ATAQUE DES TITANS",
          "author"=> "PIKA SEINEN",
          "price"=> 185.89,
          "stock"=> 35,
          "image"=> "product-12.jpg",
          "smallimage"=> "cart-11.jpg",
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
        ->setAuthor($bookItem["author"])
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

      
      $useradmin = new Admin();
      $passAdminHash = $this->encoder->hashPassword($useradmin,"password");
      $useradmin
      ->setName("SysAdmin")
      ->setEmail("admin@app.com")
      ->setPassword($passAdminHash);
      
      $manager->persist($useradmin);
      $manager->flush();
    }
}
