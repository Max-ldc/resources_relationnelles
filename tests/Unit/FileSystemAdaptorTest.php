<?php

namespace Unit;

use App\Service\FileSystemAdaptor;
use League\Flysystem\Filesystem;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileSystemAdaptorTest extends TestCase
{
    private Filesystem|MockObject $filesystem;

    public function setUp(): void
    {
        parent::setUp();

        $this->filesystem = $this->createMock(Filesystem::class);
    }

    public function getInstance(): FileSystemAdaptor
    {
        return new FileSystemAdaptor($this->filesystem);
    }

    public function testAddFile(): void
    {
        $instance = $this->getInstance();
        $filename = 'test.txt';
        $content = 'This is a test content.';

        $instance->addFile($filename, $content);

        // Assert that the file exists in the filesystem
        $this->assertTrue($instance->getFile($filename));
    }

    public function testGetFile(): void
    {
        $instance = $this->getInstance();
        $filename = 'test.txt';
        $content = 'This is another test content.';

        // Add a file for testing
        $instance->addFile($filename, $content);

        // Get the file content and assert it matches the expected content
        $actualContent = $instance->getFile($filename);
        $this->assertEquals($content, $actualContent);
    }
}
