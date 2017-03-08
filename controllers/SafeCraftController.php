<?php
/**
 * ManageCraft plugin for Craft CMS
 *
 * SafeCraft Controller
 *
 * @author    QuebecStudio
 * @copyright Copyright (c) 2017 QuebecStudio
 * @link      http://quebecstudio.com
 * @package   SafeCraft
 * @since     1.0.0
 */
 
namespace Craft;

class SafeCraftController extends BaseController
{

    protected $allowAnonymous = array('actionBackup');

    
    public function actionBackup()
    {
        $settings = craft()->safeCraft->getSettings();
		
	// get key
        $key = craft()->request->getParam('key');
        // verify key
        if (!$settings->secretKey or $key != $settings->secretKey) {
            echo Craft::t('Unauthorised key');
            die();
        }
		
        if ($file = craft()->safeCraft->doBackup()){
            switch ($settings->destination){
                case 'STORAGE':
                        craft()->safeCraft->saveBackupToStorage($file);
                        break;
                case 'FTP':
                        craft()->safeCraft->saveBackupToFTP($file);
                        break;
            }
        }else{
            echo Craft::t('A problem occured while backup.');
        }

        die();
    }
}