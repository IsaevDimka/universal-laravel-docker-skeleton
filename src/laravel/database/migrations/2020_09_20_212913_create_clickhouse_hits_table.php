<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Clickhouse;

class CreateClickhouseHitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected string $table_name = 'hits';

    public function up()
    {
        /**
         * For clickhouse
         */
        $engine  = 'MergeTree(EventDate, (UserID, EventTime, EventDate), 8192)';
        $columns = [
            'UserID'         => Clickhouse::COLUMN_UINT64,
            'Domain'         => Clickhouse::COLUMN_STRING,
            'URLScheme'      => Clickhouse::COLUMN_STRING,
            'URL'            => Clickhouse::COLUMN_STRING,
            'Environment'    => Clickhouse::COLUMN_STRING,
            'AppVersion'     => Clickhouse::COLUMN_STRING,
            'LaravelVersion' => Clickhouse::COLUMN_STRING,
            'QueryParams'    => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'SearchPhrase'   => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),

            'Duration' => Clickhouse::COLUMN_UINT32,

            'BrowserEngine' => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'BrowserName'   => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'DeviceBrand'   => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'DeviceModel'   => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'DeviceType'    => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'Locale'        => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'TimeZone'      => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),

            'IP'   => Clickhouse::Nullable(Clickhouse::COLUMN_IPV4),
            'IPv6' => Clickhouse::Nullable(Clickhouse::COLUMN_IPV6),

            'IsBot'    => Clickhouse::Nullable(Clickhouse::COLUMN_UINT8),
            'Route'    => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'IsMobile' => Clickhouse::Nullable(Clickhouse::COLUMN_UINT8),
            'IsAjax'   => Clickhouse::Nullable(Clickhouse::COLUMN_UINT8),

            'LocationCity'      => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'LocationCountry'   => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'LocationLatitude'  => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'LocationLongitude' => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'LocationRegion'    => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),

            'UserAgent' => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'Os'        => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'OsVersion' => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),

            'Referer' => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),

            'UTMSource'   => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'UTMMedium'   => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'UTMCampaign' => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'UTMContent'  => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'UTMTerm'     => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),

            'EventTime' => Clickhouse::COLUMN_DATETIME,
            'EventDate' => 'DEFAULT toDate(EventTime)',
        ];

        $hits = [
//            'WatchID'                    => 'UInt64',
//            'JavaEnable'                 => 'UInt8',
            'Title'                      => 'String',
//            'GoodEvent'                  => 'Int16',
            'EventTime'                  => 'DateTime',
            'EventDate'                  => 'Date',
//            'CounterID'                  => 'UInt32',
            'ClientIP'                   => 'UInt32',
            'ClientIP6'                  => 'FixedString(16)',
            'RegionID'                   => 'UInt32',
            'UserID'                     => 'UInt64',
//            'CounterClass'               => 'Int8',
            'OS'                         => 'UInt8',
            'UserAgent'                  => 'UInt8',
            'URL'                        => 'String',
            'Referer'                    => 'String',
            'URLDomain'                  => 'String',
            'RefererDomain'              => 'String',
            'Refresh'                    => 'UInt8',
            'IsRobot'                    => 'UInt8',
            'RefererCategories'          => 'Array(UInt16)',
            'URLCategories'              => 'Array(UInt16)',
            'URLRegions'                 => 'Array(UInt32)',
//            'RefererRegions'             => 'Array(UInt32)',
            'ResolutionWidth'            => 'UInt16',
            'ResolutionHeight'           => 'UInt16',
            'ResolutionDepth'            => 'UInt8',
//            'FlashMajor'                 => 'UInt8',
//            'FlashMinor'                 => 'UInt8',
//            'FlashMinor2'                => 'String',
//            'NetMajor'                   => 'UInt8',
//            'NetMinor'                   => 'UInt8',
            'UserAgentMajor'             => 'UInt16',
            'UserAgentMinor'             => 'FixedString(2)',
            'CookieEnable'               => 'UInt8',
            'JavascriptEnable'           => 'UInt8',
            'IsMobile'                   => 'UInt8',
            'MobilePhone'                => 'UInt8',
            'MobilePhoneModel'           => 'String',
//            'IPNetworkID'                => 'UInt32',
            'TraficSourceID'             => 'Int8',
            'SearchEngineID'             => 'UInt16',
            'SearchPhrase'               => 'String',
//            'AdvEngineID'                => 'UInt8',
//            'IsArtifical'                => 'UInt8',
            'WindowClientWidth'          => 'UInt16',
            'WindowClientHeight'         => 'UInt16',
            'ClientTimeZone'             => 'Int16',
            'ClientEventTime'            => 'DateTime',
//            'SilverlightVersion1'        => 'UInt8',
//            'SilverlightVersion2'        => 'UInt8',
//            'SilverlightVersion3'        => 'UInt32',
//            'SilverlightVersion4'        => 'UInt16',
            'PageCharset'                => 'String',
            'CodeVersion'                => 'UInt32',
//            'IsLink'                     => 'UInt8',
//            'IsDownload'                 => 'UInt8',
//            'IsNotBounce'                => 'UInt8',
//            'FUniqID'                    => 'UInt64',
//            'HID'                        => 'UInt32',
//            'IsOldCounter'               => 'UInt8',
//            'IsEvent'                    => 'UInt8',
//            'IsParameter'                => 'UInt8',
//            'DontCountHits'              => 'UInt8',
//            'WithHash'                   => 'UInt8',
//            'HitColor'                   => 'FixedString(1)',
            'UTCEventTime'               => 'DateTime',
//            'Age'                        => 'UInt8',
//            'Sex'                        => 'UInt8',
//            'Income'                     => 'UInt8',
//            'Interests'                  => 'UInt16',
//            'Robotness'                  => 'UInt8',
//            'GeneralInterests'           => 'Array(UInt16)',
//            'RemoteIP'                   => 'UInt32',
//            'RemoteIP6'                  => 'FixedString(16)',
//            'WindowName'                 => 'Int32',
//            'OpenerName'                 => 'Int32',
//            'HistoryLength'              => 'Int16',
            'BrowserLanguage'            => 'FixedString(2)',
            'BrowserCountry'             => 'FixedString(2)',
            'SocialNetwork'              => 'String',
            'SocialAction'               => 'String',
            'HTTPError'                  => 'UInt16',
            'SendTiming'                 => 'Int32',
            'DNSTiming'                  => 'Int32',
            'ConnectTiming'              => 'Int32',
            'ResponseStartTiming'        => 'Int32',
            'ResponseEndTiming'          => 'Int32',
            'FetchTiming'                => 'Int32',
            'RedirectTiming'             => 'Int32',
//            'DOMInteractiveTiming'       => 'Int32',
//            'DOMContentLoadedTiming'     => 'Int32',
//            'DOMCompleteTiming'          => 'Int32',
//            'LoadEventStartTiming'       => 'Int32',
//            'LoadEventEndTiming'         => 'Int32',
//            'NSToDOMContentLoadedTiming' => 'Int32',
//            'FirstPaintTiming'           => 'Int32',
//            'RedirectCount'              => 'Int8',
            'SocialSourceNetworkID'      => 'UInt8',
            'SocialSourcePage'           => 'String',
//            'ParamPrice'                 => 'Int64',
//            'ParamOrderID'               => 'String',
//            'ParamCurrency'              => 'FixedString(3)',
//            'ParamCurrencyID'            => 'UInt16',
            'GoalsReached'               => 'Array(UInt32)',
//            'OpenstatServiceName'        => 'String',
//            'OpenstatCampaignID'         => 'String',
//            'OpenstatAdID'               => 'String',
//            'OpenstatSourceID'           => 'String',
            'UTMSource'                  => 'String',
            'UTMMedium'                  => 'String',
            'UTMCampaign'                => 'String',
            'UTMContent'                 => 'String',
            'UTMTerm'                    => 'String',
//            'FromTag'                    => 'String',
//            'HasGCLID'                   => 'UInt8',
            'RefererHash'                => 'UInt64',
            'URLHash'                    => 'UInt64',
//            'CLID'                       => 'UInt32',
//            'YCLID'                      => 'UInt64',
//            'ShareService'               => 'String',
//            'ShareURL'                   => 'String',
//            'ShareTitle'                 => 'String',
            'IslandID'                   => 'FixedString(16)',
            'RequestNum'                 => 'UInt32',
            'RequestTry'                 => 'UInt8',
            //
            //            'CounterID'               => 'UInt32',
            'StartDate'               => 'Date',
            //            'Sign'                    => 'Int8',
            'IsNew'                   => 'UInt8',
            'VisitID'                 => 'UInt64',
            'StartTime'               => 'DateTime',
            'Duration'                => 'UInt32',
            'UTCStartTime'            => 'DateTime',
            'PageViews'               => 'Int32',
            'Hits'                    => 'Int32',
            //            'IsBounce'                => 'UInt8',
            'StartURL'                => 'String',
            'StartURLDomain'          => 'String',
            'EndURL'                  => 'String',
            'LinkURL'                 => 'String',
            //            'IsDownload'              => 'UInt8',
            'TrafficSourceID'          => 'Int8',
            //            'SearchEngineID'          => 'UInt16',
            'AdvEngineID'             => 'UInt8',
            'PlaceID'                 => 'Int32',
            'RefererRegions'          => 'Array(UInt32)',
            'IsYandex'                => 'UInt8',
            'GoalReachesDepth'        => 'Int32',
            'GoalReachesURL'          => 'Int32',
            'GoalReachesAny'          => 'Int32',
            'RemoteIP'                => 'UInt32',
            'RemoteIP6'               => 'FixedString(16)',
            'IPNetworkID'             => 'UInt32',
            'SilverlightVersion3'     => 'UInt32',
            'SilverlightVersion2'     => 'UInt8',
            'SilverlightVersion4'     => 'UInt16',
            'FlashVersion3'           => 'UInt16',
            'FlashVersion4'           => 'UInt16',
            'FlashMajor'              => 'UInt8',
            'FlashMinor'              => 'UInt8',
            'NetMajor'                => 'UInt8',
            'NetMinor'                => 'UInt8',
            'SilverlightVersion1'     => 'UInt8',
            'Age'                     => 'UInt8',
            'Sex'                     => 'UInt8',
            'Income'                  => 'UInt8',
            'JavaEnable'              => 'UInt8',
            'Interests'               => 'UInt16',
            'Robotness'               => 'UInt8',
            'GeneralInterests'        => 'Array(UInt16)',
            'Params'                  => 'Array(String)',
            'Goals'                   => 'Nested(
                ID UInt32,
                Serial UInt32,
                EventTime DateTime,
                Price Int64,
                OrderID String,
                CurrencyID UInt32)',
            'atchIDs'                 => 'Array(UInt64)',
            'ParamSumPrice'           => 'Int64',
            'ParamCurrency'           => 'FixedString(3)',
            'ParamCurrencyID'         => 'UInt16',
            'ClickLogID'              => 'UInt64',
            'ClickEventID'            => 'Int32',
            'ClickGoodEvent'          => 'Int32',
            'ClickEventTime'          => 'DateTime',
            'ClickPriorityID'         => 'Int32',
            'ClickPhraseID'           => 'Int32',
            'ClickPageID'             => 'Int32',
            'ClickPlaceID'            => 'Int32',
            'ClickTypeID'             => 'Int32',
            'ClickResourceID'         => 'Int32',
            'ClickCost'               => 'UInt32',
            'ClickClientIP'           => 'UInt32',
            'ClickDomainID'           => 'UInt32',
            'ClickURL'                => 'String',
            'ClickAttempt'            => 'UInt8',
            'ClickOrderID'            => 'UInt32',
            'ClickBannerID'           => 'UInt32',
            'ClickMarketCategoryID'   => 'UInt32',
            'ClickMarketPP'           => 'UInt32',
            'ClickMarketCategoryName' => 'String',
            'ClickMarketPPName'       => 'String',
            'ClickAWAPSCampaignName'  => 'String',
            'ClickPageName'           => 'String',
            'ClickTargetType'         => 'UInt16',
            'ClickTargetPhraseID'     => 'UInt64',
            'ClickContextType'        => 'UInt8',
            'ClickSelectType'         => 'Int8',
            'ClickOptions'            => 'String',
            'ClickGroupBannerID'      => 'Int32',
            'OpenstatServiceName'     => 'String',
            'OpenstatCampaignID'      => 'String',
            'OpenstatAdID'            => 'String',
            'OpenstatSourceID'        => 'String',
            'FromTag'                 => 'String',
            'HasGCLID'                => 'UInt8',
            'FirstVisit'              => 'DateTime',
            'PredLastVisit'           => 'Date',
            'LastVisit'               => 'Date',
            'TotalVisits'             => 'UInt32',
            'TraficSource'            => 'Nested(
                ID Int8,
                SearchEngineID UInt16,
                AdvEngineID UInt8,
                PlaceID UInt16,
                SocialSourceNetworkID UInt8,
                Domain String,
                SearchPhrase String,
                SocialSourcePage String)',
            'Attendance'              => 'FixedString(16)',
            'CLID'                    => 'UInt32',
            'YCLID'                   => 'UInt64',
            'NormalizedRefererHash'   => 'UInt64',
            'SearchPhraseHash'        => 'UInt64',
            'RefererDomainHash'       => 'UInt64',
            'NormalizedStartURLHash'  => 'UInt64',
            'StartURLDomainHash'      => 'UInt64',
            'NormalizedEndURLHash'    => 'UInt64',
            'TopLevelDomain'          => 'UInt64',
            'URLScheme'               => 'UInt64',
            'OpenstatServiceNameHash' => 'UInt64',
            'OpenstatCampaignIDHash'  => 'UInt64',
            'OpenstatAdIDHash'        => 'UInt64',
            'OpenstatSourceIDHash'    => 'UInt64',
            'UTMSourceHash'           => 'UInt64',
            'UTMMediumHash'           => 'UInt64',
            'UTMCampaignHash'         => 'UInt64',
            'UTMContentHash'          => 'UInt64',
            'UTMTermHash'             => 'UInt64',
            'FromHash'                => 'UInt64',
            'WebVisorEnabled'         => 'UInt8',
            'WebVisorActivity'        => 'UInt32',
            'ParsedParams'            => 'Nested(
                Key1 String,
                Key2 String,
                Key3 String,
                Key4 String,
                Key5 String,
                ValueDouble Float64)',
            'Market'                  => 'Nested(
                Type UInt8,
                GoalID UInt32,
                OrderID String,
                OrderPrice Int64,
                PP UInt32,
                DirectPlaceID UInt32,
                DirectOrderID UInt32,
                DirectBannerID UInt32,
                GoodID String,
                GoodName String,
                GoodQuantity Int32,
                GoodPrice Int64)',
        ];

        $hits_engine = "ENGINE = MergeTree()
                        PARTITION BY toYYYYMM(EventDate)
                        ORDER BY (CounterID, EventDate, intHash32(UserID))
                        SAMPLE BY intHash32(UserID)
                        SETTINGS index_granularity = 8192";

        $visits_engine = "ENGINE = CollapsingMergeTree(Sign)
                        PARTITION BY toYYYYMM(StartDate)
                        ORDER BY (CounterID, StartDate, intHash32(UserID), VisitID)
                        SAMPLE BY intHash32(UserID)
                        SETTINGS index_granularity = 8192";

//        Clickhouse::createTableIfNotExists($this->table_name, $engine, $columns);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Clickhouse::dropTableIfExists($this->table_name);
    }
}
