<?php

namespace App\Controller;

use App\Repository\ResourceRepository;
use App\Storage\FileSystemAdaptor;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsController]
class ReadResourceController
{
    public function __construct(
        private readonly FileSystemAdaptor $fileSystemAdaptor,
        LoggerInterface $logger,
        private ResourceRepository $resourceRepository)
    {
    }

    public function __invoke(int $id): Response
    {
        $resource = $this->resourceRepository->find($id);

        if (!$resource) {
            throw new NotFoundHttpException('Resource not found');
        }

        $fileName = $resource->getFileName();

        if (!$this->fileSystemAdaptor->checkFile($fileName)) {
            throw new NotFoundHttpException('File not found');
        }

        // Get the stream resource
        $stream = $this->fileSystemAdaptor->streamFileContent($fileName);

        // Return a streamed response
        return new StreamedResponse(function () use ($stream) {
            if (is_resource($stream)) {
                fpassthru($stream);
                fclose($stream);
            }
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($fileName).'"',
        ]);
    }
}

