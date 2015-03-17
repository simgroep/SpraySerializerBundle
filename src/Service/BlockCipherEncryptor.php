<?php

namespace Spray\SerializerBundle\Service;

use Zend\Crypt\BlockCipher;

class BlockCipherEncryptor implements Encryptor
{
    /**
     * @var BlockCipher
     */
    private $blockCipher;

    /**
     * @param string $encryptionKey
     */
    public function __construct($encryptionKey)
    {
        $this->blockCipher = BlockCipher::factory('mcrypt', array('algo' => 'aes'));
        $this->blockCipher->setKey($encryptionKey);
    }

    /**
     * {@inheritDoc}
     */
    public function encrypt($data)
    {
        return $this->blockCipher->encrypt(json_encode($data));
    }

    /**
     * {@inheritDoc}
     */
    public function decrypt($data)
    {
        return json_decode($this->blockCipher->decrypt($data), true);
    }
}

