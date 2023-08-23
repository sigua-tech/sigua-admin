<?php
/**
 * 丝瓜管理后台（Sigua admin）
 * 一个基于 Laravel 的管理后台系统，让中后台开发更简单！
 *
 * @author    Yiba <yibafun@gmail.com>
 * @copyright sigua.tech
 * @license   MIT (http://opensource.org/licenses/MIT)
 */
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('group')->default('system');
            $table->string('title');
            $table->text('value')->nullable()->comment('如果 input_type 是 select_multiple，则保存时转为字符串（用">"隔开）');
            $table->string('input_type')->default('text')->comment('输入框类型：text/textarea/switch/radio/select/select_multiple');
            $table->json('options')->nullable()->comment('input_type 为 radio/select/select_multiple 时的备选项');
            $table->string('tips')->nullable()->comment('提示信息');
        });

        Sigua\Models\Setting::upsert([
            ['group' => 'system', 'key' => 'site_name', 'title' => '网站名称', 'value' => '丝瓜Admin', 'input_type' => 'text'],
            ['group' => 'system', 'key' => 'site_logo_url', 'title' => 'LogoUrl', 'value' => '', 'input_type' => 'text'],
            ['group' => 'system', 'key' => 'site_copyright', 'title' => '版权信息', 'value' => '@Sigua.tech', 'input_type' => 'text'],
            ['group' => 'system', 'key' => 'site_stats_code', 'title' => '流量统计代码', 'value' => '', 'input_type' => 'textarea'],
            ['group' => 'contact', 'key' => 'contact_linkman', 'title' => '联系人', 'value' => '', 'input_type' => 'text'],
            ['group' => 'contact', 'key' => 'contact_phone', 'title' => '联系电话', 'value' => '', 'input_type' => 'text'],
            ['group' => 'contact', 'key' => 'contact_email', 'title' => '联系邮箱', 'value' => '', 'input_type' => 'text'],
            ['group' => 'contact', 'key' => 'contact_address', 'title' => '联系地址', 'value' => '', 'input_type' => 'textarea'],
            ['group' => 'sms', 'key' => 'sms_on', 'title' => '短信开关', 'value' => '0', 'input_type' => 'switch'],
            ['group' => 'sms', 'key' => 'sms_key', 'title' => 'AppKey', 'value' => '', 'input_type' => 'text'],
            ['group' => 'sms', 'key' => 'sms_secret', 'title' => 'Secret', 'value' => '', 'input_type' => 'text'],
        ], 'id');
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
