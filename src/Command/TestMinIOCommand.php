<?php

// src/Command/TestMinIOCommand.php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use League\Flysystem\FilesystemOperator;

#[AsCommand(
    name: 'app:test-minio',
    description: 'Test MinIO integration.',
)]
class TestMinIOCommand extends Command
{
    private FilesystemOperator $filesystem;

    public function __construct(FilesystemOperator $resourcesFilesystem)
    {
        parent::__construct();
        $this->filesystem = $resourcesFilesystem;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $testFileName = 'testfile.txt';
        $testContent = 'Hello, MinIO!';

        try {
            $this->filesystem->write($testFileName, $testContent);
            $output->writeln('File uploaded successfully.');

            $contents = $this->filesystem->listContents('')->toArray();
            $output->writeln('Files in bucket:');
            foreach ($contents as $object) {
                $output->writeln($object['path']);
            }

            $downloadedContent = $this->filesystem->read($testFileName);
            $output->writeln('Downloaded file content: ' . $downloadedContent);

            $this->filesystem->delete($testFileName);
            $output->writeln('File deleted successfully.');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
