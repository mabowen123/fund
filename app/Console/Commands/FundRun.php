<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FundRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fund:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'python抓取基金数据';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info($this->getDescription());
        $path = base_path('python/spider.py');
        system("python {$path}");
    }
}
