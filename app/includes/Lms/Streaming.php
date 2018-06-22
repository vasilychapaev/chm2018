<?php

class Lms_Streaming {

    public static function getSecureUrlParams($mediapath, $userId, $streams = 0, $lifeTimeSeconds = 86400) {

        $starttime = time();
        $endtime = $starttime + $lifeTimeSeconds;
        $wowzaParams = array(
            'secureendtime=' . $endtime,
            'securestarttime=' . $starttime,
            Lms_Application::getConfig('edge', 'shared_secret'),
            'secureuserid=' . $userId,
        );
        if (!empty($streams)) {
            $wowzaParams[] = 'securestreams=' . $streams;
        }
        sort($wowzaParams);
        $stringToHash = $mediapath . '?' . implode('&', $wowzaParams);
        $secureHash = base64_encode(hash('sha256', $stringToHash, true));
        $secureHash = str_replace(array('+', '/'), array('-', '_'), $secureHash);


        $url = sprintf(
            "securehash=%s&secureendtime=%d&securestarttime=%d&secureuserid=%s",
            rawurlencode($secureHash),
            $endtime,
            $starttime,
            $userId
        );
        if (!empty($streams)) {
            $url .= '&securestreams=' . $streams;
        }

        return $url;
    }

    public static function addSecureHash($url, $userId, $concurrentStreams) {


        $url = str_replace('#securehash', "", $url);
        if (strpos($url, "?")===false) {
            $url .= '?';
        } else {
            $url .= '&';
        }

        $mediapath = parse_url($url, PHP_URL_PATH);
        $mediapath = preg_replace('{/[^/]+m3u8?$}', "", $mediapath);
        $mediapath = trim($mediapath, '/');

        $secureparams =  self::getSecureUrlParams($mediapath, $userId, $concurrentStreams);

        $url .= $secureparams;

        return $url;
    }

    /**
     * @param Lms_Item_Channel $channel
     * @param string $bitrate
     * @param null $device
     * @return mixed|string
     */
    public static function getChannelStreamUrl($channel, $bitrate='auto', $device = null)
    {
        $user = Lms_User::getUser();
        $concurrentStreams = $user->getChannelConcurrentStreams($channel->getId(), null);
//        if (!$channel->getFree() && !Lms_Application::isDrmFree() && !$concurrentStreams) {
//            return false;
//        }
        $paidUser = (count($user->getActiveUserProducts()) > 0) || Lms_Application::isDrmFree();
        if ($user->getQuality()=='hq' && $channel->getStreamHqUrl() && in_array($device, Lms_Application::getConfig('stream_hq_devices'))) {
            $mediapath = $channel->getStreamHqUrl();
        } else {
            $mediapath = $channel->getStreamUrl();
        }

        $userId = $user->getId()?: self::getRandomUserId();
        $concurrentStreams = Lms_Application::isDrmFree()? 5 : $concurrentStreams;

        $resource = [
            "type" => "channel",
            "id" => $channel->getStatChannelId()?: $channel->getId(),
        ];

        if (preg_match('{^\w+://}', $mediapath)) {
            return self::processUrl($mediapath, $user, $userId, $concurrentStreams, $device, $resource);
        }

        $mediapath = trim($mediapath, '/');

        $streamUrl = $mediapath . "/playlist.m3u8";

        $streamUrl .= '?' . self::getSecureUrlParams($mediapath, $userId, $concurrentStreams);

        $streamUrl .= '&UserID=' . $userId;
        if ($device) {
            $streamUrl .= '&device_code=' . $device;
        }

        // $net = Lms_Application::getNearestLocation();
        // if (in_array($net, ['foreign'])) {
        //     $net .= '-' . ($channel->getFree()? 'free' : 'default');
        // } else if (in_array($net, ['s-unet'])) {
        //     $net = $net;//#PER-1256
        // } else {
        //     if ($paidUser) {
        //         $freeNet = 'free';
        //     } else {
        //         $freeNet = 'free2';
        //     }
        //     if (preg_match('{btc}', $net)) {
        //         if ($paidUser) {
        //             $net = 'btc1';
        //         } else {
        //             $net = 'btc2';
        //         }
        //     }
        //     $net = $channel->getFree()? $freeNet : $net;
        // }
        $streamUrl .= "&r=" . urlencode(Zend_Json::encode($resource));

        $streamUrl = "https://free.persik.tv/" . $streamUrl;

        if (!$channel->getFree()) {
            $streamUrl = str_replace("persik.tv", "persik.by", $streamUrl);
        }

        return $streamUrl;
    }

