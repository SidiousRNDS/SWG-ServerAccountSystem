<?php
/*****************************************************************
 * RNDS SWG Server System
 * @author: Sidious <sidious@rnds.io>
 * @since: 07 May 2018
 * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version: 1.0.0
 ******************************************************************
 * NAMESPACE: swgAS\helpers
 * CLASS: processauthcodes
 ******************************************************************/

namespace swgAS\helpers;

// Use swgAS
use swgAS\config\settings;
use swgAS\helpers\messaging\errormsg;

/**
 * Class processauthcodes
 * @package swgAS\helpers
 */
class processauthcodes
{
    /**
     * @var string
     */
    private $error = "ERROR";

    /**
     * @method generateAuthCode
     * @param array $args
     * @return bool|null|string|string[]
     * @throws \ReflectionException
     */
    public function genereateAuthCode($args)
    {
        $authCode = null;

        if($args['prefix'] == settings::PRIMARY_CODE_PREFIX)
        {
            $authCode = settings::PRIMARY_CODE_PREFIX;
        }

        if($args['prefix'] == settings::EXTENDED_CODE_PREFIX)
        {
            $authCode = settings::EXTENDED_CODE_PREFIX;
        }

        $primaryAuthCodeSection = $this->buildAuthCodeSections(settings::CODE_LENGTH_PRIMARY);

        if($primaryAuthCodeSection == null)
        {
            $errorMsg = errormsg::getErrorMsg("codechars", (new \ReflectionClass(self::class))->getShortName());
            $args['flash']->addMessageNow("error", $errorMsg);
            return $this->error;
        }

        $authCode = $authCode . "-" . $primaryAuthCodeSection;

        if(settings::USE_SECONDARY)
        {
            $secondaryAuthCodeSection = $this->buildAuthcodeSections((settings::CODE_LENGTH_SECONDARY));
            if($secondaryAuthCodeSection == null)
            {
                $errorMsg = errormsg::getErrorMsg("codechars", (new \ReflectionClass(self::class))->getShortName());
                $args['flash']->addMessageNow("error", $errorMsg);
                return $this->error;
            }

            $authCode = $authCode . "-" . $secondaryAuthCodeSection;
        }

        if(settings::DIVIDERS == false)
        {
            $authCode = $authCode = preg_replace("/-/","",$authCode);
        }

        $checkCodeLength = validation::validateAuthCodeLength($authCode);

        if($checkCodeLength == true)
        {
            return $authCode;
        }

        return $checkCodeLength;
    }


    /**
     * @method buildAuthCodeSections
     * @param int $sectionLength
     * @return null|string
     */
    private function buildAuthcodeSections(int $sectionLength)
    {
        if(settings::CODE_CHARS == "")
        {
            return null;
        }

        $section = null;

        for($i=0; $i < $sectionLength; $i++)
        {
            $section .= settings::CODE_CHARS[rand(0,strlen(settings::CODE_CHARS) - 1)];
        }

        return $section;
    }
}