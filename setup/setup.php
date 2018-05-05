<?php
/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 04 May 2018
 * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version: 1.0.0
 ******************************************************************
 *
 *
 ******************************************************************/

    require dirname(dirname(__FILE__)).'/vendor/autoload.php';
    require dirname(dirname(__FILE__)).'/app/config/di.php';

    // Use swgAS
    use \swgAS\swgLoad\loadstarterdataModel;

    $md5Data = file_get_contents(dirname(dirname(__FILE__)).'/database/swgASAdmin/treMD5.json');

    $args = [];
    $args['md5Data'] = $md5Data;
    $args['mongodb'] = $ci['mongodb'];

    // Load Up intial MD5 Data
    echo"Loading MD5 Entries\n";
    echo"**********************************************************\n";
    $loaded = loadstarterdataModel::initloadTreMD5($args);
    if($loaded[0] == $loaded[1])
    {
        echo $loaded[0] ." out of ". $loaded[1] ." MD5 Entries were loaded.\n";
    }
