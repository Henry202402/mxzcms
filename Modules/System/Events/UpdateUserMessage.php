<?php

namespace Modules\System\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UpdateUserMessage {
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     *
     *  moduleName      功能模块名称
     *  operate_type    操作类型 1=已读 2=全部已读 3=删除 4=列表 5=详情 6=添加
     *  uid             接收者uid          operate_type=1,2,3,4,5,6
     *  ids             信息id数组         operate_type=1,3
     *  module          模块名称           operate_type=6
     *  title           标题               operate_type=6
     *  content         内容               operate_type=6
     *  send_uid        发送者uid，选填    operate_type=6
     *  json_str        json，选填         operate_type=6
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
