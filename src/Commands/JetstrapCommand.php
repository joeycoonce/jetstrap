<?php

namespace JoeyCoonce\Jetstrap\Commands;

use Illuminate\Console\Command;

class JetstrapCommand extends Command
{
    public $signature = 'jetstrap';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
