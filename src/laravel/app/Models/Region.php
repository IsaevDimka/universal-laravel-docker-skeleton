<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Class Region
 *
 * @package App\Models
 *
 * Справочник информации о регионах России
 * @property int $id
 * @property string $name Название
 * @property string $type Тип
 * @property string $name_with_type Тип и название одной строкой
 * @property string $federal_district Федеральный округ
 * @property string $kladr_id КЛАДР-код
 * @property string $fias_id ФИАС-код
 * @property string $okato Код ОКАТО
 * @property string|null $oktmo Код ОКТМО
 * @property string $tax_office Код ИФНС
 * @property string|null $postal_code Почтовый индекс
 * @property string $iso_code ISO-код
 * @property string $timezone Часовой пояс
 * @property string $geoname_code Код региона по справочнику GeoNames
 * @property int $geoname_id Идентификатор региона по справочнику GeoNames
 * @property string $geoname_name Англоязычное название региона по справочнику GeoNames
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @property-read int|null $cities_count
 * @method static \Illuminate\Database\Eloquent\Builder|Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region query()
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereFederalDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereFiasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereGeonameCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereGeonameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereGeonameName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereIsoCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereKladrId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereNameWithType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereOkato($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereOktmo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereTaxOffice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $nameWithType Тип и название одной строкой
 * @property string $federalDistrict Федеральный округ
 * @property string $kladrId КЛАДР-код
 * @property string $fiasId ФИАС-код
 * @property string $taxOffice Код ИФНС
 * @property string|null $postalCode Почтовый индекс
 * @property string $isoCode ISO-код
 * @property string $geonameCode Код региона по справочнику GeoNames
 * @property int $geonameId Идентификатор региона по справочнику GeoNames
 * @property string $geonameName Англоязычное название региона по справочнику GeoNames
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read int|null $citiesCount
 * @property int|null $areaId ID региона
 * @property bool $isActive
 * @property-read \App\Models\Area|null $area
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereIsActive($value)
 * @property int|null $area_id ID региона
 * @property bool $is_active
 * @mixin IdeHelperRegion
 */
class Region extends AbstractModel
{
    public $timestamps = false;

    protected $table = 'regions';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'type',
        'name_with_type',
        'federal_district',
        'kladr_id',
        'fias_id',
        'okato',
        'oktmo',
        'tax_office',
        'postal_code',
        'iso_code',
        'timezone',
        'geoname_code',
        'geoname_id',
        'geoname_name',
        'area_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
