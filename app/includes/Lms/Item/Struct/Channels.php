<?php
if (false) {
    /**
    * @method integer getChannelId()
    * @method Lms_Item_Channel setChannelId(integer $value)
    * @method string getName()
    * @method Lms_Item_Channel setName(string $value)
    * @method string getNameEpg()
    * @method Lms_Item_Channel setNameEpg(string $value)
    * @method string getIcon()
    * @method Lms_Item_Channel setIcon(string $value)
    * @method string getDescription()
    * @method Lms_Item_Channel setDescription(string $value)
    * @method integer getGenreId()
    * @method Lms_Item_Channel setGenreId(integer $value)
    * @method float getRank()
    * @method Lms_Item_Channel setRank(float $value)
    * @method float getRankCurrent()
    * @method Lms_Item_Channel setRankCurrent(float $value)
    * @method string getPlaylistPriority()
    * @method Lms_Item_Channel setPlaylistPriority(string $value)
    * @method string getStreamType()
    * @method Lms_Item_Channel setStreamType(string $value)
    * @method string getStreamUrl()
    * @method Lms_Item_Channel setStreamUrl(string $value)
    * @method string getStreamHqUrl()
    * @method Lms_Item_Channel setStreamHqUrl(string $value)
    * @method string getDvrUrl()
    * @method Lms_Item_Channel setDvrUrl(string $value)
    * @method string getAspect()
    * @method Lms_Item_Channel setAspect(string $value)
    * @method string getDvr()
    * @method Lms_Item_Channel setDvr(string $value)
    * @method integer getWorldwide()
    * @method Lms_Item_Channel setWorldwide(integer $value)
    * @method integer getWeb()
    * @method Lms_Item_Channel setWeb(integer $value)
    * @method string getExternal()
    * @method Lms_Item_Channel setExternal(string $value)
    * @method integer getFree()
    * @method Lms_Item_Channel setFree(integer $value)
    * @method integer getListed()
    * @method Lms_Item_Channel setListed(integer $value)
    * @method integer getActive()
    * @method Lms_Item_Channel setActive(integer $value)
    * @method string getAgeRating()
    * @method Lms_Item_Channel setAgeRating(string $value)
    * @method string getSeoTitle()
    * @method Lms_Item_Channel setSeoTitle(string $value)
    * @method string getSeoKeywords()
    * @method Lms_Item_Channel setSeoKeywords(string $value)
    * @method string getSeoDescription()
    * @method Lms_Item_Channel setSeoDescription(string $value)
    * @method string getEpgSource()
    * @method Lms_Item_Channel setEpgSource(string $value)
    * @method string getEpgId()
    * @method Lms_Item_Channel setEpgId(string $value)
    * @method integer getStatChannelId()
    * @method Lms_Item_Channel setStatChannelId(integer $value)
    */
    class Lms_Item_Channel {}
}
class Lms_Item_Struct_Channels
{
    static public function getColumns()
    {
        return array (
  'channel_id' => 
  array (
    'type' => 'int(11)',
    'null' => 'NO',
    'key' => 'PRI',
    'default' => NULL,
    'extra' => '',
    'escape' => 2,
  ),
  'name' => 
  array (
    'type' => 'varchar(100)',
    'null' => 'NO',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'name_epg' => 
  array (
    'type' => 'varchar(100)',
    'null' => 'NO',
    'key' => '',
    'default' => '',
    'extra' => '',
    'escape' => 8,
  ),
  'icon' => 
  array (
    'type' => 'varchar(255)',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'description' => 
  array (
    'type' => 'varchar(1000)',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'genre_id' => 
  array (
    'type' => 'int(11)',
    'null' => 'YES',
    'key' => 'MUL',
    'default' => NULL,
    'extra' => '',
    'escape' => 2,
  ),
  'rank' => 
  array (
    'type' => 'float',
    'null' => 'NO',
    'key' => '',
    'default' => '0',
    'extra' => '',
    'escape' => 4,
  ),
  'rank_current' => 
  array (
    'type' => 'float',
    'null' => 'NO',
    'key' => '',
    'default' => '0',
    'extra' => '',
    'escape' => 4,
  ),
  'playlist_priority' => 
  array (
    'type' => 'smallint(6)',
    'null' => 'NO',
    'key' => '',
    'default' => '20',
    'extra' => '',
    'escape' => 8,
  ),
  'stream_type' => 
  array (
    'type' => 'varchar(50)',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'stream_url' => 
  array (
    'type' => 'varchar(100)',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'stream_hq_url' => 
  array (
    'type' => 'varchar(255)',
    'null' => 'NO',
    'key' => '',
    'default' => '',
    'extra' => '',
    'escape' => 8,
  ),
  'dvr_url' => 
  array (
    'type' => 'varchar(255)',
    'null' => 'NO',
    'key' => '',
    'default' => '',
    'extra' => '',
    'escape' => 8,
  ),
  'aspect' => 
  array (
    'type' => 'varchar(5)',
    'null' => 'NO',
    'key' => '',
    'default' => '4:3',
    'extra' => '',
    'escape' => 8,
  ),
  'dvr' => 
  array (
    'type' => 'time',
    'null' => 'NO',
    'key' => '',
    'default' => '00:00:00',
    'extra' => '',
    'escape' => 8,
  ),
  'worldwide' => 
  array (
    'type' => 'tinyint(4)',
    'null' => 'NO',
    'key' => '',
    'default' => '1',
    'extra' => '',
    'escape' => 2,
  ),
  'web' => 
  array (
    'type' => 'tinyint(4)',
    'null' => 'NO',
    'key' => '',
    'default' => '1',
    'extra' => '',
    'escape' => 2,
  ),
  'external' => 
  array (
    'type' => 'text',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'free' => 
  array (
    'type' => 'tinyint(4)',
    'null' => 'NO',
    'key' => '',
    'default' => '0',
    'extra' => '',
    'escape' => 2,
  ),
  'listed' => 
  array (
    'type' => 'tinyint(4)',
    'null' => 'NO',
    'key' => '',
    'default' => '1',
    'extra' => '',
    'escape' => 2,
  ),
  'active' => 
  array (
    'type' => 'tinyint(3) unsigned',
    'null' => 'NO',
    'key' => '',
    'default' => '0',
    'extra' => '',
    'escape' => 2,
  ),
  'age_rating' => 
  array (
    'type' => 'enum(\'0+\',\'6+\',\'12+\',\'16+\',\'18+\')',
    'null' => 'NO',
    'key' => '',
    'default' => '0+',
    'extra' => '',
    'escape' => 8,
  ),
  'seo_title' => 
  array (
    'type' => 'varchar(255)',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'seo_keywords' => 
  array (
    'type' => 'text',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'seo_description' => 
  array (
    'type' => 'text',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'epg_source' => 
  array (
    'type' => 'varchar(255)',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'epg_id' => 
  array (
    'type' => 'varchar(255)',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'stat_channel_id' => 
  array (
    'type' => 'int(11)',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 2,
  ),
);
    }
}