<?php

namespace App\Command;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:upload-files',
    description: 'Upload specified files to MinIO.',
)]
class UploadFilesToMinIOCommand extends Command
{
    private FilesystemOperator $filesystem;

    public function __construct(FilesystemOperator $resourcesFilesystem)
    {
        parent::__construct();
        $this->filesystem = $resourcesFilesystem;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filesToUpload = [
            'Manuel d\'Epictète.pdf',
            'Le Loup des Steppes.pdf',
            'Extrait - La Boétie.pdf',
        ];

        foreach ($filesToUpload as $fileName) {
            $this->uploadFilesToMinIO($fileName, $output);
        }

        return Command::SUCCESS;
    }

    private function uploadFilesToMinIO(string $fileName, OutputInterface $output): void
    {
        $filePath = __DIR__ . '/../DataFixtures/Files/' . $fileName;

        if (!file_exists($filePath)) {
            $output->writeln("Le fichier $fileName n'existe pas.");
            return;
        }

        $fileContent = file_get_contents($filePath);
        if ($fileContent === false) {
            throw new \RuntimeException("Impossible de lire le fichier: $filePath");
        }

        $this->filesystem->write($fileName, $fileContent);
        $output->writeln("Le fichier $fileName a été chargé avec succès.");
    }
}
