<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class SendSMS {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     *  cloudType           类型 plugin=插件里的事件，module=模块里的事件
     *  moduleName          插件标识/模块标识，调用哪个模块的
     *  params[area_code]   手机区号
     *  params[phone]       发送手机
     *  params[code_type]   验证码类型【1=登录，2=注册，3=忘记密码，4=绑定，5=解绑】
     *  params[code]        单个参数时，验证码， code/sms_params/content 三选一
     *  params[sms_params]  当有多个参数时，模板参数列表，如模板 {1}...{2}...{3}，那么需要带三个参数；code/sms_params/content 三选一
     *  params[content]     发送总内容，没有模板的，可以使用全部内容 code/sms_params/content 三选一
     *  params[title]       发送标题，选填
     *  params[msg]         验证码类型提示，选填
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
