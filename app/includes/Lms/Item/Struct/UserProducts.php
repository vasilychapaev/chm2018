<?php
if (false) {
    /**
    * @method integer getUserProductId()
    * @method Lms_Item_UserProduct setUserProductId(integer $value)
    * @method integer getUserId()
    * @method Lms_Item_UserProduct setUserId(integer $value)
    * @method integer getProductOptionId()
    * @method Lms_Item_UserProduct setProductOptionId(integer $value)
    * @method integer getInvoiceId()
    * @method Lms_Item_UserProduct setInvoiceId(integer $value)
    * @method string getCreatedAt()
    * @method Lms_Item_UserProduct setCreatedAt(string $value)
    * @method string getStartedAt()
    * @method Lms_Item_UserProduct setStartedAt(string $value)
    * @method string getExpiredAt()
    * @method Lms_Item_UserProduct setExpiredAt(string $value)
    * @method integer getCount()
    * @method Lms_Item_UserProduct setCount(integer $value)
    * @method string getCost()
    * @method Lms_Item_UserProduct setCost(string $value)
    * @method integer getActive()
    * @method Lms_Item_UserProduct setActive(integer $value)
    */
    class Lms_Item_UserProduct {}
}
class Lms_Item_Struct_UserProducts
{
    static public function getColumns()
    {
        return array (
  'user_product_id' => 
  array (
    'type' => 'int(11)',
    'null' => 'NO',
    'key' => 'PRI',
    'default' => NULL,
    'extra' => 'auto_increment',
    'escape' => 2,
  ),
  'user_id' => 
  array (
    'type' => 'int(11)',
    'null' => 'NO',
    'key' => 'MUL',
    'default' => NULL,
    'extra' => '',
    'escape' => 2,
  ),
  'product_option_id' => 
  array (
    'type' => 'int(11)',
    'null' => 'NO',
    'key' => 'MUL',
    'default' => NULL,
    'extra' => '',
    'escape' => 2,
  ),
  'invoice_id' => 
  array (
    'type' => 'int(11)',
    'null' => 'YES',
    'key' => 'MUL',
    'default' => NULL,
    'extra' => '',
    'escape' => 2,
  ),
  'created_at' => 
  array (
    'type' => 'datetime',
    'null' => 'NO',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'started_at' => 
  array (
    'type' => 'datetime',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'expired_at' => 
  array (
    'type' => 'datetime',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'count' => 
  array (
    'type' => 'int(11)',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 2,
  ),
  'cost' => 
  array (
    'type' => 'decimal(18,2)',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'active' => 
  array (
    'type' => 'tinyint(3) unsigned',
    'null' => 'NO',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 2,
  ),
);
    }
}