    /**
     * @param Lms_Item_Channel $channel
     * @param Lms_Item_User $user
     * @param string|null $bitrate
     * @return string
     */
    public static function getChannelStbProxyUrl($channel, $user, $bitrate = null)
    {
        $host = !$channel->getFree()? "persik.by" : "persik.tv";
        $proxyUrl = sprintf('http://%s/stream/%d/%d/%d%s.m3u8', $host, $user->getPin(), $user->getId(), $channel->getId(), $bitrate? "_$bitrate" : '');
        return $proxyUrl;
    }

    /**
     * @param Lms_Item_Channel $channel
     * @param Lms_Item_User $user
     * @param null $bitrate
     * @param bool $disableUserKey
     * @return mixed|string
     */
    public static function getChannelStbUrl($channel, $user = null, $bitrate = null, $disableUserKey = false)
    {
        $device = 'playlist';
        $userId = $user->getId()?: self::getRandomUserId();

        if ($user->getQuality()=='hq' && $channel->getStreamHqUrl() && in_array($device, Lms_Application::getConfig('stream_hq_devices'))) {
            $mediapath = $channel->getStreamHqUrl();
        } else {
            $mediapath = $channel->getStreamUrl();
        }

        $concurrentStreams = $user? $user->getChannelConcurrentStreams($channel->getId(), null) : 0;
        $concurrentStreams = Lms_Application::isDrmFree()? 5 : $concurrentStreams;

        $resource = [
            "type" => "channel",
            "id" => $channel->getStatChannelId()?: $channel->getId(),
        ];

        if (preg_match('{^\w+://}', $mediapath)) {
            return self::processUrl($mediapath, $user, $userId, $concurrentStreams, $device, $resource);
        }

        $mediapath = trim($mediapath, '/');

        if ($bitrate) {
            $mediapath = $mediapath . '_' . $bitrate;
        }

        $streamUrl = $mediapath . "/playlist.m3u8";



        $streamUrl .= '?' . self::getSecureUrlParams($mediapath, $userId,  $concurrentStreams);

        $streamUrl .= "&UserID=$userId";
        $streamUrl .= "&device_code=$device";

        $net = Lms_Application::getNearestLocation();
        if (in_array($net, ['foreign'])) {
            $net .= '-' . ($channel->getFree() ? 'free' : 'default');
        } else if (in_array($net, ['s-unet'])) {
            $net = $net;//#PER-1256
        } else {
            if (rand(0, 1000)>500) {
                $freeNet = 'free';
            } else {
                $freeNet = 'free2';
            }
            $net = $channel->getFree()? $freeNet : $net;
        }
        $streamUrl .= "&r=" . urlencode(Zend_Json::encode($resource));


        $streamUrl = "http://$net.persik.tv:82/" . $streamUrl;

        if (!$channel->getFree()) {
            $streamUrl = str_replace("persik.tv", "persik.by", $streamUrl);
        }

        return $streamUrl;
    }

