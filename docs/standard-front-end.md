---
前端开发规范
---

## 代码格式化工具

前端用 `eslint` 格式化代码，统一代码风格。

## 参考编码规范
- [Vue 代码风格指南](https://cn.vuejs.org/style-guide/)
- [《阿里巴巴前端规约](https://github.com/alibaba/f2e-spec) ，


## 路由

path 采用 `kebab-case` 命名规范以外，必须以 `/` 开头，即使是 `children` 里的 `path` 也要以 `/` 开头。
目的：经常有这样的场景：某个页面有问题，要立刻找到这个 `vue` 文件，如果不用以 `/` 开头，`path` 为 `parent` 和 `children`
组成的，可能经常需要在 `router` 文件里搜索多次才能找到，而如果以 `/` 开头，则能立刻搜索到对应的组件。

## Vue 规范

### 使用内联箭头函数绑定事件

@TODO 此条整理出一篇博文

Vue 自定义组件事件回调函数会箭头函数化，即你绑定了一个非箭头函数的函数名，编译后绑定函数会被转换成箭头函数。
根本的原因是 `ES` 中的可变变量回调函数会导致 `this`丢失（回调函数变成箭头函数）。

解决办法：`绑定事件时用内联箭头函数`。

```html

<template>
    <MyComponent @my-event="fn"/>
</template>

<script setup>
    function fn () {
    } // 绑定给 MyComponent my-event 事件后，将被编译成等同于 const fn = () => {}
</script>
```

你定义的是 `function fn() {}`，绑定到事件编译后会变成等同于 `const fn = () => {}`。
如果你不了解箭头函数的特性，可能会觉得，这没什么，不是一样能用嘛。
如果你清楚箭头函数的特性，你就会发现，它把你的 `this` 弄没了。
当然，我们直接用箭头函数定义函数就没问题了，但是有时候还必须用到 `this`，比如我们用对象封装的时候。
其实我们要解决这个问题是很简单的，不是去把我们封装的函数改成箭头函数，而是绑定事件时用内联箭头函数。

```html

<script setup>
    class MyClass {
        a = 1;

        selectChange (items) {
            console.log(items, this.a); // 需要访问其他属性或方法
        }
    }

    const my = new MyClass();
</script>

<template>
    <!-- ❌ Vue 自定义组件事件回调会 -->
    <el-table :data="data" @selection-change="my.selectChange">
    </el-table>

    <!-- ✅ -->
    <el-table :data="data" @selection-change="items => my.selectChange(items)">
    </el-table>
</template>
```

> `VSCode` 装了 `Volar` 插件后，绑定事件时，它总是很烦人的在前面自动加上 `$event =>`，
> 明明想要的是 `@click="myFn"`，总是自己变成 `@click="$event => myFn"`，`$event =>` 删都删不掉，是不是很烦人。
> 其实他就是在告诉你，应该这样用才比较靠谱。

### 权限控制

路由页面权限控制在路由跳转前进行，弹窗页面权限在列表页检查，弹窗逻辑中不再检查。
在列表页先用 can() 获取员工是否有查看详情、编辑、删除操作权限，再在遍历中使用到操作按钮上。
权限变量值不需要改变，因此其不需要用响应式变量。

```html
// pages/staff/Index.vue
<script setup>
import { useCans } from '../store/auth';
const cans = useCans('staff', ['view', 'create', 'update', 'delete']);
</script>

<template>
    <el-button type="primary" @click="showCreate" :disabled="!cans.create">+ 新建</el-button>
    <el-table :data="listData.items">
        <el-table-column label="操作">
            <template #default="scope">
                <el-link type="primary" :href="'`#/staff/${scope.row.id}" :disabled="!cans.view">详情</el-link> |
                <el-link type="primary" :href="`#/staff/${scope.row.id}/edit`" :disabled="!cans.update">编辑</el-link> |
                <PopconfirmDelete
                    title="您确定要删除该员工吗？"
                    :disabled="!cans.delete"
                    @confirm="deleteItem(scope.row.id)"
                />
            </template>
        </el-table-column>
    </el-table>
</template>
```

## 平滑清空表单数据（清空时不显示表单验证错误信息）

### 1、el-form 动态加 css 类 `halt-validate-error`

` :class="is.showValidateError ? 'halt-validate-error' : ''" `

### 2、停用表单验证错误
```js
const reset = () => {
    is.showValidateError = true; // 停用错误
    refKit.empty(item);
    setTimeout(() => {
        formRef.value.clearValidate(); // 等待清空对象完成后，清空错误信息，然后在恢复显示错误
        is.showValidateError = false;
    }, 200);
};
```
