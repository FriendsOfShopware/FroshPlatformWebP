<?php

namespace Frosh\WebP\Subscriber;

use Shopware\Core\Content\ImportExport\Message\DeleteFileMessage;
use Shopware\Core\Content\Media\Event\MediaThumbnailDeletedEvent;
use Shopware\Core\Content\Media\Pathname\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MediaThumbnailSubscriber implements EventSubscriberInterface
{

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(UrlGeneratorInterface $urlGenerator, MessageBusInterface $messageBus)
    {
        $this->urlGenerator = $urlGenerator;
        $this->messageBus = $messageBus;
    }

    public static function getSubscribedEvents()
    {
        return [
            'media_thumbnail.after_delete' => 'onMediaThumbnailDelete'
        ];
    }

    public function onMediaThumbnailDelete(MediaThumbnailDeletedEvent $event)
    {
        $thumbnails = $event->getThumbnails();

        $thumbnailPaths = [];

        foreach ($thumbnails as $thumbnail) {
            $path = $this->urlGenerator->getRelativeThumbnailUrl(
                $thumbnail->getMedia(),
                $thumbnail
            );

            $path = pathinfo($path);

            $thumbnailPaths[] = $path['dirname'] . '/' . $path['filename'] . '.webp';
        }

        $deleteMsg = new DeleteFileMessage();
        $deleteMsg->setFiles($thumbnailPaths);
        $this->messageBus->dispatch($deleteMsg);
    }
}
