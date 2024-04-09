<?php

namespace App\Storage;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToListContents;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToWriteFile;
use Psr\Log\LoggerInterface;

class FileSystemAdaptor
{
    private Filesystem $filesystem;
    private LoggerInterface $logger;

    public function __construct(Filesystem $resourceFilesystem, LoggerInterface $logger)
    {
        $this->filesystem = $resourceFilesystem;
        $this->logger = $logger;
    }

    /**
     * Add a file inside the minIO filesystem
     * Returns true if the file is correctly added.
     *
     * @throws \Exception
     */

    public function checkFile(string $filename): bool
    {
        try {
            $this->filesystem->fileExists($filename);

            return true;
        } catch (UnableToWriteFile|FilesystemException $exception) {
            $this->logger->error(sprintf('Error %s"', $exception->getMessage()));
            throw $exception;
        }
    }

    public function addFile(string $filename, string $content): bool
    {
        try {
            $this->filesystem->write($filename, $content);

            return true;
        } catch (UnableToWriteFile|FilesystemException $exception) {
            $this->logger->error(sprintf('Error %s"', $exception->getMessage()));
            throw $exception;
        }
    }

    /**
     * Retrieve a file inside the minIO file system.
     */
    public function getFileContent(string $filename): string
    {
        try {
            return $this->filesystem->read($filename);
        } catch (UnableToReadFile|FilesystemException $exception) {
            $this->logger->error(sprintf('Error %s"', $exception->getMessage()));
            throw $exception;
        }
    }

    public function streamFileContent(string $filename): mixed
    {
        try {
            return $this->filesystem->readStream($filename);
        } catch (UnableToReadFile|FilesystemException $exception) {
            $this->logger->error(sprintf('Error %s"', $exception->getMessage()));
            throw $exception;
        }
    }

    public function getAllFiles(): array
    {
        try {
            return $this->filesystem->listContents('')->toArray();
        } catch (UnableToListContents|FilesystemException $exception) {
            $this->logger->error(sprintf('Error %s"', $exception->getMessage()));
            throw $exception;
        }
    }

    public function delete(string $filename): bool
    {
        try {
            $this->filesystem->delete($filename);

            return true;
        } catch (UnableToDeleteFile|FilesystemException $exception) {
            $this->logger->error(sprintf('Error %s"', $exception->getMessage()));
            throw $exception;
        }
    }
}
