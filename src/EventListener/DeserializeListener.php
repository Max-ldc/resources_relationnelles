<?php

namespace App\EventListener;

use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use ApiPlatform\Symfony\EventListener\DeserializeListener as DecoratedListener;
use ApiPlatform\Symfony\Util\RequestAttributesExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class DeserializeListener
{
    public function __construct(
        private DecoratedListener $decorated,
        private SerializerContextBuilderInterface $serializerContextBuilder,
        private DenormalizerInterface $denormalizer
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if ($request->isMethodCacheable() || $request->isMethod(Request::METHOD_DELETE)) {
            return;
        }

        // must be 'form' and not 'multipart' to work
        if ($request->getContentTypeFormat() === 'form') {
            $this->denormalizeFromRequest($request);
        } else {
            $this->decorated->onKernelRequest($event);
        }
    }

    private function denormalizeFromRequest(Request $request): void
    {
        $attr = RequestAttributesExtractor::extractAttributes($request);
        if (empty($attr)) {
            return;
        }

        $context = $this->serializerContextBuilder->createFromRequest($request, false, $attr);

        $populated = $request->attributes->get('data');
        if ($populated !== null) {
            $context['object_to_populate'] = $populated;
        }
        $data = $request->request->all();
        $files = $request->files->all();

        $object = $this->denormalizer->denormalize(
            array_merge($data, $files),
            $attr['resource_class'],
            null,
            $context
        );

        $request->attributes->set('data', $object);
    }
}