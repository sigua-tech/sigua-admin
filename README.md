丝瓜管理后台 (Sigua Admin)
------

「丝瓜管理后台」是一个中后台全栈解决方案，后端基于强大的 Laravel 应用程序框架提供 API，前端基于 Vue3 和 ElementPlus 实现单页面应用，让复杂的中后台开发技术变得更简单！

## Install

### 环境要求
安装之前，你的电脑上需要先安装以下应用程序：
- PHP 8.2+
- MySQL 5.7+ 推荐使用 8.x
- Node.js 18+

数据库方面你也可以选择使用 `MariaDB`/`PostgreSQL`/`SQLServer` 等主流数据库，但不支持 `SQLite` 数据库，因为 `SQLite` 的锁机制只能串行写入，在事务中对一个表进行多次写入时就会出现死锁的问题。

### 安装方式一、Git 克隆项目

```shell
git clone https://github.com/sigua-tech/sigua-admin.git
```

### 安装方式二、用 Composer 安装（TODO）
```shell
composer create-project sigua-tech/sigua-admin sigua-admin
```

### 配置项目参数

新建 MySQL 库，，命名为 `sigua-db`，使用 `utf8mb4_unicode_ci` 字符集。
复制项目根目录下的 `.env.example` 文件，文件名为 `.env`，然后编辑 `.env` 文件，设置数据库连接参数，如下

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sigua-db
DB_USERNAME=root
DB_PASSWORD=
```

### 安装依赖包

```shell
# 进入项目文件夹
cd sigua-admin

# 安装 php 依赖包
composer install

# 生成加密用的唯一 key
php artisan key:generate

# 创建数据表
php artisan migrate # 安装体验数据用 php artisan migrate --seed

# 创建存储目录软链，把 public/storage 目录软链到 storage/app/public 目录。
php artisan storage:link

# 安装 pnpm 包，用 pnpm 代替 npm 管理 node_modules 依赖包（如果你已全局安装 pnpm 就不需要再操作这一步）
npm i -g pnpm

# 用 pnpm 安装 js 依赖包
pnpm i

```

### 启动开发服务器
```shell
# 进入项目文件夹后执行命令
# 启动本地 php 开发服务器
php artisan serve

# 重新打开一个终端窗口，进入项目文件夹后执行命令
# 启动前端开发服务器
pnpm dev

```

启动服务后，用 `Chrome` 浏览器打开网址 http://localhost:8000/admin 浏览管理后台。

