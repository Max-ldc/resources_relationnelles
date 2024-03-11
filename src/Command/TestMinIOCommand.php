<?php

// src/Command/TestMinIOCommand.php
namespace App\Command;

use App\Storage\FileSystemAdaptor;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:test-minio',
    description: 'Test MinIO integration.',
)]
class TestMinIOCommand extends Command
{
    public function __construct(
        private FileSystemAdaptor $fileSystemAdaptor,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $testFileName = 'testfile.txt';
        $testContent = 'Hello, MinIO!';

        try {
            $this->fileSystemAdaptor->addFile($testFileName, $testContent);
            $output->writeln('File uploaded successfully.');

            $contents = $this->fileSystemAdaptor->getAllFiles();
            $output->writeln('Files in bucket:');
            foreach ($contents as $object) {
                $output->writeln($object['path']);
            }

            $downloadedContent = $this->fileSystemAdaptor->getFileContent($testFileName);
            $output->writeln('Downloaded file content: ' . $downloadedContent);

            $this->fileSystemAdaptor->delete($testFileName);
            $output->writeln('File deleted successfully.');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
