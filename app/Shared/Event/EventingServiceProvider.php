<?php

declare(strict_types=1);

namespace App\Shared\Event;

use League\Container\ServiceProvider\AbstractServiceProvider;

final class EventingServiceProvider extends AbstractServiceProvider
{

    public function provides(string $id) : bool
    {
        return $id === EventDispatcher::class;
    }

    public function register() : void
    {
        $container = $this->container;
        $container->addShared(EventDispatcher::class, function () use ($container) {
            $eventDispatcher = new EventDispatcher($container);
            $eventDispatcher->addListners(
                [
                    // add EventListener::class[]
                ]
            );
            return $eventDispatcher;
        });
    }
}