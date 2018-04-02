<?php

namespace swgAPI\utils;

/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 16 March 2018
 * @link
 * @version 1.0.0
 * ****************************************************************
 * NAMESPACE: swgAPI\utils
 * CLASS: station
 ******************************************************************/

// Use

// swgAPI Use
use swgAPI\config\settings;
use swgAPI\models\accountModel;

class station
{
	/**
	 * Summary of getStationId
	 * @method getStationId()
	 * @param array $args
	 * @return mixed
	 */
	public function getStationId(array $args)
	{
		$results = $args['db']::table(accountModel::getTableName())
			->select('account_id')
			->where('station_id', '=', $args['station_id'])
			->first();

		return $results;
	}

	/**
	 * Summary of generateStationId
	 * @method generateStationId()
	 * @return integer
	 */
	public function generateStationId($args)
	{
		$genStationId = rand(settings::STATIONID_START, settings::STATIONID_END);
		$args['station_id'] = $genStationId;

		$checkId = $this->getStationId($args);

		if($checkId === null)
		{
			return $genStationId;
		}
		else {
			$this->generateStationId($args);
		}
	}
}

?>