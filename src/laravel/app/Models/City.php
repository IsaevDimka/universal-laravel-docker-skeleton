<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Class City
 *
 * @description Справочник информации о городах России
 * @package App\Models
 * @property int $id
 * @property string $address Адрес одной строкой
 * @property string $postal_code Почтовый индекс
 * @property string $country Страна
 * @property string $federal_district Федеральный округ
 * @property int $region_id ID региона
 * @property string $region_type Тип региона
 * @property \App\Models\Region $region Регион
 * @property string|null $area_type Тип района
 * @property string|null $area Район
 * @property string $type Тип города
 * @property string $name Город
 * @property string $name_with_type Тип и название одной строкой
 * @property string|null $settlement_type Тип населенного пункта
 * @property string|null $settlement Населенный пункт
 * @property string $kladr_id КЛАДР-код
 * @property string $fias_id ФИАС-код
 * @property int $fias_level Уровень по ФИАС
 * @property int $capital_marker Признак центра региона или района
 * @property string $okato Код ОКАТО
 * @property string $oktmo Код ОКТМО
 * @property string $tax_office Код ИФНС
 * @property string $timezone Часовой пояс
 * @property float|null $geo_lat Широта
 * @property float|null $geo_lon Долгота
 * @property string $population Население
 * @property string $foundation_year Год основания
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 * @method static \Illuminate\Database\Eloquent\Builder|City whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereAreaType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCapitalMarker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereFederalDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereFiasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereFiasLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereFoundationYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereGeoLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereGeoLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereKladrId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereNameWithType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereOkato($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereOktmo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City wherePopulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereRegionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereSettlement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereSettlementType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereTaxOffice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $postalCode Почтовый индекс
 * @property string $federalDistrict Федеральный округ
 * @property int $regionId ID региона
 * @property string $regionType Тип региона
 * @property string|null $areaType Тип района
 * @property string $nameWithType Тип и название одной строкой
 * @property string|null $settlementType Тип населенного пункта
 * @property string $kladrId КЛАДР-код
 * @property string $fiasId ФИАС-код
 * @property int $fiasLevel Уровень по ФИАС
 * @property int $capitalMarker Признак центра региона или района
 * @property string $taxOffice Код ИФНС
 * @property float|null $geoLat Широта
 * @property float|null $geoLon Долгота
 * @property string $foundationYear Год основания
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property bool $isActive
 * @method static \Illuminate\Database\Eloquent\Builder|City whereIsActive($value)
 * @property string $regionName Регион
 * @method static \Illuminate\Database\Eloquent\Builder|City whereRegionName($value)
 * @property string $region_name Регион
 * @property bool $is_active
 * @mixin IdeHelperCity
 */
class City extends BaseModel
{
    public $timestamps = false;

    protected $table = 'cities';

    protected $primaryKey = 'id';

    protected $fillable = [
        'address',
        'postal_code',
        'country',
        'federal_district',
        'region_id',
        'region_type',
        'region_name',
        'area_type',
        'area',
        'type',
        'name',
        'name_with_type',
        'settlement_type',
        'settlement',
        'kladr_id',
        'fias_id',
        'fias_level',
        'capital_marker',
        'okato',
        'oktmo',
        'tax_office',
        'timezone',
        'geo_lat',
        'geo_lon',
        'population',
        'foundation_year',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
