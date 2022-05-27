<?php

/**
 * Created by PhpStorm.
 * User: jasser
 * Date: 19/05/22
 * Time: 15:13
 */

namespace App\Resources\config\denormalizer;

use App\Entity\CarsCollection;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Class CarsCollectionDenormalizer
 * @package App\Resources\config\denormalizer
 */
class CarsCollectionDenormalizer implements DenormalizerInterface, CacheableSupportsMethodInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        return CarsCollection::class === $type;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        dump('inisde CarsCollectionDenormalizer');

        return $data;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}