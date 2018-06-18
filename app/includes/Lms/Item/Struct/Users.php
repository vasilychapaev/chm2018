<?php
if (false) {
    /**
    * @method integer getUserId()
    * @method Lms_Item_User setUserId(integer $value)
    * @method string getUserName()
    * @method Lms_Item_User setUserName(string $value)
    * @method string getPasswordHash()
    * @method Lms_Item_User setPasswordHash(string $value)
    * @method string getSalt()
    * @method Lms_Item_User setSalt(string $value)
    * @method string getEmail()
    * @method Lms_Item_User setEmail(string $value)
    * @method string getPhoneNumber()
    * @method Lms_Item_User setPhoneNumber(string $value)
    * @method string getIp()
    * @method Lms_Item_User setIp(string $value)
    * @method string getCountry()
    * @method Lms_Item_User setCountry(string $value)
    * @method string getFullName()
    * @method Lms_Item_User setFullName(string $value)
    * @method string getAbout()
    * @method Lms_Item_User setAbout(string $value)
    * @method integer getYear()
    * @method Lms_Item_User setYear(integer $value)
    * @method string getGender()
    * @method Lms_Item_User setGender(string $value)
    * @method integer getUserGroup()
    * @method Lms_Item_User setUserGroup(integer $value)
    * @method string getRegisterAt()
    * @method Lms_Item_User setRegisterAt(string $value)
    * @method integer getEmailVerified()
    * @method Lms_Item_User setEmailVerified(integer $value)
    * @method string getResetPasswordAt()
    * @method Lms_Item_User setResetPasswordAt(string $value)
    * @method string getVerificationToken()
    * @method Lms_Item_User setVerificationToken(string $value)
    * @method string getSourceHost()
    * @method Lms_Item_User setSourceHost(string $value)
    * @method string getPassCode()
    * @method Lms_Item_User setPassCode(string $value)
    * @method string getPin()
    * @method Lms_Item_User setPin(string $value)
    * @method integer getAgentId()
    * @method Lms_Item_User setAgentId(integer $value)
    * @method integer getAgentProgramId()
    * @method Lms_Item_User setAgentProgramId(integer $value)
    * @method string getAgentRegisterAt()
    * @method Lms_Item_User setAgentRegisterAt(string $value)
    * @method integer getAgentHits()
    * @method Lms_Item_User setAgentHits(integer $value)
    * @method string getAgentRest()
    * @method Lms_Item_User setAgentRest(string $value)
    * @method string getQuality()
    * @method Lms_Item_User setQuality(string $value)
    * @method string getComment()
    * @method Lms_Item_User setComment(string $value)
    * @method string getLocation()
    * @method Lms_Item_User setLocation(string $value)
    * @method integer getTrial()
    * @method Lms_Item_User setTrial(integer $value)
    * @method integer getIpSynced()
    * @method Lms_Item_User setIpSynced(integer $value)
    * @method integer getDrm()
    * @method Lms_Item_User setDrm(integer $value)
    * @method string getUserKey()
    * @method Lms_Item_User setUserKey(string $value)
    * @method string getCurrency()
    * @method Lms_Item_User setCurrency(string $value)
    * @method string getBalans()
    * @method Lms_Item_User setBalans(string $value)
    * @method integer getEmailSubscribtion()
    * @method Lms_Item_User setEmailSubscribtion(integer $value)
    */
    class Lms_Item_User {}
}
class Lms_Item_Struct_Users
{
    static public function getColumns()
    {
        return array (
  'user_id' => 
  array (
    'type' => 'int(11)',
    'null' => 'NO',
    'key' => 'PRI',
    'default' => NULL,
    'extra' => 'auto_increment',
    'escape' => 2,
  ),
  'user_name' => 
  array (
    'type' => 'varchar(64)',
    'null' => 'NO',
    'key' => 'MUL',
    'default' => '',
    'extra' => '',
    'escape' => 8,
  ),
  'password_hash' => 
  array (
    'type' => 'varchar(40)',
    'null' => 'NO',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'salt' => 
  array (
    'type' => 'varchar(8)',
    'null' => 'NO',
    'key' => '',
    'default' => '',
    'extra' => '',
    'escape' => 8,
  ),
  'email' => 
  array (
    'type' => 'varchar(255)',
    'null' => 'NO',
    'key' => 'UNI',
    'default' => '',
    'extra' => '',
    'escape' => 8,
  ),
  'phone_number' => 
  array (
    'type' => 'varchar(20)',
    'null' => 'YES',
    'key' => 'UNI',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'ip' => 
  array (
    'type' => 'varchar(15)',
    'null' => 'NO',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'country' => 
  array (
    'type' => 'varchar(4)',
    'null' => 'YES',
    'key' => 'MUL',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'full_name' => 
  array (
    'type' => 'varchar(100)',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'about' => 
  array (
    'type' => 'text',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'year' => 
  array (
    'type' => 'int(11)',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 2,
  ),
  'gender' => 
  array (
    'type' => 'char(1)',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'user_group' => 
  array (
    'type' => 'tinyint(3) unsigned',
    'null' => 'NO',
    'key' => '',
    'default' => '0',
    'extra' => '',
    'escape' => 2,
  ),
  'register_at' => 
  array (
    'type' => 'datetime',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'email_verified' => 
  array (
    'type' => 'tinyint(3) unsigned',
    'null' => 'NO',
    'key' => '',
    'default' => '0',
    'extra' => '',
    'escape' => 2,
  ),
  'reset_password_at' => 
  array (
    'type' => 'datetime',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'verification_token' => 
  array (
    'type' => 'varchar(50)',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'source_host' => 
  array (
    'type' => 'varchar(255)',
    'null' => 'YES',
    'key' => '',
    'default' => '',
    'extra' => '',
    'escape' => 8,
  ),
  'pass_code' => 
  array (
    'type' => 'varchar(4)',
    'null' => 'NO',
    'key' => '',
    'default' => '0000',
    'extra' => '',
    'escape' => 8,
  ),
  'pin' => 
  array (
    'type' => 'varchar(4)',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'agent_id' => 
  array (
    'type' => 'int(11)',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 2,
  ),
  'agent_program_id' => 
  array (
    'type' => 'int(11)',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 2,
  ),
  'agent_register_at' => 
  array (
    'type' => 'datetime',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'agent_hits' => 
  array (
    'type' => 'int(11)',
    'null' => 'NO',
    'key' => '',
    'default' => '0',
    'extra' => '',
    'escape' => 2,
  ),
  'agent_rest' => 
  array (
    'type' => 'decimal(9,2)',
    'null' => 'NO',
    'key' => '',
    'default' => '0.00',
    'extra' => '',
    'escape' => 8,
  ),
  'quality' => 
  array (
    'type' => 'enum(\'\',\'hq\')',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'comment' => 
  array (
    'type' => 'text',
    'null' => 'YES',
    'key' => '',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'location' => 
  array (
    'type' => 'enum(\'default\',\'niks\')',
    'null' => 'YES',
    'key' => 'MUL',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'trial' => 
  array (
    'type' => 'tinyint(4)',
    'null' => 'NO',
    'key' => '',
    'default' => '0',
    'extra' => '',
    'escape' => 2,
  ),
  'ip_synced' => 
  array (
    'type' => 'tinyint(4)',
    'null' => 'NO',
    'key' => '',
    'default' => '0',
    'extra' => '',
    'escape' => 2,
  ),
  'drm' => 
  array (
    'type' => 'tinyint(4)',
    'null' => 'NO',
    'key' => '',
    'default' => '0',
    'extra' => '',
    'escape' => 2,
  ),
  'user_key' => 
  array (
    'type' => 'varchar(32)',
    'null' => 'YES',
    'key' => 'MUL',
    'default' => NULL,
    'extra' => '',
    'escape' => 8,
  ),
  'currency' => 
  array (
    'type' => 'enum(\'BYN\',\'RUB\',\'EUR\')',
    'null' => 'NO',
    'key' => '',
    'default' => 'BYN',
    'extra' => '',
    'escape' => 8,
  ),
  'balans' => 
  array (
    'type' => 'decimal(9,2)',
    'null' => 'NO',
    'key' => '',
    'default' => '0.00',
    'extra' => '',
    'escape' => 8,
  ),
  'email_subscribtion' => 
  array (
    'type' => 'tinyint(3) unsigned',
    'null' => 'NO',
    'key' => '',
    'default' => '0',
    'extra' => '',
    'escape' => 2,
  ),
);
    }
}