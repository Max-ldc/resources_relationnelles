<?php

namespace App\Service;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemOperator;

require 'vendor/autoload.php';

class FileSystemAdaptor
{
    private FileSystem $filesystem;

    public function __construct(private FilesystemOperator $resourcesFilesystem)
    {
    }

    public function addFile(string $filename, string $content): void
    {
        $this->filesystem->write($filename, $content);
    }

    public function getFile(string $filename)
    {
        $this->filesystem->read($filename);
    }
}
