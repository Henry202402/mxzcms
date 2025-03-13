<?php

namespace Modules\Member\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class AddWalletRecord {
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     * moduleName   :   模块名称 固定值 Member
     * module       :   模块名称 调用本事件的模块
     * uid          :   用户uid
     * type         :   类型【1=加，2=减】
     * amount_type  :   操作对象类型【1=可提现余额，2=余额，3=积分等等，4=在线支付】
     * amount       :   数量
     * remark       :   备注
     * extra        :   扩展json
     */
    public function __construct($data) {
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn() {
        return new PrivateChannel('channel-name');
    }

}
