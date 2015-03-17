<?php

namespace Spray\SerializerBundle\Service;

interface Encryptor
{
    /**
     * encrypt
     *
     * @param string $data
     *
     * @return string
     */
    public function encrypt($data);

    /**
     * decrypt
     *
     * @param string $data
     *
     * @return string
     */
    public function decrypt($data);
}
