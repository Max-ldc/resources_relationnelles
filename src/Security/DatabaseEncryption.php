<?php

namespace App\Security;

use phpseclib3\Crypt\AES;

class DatabaseEncryption
{
    private AES $aes;

    public function __construct(string $pseudonymizeKey)
    {
        $this->aes = new AES('cbc');
        $key = hex2bin($pseudonymizeKey);
        $this->aes->setKey($key);
    }

    /**
     * @throws \Exception
     */
    public function encrypt(string $data): string
    {
        $iv = random_bytes($this->aes->getBlockLength() >> 3);
        $this->aes->setIV($iv);
        $encryptedData = $this->aes->encrypt($data);

        return base64_encode($iv.$encryptedData);
    }

    public function decrypt(string $data): string
    {
        $combined = base64_decode($data);
        $ivLength = $this->aes->getBlockLength() >> 3;
        $iv = substr($combined, 0, $ivLength);
        $encryptedData = substr($combined, $ivLength);
        $this->aes->setIV($iv);

        return $this->aes->decrypt($encryptedData);
    }
}
