<?php

namespace AhmedArafat\AllInOne\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\progress;

class DatabaseInitialSeedersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected string $description = 'Run All Database Required Seeders';

    private array $allSeedersObjects = [];

    public function __construct()
    {
        parent::__construct();
        $this->allSeedersObjects = [
            //new XyzSeeder(),
        ];
    }

    private function executeAllSeeders()
    {
        DB::transaction(function () {
            foreach ($this->allSeedersObjects as $key => $object) {
                $object->run();
            }
        });
    }

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle(): void
    {
        progress(
            "Seeding Database ...",
            count($this->allSeedersObjects),
            function ($num) {
                $this->allSeedersObjects[$num]->run();
            }
        );
        $this->info("Done Seeding Database <3");
    }
}
