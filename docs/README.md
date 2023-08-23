# 丝瓜管理后台文档

> 本文档支持使用 `docsify` 浏览。  
> 首先安装 `docsify`，安装命令 `npm i docsify-cli -g`；  
> 然后在项目文件夹执行 `docsify serve docs` 启动服务。  
> 浏览地址： http://localhost:3000


## 技术架构

- 后端
  - PHP 8.2+
  - MySQL/PostgreSQL/SQLServer（不支持 SQLite）
  - [Laravel 10+](https://learnku.com/docs/laravel/10.x)
  - Redis（可选）
  - Octane（可选）
  -  工具
    - `php-cs-fixer` 格式化 `PHP` 代码。不用 `pint`，`php-cs-fixer` 结合 `PHPStorm` 用起来更方便。
- 前端
  - `Vue3` 组合式 API
  - `VueRouter`
  - 用 Vue `reactive` 管理状态
  - [`ElementPlus`](https://element-plus.gitee.io/zh-CN/) 完整引入（相对于自动导入极大提高预览的加载速度）
  - [`TailwindCSS`](https://tailwindcss.com/docs) （扩充 ElementPlus 细节控制，布局、边距、文字、颜色）
    - 在 scss 中用 @apply 复用 tailwind 样式
  - `Tinymce` 富文本编辑器
  - `vuedraggable` 拖拽排序
  - 使用 `scss` 写样式
  - 图标
    - 主要图标使用 `iconfont` 字体图标，图标库在 `iconfont.cn` 图标项目维护
    - 图标风格必须统一
    - 图标必须在图标项目里调整统一尺寸
    - 外部图标可上传 svg 到图标项目
    - 一些工具图标可用 `@element-plus/icons-vue` 图标
  - 工具
    - `Vite` 开发模式热加载，预览速度和编译后的一样快
    - `pnpm` 管理 `npm` 包，优势：极大优化性能，速度更快、占空间更少、node_modules 目录结构简洁清晰（使用树状结构而不是扁平结构，带来了依赖的安全性，IDE 打开 node_modules 目录不卡）等
    - `eslint` 格式化 js 代码，并整合 `PHPStorm` 的 `eslint` 工具，勾选保存时运行 `eslint --fix`，以支持保存时自动格式化 js/vue 文件。
    - `rollup-plugin-visualizer` 可视化并分析包依赖、模块占用空间大小

