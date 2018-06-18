<?php
if (false) {
    /**
    * @method string getTvshowId()
    * @method Lms_Item_Tvshow setTvshowId(string $value)
    * @method integer getChannelId()
    * @method Lms_Item_Tvshow setChannelId(integer $value)
    * @method string getTitle()
    * @method Lms_Item_Tvshow setTitle(string $value)
    * @method string getTitleGroup()
    * @method Lms_Item_Tvshow setTitleGroup(string $value)
    * @method string getDate()
    * @method Lms_Item_Tvshow setDate(string $value)
    * @method string getStart()
    * @method Lms_Item_Tvshow setStart(string $value)
    * @method string getStop()
    * @method Lms_Item_Tvshow setStop(string $value)
    * @method integer getMovieId()
    * @method Lms_Item_Tvshow setMovieId(integer $value)
    * @method integer getArchived()
    * @method Lms_Item_Tvshow setArchived(integer $value)
    * @method integer getUnique()
    * @method Lms_Item_Tvshow setUnique(integer $value)
    * @method integer getVod()
    * @method Lms_Item_Tvshow setVod(integer $value)
    * @method string getParsedAt()
    * @method Lms_Item_Tvshow setParsedAt(string $value)
    */
    class Lms_Item_Tvshow {}
}
class Lms_Item_Struct_Tvshows
{
    static public function getColumns()
    {
        return array (
  'tvshow_id' => 
  array (
    'type' => 'varchar(32)',
    'null' => 'NO',
    'key' => 'PRI',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'channel_id' => 
  array (
    'type' => 'int(11)',
    'null' => 'NO',
    'key' => 'MUL',
    'default' => NULL,
    'extra' => '',
    'escape' => 2,
  ),
  'title' => 
  array (
    'type' => 'varchar(255)',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'title_group' => 
  array (
    'type' => 'varchar(255)',
    'null' => 'YES',
    'key' => 'MUL',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'date' => 
  array (
    'type' => 'date',
    'null' => 'NO',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'start' => 
  array (
    'type' => 'datetime',
    'null' => 'NO',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'stop' => 
  array (
    'type' => 'datetime',
    'null' => 'NO',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'movie_id' => 
  array (
    'type' => 'int(11)',
    'null' => 'NO',
    'key' => 'MUL',
    'default' => NULL,
    'extra' => '',
    'escape' => 2,
  ),
  'archived' => 
  array (
    'type' => 'tinyint(4)',
    'null' => 'NO',
    'key' => '',
    'default' => '0',
    'extra' => '',
    'escape' => 2,
  ),
  'unique' => 
  array (
    'type' => 'tinyint(4)',
    'null' => 'NO',
    'key' => 'MUL',
    'default' => '0',
    'extra' => '',
    'escape' => 2,
  ),
  'vod' => 
  array (
    'type' => 'tinyint(4)',
    'null' => 'NO',
    'key' => '',
    'default' => '1',
    'extra' => '',
    'escape' => 2,
  ),
  'parsed_at' => 
  array (
    'type' => 'timestamp',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
);
    }
}