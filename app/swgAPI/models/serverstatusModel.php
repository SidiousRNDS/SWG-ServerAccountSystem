<?php
/*****************************************************************
 * RNDS SWG Server System
 * @author: Sidious <sidious@rnds.io>
 * @since: 09 April 2018
 * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version: 1.0.0
 ******************************************************************
 * NAMESPACE: swgAPI\models
 * CLASS: serverstatusModel
 ******************************************************************/

namespace swgAS\swgAPI\models;

// Use
use \Illuminate\Database\Eloquent\Model as Model;
use \MongoDB\Driver\Query as MongoQuery;

// Use swgAS
use \swgAS\config\settings;

class serverstatusModel extends Model
{
    private $serverStatusCollection = "server_status";

    /**
     * Summary getLastSevenDays - Get the last 7 days of status gameupdates from the db
     * @param $args
     * @return array
     */
    public function getLastSevenDaysLive($args)
    {
        $days = [];

        $date = new \DateTime();
        
        $currentTime = $date->getTimestamp();
        $sevenDaysAgo = $date->getTimestamp() - 7 * 24 * 60 * 60;

        $statusFilter = ['last_check' => ['$gte' => $sevenDaysAgo, '$lte' => $currentTime], 'server_name' => 'Live'];
        $options = ['sort' => ['_id' => 1]];
        $query = new MongoQuery($statusFilter,$options);

        $res = $args['mongodb']->executeQuery(settings::MONGO_ADMIN.".".$this->serverStatusCollection, $query);

        foreach($res as $r)
        {

            $timeDays = date('mdY', $r->last_check);
            $dateReported = date('Y-m-d H:i:s', $r->last_check);

            $days[$timeDays]['date'] = $dateReported;
            $days[$timeDays]['server'] = $r->server_name;


            // Add To Days Array

            if($days[$timeDays]['population_low'] == "" || $days[$timeDays]['population_low'] > $r->population)
            {
                $days[$timeDays]['population_low'] = $r->population;
            }

            if($days[$timeDays]['population_high'] < $r->population)
            {
                $days[$timeDays]['population_high'] = $r->population;
            }
        }

        return $days;
    }

    /**
     * Summary getLast24HoursLive - Get the last 24 hours stats
     * @param $args
     * @return array
     */
    public function getlast24HoursLive($args)
    {
        $hours = [];
        $dayStart = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
        $dayEnd = mktime(24, 0, 0, date("m")  , date("d"), date("Y"));

        $statusFilter = ['last_check' => ['$gte' => $dayStart, '$lte' => $dayEnd], 'server_name' => 'Live'];
        $options = ['sort' => ['_id' => 1]];
        $query = new MongoQuery($statusFilter,$options);

        $res = $args['mongodb']->executeQuery(settings::MONGO_ADMIN.".".$this->serverStatusCollection, $query);

        foreach($res as $r)
        {
            $hour = date('H', $r->last_check);
            $hours[$hour]['hourreported'] = $hour;
            $hours[$hour]['servername'] = $r->server_name;
            $hours[$hour]['recordedDate'] = date('Y-m-d H:i:s', $r->last_check);
            $hours[$hour]['currentTime'] = date('Y-m-d H:i:s');

            // Add Entry to the hour Array
            if($hours[$hour]['population_low'] == "" || $hours[$hour]['population_low'] > $r->population)
            {
                $hours[$hour]['population_low'] = $r->population;
            }

            if($hours[$hour]['population_high'] < $r->population)
            {
                $hours[$hour]['population_high'] = $r->population;
            }
        }

        return $hours;
    }

    /**
     * @param $args
     * @return array
     */
    public function getUniqueAccounts($args)
    {
        $startMonthDay = mktime(0, 0, 0, date("m"), 1, date("Y"));
        $endMonthDay = mktime(0, 0, 0, date("m") + 1, 0, date("Y"));

        $from = date('Y-m-d 00:00:00', $startMonthDay);
        $to = date('Y-m-d 23:59:59', $endMonthDay);

        $logins = [];
        //$from = '2017-10-01 00:00:00';
        //$to = '2017-10-31 23:59:59';

        $res = $args['db']::table('account_ips')
            ->distinct()
            ->whereBetween('timestamp',[$from, $to])
            ->groupBy('ip')
            ->get(['ip','account_id','timestamp']);

        foreach($res as $r)
        {
            $splitTimeStamp = preg_split("/[\s]+/",$r->timestamp);
            $loginDate = $splitTimeStamp[0];
            $loginTime = $splitTimeStamp[1];

            if(array_key_exists($loginDate,$logins)) {
                array_push($logins[$loginDate]['userdata'], array(['ip'=>$r->ip, 'aid'=>$r->account_id, 'loginTime' => $loginTime]));
            }
            else {
                $logins[$loginDate]['loginDate'] = $loginDate;
                $logins[$loginDate]['userdata'] = array(array(['ip'=>$r->ip, 'aid'=>$r->account_id, 'loginTime' => $loginTime]));
            }
            
        }

        return $logins;
    }
}