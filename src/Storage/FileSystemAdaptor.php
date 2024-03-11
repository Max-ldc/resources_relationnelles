<?php

namespace App\Storage;

use Exception;
use League\Flysystem\FilesystemOperator;

class FileSystemAdaptor
{
    private FilesystemOperator $filesystem;

    public function __construct(private FilesystemOperator $resourcesFilesystem)
    {
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
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Retrieve a file inside the minIO file system
     *
     * @param string $filename
     * @return void
     */
    public function getFileContent(string $filename)
    {
        try {
            return $this->filesystem->read($filename);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAllFiles(): array
    {
        try {
            return $this->filesystem->listContents('')->toArray();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(string $filename): bool
    {
        try {
            $this->filesystem->delete($filename);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
