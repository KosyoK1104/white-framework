<?php

declare(strict_types=1);

namespace App\Commands;

final class Migrate extends \Phinx\Console\Command\Migrate
{
    protected function configure() : void
    {
        parent::configure();
        $this->setName('migrate');
    }
}
