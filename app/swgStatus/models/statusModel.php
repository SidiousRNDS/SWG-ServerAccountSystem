<?php

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAS\swgStatus\models
 * CLASS: statusModel
 ******************************************************************/

namespace swgAS\swgStatus\models;


// Use
use \MongoDB\Driver\Query as MongoQuery;
use \MongoDB\Driver\BulkWrite;
use \MongoDB\Driver\Exception\Exception as MongoExpception;
use \MongoDB\BSON\ObjectId as MongoID;

// Use swgAS
use swgAS\config\settings;
use swgAS\utils\messaging\errormsg;
use swgAS\utils\password;
use swgAS\utils\security;
use swgAS\utils\sessions;
use swgAS\utils\utilities;

class statusModel
{
    private $serverStatusCollection = "server_status";

    public static function getServerStatus($args)
    {
        $liveServer = settings::LIVE_GAME_SERVER;
        $testServer = settings::TEST_GAME_SERVER;
        $statusPort = settings::STATUS_PORT;

        if($liveServer)
        {
            $liveStatus = self::pollServer(['server'=>$liveServer,'port'=>$statusPort]);
            $liveStatus->name = "Live";

            /*
             *  $session = ['_id' => new MongoID, 'sessionID' => $sessionID, 'username'=>$args['username'], 'expire'=>$sessionExpire, 'setat'=>time(), 'created_at'=>date('Y-m-d H:i:s')];
             *  $createSession = new BulkWrite;
             *  $createSession->insert($session);
             */

            $live = ['_id' => new MongoID, 'server_name' => $liveStatus->name, 'server_status' => $liveStatus->server_status,
                     'popluation' => $liveStatus->users_conneted, 'popluation_since_last_restart' => $liveStatus->users_connected_since_last_restart,
                     'uptime_days' => $liveStatus->up_time->days, 'uptime_hours' => $liveStatus->up_time->hours,
                     'uptime_minutes' => $liveStatus->up_time->minutes, 'uptime_seconds' => $liveStatus->up_time->seconds,
                     'last_check' => $liveStatus->last_check
            ];
        }

        if($testServer)
        {
            $testStatus = self::pollServer(['server'=>$testServer,'port'=>$statusPort]);
            $testStatus->name = "Test";

            $test = ['_id' => new MongoID, 'server_name' => $testStatus->name, 'server_status' => $testStatus->server_status,
                'popluation' => $testStatus->users_conneted, 'popluation_since_last_restart' => $testStatus->users_connected_since_last_restart,
                'uptime_days' => $testStatus->up_time->days, 'uptime_hours' => $testStatus->up_time->hours,
                'uptime_minutes' => $testStatus->up_time->minutes, 'uptime_seconds' => $testStatus->up_time->seconds,
                'last_check' => $testStatus->last_check
            ];
        }


    }


    /**
     * Summary pollServer - Poll the server and get the XML data back from the server
     * @param $args
     * @return \SimpleXMLElement|void
     */
    private static function pollServer($args)
    {
        $stream = stream_socket_client("tcp://".$args['server'].":".$args['port'], $streamErrno, $streamErrorStr, 30);

        if(!$stream)
        {
            // Throw Error to the error handler and log
            throw new Exception("Could not connect to server (".$args['server'].") :: ".$streamErrorStr."(".$streamErrno.")\n");
            return;
        }
        else
        {
            // Write to the stream
            fwrite($stream, "\n");
            // Capture the data returned from the server
            $streamXML = fread($stream,1000);
            // Close the stream
            fclose($stream);

            // Put the data returned from the server into a nicely formated XML Array
            $xml = simplexml_load_string($streamXML);

            return self::statusDataFormat($xml);        // Return the formated xml data as an object
        }
    }

    /**
     * Summary statusDataFormat - Format all the returned XML into a nice neat object
     * @param $streamXML
     * @return object
     */
    private static function statusDataFormat($streamXML)
    {
        if($streamXML->status == "up")
        {
            $status = "Online";
        }
        else
        {
            $status = "Offline";
        }

        $serverStatus = (object) [
            "server_name" => '',
            "server_status" => $status,
            "users_connected" => trim($streamXML->users->connected),
            "users_connected_since_last_restart" => trim($streamXML->users->max),
            "user_cap" => trim($streamXML->users->cap),
            "up_time" => self::statusUptimeFormat($streamXML->uptime),
            "last_check" => trim($streamXML->timestamp)
        ];

        return $serverStatus;
    }

    /**
     * Summary statusUptimeFormat - Format the xml data for uptime into a human readable format
     * @param $streamXMLUptime
     * @return object
     */
    private static function statusUptimeFormat($streamXMLUptime)
    {
        // Set Days
        $days = floor($streamXMLUptime / (24*60*60));

        // Set Hours
        $hours = floor(($streamXMLUptime - ($days*24*60*60)) / (60*60));

        // Set Minutes
        $minutes = floor(($streamXMLUptime - ($days*24*60*60) - ($hours*60*60)) / 60);

        // Set Seconds
        $seconds = ($streamXMLUptime - ($days*24*60*60) - ($hours*60*60) - ($minutes*60)) %60;

        // Build ServerUptime Object
        $serverUptime = (object) [
            "days" => $days,
            "hours" => $hours,
            "minutes" => $minutes,
            "seconds" => $seconds
        ];

        return $serverUptime;
    }
}