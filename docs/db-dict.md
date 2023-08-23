数据字段
------

- 门店
    - 门店表 `stores` 字段
        - id
        - title
        - address
        - info 门店介绍
        - album 门店相册
        - created_at
        - updated_at
- 商品
    - 商品表 `goods` 字段
        - id
        - code 商品编号
        - title
        - usp 卖点（Unique Selling Point）
        - album 商品相册
        - brief 商品简介
        - detail 商品详细介绍
        - price 售价
        - unit 销售单位
        - packing_fee 打包费
        - sales_count 总销量
        - like_count 点赞量
        - evaluate_count 评价数量
        - evaluate_score 平均评分（定时更新）
        - is_on_sale 是否售卖中
        - asc_num 列表排序序号
        - desc_num asc_num 反向数，用于反向排序， desc_num = 9999 - asc_num
        - created_at
        - updated_at

        + categories 分类
        + tags 标签
- 商品规格
  - 每个商品最多有3种规格
  - 
- 商品标签
    - 每个商品可关联多个标签
    - 商品标签表 `goods_tags`
        - id
        - title
        - goods_count
    - 商品标签关联表 `goods_tag_pivots`
        - id
        - goods_id
        - tag_id
- 商品分类
  一个商品可以选择多个分类。
    - 商品分类表 `goods`
        - id
        - parent_id
        - title 标题
        - goods_count 分类商品数量
        - is_show 是否显示在顾客界面菜单
        - asc_num 正序排序数
        - created_at
        - updated_at
    - 商品分类关联表 `goods_category_pivots`
        - id
        - goods_id
        - category_id
        - created_at
        - updated_at

## 管理员&员工

- 员工

    - 员工数据表 `staffs`
        - id
        - type 用户类型，system）系统用户，store）门店人员
        - store_id 门店id，门店人员关联门店
        - department_id 部门 id
        - name 用户名（登录账号）
        - mobile 手机号（登录账号）
        - email 邮箱（登录账号）
        - password
        - realname 真实姓名
        - enabled 是否启用
        - remember_token
        - created_at
        - updated_at
        - deleted_at

    - 部门表 `staff_departments`
        - id
        - parent_id
        - title 部门名称
        - brief 部门剪辑
        - asc_num 显示排序
        - staff_count 部门员工数
        - created_at
        - updated_at

    - 员工角色表 `staff_roles`
        - id
        - type 角色类型，和员工类型对应。system）系统用户，store）门店员工
        - title
        - brief
        - list_order
        - permissions
        - enabled
        - created_at
        - updated_at

    - 员工角色关联表 `staff_role_pivots`
        - id
        - staff_id
        - role_id
        - created_at
        - updated_at

- 系统设置

    - 系统设置表 `settings`
        - id
        - name
        - group
        - title
        - value
        - type 值数据类型：string/int/float/bool/array

