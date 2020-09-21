<?php

namespace App\Console\Commands;

use App\Library\Services\Unleashed;
use Illuminate\Console\Command;

class importProductsFromUnleashed extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unleashed:importproducts {--refresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports new and amended products from Unleashed';

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
        $factName = 'unleashedProductsDateSince';
        $className = '\App\Product';
        $unleashedFunctionName = 'getProductsModifiedSince';

        $unleashed = new Unleashed();
        $unleashed->importThing($factName, $className, $unleashedFunctionName, $this->option('refresh'), ['includeAttributes'=>'true']);
    }
}