<?php


namespace App\Http\Controllers;


use App\Cache\Name;
use App\Models\UserFund;
use App\Traits\FundRun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class FundController
{
    use FundRun;

    public function fund()
    {
        $data['list'] = UserFund::whereUserId(Auth::id())->get([
            'id', 'fund_id', 'share', 'amount'
        ])->transform(function ($item) {
            if ($data = Redis::hGetAll(Name::fund($item['fund_id']))) {
                $item->estimated_earnings = round($item->share * ($data['estimated_net_worth'] - $data['net_worth']), 2);
                $item->actual_earnings = $data['actual_net_worth'] ? round($item->share * ($data['actual_net_worth'] - $data['net_worth']), 2) : '未更新';
                $item->cumulative_income = round(($item->share * $data['net_worth']) - $item->amount, 2);
                $item->update_at = $data['update_at'];
                $item->estimated_net_worth_ratio = $data['estimated_net_worth_ratio'];
                $item->actual_net_worth_ratio = $data['actual_net_worth_ratio'] ? (float)$data['actual_net_worth_ratio'] : '未更新';
                $item->fund_name = $data['fund_name'];
            }
            return $item;
        })->toArray();

        $data['amount'] = [
            'cumulative_income' => round(array_sum(array_column($data['list'], 'cumulative_income')), 2),
            'estimated_earnings' => round(array_sum(array_column($data['list'], 'estimated_earnings')), 2),
            'actual_earnings' => round(array_sum(array_column($data['list'], 'actual_earnings')), 2),
            'amount' => round(array_sum(array_column($data['list'], 'amount')), 2),
        ];

        $data['amount']['estimated_earnings_rate'] = $data['amount']['amount'] > 0 ? round(($data['amount']['estimated_earnings'] / $data['amount']['amount']) * 100, 2) : 0;
        $data['amount']['actual_earnings_rate'] = $data['amount']['amount'] > 0 && $data['amount']['actual_earnings'] <> 0 ? round(($data['amount']['actual_earnings'] / $data['amount']['amount']) * 100, 2) : 0;

        $keys = Redis::keys('market*');
        $market = [];
        array_map(function ($key) use (&$market) {
            $item = Redis::hGetAll($key);
            $item['time'] = date('Y-m-d H:i:s', strtotime($item['time']));
            $item['percent'] = round($item['percent'] * 100, 2);
            $market[] = $item;
        }, $keys);
        $data['market'] = $market;

        return success($data);
    }

    public function delete(int $id)
    {
        $userFund = UserFund::where('user_id', Auth::id())->findOrFail($id, ['id']);
        $userFund->delete();
        return success();
    }

    public function edit(int $id, Request $request)
    {
        $userFund = UserFund::where('user_id', Auth::id())->findOrFail($id, ['id']);
        $userFund->fill($request->all(['share', 'amount']));
        $userFund->save();
        return success();
    }

    public function create(Request $request)
    {
        $data = $request->all(['fund_id', 'share', 'amount']);
        $data['user_id'] = Auth::id();
        $run = UserFund::Where('fund_id', $data['fund_id'])->doesntExist();
        UserFund::create($data);
        if ($run) {
            $this->fundRun();
        }

        return success();
    }
}

