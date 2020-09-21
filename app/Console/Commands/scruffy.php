<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\OrdersDespatchUpdateFailureNotification;
class scruffy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'go:scruffy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Just scruffy stuff';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
                \Log::error('You are cheese');
    }
}
