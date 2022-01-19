<?php

namespace App\EventSubscriber;

use App\Entity\Book;
use Intervention\Image\ImageManagerStatic as Image;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class BookSmallImageSubscriber implements EventSubscriberInterface
{
    public function __construct(string $app_path_book_images, string $app_path_book_small_images)
    {
        $this->imagesPath = $app_path_book_images;
        $this->smallImagesPath = $app_path_book_small_images;
        
    }
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => 'createThumbNail',
            BeforeEntityUpdatedEvent::class => 'updateThumbNail',
            BeforeEntityDeletedEvent::class => 'deleteThumbNail',
        ];
    }
    public function createThumbNail(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Book)) {
            return;
        }
        if($entity->getImageFile()){
            $this->setSmall( 
             $entity,
             $entity->getImageFile()->getRealPath(),
            );
        }
        
    }
    public function updateThumbNail(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Book)) {
            return;
        }
        if($entity->getImageFile()){
            $this->unsetImages($entity);
            $this->setSmall(
             $entity,
             $entity->getImageFile()->getRealPath(),
            );
        }
        
    }
    public function deleteThumbNail(BeforeEntityDeletedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Book)) {
            return;
        }
        $this->unsetImages($entity);
    }
    private function setSmall($entity, $fileName)
    {
        Image::configure(['driver' => 'gd']);
        Image::make($fileName)
        ->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })
        ->save($this->smallImagesPath.str_replace(' ','_',time().uniqid().'.'.$entity->getImageFile()->getClientOriginalExtension()));
        $entity->setSmallimage(str_replace(' ','_',time().uniqid().'.'.$entity->getImageFile()->getClientOriginalExtension()));

    }
    private function unsetImages($entity)
    {
        if ($entity->getImage() && file_exists($this->imagesPath.$entity->getImage())) {
            unlink($this->imagesPath.$entity->getImage());
        }
        if ($entity->getSmallimage() && file_exists($this->smallImagesPath.$entity->getSmallimage())) {
            unlink($this->smallImagesPath.$entity->getSmallimage());
        }

    }
}
