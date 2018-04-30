<?php
    /*****************************************************************
     * RNDS SWG Account System
     * @author:  Sidious <sidious@rnds.io>
     * @since:   30 April 2018
     * @link:    https://github.com/SidiousRNDS/SWGRO-AccountSystem
     * @version: 1.0.0
     *****************************************************************
     * NAMESPACE: swgAS\swgAdmin\models
     * CLASS: admingameupdatesModel
     *****************************************************************/
    
    namespace swgAS\swgAdmin\models;
    
    
    class admingameupdatesModel
    {
        // TODO gameupdates will be stored in the mongoDB one collection will be for server gameupdates and one will be for launcher gameupdates
        // TODO we will need to make an API to access these entries as well for use in the launcher and the forums - (forums may be a direct input but we will
        // TODO also need to setup an API call so that other forums other then myBBS can use them as well so the users only have to make a single entry in this system
        // TODO to push out update information to their system. After launch we may look at doing intrgrations with other bbs system but at this time we will just create
        // TODO the API)
    
        private $serverUpdateCollection = "server_updates";
        private $launcherUpdateCollection = "launcher_updates";
    }