<?php

declare(strict_types=1);

namespace App\Commands;

final class Create extends \Phinx\Console\Command\Create
{
    protected function configure() : void
    {
        parent::configure();
        $this->setName('migration:create');
    }
}
