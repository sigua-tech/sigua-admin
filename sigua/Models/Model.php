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

namespace Sigua\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * @method static null|Collection|static create(array $attributes = [])
 * @method static null|Collection|static firstOrCreate(array $attributes = [], array $values = [])
 * @method static null|Collection|static firstOrNew(array $attributes = [], array $values = [])
 * @method static null|Collection|static updateOrCreate(array $attributes = [], array $values = [])
 * @method static null|Builder[]|Collection|static find(mixed $id, $columns = ['*'])
 * @method static null|Builder[]|Collection|static findOrFail(mixed $id, $columns = ['*'])
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder whereJsonContains($column, $value, $boolean = 'and', $not = false)
 * @method static Builder whereJsonLength($column, $operator, $value = null, $boolean = 'and')
 * @method static Builder whereJsonDoesntContain($column, $value, $boolean = 'and')
 * @property int $id
 */
class Model extends BaseModel
{
}
