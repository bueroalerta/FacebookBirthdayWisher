<?php
require_once 'vendor/autoload.php';
require_once 'config.php';

require_once 'FacebookGateway.php';

$fb_gateway = new FacebookGateway();
if($fb_gateway->getUser() == 0)
{
    echo "<a href=\"{$fb_gateway->getLoginUrl()}\">Login</a>";
}
else
{
    echo "<a href=\"{$fb_gateway->getLogoutUrl()}\">Logout</a>";
    // $friends = $fb_gateway->getFriends();
    $friends = $fb_gateway->getTodayBirthdays();
    foreach ($friends as $friend)
    {
        var_dump($friend);
        echo '<a target="_blank" href="http://facebook.com/'.$friend['uid'].'">Link to Facebook Profile</a>';
    }
}
