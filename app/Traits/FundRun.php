<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait  FundRun
{
    public function fundRun()
    {
        $hour = now()->hour;
        Log::debug('抓取基金数据');
        if (($hour >= 9 && $hour <= 15) || $hour >= 20) {
            $path = base_path('python/spider.py');
            system("python3 {$path}");
        }
    }
}
