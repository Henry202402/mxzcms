<?php

namespace Modules\Member\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Member\Models\Wallet;
use Modules\Member\Models\WalletRecord;

class UserController extends JWTController {

    //获取用户钱包
    public function getUserWallet(Request $request) {
        $user = $this->current_user();
        $data = Wallet::getWallet($user['uid']);
        $data['withdrawable'] *= 1;
        $data['balance'] *= 1;
        $data['integral'] *= 1;
        $data['soon_funds_received'] = 0;
        $data['acc_funds_received'] = 0;
        $data['acc_withdrawal'] = 0;
        unset($data['created_at'], $data['updated_at']);
        return returnArr(200, '成功', $data);
    }

    public function getUserWalletRecord(Request $request) {
        $user = $this->current_user();
        $all = $request->all();
        $data = WalletRecord::query()
            ->where('uid', $user['uid'])
            ->where(function ($q)use($all){
                if($all['amount_type']) $q->where('amount_type',$all['amount_type']);
            })
            ->latest()
            ->select([
                'id', 'module', 'uid', 'type',
                'amount_type', 'amount', 'remark', 'type', 'created_at',
            ])
            ->paginate(getLen($all))->toArray();
        $data = dealPage($data);
        foreach ($data['list'] as &$d) {
            $d['amount'] *= 1;
            $d['amount_msg'] = ($d['type'] == 1 ? '+' : '-') . $d['amount'];
            $d['amount_type_msg'] = WalletRecord::amount_type()[$d['amount_type']];
            unset($d['amount_type']);
        }
        return returnArr(200, '成功', $data);
    }
}
