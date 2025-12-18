<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeDomain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:domain {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Domain Class';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = $this->argument('name');
        $path = app_path("Domain/{$name}.php");

        if ($this->files->exists($path)) {
            $this->error("The {$name} already exists!");
            return;
        }

        $stub = <<<PHP
            <?php

            namespace App\Domain;

            class {$name}
            {
                //
            }
        PHP;

        $this->files->put($path, $stub);
        $this->info("Class {$name} to Domain created!");
    }
}
