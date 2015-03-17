<?php

namespace Spray\SerializerBundle\Integration;

use Broadway\Serializer\SerializerInterface as BroadwaySerializerInterface;
use Spray\Serializer\SerializerInterface as SpraySerializerInterface;
use Spray\SerializerBundle\Service\Encryptor;

class BroadwayEncryptingSerializer implements BroadwaySerializerInterface
{
    /**
     * @var SpraySerializerInterface
     */
    private $serializer;

    /**
     * @var Encryptor
     */
    private $encryptor;

    /**
     * @param SpraySerializerInterface $serializer
     */
    public function __construct(SpraySerializerInterface $serializer, Encryptor $encryptor)
    {
        $this->serializer = $serializer;
        $this->encryptor  = $encryptor;
    }

    /**
     * @param array $serializedObject
     *
     * @return object
     */
    public function deserialize(array $serializedObject)
    {
        $decryptedObject = $this->encryptor->decrypt($serializedObject['payload']);

        return $this->serializer->deserialize(
            $serializedObject['class'],
            $decryptedObject
        );
    }

    /**
     * @param object $object
     * @return array
     */
    public function serialize($object)
    {
        return array(
            'class' => get_class($object),
            'payload' => $this->encryptor->encrypt($this->serializer->serialize($object))
        );
    }
}
