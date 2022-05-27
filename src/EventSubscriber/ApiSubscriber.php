<?php

/**
 * Created by PhpStorm.
 * User: jasser
 * Date: 27/05/22
 * Time: 15:52
 */

namespace App\EventSubscriber;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class ApiSubscriber.
 */
class ApiSubscriber implements EventSubscriberInterface
{
    /**
     * @var NormalizerInterface[]
     */
    private $normalizers;

    /**
     * @var DenormalizerInterface[]
     */
    private $denormalizers;

    /**
     * @var array
     */
    private $normalizerCache;

    /**
     * @var array
     */
    private $denormalizerCache;

    /**
     * ApiSubscriber constructor.
     *
     * @param NormalizerInterface[] $normalizers
     */
    public function __construct(iterable $normalizers)
    {
        $this->normalizers = [];
        $this->denormalizers = [];
        $this->normalizerCache = [];
        $this->denormalizerCache = [];

        foreach ($normalizers as $normalizer) {
            if ($normalizer instanceof NormalizerInterface) {
                $this->normalizers[] = $normalizer;
            }

            if ($normalizer instanceof DenormalizerInterface) {
                $this->denormalizers[] = $normalizer;
            }
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ['event' => 'serializer.pre_deserialize', 'method' => 'preDeserialize'],
        ];
    }

    /**
     * @param PreDeserializeEvent $event
     */
    public function preDeserialize(PreDeserializeEvent $event)
    {
        $data = $event->getData();
        $type = $event->getType()['name'];
        $format = $event->getContext()->getFormat();
        $context = $event->getContext()->hasAttribute('context') ? $event->getContext()->getAttribute('context') : [];

        if ($denormalizer = $this->getDenormalizer($data, $type, $format)) {
            $data = $denormalizer->denormalize($data, $type, $format, $context);
        }

        $event->setData($data);
    }

    /**
     * @param mixed       $data
     * @param string      $class
     * @param null|string $format
     *
     * @return null|DenormalizerInterface
     */
    private function getDenormalizer($data, string $class, ?string $format): ?DenormalizerInterface
    {
        if ('DateTime' === $class) {
            return null;
        }

        if (!isset($this->denormalizerCache[$format][$class])) {
            $this->denormalizerCache[$format][$class] = [];
            foreach ($this->denormalizers as $k => $denormalizer) {
                if (!$denormalizer instanceof CacheableSupportsMethodInterface || !$denormalizer->hasCacheableSupportsMethod()) {
                    $this->denormalizerCache[$format][$class][$k] = false;
                } elseif ($denormalizer->supportsDenormalization(null, $class, $format)) {
                    $this->denormalizerCache[$format][$class][$k] = true;
                    break;
                }
            }
        }

        foreach ($this->denormalizerCache[$format][$class] as $k => $cached) {
            $denormalizer = $this->denormalizers[$k];
            if ($cached || $denormalizer->supportsDenormalization($data, $class, $format)) {
                return $denormalizer;
            }
        }

        return null;
    }
}