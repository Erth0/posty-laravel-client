<?php

namespace Mukja\Posty\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class GenerateApiTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posty:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate posty-cli api key';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('API Key: ' . Str::random(64));
    }
}
