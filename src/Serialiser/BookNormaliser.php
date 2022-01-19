<?php
// api/src/Serializer/MediaObjectNormalizer.php

namespace App\Serialiser;

use App\Entity\Book;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

class BookNormaliser implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'BOOk_OBJECT_NORMALIZER_ALREADY_CALLED';

    public function __construct(string $book_images_base_url, string $book_small_images_base_url)
    {
        $this->baseUrl = $book_images_base_url;
        $this->baseUrlSmall = $book_small_images_base_url;
        
    }
   
    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }
        
        return $data instanceof Book;
    }
    
    public function normalize($object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;

        $object->image = $this->baseUrl.$object->image;
        $object->smallimage = $this->baseUrlSmall.$object->smallimage;

        return $this->normalizer->normalize($object, $format, $context);
    }
}