<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait  FundRun
{
    public function fundRun()
    {
        Log::debug('抓取基金数据');
        $path = base_path('python/spider.py');
        system("python3 {$path}");
    }
}
