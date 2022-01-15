<?php
namespace App\Serialiser;

use ReflectionClass;
use App\Entity\UserOwnedInterface;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class UserOwnedDenormaliser implements ContextAwareDenormalizerInterface, DenormalizerAwareInterface
{
 use DenormalizerAwareTrait;
 private const ALREADY_CALLED_DENORMALIZER = 'UserOwnedDenormaliserCalled';
 private Security $security;
 public function __construct(Security $security)
 {
    $this->security= $security;
 }
 public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
 {
    $reflectionClass = new \ReflectionClass($type);
    $alreadyCalled = $data[self::ALREADY_CALLED_DENORMALIZER] ?? false;
    return ($reflectionClass->implementsInterface(UserOwnedInterface::class) && $alreadyCalled === false && $this->security->getUser() !== null);
 }

 public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
 {
    $data[self::ALREADY_CALLED_DENORMALIZER] = true;
    $object = $this->denormalizer->denormalize($data, $type, $format, $context);
    $object->setUser($this->security->getUser());
    return $object;
 }

}