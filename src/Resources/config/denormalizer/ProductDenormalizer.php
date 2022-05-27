<?php

/**
 * Created by PhpStorm.
 * User: jasser
 * Date: 19/05/22
 * Time: 15:13
 */

namespace App\Resources\config\denormalizer;

use App\Entity\Product;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Class ProductDenormalizer
 * @package App\Resources\config\denormalizer
 */
class ProductDenormalizer implements DenormalizerInterface, CacheableSupportsMethodInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        return Product::class === $type;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        dump('inside ProductDenormalizer');

        $arr = [
            'carsCollection' => [
                'items' =>
                    [
                        [
                            'name' => 'Ferrari',
                            'color' => 'Red',
                        ],

                        [
                            'name' => 'fiat',
                            'color' => 'white',
                        ],
                    ]
            ],
            'person' => [
                'name' => 'P1'
            ],
            'code' => 'bar code',
            'nametoto' => 'toto',
            'price' => 10.336,
        ];

        return $arr;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}