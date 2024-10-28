<?php

namespace Modules\System\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class GetCommandList {
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     * moduleName       事件模块
     * all.name         用户名/手机/邮箱
     * all.password     密码
     * all.captcha      验证码
     * loginRegister    注册配置
     * api_login        是否是api登录
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
