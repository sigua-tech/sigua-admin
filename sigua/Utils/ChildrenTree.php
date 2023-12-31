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

namespace Sigua\Utils;

class ChildrenTree
{
    protected array $data = [];

    protected string $idKey = 'id';

    protected string $parentIdKey = 'parent_id';

    protected string $disabledKey = 'disabled';

    protected string $childrenKey = 'children';

    protected array $excluded = [];

    protected array $disabled = [];

    public function __construct(array $data, array $disabled = [], $excluded = [], array $props = [])
    {
        foreach ($props as $key => $prop) {
            $attribute = str_replace('_i', 'I', $key) . 'Key';
            if (isset($this->{$attribute})) {
                $this->{$attribute} = $prop;
            }
        }

        $this->excluded = $excluded;
        $this->disabled = $disabled;
        $this->setData($data);
    }

    /**
     * @param array $disabled [$key => $value] 元素的 $key 属性的值等于 $value，则给元素及其子元素加上 [$this->disabledKey => true] 属性
     * @param array $excluded [$key => $value] 元素的 $key 属性的值等于 $value，则移除掉
     * @param array $props 修改字段名 [<pre>
     *                     'id' => 'your_id',
     *                     'parent_id' => 'your_parent_id',
     *                     'disabled' => 'your_disabled',
     *                     'children' => 'your_children'
     *                     ]</pre>
     */
    public static function make(array $data, array $disabled = [], array $excluded = [], array $props = []): array
    {
        if (empty($data)) {
            return [];
        }

        return (new static($data, $disabled, $excluded, $props))->process();
    }

    public function process(int|string $parentId = 0): array
    {
        $items = [];
        foreach ($this->data[$parentId] as $item) {
            $childParentId = $item[$this->idKey];
            if (isset($this->data[$childParentId])) {
                $item[$this->childrenKey] = $this->process($childParentId);
            }
            $items[] = $item;
        }

        return $items;
    }

    public function setData(array $data): self
    {
        // 数据预处理
        foreach ($data as $item) {
            if ($this->excluded) {
                foreach ($this->excluded as $excludeKey => $excludeValue) {
                    if ($item[$excludeKey] === $excludeValue) {
                        continue;
                    }
                }
            }
            $parentId = $item[$this->parentIdKey] ?: 0;
            $this->data[$parentId][] = $item;
        }

        $this->processDisabled();
        return $this;
    }

    protected function processDisabled(): void
    {
        if (! $this->disabled) {
            return;
        }

        $disableByParentId = function ($parentId) use (&$disableByParentId) {
            if (empty($this->data[$parentId])) {
                return;
            }
            foreach ($this->data[$parentId] as $key => $item) {
                $this->data[$parentId][$key][$this->disabledKey] = true;
                $disableByParentId($item[$this->idKey]);
            }
        };

        foreach ($this->data as $parentId => $items) {
            foreach ($items as $key => $item) {
                foreach ($this->disabled as $disabledKey => $disabledValue) {
                    if ($item[$disabledKey] !== $disabledValue) {
                        continue;
                    }
                    $this->data[$parentId][$key][$this->disabledKey] = true;
                    $disableByParentId($item[$this->idKey]);
                }
            }
        }
    }
}
