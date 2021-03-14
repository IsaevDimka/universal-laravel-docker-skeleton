<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TypeOfAddress
 *
 * @package App\Models
 *
 * Типы адресных объектов в ФИАС
 * @property int $id
 * @property int $type_id Уникальный идентификатор типа
 * @property int $fias_level Уровень адресного объекта
 * @property string $name Краткое название типа
 * @property string $name_full Полное название типа
 * @method static \Illuminate\Database\Eloquent\Builder|TypeOfAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeOfAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeOfAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeOfAddress whereFiasLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeOfAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeOfAddress whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeOfAddress whereNameFull($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeOfAddress whereTypeId($value)
 * @mixin \Eloquent
 * @property int $typeId Уникальный идентификатор типа
 * @property int $fiasLevel Уровень адресного объекта
 * @property string $nameFull Полное название типа
 * @mixin IdeHelperTypeOfAddress
 */
class TypeOfAddress extends Model
{
    public $timestamps = false;

    protected $table = 'type_of_addresses';

    protected $primaryKey = 'id';

    protected $fillable = [
        'type_id', // Уникальный идентификатор типа
        'fias_level', // Уровень адресного объекта
        'name', // Краткое название типа
        'name_full', // Полное название типа
    ];
}
