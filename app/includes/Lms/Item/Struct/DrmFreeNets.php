<?php
if (false) {
    /**
    * @method integer getDrmFreeNetId()
    * @method Lms_Item_DrmFreeNet setDrmFreeNetId(integer $value)
    * @method string getName()
    * @method Lms_Item_DrmFreeNet setName(string $value)
    * @method string getNets()
    * @method Lms_Item_DrmFreeNet setNets(string $value)
    * @method integer getActive()
    * @method Lms_Item_DrmFreeNet setActive(integer $value)
    */
    class Lms_Item_DrmFreeNet {}
}
class Lms_Item_Struct_DrmFreeNets
{
    static public function getColumns()
    {
        return array (
  'drm_free_net_id' => 
  array (
    'type' => 'int(11)',
    'null' => 'NO',
    'key' => 'PRI',
    'default' => NULL,
    'extra' => 'auto_increment',
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
  'nets' => 
  array (
    'type' => 'text',
    'null' => 'NO',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'active' => 
  array (
    'type' => 'tinyint(4)',
    'null' => 'NO',
    'key' => '',
    'default' => '1',
    'extra' => '',
    'escape' => 2,
  ),
);
    }
}