<?php
/**
 * SafeCraft plugin for Craft CMS
 *
 * SafeCraft plugin allows you backup your Craft CMS website.
 *
 * @author    QuebecStudio
 * @copyright Copyright (c) 2017 QuebecStudio
 * @link      http://quebecstudio.com
 * @package   SafeCraft
 * @since     1.1.0
 */

namespace Craft;

class SafeCraftPlugin extends BasePlugin
{

    private $destinations = array(
        'STORAGE' => 'Craft Storage',
        'FTP' => 'FTP',
				'SFTP' => 'SFTP',
        'DROPBOX' => 'Dropbox'
    );


    public function init()
    {
        require_once __DIR__ . '/vendor/autoload.php';

        parent::init();
    }


    public function getName()
    {
         return Craft::t('SafeCraft');
    }


    public function getDescription()
    {
        return Craft::t('SafeCraft plugin allows you to custom backup your Craft website.');
    }



    public function getVersion()
    {
        return '1.2.0';
    }


    public function getSchemaVersion()
    {
        return '1.0.0';
    }

    public function getDeveloper()
    {
        return 'Québec Studio';
    }


    public function getDeveloperUrl()
    {
        return 'http://quebecstudio.com';
    }


    public function hasCpSection()
    {
        return false;
    }


    public function onBeforeInstall()
    {
    }


    public function onAfterInstall()
    {
    }


    public function onBeforeUninstall()
    {
    }


    public function onAfterUninstall()
    {
    }


    protected function defineSettings()
    {

        return array(
            'secretKey' => array(AttributeType::String, 'label' => 'secretKey', 'default' => '1234567890'),
            'backupConfig' => array(AttributeType::Bool, 'label' => 'Backup craft/config', 'default' => true),
            'backupPlugins' => array(AttributeType::Bool, 'label' => 'Backup craft/plugins', 'default' => true),
            'backupAssets' => array(AttributeType::Bool, 'label' => 'Backup craft/storage/runtime/assets', 'default' => true),
            'backupLogs' => array(AttributeType::Bool, 'label' => 'Backup craft/storage/runtime/logs', 'default' => true),
            'backupTemplates' => array(AttributeType::Bool, 'label' => 'Backup craft/templates', 'default' => true),
            'backupTranslations' => array(AttributeType::Bool, 'label' => 'Backup craft/translations', 'default' => true),
            'backupPublic' => array(AttributeType::Bool, 'label' => 'Backup public folder', 'default' => false),
            'backupDB' => array(AttributeType::Bool, 'label' => 'Backup craft/storage/backups', 'default' => true),
            'newDB' => array(AttributeType::Bool, 'label' => 'Perform a new backup before packing', 'default' => true),
            'revisions' => array(AttributeType::Number, 'label' => 'Old backups to keep', 'default' => 5),
            'destination' => array(AttributeType::Enum, 'label' => 'Backup file destination', 'values' => array_keys($this->destinations), 'default' => 'STORAGE'),
            'revisions_storage' => array(AttributeType::Number, 'label' => 'Old backups to keep', 'default' => 5),
            'ftp_server' => array(AttributeType::String, 'label' => 'FTP Server', 'default' => ''),
            'ftp_username' => array(AttributeType::String, 'label' => 'FTP Username', 'default' => ''),
            'ftp_password' => array(AttributeType::String, 'label' => 'FTP Password', 'default' => ''),
            'ftp_path' => array(AttributeType::String, 'label' => 'FTP Path', 'default' => ''),
            'ftp_port' => array(AttributeType::Number, 'label' => 'FTP Port', 'default' => 21),
            'ftp_pasv' => array(AttributeType::Bool, 'label' => 'FTP Passive', 'default' => false),
            'ftp_ssl' => array(AttributeType::Bool, 'label' => 'FTP Secure', 'default' => false),
            'dropbox_client_id' => array(AttributeType::String, 'label' => 'Dropbox Client Id', 'default' => ''),
            'dropbox_client_secret' => array(AttributeType::String, 'label' => 'Dropbox Client Secret', 'default' => ''),
            'dropbox_access_token' => array(AttributeType::String, 'label' => 'Dropbox Access Token', 'default' => ''),
            'dropbox_folder_prefix' => array(AttributeType::String, 'label' => 'Dropbox Folder Prefix', 'default' => ''),

						'sftp_server' => array(AttributeType::String, 'label' => 'SFTP Server', 'default' => ''),
            'sftp_username' => array(AttributeType::String, 'label' => 'SFTP Username', 'default' => ''),
            'sftp_password' => array(AttributeType::String, 'label' => 'SFTP Password', 'default' => ''),
						'sftp_port' => array(AttributeType::Number, 'label' => 'SFTP Port', 'default' => 21),
						'sftp_path' => array(AttributeType::String, 'label' => 'SFTP Path', 'default' => ''),
        );
    }


    public function getSettingsHtml()
    {
       return craft()->templates->render('safecraft/SafeCraft_Settings', array(
           'settings' => $this->getSettings(),
            'options' => $this->destinations
       ));
    }


    public function prepSettings($settings)
    {
        return $settings;
    }


}
