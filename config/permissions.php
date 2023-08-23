<?php
declare(strict_types=1);

$crud = [
    'viewAll' => '查看列表',
    'view' => '查看详情',
    'create' => '新建',
    'update' => '编辑',
    'delete' => '删除',
];
$crudAndBatDelete = [
    ...$crud,
    'batDelete' => '批量删除',
];

return [
    'order' => [
        'title' => '订单管理',
        'items' => [
            ...$crud,
            'append' => '追加商品',
        ],
    ],
    'goods' => [
        'title' => '商品管理',
        'items' => $crud,
    ],
    'goods.stock' => [
        'title' => '库存管理',
        'items' => [
            'update' => '修改库存',
            'taking' => '盘点',
        ],
    ],
    'goods.category' => [
        'title' => '商品分类管理',
        'items' => $crud,
    ],
    'goods.tag' => [
        'title' => '商品标签管理',
        'items' => $crudAndBatDelete,
    ],
    'member' => [
        'title' => '会员管理',
        'items' => $crud,
    ],
    'staff' => [
        'title' => '员工管理',
        'items' => $crudAndBatDelete,
    ],
    'staff.role' => [
        'title' => '角色管理',
        'items' => $crud,
    ],
    'staff.department' => [
        'title' => '部门管理',
        'items' => $crud,
    ],
    'setting' => [
        'title' => '系统设置',
        'items' => [
            'basic' => '基本设置',
            'trade' => '营业设置',
        ],
    ],
];
