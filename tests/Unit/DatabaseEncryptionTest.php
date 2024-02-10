<?php

namespace Unit;

use App\Security\DatabaseEncryption;
use PHPUnit\Framework\TestCase;

class DatabaseEncryptionTest extends TestCase
{
    private DatabaseEncryption $databaseEncryption;

    protected function setUp(): void
    {
        $this->databaseEncryption = new DatabaseEncryption('dc060a2974cd31f104f63f08743d3fbd15cab49aefdf8ec58663463da576c8fe');
    }

    public function testEncryptDecrypt(): void
    {
        $stringToEncrypt = 'string_to_encrypt';

        $stringEncrypted = $this->databaseEncryption->encrypt($stringToEncrypt);
        $stringDecrypted = $this->databaseEncryption->decrypt($stringEncrypted);

        $this->assertSame($stringToEncrypt, $stringDecrypted);
    }
}
