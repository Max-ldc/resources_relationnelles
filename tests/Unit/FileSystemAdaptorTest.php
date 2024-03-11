<?php

namespace Unit;

use App\Storage\FileSystemAdaptor;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToWriteFile;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class FileSystemAdaptorTest extends TestCase
{
    private FilesystemOperator|MockObject $filesystemOp;
    private LoggerInterface|MockObject $logger;
    private string $filename;
    private string $content;

    public function setUp(): void
    {
        parent::setUp();

        $this->filesystemOp = $this->createMock(FilesystemOperator::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->filename = 'test.txt';
        $this->content = 'This is a test content.';
    }

    public function getInstance(): FileSystemAdaptor
    {
        return new FileSystemAdaptor($this->filesystemOp, $this->logger);
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
        $instance->addFile($this->filename, $this->content);
    }

    public function testGetFileContentFailure(): void
    {
        $instance = $this->getInstance();

        // Mock
        $this->filesystemOp->method('read')->willThrowException(new UnableToReadFile());

        $this->expectException(UnableToReadFile::class);
        $instance->getFileContent('unfoundable_file.pdf');
    }

    public function testDelete(): void
    {
        $instance = $this->getInstance();

        $this->assertTrue($instance->delete($this->filename));
    }

    public function testDeleteFailure(): void
    {
        $instance = $this->getInstance();

        // Mock
        $this->filesystemOp->method('delete')->willThrowException(new UnableToDeleteFile());

        $this->expectException(UnableToDeleteFile::class);
        $instance->delete('unfoundable_file.pdf');
    }
}
