<?php
/*****************************************************************
 * RNDS SWG Account System
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
use \MongoDB\Driver\Query as MongoQuery;
use \MongoDB\Driver\Command as MongoCommand;
use \MongoDB\Driver\BulkWrite;
use \MongoDB\Driver\Exception\Exception as MongoExpception;
use \MongoDB\BSON\ObjectId as MongoID;

// Use swgAS
use \swgAS\config\settings;

class serverstatusModel
{
    private $serverStatusCollection = "server_status";

    public function getLastSevenDays($args)
    {
        $cols = [];
        $rows = [];
        $days = [];
        $hours = [];

        $currentTime = time();
        $sevenDaysAgo = time() - 7 * 24 * 60 * 60;

        $statusFilter = ['last_check' => ['$gt' => $sevenDaysAgo, '$lt' => $currentTime]];
        $options = ['sort' => ['_id' => 1]];
        $query = new MongoQuery($statusFilter,$options);

        $res = $args['mongodb']->executeQuery(settings::MONGO_ADMIN.".".$this->serverStatusCollection, $query);

        foreach($res as $r)
        {
            $ftime = date('mdy H', $r->last_check);
            $timeDays = date('mdY', $r->last_check);
            $timeHours = date('H',$r->last_check);

            $day = date('d', $r->last_check);

            $days[$timeDays]->server = $r->server_name;

            $population = $r->population;
            print"FTIME: " . $ftime ." :: population: " . $population . "\n";

            // Add To Days Objects
            if($days[$timeDays]->population_high < $population)
            {
                if($days[$timeDays]->population_low == "" || $days[$$timeDays]->population_low > $population)
                {
                    $days[$timeDays]->population_low = $population;
                }

                $days[$timeDays]->population_high = $population;
            }

            $days[$timeDays]->byHour->$timeHours->population = $population;
            $days[$timeDays]->byHour->$timeHours->status = $r->server_status;

        }

        return $days;
    }
}