<?php
/**
 * This class manages fetching and pushing data from & to Facebook
 * It acts as simple interface for FB data
 * @author Alex Kolarski <aleks.rk@gmail.com>
 */

class FacebookGateway
{
    /**
     * Holds referance to Facebook Connection Object
     * @var Facebook
     */
    private $fbConnection = null;

    /**
     * Initialize Facebook Connection Object
     * @param Facebook $fbConnection (optional) Predefined connection object
     */
    public function __construct($fbConnection = null)
    {
        if($fbConnection == null)
        {
            global $fb_config;
            $this->fbConnection = new Facebook($fb_config);
        }
        else
        {
            $this->fbConnection = $fbConnection;
        }
    }

    /**
     * Fetches all your friend birthdays from FB
     * @return array List of your friends birthdays
     */
    public function getFriendsBirthdays()
    {
        $birthdays = array();
        return $birthdays;
    }

    // public function getFriends()
    // {
    //     $all_friends = array();
    //     $friends = $this->fbConnection->api('/me/friends?fields=name,birthday');
    //     $all_friends = array_merge($all_friends, $friends['data']);

    //     while(count($friends['data']) > 0);
    //     {
    //         $friends = $this->fbConnection->api($friends['paging']['next']);
    //         var_dump($friends);
    //         exit;
    //     //     array_merge($all_friends, $friends['data']);
    //     }
    //     return $friends;
    // }
    public function getFriends()
    {
        $friends = $this->fbConnection->api(array(
            'method' => 'fql.query',
            'query'  => 'select uid, name, birthday_date from user where uid in (select uid2 from friend where uid1=me())'
        ));
        return $friends;
    }

    public function getTodayBirthdays()
    {
        $this_month = array();
        $today      = array();
        $m = date('m', time());
        $d = date('d', time());
        $friends = $this->getFriends();
        foreach ($friends as $friend)
        {
            if ($friend['birthday_date'] != null)
            {
                $bday = explode('/', $friend['birthday_date']);
                if ($bday[0] == $m && $bday[1] >= $d)
                {
                    $this_month[] = $friend;
                    if ($bday[1] == $d)
                    {
                        $today[] = $friend;
                    }
                }
            }   
        }
        // var_dump($this_month);
        // var_dump($today);
        return $today;
    }

    /**
     * Returns Login Url with permissions to fetch friend birthdays
     * @return string LoginUrl
     */
    public function getLoginUrl()
    {
        return $this->fbConnection->getLoginUrl(array(
            'scope' => array('friends_birthday'),
            'display' => 'popup'
        ));
    }

    public function getLogoutUrl()
    {
        return $this->fbConnection->getLogoutUrl();
    }
    public function getUser()
    {
        return $this->fbConnection->getUser();
    }
}