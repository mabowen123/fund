<?php


namespace App\Models;


class UserFund extends BaseModel
{
    protected $fillable = ['share', 'amount', 'fund_id', 'user_id'];
}