    /**
     * @param Lms_Item_Channel $channel
     * @param Lms_Item_Tvshow $tvshow
     * @param string|null $bitrate
     * @param string|null $device
     * @return bool|mixed|string
     */
    public static function getDvrStreamUrl($channel, $tvshow, $bitrate = null, $device = null)
    {
        $user = Lms_User::getUser();

        if (!$channel->getFree() && !$user->getId() && !Lms_Application::isDrmFree()) {
            return false;
        }

        $userId = $user->getId()?: self::getRandomUserId();
        $channelUrl = $channel->getDvrUrl()?: $channel->getStreamUrl();
        if (preg_match('{^/dvr}', $channelUrl)) {
            $startTime = strtotime($tvshow->getStart());
            $duration = $tvshow->getDuration();
            $streamUrl = preg_replace('{^/dvr/}i', 'https://dvr.persik.by/dvr/', $channelUrl);
            $streamUrl .= sprintf('/dvr.m3u8?s=%s&d=%s&UserID=%s',
                $startTime,
                $duration,
                $userId
            );
        } else {
            $startTime = strtotime($tvshow->getStart()) * 1000;
            $duration = $tvshow->getDuration() * 1000;
            $streamUrl = preg_replace('{/live/}i', 'http://ed270.persik.by:8086/dvr/', $channelUrl);
            $streamUrl .= sprintf('_1200/playlist.m3u8?start=%s&duration=%s&UserID=%s',
                $startTime,
                $duration,
                $userId
            );
        }
        if ($device) {
            $streamUrl .= '&device_code=' . $device;
        }
        $resource = [
            "type" => "tvshow",
            "id" => $tvshow->getId(),
        ];
        $streamUrl .= "&r=" . urlencode(Zend_Json::encode($resource));

        return $streamUrl;
    }

    /**
     * @param Lms_Item_Movie $movie
     * @param string|null $bitrate
     * @param string|null $device
     * @return bool|string
     */
    public static function getMovieStreamUrl($movie, $bitrate = null, $device = null, $checkAvailability = true)
    {
        $user = Lms_User::getUser();
        if ($checkAvailability && !Lms_Application::isDrmFree() && !$movie->availableForUser($user, $device)) {
            return false;
        }

        if ($movie->isTvzavr()) {
            $tvzavrUrl = Lms_Service_Tvzavr::getTvzavrStream([
                'movie_id' => $movie->getId(),
                'tvzavr_id' => $movie->getTvzavrId()
            ]);
            if ($tvzavrUrl instanceof Lms_Api_Response)
                return $tvzavrUrl;
            elseif (is_string($tvzavrUrl)) {
                $streamUrl = $tvzavrUrl;
                return $streamUrl;
            } else
                return new Lms_Api_Response(403);
        }

        $path = $movie->getPath();
        $mediapath = "vod/_definst_/$path/video_1200.mp4";

        $mediapathEncoded = implode('/', array_map('rawurlencode', explode('/', $mediapath)));

        $streamUrl = "$mediapathEncoded/playlist.m3u8";

        $userId = $user->getId()?: self::getRandomUserId();
        $streamUrl .= '?' . self::getSecureUrlParams($mediapathEncoded, $userId);

        $streamUrl .= '&UserID=' . $userId;
        if ($device) {
            $streamUrl .= '&device_code=' . $device;
        }
        $resource = [
            "type" => "vod",
            "id" => $movie->getId(),
        ];
        $streamUrl .= "&r=" . urlencode(Zend_Json::encode($resource));


        $net = 'vod';
        $streamUrl = "https://$net.persik.by/" . $streamUrl;

        return $streamUrl;
    }

    /**
     * @param $mediapath
     * @param Lms_Item_User $user
     * @param $userId
     * @param $concurrentStreams
     * @param $device
     * @param $resource
     * @return null|string|string[]
     */
    private static function processUrl($mediapath, $user, $userId, $concurrentStreams, $device, $resource)
    {
        if (preg_match('{\#securehash}i', $mediapath)) {
            $mediapath = self::addSecureHash($mediapath, $userId, $concurrentStreams);
            $mediapath .= "&UserID=$userId";
            $mediapath .= "&device_code=$device";
            $mediapath .= "&r=" . urlencode(Zend_Json::encode($resource));

        }
        if (preg_match('{\#gender}i', $mediapath)) {
            $mediapath = str_replace('#gender', "", $mediapath);
            if ($user->getGender()) {
                if (strpos($mediapath, "?")===false) {
                    $mediapath .= '?';
                } else {
                    $mediapath .= '&';
                }
                $genderMap = [
                    'f' => 0,
                    'm' => 1,
                ];
                $mediapath .= "gender=" . $genderMap[$user->getGender()];
            }
        }
        return $mediapath;
    }

    public static function getRandomUserId()
    {
        $ip = Lms_Ip::getIp();
        return 'ip-' . $ip;
        return 'r' . rand(100000000, 999999999);
    }
}
