<?php
    /*****************************************************************
     * RNDS SWG Account System
     * @author: Sidious <sidious@rnds.io>
     * @since: 16 March 2018
     * @link https://github.com/SidiousRNDS/SWGRO-AccountSystem
     * @version 1.0.0
     * ****************************************************************
     * This is just the cron file that allows you to get status from
     * your servers
     ******************************************************************/
    
    require dirname(dirname(__FILE__)).'/vendor/autoload.php';
    
    // Use swgAS
    use \swgAS\config\settings;
    use \swgAS\swgStatus\models\statusModel;
    
    require dirname(dirname(__FILE__)).'/app/config/di.php';
    
    statusModel::getServerStatus(['mongodb'=>$ci['mongodb']]);
    
    
    