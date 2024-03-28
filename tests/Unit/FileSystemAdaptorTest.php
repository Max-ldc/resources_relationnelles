<?php

namespace Unit;

use App\Storage\FileSystemAdaptor;
use League\Flysystem\DirectoryListing;
use League\Flysystem\Filesystem;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToListContents;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToWriteFile;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class FileSystemAdaptorTest extends TestCase
{
    private Filesystem|MockObject $filesystemOp;
    private LoggerInterface|MockObject $logger;
    private string $filename;
    private string $content;

    public function setUp(): void
    {
        parent::setUp();

        $this->filesystemOp = $this->createMock(Filesystem::class);
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

        $this->filesystemOp->expects($this->once())
            ->method('write')
            ->with($this->filename, $this->content);
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

        $this->logger->expects($this->once())
            ->method('error');
        $this->expectException(UnableToWriteFile::class);
        $instance->addFile($this->filename, $this->content);
    }

    public function testGetFileContentFailure(): void
    {
        $instance = $this->getInstance();

        // Mock
        $this->filesystemOp->method('read')->willThrowException(new UnableToReadFile());

        $this->logger->expects($this->once())
            ->method('error');
        $this->expectException(UnableToReadFile::class);
        $instance->getFileContent('unfoundable_file.pdf');
    }

    public function testDelete(): void
    {
        $instance = $this->getInstance();

        $this->filesystemOp->expects($this->once())
            ->method('delete')
            ->with($this->filename);
        $this->assertTrue($instance->delete($this->filename));
    }

    public function testDeleteFailure(): void
    {
        $instance = $this->getInstance();

        // Mock
        $this->filesystemOp->method('delete')->willThrowException(new UnableToDeleteFile());

        $this->logger->expects($this->once())
            ->method('error');
        $this->expectException(UnableToDeleteFile::class);
        $instance->delete('unfoundable_file.pdf');
    }

    public function testGetAllFiles(): void
    {
        $instance = $this->getInstance();
        $mockedFiles = [
            ['type' => 'file', 'path' => 'file1.txt'],
            ['type' => 'file', 'path' => 'file2.txt'],
        ];
        $this->filesystemOp->method('listContents')
            ->willReturn(new DirectoryListing($mockedFiles));

        $files = $instance->getAllFiles();
        $this->assertCount(2, $files);
        $this->assertEquals('file1.txt', $files[0]['path']);
        $this->assertEquals('file2.txt', $files[1]['path']);
    }

    public function testGetAllFilesFailure(): void
    {
        $instance = $this->getInstance();
        // Mock
        $this->filesystemOp->method('listContents')
            ->willThrowException(new UnableToListContents());

        $this->logger->expects($this->once())
            ->method('error');

        $this->expectException(UnableToListContents::class);
        $instance->getAllFiles();
    }
}
