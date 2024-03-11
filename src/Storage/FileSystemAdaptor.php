<?php

namespace App\Storage;

use Exception;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToListContents;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToWriteFile;
use Psr\Log\LoggerInterface;

class FileSystemAdaptor
{
    private FilesystemOperator $filesystem;

    public function __construct(
        private FilesystemOperator $resourcesFilesystem,
        private LoggerInterface $logger
    ) {
        $this->filesystem = $resourcesFilesystem;
    }

    /**
     * Add a file inside the minIO filesystem
     * Returns true if the file is correctly added
     *
     * @param string $filename
     * @param string $content
     * @return boolean
     * @throws Exception
     */
    public function addFile(string $filename, string $content): bool
    {
        try {
            $this->filesystem->write($filename, $content);
            return true;
        } catch (UnableToWriteFile | FilesystemException $exception) {
            $this->logger->error(sprintf('Error %s"', $exception->getMessage()));
            throw $exception;
        }
    }

    /**
     * Retrieve a file inside the minIO file system
     *
     * @param string $filename
     * @return string
     */
    public function getFileContent(string $filename): string
    {
        try {
            return $this->filesystem->read($filename);
        } catch (UnableToReadFile | FilesystemException $exception) {
            $this->logger->error(sprintf('Error %s"', $exception->getMessage()));
            throw $exception;
        }
    }

    public function getAllFiles(): array
    {
        try {
            return $this->filesystem->listContents('')->toArray();
        } catch (UnableToListContents | FilesystemException $exception) {
            $this->logger->error(sprintf('Error %s"', $exception->getMessage()));
            throw $exception;
        }
    }

    public function delete(string $filename): bool
    {
        try {
            $this->filesystem->delete($filename);
            return true;
        } catch (UnableToDeleteFile | FilesystemException $exception) {
            $this->logger->error(sprintf('Error %s"', $exception->getMessage()));
            throw $exception;
        }
    }
}
