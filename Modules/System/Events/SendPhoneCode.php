<?php

namespace Modules\System\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class SendPhoneCode {
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     * moduleName   :   模块名
     * operate_type :   操作类型 send=发送验证码，get=获取发送验证码，verify=验证验证码
     * email        :   接收邮箱
     * key          ：  加密字符串
     * uid          ：  用户uid
     * code         ：  用户填写的验证码，验证使用
     * code_type    :   验证码类型 1=登录，2=注册，3=忘记密码
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
