<?php

namespace AhmedArafat\AllInOne\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
    protected $description = 'Run All Database Required Seeders';

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle(): void
    {
        DB::transaction(function () {
            # Execute all your seeders here, like this:
            //(new XyzSeeder())->run();
        });
        $this->info("Done Inserting In DataBase");
    }
}
