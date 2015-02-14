<?php

namespace Spray\SerializerBundle\Integration\Serializer;

use Broadway\Serializer\SerializerInterface as BroadwaySerializerInterface;
use Spray\Serializer\SerializerInterface as SpraySerializerInterface;

class BroadwaySerializer implements BroadwaySerializerInterface
{
    /**
     * @var SpraySerializerInterface 
     */
    private $serializer;
    
    /**
     * @param SpraySerializerInterface $serializer
     */
    public function __construct(SpraySerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    
    /**
     * @param array $serializedObject
     * @return object
     */
    public function deserialize(array $serializedObject)
    {
        return $this->serializer->deserialize(
            $serializedObject['class'],
            $serializedObject['payload']
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
            'payload' => $this->serializer->serialize($object)
        );
    }
}
