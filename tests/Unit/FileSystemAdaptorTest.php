<?php

namespace Unit;

use App\Storage\FileSystemAdaptor;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\UnableToWriteFile;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileSystemAdaptorTest extends TestCase
{
    private FilesystemOperator|MockObject $filesystemOp;
    private string $filename;
    private string $content;

    public function setUp(): void
    {
        parent::setUp();

        $this->filesystemOp = $this->createMock(FilesystemOperator::class);
        $this->filename = 'test.txt';
        $this->content = 'This is a test content.';
    }

    public function getInstance(): FileSystemAdaptor
    {
        return new FileSystemAdaptor($this->filesystemOp);
    }

    public function testAddFile(): void
    {
        $instance = $this->getInstance();

        $this->assertTrue($instance->addFile($this->filename, $this->content));
    }

    public function testGetFileContent(): void
    {
        $instance = $this->getInstance();

        // Mock
        $this->filesystemOp->method('read')->willReturn($this->content);

        $actualContent = $instance->getFileContent($this->filename);
        $this->assertEquals($this->content, $actualContent);
    }

    public function testAddFileFailure(): void
    {
        $instance = $this->getInstance();

        // Mock
        $this->filesystemOp->method('write')->willThrowException(new UnableToWriteFile());

        $this->expectException(UnableToWriteFile::class);
        $instance->addFile(10, $this->content);
    }
}
