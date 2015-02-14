<?php

namespace Spray\SerializerBundle;

use Spray\BundleIntegration\IntegrationTestCase;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use DateTime;

class SerializerIntegrationTest extends IntegrationTestCase
{
    public function registerBundles()
    {
        return array(
            new FrameworkBundle(),
            new SpraySerializerBundle(),
        );
    }
    
    protected function createSerializer()
    {
        return $this->createContainer()->get('spray_serializer');
    }
    
    public function testSerializeTagged()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', '2015-01-01 12:00:00');
        $this->assertSame(
            '2015-01-01 12:00:00',
            $this->createSerializer()->serialize($date)
        );
    }
}
