<?php

declare(strict_types=1);

namespace IsaevDimka\RussianPost\Enum;

/**
 * Class PostType
 * @package LapayGroup\RussianPost
 * @see https://delivery.pochta.ru/calc_rpo_delivery_api.pdf (Данные из справочника)
 */
class PostType
{
    // Письма
    public const MAIL = 2;  // Письмо

    public const MAIL2_0 = 11; // Письмо 2.0

    public const MAIL_ONE_CLASS = 15; // Письмо первого класса

    public const MAIL_EXPRESS = 32; // Письмо Экспресс

    public const MAIL_COURIER = 33; // Письмо Курьерское

    public const TRACK_MAIL = 37; // Трек-письмо

    public const MAIL_ONE_CLASS2_0 = 48; // Письмо 1-го класса 2.0

    public const MAIL_ONE_CLASS_COURIER = 49; // Письмо 1-го класса Курьерское

    // Бандероли
    public const BANDEROL = 3;  // Бандероль

    public const BANDEROL_ONE_CLASS = 16; // Бандероль первого класса

    public const BANDEROL_SET = 35; // Бандероль-комплект

    // Посылки
    public const POSILKA = 4;  // Посылка

    public const POSILKA_ONE_CLASS = 47; // Посылка 1-го класса

    public const POSILKA_ONLINE = 23; // Посылка онлайн

    public const POSILKA_STANDART = 27; // Посылка стандарт

    public const POSILKA_COURIER = 28; // Посылка курьер

    public const POSILKA_COURIER_EMS = 28; // Посылка курьер EMS

    public const POSILKA_EXPRESS = 29; // Посылка экспресс

    public const POSILKA_EKOMPRO = 38; // Посылка-экомпро

    public const POSILKA_ONLINE_PLUS = 42; // Посылка онлайн плюс

    // Курьерка
    public const COURIER_ONLINE = 24; // Курьер онлайн

    public const COURIER_ONLINE_PLUS = 43; // Курьер онлайн плюс

    public const BUSINESS_COURIER = 30; // Бизнес курьер

    public const BUSINESS_COURIER_EXPRESS = 31; // Бизнес курьер экспресс

    // ЕМС
    public const EMS = 7;  // Отправление EMS

    public const EMS_OPTIMAL = 34; // EMS оптимальное

    public const EMS_PT = 41; // EMS PT

    // Уведомления
    public const NOTIFICATION_FORM = 12; // Бланк уведомлений

    public const NOTIFICATION_FORM_ONE_CLASS = 17; // Бланк уведомления 1 класса

    // ОВПО
    public const OVPO_MAIL = 19; // ОВПО - письмо

    public const OVPO_CARD = 22; // ОВПО - карточка

    public const VGPO_ONE_CLASS = 46; // ВГПО 1 кл

    // КПО
    public const KPO_STANDART = 39; // КПО-стандарт

    public const KPO_ECONOMY = 40; // КПО-эконом

    // Прочее
    public const NEWSPAPER_PACK = 13; // Газетная пачка

    public const GROUPED_SHIPMENTS = 14; // Консигнация

    public const SMALL_PACKAGE = 5;  // Маленький пакет

    public const POST_CARD = 6;  // Почтовая карточка

    public const SEKOGRAMMA = 8;  // Секограмма

    public const BAG_M = 9;  // Мешок "М"

    public const VSD = 10; // Отправление VSD

    public const INSURANCE_BAG = 18; // Сумка страховая

    public const MULTI_ENVELOPE = 20; // Мультиконверт

    public const HEAVY_MAILING = 21; // Тяжеловесное почтовое отправление

    public const DEPARTURE_DM = 25; // Отправление ДМ

    public const PACKAGE_DM = 26; // Пакет ДМ

    public const TRACK_CARD = 36; // Трек-открытка
}
