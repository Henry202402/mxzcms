<?php

namespace Modules\Member\Database\Seeders;

use Illuminate\Database\Seeder;

class ModuleMemberBaseConfigurationSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('module_member_base_configuration')->delete();

        \DB::table('module_member_base_configuration')->insert(array (
  0 => 
  array(
     'id' => 1,
     'name' => 'vipConfig',
     'json_str' => '{"interests":[{"name":"专属客服","value":"24小时专属服务"},{"name":"心动折扣","value":"折上95折"}],"vip_rule":"<p>VIP规则：<\\/p>\\r\\n<p>1、开通会员前请阅读《用户协议》，会员服务一经开通后不可退款。<\\/p>\\r\\n<p>2、开通会员可免费观看文章和内容。<\\/p>\\r\\n<p>3、会员权益与手机号相对应，无共享账号。<\\/p>\\r\\n<p>4、严禁使用任何手段爬取本产品数据，一经发现可能面临账号禁用风险。<\\/p>"}',
     'updated_at' => '2025-03-12 11:56:13',
  ),
  1 => 
  array(
     'id' => 2,
     'name' => 'signInConfig',
     'json_str' => '{"integral_alias":"积分","day_int":[{"key":"1","value":"1"},{"key":"2","value":"2"},{"key":"3","value":"3"},{"key":"4","value":"4"}],"sign_in_rules":"<p>用户签到须知：<\\/p>\\r\\n<p>1. 一天只需签到一次，24小时内任意时间均可签到，首次签到1次获1积分，次日签到1次获2积分，以此类推至第五天固定获4积分，签到中断，退回起点重新开始签到。<\\/p>\\r\\n<p>2. 每日签到攒积分，兑换精美好礼（包括但不限于 vip账号、话费充值等）签到兑换规则根据时间段或礼品不同会随时调整。<\\/p>\\r\\n<p>3. 积分不清零！连续签到可获得相应奖励，积分越高的用户随机爆出惊喜奖品的机率越高，奖品可凭兑换码联系客服兑换，领取不限次数。<\\/p>"}',
     'updated_at' => '2025-03-12 11:51:08',
  ),
));


    }
}
