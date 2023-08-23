后端开发规范
---

开发规范与规范参考：

- 代码风格遵循 PSR12 规范 [英文](https://www.php-fig.org/psr/psr-12/) | [中文](https://learnku.com/docs/psr/psr-12-extended-coding-style-guide)
- 使用 `PHP-CS-Fixer` 格式化代码
- 参考 [Laravel 开发规范](https://learnku.com/docs/laravel-specification)

## 命名规范

### 单数/复数

- 单数
    - 模型/控制器/请求/服务等类名
    - 文件夹名
    - 路由名称
    - 模板文件&文件夹（包括 `blade` 和 `Vue` ）
- 复数
    - 有复数意义的链接（路由 `path`）
    - 数据表名、 `migration` 文件名用复数

链接和表名我都想用单数，但是 artisan -m make:model 生成的表名默认是复数的，就遵循它的规范吧，省心省事。

### 权限控制

以 `App\Admin\Controllers\UserController` 控制器举例，`REST` 风格的控制器的 `action` 对应的访问权限命名规范如下：

| 功能   | method | 路由 path                  | action    | 权限命名           |
|------|--------|--------------------------|-----------|----------------|
| 列表页  | GET    | /admin/users             | index     | user.viewAll   |
| 详情页  | GET    | /admin/users/{user}      | show      | user.view      |
| 新建页  | GET    | /admin/users             | create    | user.create    |
| 新建保存 | POST   | /admin/users             | store     | user.create    |
| 编辑页  | GET    | /admin/users/{user}/edit | edit      | user.update    |
| 编辑保存 | PUT    | /admin/users/{user}      | update    | user.update    |
| 删除   | DELETE | /admin/users/{user}      | destroy   | user.delete    |
| 批量删除 | POST   | /admin/users/bat/delete  | batDelete | user.batDelete |


## 数据库
- 使用默认（UTC）时区

### 字段名禁用关键字

- `disabled` 前端表单选择菜单使用 disabled 属性作为禁止选择，避免冲突，可用 `enabled` 字段名代替。

## 时区
方案一：前端格式化显示日期（默认方案）
使用 `UTC` 时区，数据表字段类型为 `datetime`，API 给前端返回时间格式为 `2023-06-22T04:34:45.000000Z`，前端显示时用 `lib/util.js` 里的 `utcDateTimeToLocal()` 函数格式化为本地时间。

方案二：使用本地时区（备用方案）
step 1、修改 `config/app.php` 的 `timezone` 为 `Asia/Shanghai`；
step 2、在模型基类或模型类加入 `use \Sigua\Models\traits\SerializeDate;`


另外还有一种日期处理方式：使用 `Carbon` 的函数， `$model->created_at->format('Y-m-d H:i:s') `，可以使用在模型中加 `Attribute` 的方式。

## URL 命名规范

- 小写
- 单词用 - 分隔
