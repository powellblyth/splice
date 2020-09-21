<?php

namespace App\Console\Commands;

use App\Library\Services\Unleashed;
use Illuminate\Console\Command;

class importProductGroupsFromUnleashed extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unleashed:importproductGroups {--refresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all the product groups from Unleashed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $factName = 'unleashedProductGroupsDateSince';
        $className = 'App\\ProductGroup';
        $unleashedFunctionName = 'getProductGroups';

        $unleashed = new Unleashed();
        $unleashed->importThing($factName, $className, $unleashedFunctionName, $this->option('refresh'));
    }
}