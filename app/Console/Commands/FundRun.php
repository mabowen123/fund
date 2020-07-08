<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Traits\FundRun as pFundRun;

class FundRun extends Command
{
    use pFundRun;
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
        $this->fundRun();
    }
}
