<?php
/**
 * @copyright 2006-2011 LanMediaService, Ltd.
 * @license    http://www.lanmediaservice.com/license/1_0.txt
 * @author Ilya Spesivtsev <macondos@gmail.com>
 * @version $Id: User.php 700 2011-06-10 08:40:53Z macondos $
 */

class Lms_User
{
    private static $_acl;

    private static $_identified = null;

    /**
     * @var Lms_Item_User
     */
    private static $_userInstance;
   
    public static function getUser()
    {
        if (!self::$_userInstance) {
            self::$_userInstance = Lms_Item::create('User');
            self::initUserInstance();
        }
        if (self::$_identified===null) {
            self::authenticate();
            self::$_identified = true;
        }
        return self::$_userInstance;
    }
    
    public static function setUser($userIdOrItem)
    {
        if ($userIdOrItem instanceof Lms_Item_User) {
            $user = $userIdOrItem;
        } else {
            $user = Lms_Item::create('User', $userIdOrItem);
        }
        self::$_userInstance = $user;
        self::initUserInstance();
    }

    
    public static function authenticate()
    {
        if (self::$_userInstance) {
            $authToken = Lms_Application::loadAuthToken();
            if ($authToken) {
                $userId = Lms_Item_AuthToken::getUserIdByAuthToken($authToken);
                $unet = Lms_Item_Unet::getByIp();
                if ($unet && $unet->getStatus() && ($unet->getId() !== $userId)) {
                    Lms_Item::create('AuthToken', $authToken)->delete();
                    return;
                }
                self::$_userInstance->loadFromDbByUserId($userId);
            }
        }
    }

    public static function setAcl($acl)
    {
        self::$_acl = $acl;
    }
    
    private static function initUserInstance()
    {
        self::$_userInstance->setAcl(self::$_acl);
    }
}
