<?php
/**
 * SafeCraft plugin for Craft CMS
 *
 * SafeCraft Service
 *
 * @author    QuebecStudio
 * @copyright Copyright (c) 2017 QuebecStudio
 * @link      http://quebecstudio.com
 * @package   SafeCraft
 * @since     1.0.0
 */

namespace Craft;

class SafeCraftService extends BaseApplicationComponent
{
     
	public function getSettings(){
		if (!$plugin = craft()->plugins->getPlugin('safecraft')) {
            die('Could not find the plugin');
        }        
        return $plugin->getSettings();
	}
	 
	public function saveBackupToStorage($file){
		$path = craft()->path->getStoragePath().'safecraft/backups/';
		if (!is_dir($path)){
			mkdir($path, 0777, true);
		}
		$settings = $this->getSettings();
		$filesDeleted = $this->_deleteOldBackups_Storage($settings->revisions_storage);
		echo Craft::t("Storage: File moved.")."\n";
		return rename($file, $path.basename($file));
	}
	
	public function saveBackupToFTP($file){
		$settings = $this->getSettings();
		
		$path = trim($settings->ftp_path)?$this->fixpath(trim($settings->ftp_path)):'';
		
		if ($settings->ftp_ssl){
			$conn_id = ftp_ssl_connect($settings->ftp_server, $settings->ftp_port);
		} else {
			$conn_id = ftp_connect($settings->ftp_server, $settings->ftp_port);
		}
				
		$login_result = ftp_login($conn_id, $settings->ftp_username, $settings->ftp_password);
		
		if ($settings->ftp_pasv){
			ftp_pasv($conn_id, true);
		}
		
		if (ftp_put($conn_id, $path.basename($file), $file, FTP_BINARY)) {
		 echo Craft::t("FTP: File transfered.")."\n";
		 $result = true;
		} else {
		 echo Craft::t("FTP: Error occured while transfering file.")."\n";
		 $result = false;
		}
		unlink($file);
		ftp_close($conn_id);
		return $result;
	}
    	
	public function doBackup(){
		
		
        $settings = $this->getSettings();		
		
		craft()->config->maxPowerCaptain();
		
		$file = 'safecraft-backup_'.date('Y-m-d').'_'.date('H-i-s').'.zip';
		
		$zipPath =  craft()->path->getTempPath().$file;
		
		require_once(__DIR__ . '/../src/MyZipArchive.php');
		$zip = new \QuebecStudio_SafeCraft\MyZipArchive();
				
		$res = $zip->open($zipPath, \ZipArchive::CREATE);
		if ($res === TRUE) {
			
			if (($settings->backupPlugins)&&(is_dir(craft()->path->getPluginsPath()))){
				$zip->addDir(craft()->path->getPluginsPath(), 'craft/plugins');
			}
			
			if (($settings->backupAssets)&&(is_dir(craft()->path->getAssetsPath()))){
				$zip->addDir(craft()->path->getAssetsPath(), 'craft/storage/runtime/assets');
			}
			
			if (($settings->backupLogs)&&(is_dir(craft()->path->getLogPath()))){
				$zip->addDir(craft()->path->getLogPath(), 'craft/storage/runtime/logs');
			}
			
			if (($settings->backupConfig)&&(is_dir(craft()->path->getConfigPath()))){
				$zip->addDir(craft()->path->getConfigPath(), 'craft/config');
			}
			
			if (is_dir(craft()->path->getSiteTranslationsPath())){
				$zip->addDir(craft()->path->getSiteTranslationsPath(), 'craft/translations');
			}
			
			if (is_dir(craft()->templates->getTemplatesPath())){
				$zip->addDir(craft()->templates->getTemplatesPath(), 'craft/templates');
			}
			
			if (($settings->backupDB)&&(is_dir(craft()->path->getDbBackupPath()))){
				if ($settings->newDB){
					craft()->db->backup();
				}
				$filesDeleted = $this->_deleteOldBackups_DB($settings->revisions);
				$zip->addDir(craft()->path->getDbBackupPath(), 'craft/storage/backups');
			}
			
			if (($settings->backupPublic)&&(is_dir(dirname($_SERVER["SCRIPT_FILENAME"])))){				
				$zip->addDir(dirname($_SERVER["SCRIPT_FILENAME"]), 'public');				
			}
			
			$zip->close();
			return $zipPath;
		}
		return false; 	 
    }
	
	private function fixpath($p) {
		$p=str_replace('\\','/',trim($p));
		return (substr($p,-1)!='/') ? $p.='/' : $p;
	}
	
	private function _deleteOldBackups_DB($revisions = null)
    {        
        if (!is_numeric($revisions)) {
            return 0;
        }
        $backupPath = craft()->path->getDbBackupPath();        
        if ($files = scandir($backupPath, SCANDIR_SORT_DESCENDING)) {            
            $files = array_slice($files, ($revisions - 1));
            $i = 0;            
            foreach ($files as $file) {
                $filePath = $backupPath . $file;
                if (is_file($filePath)) {
                    unlink($filePath);
                    $i++;
                }
            }
            return $i;
        }
    }
	
	private function _deleteOldBackups_Storage($revisions = null)
    {        
        if (!is_numeric($revisions)) {
            return 0;
        }
        $backupPath = craft()->path->getStoragePath().'safecraft/backups/';     
        if ($files = scandir($backupPath, SCANDIR_SORT_DESCENDING)) {            
            $files = array_slice($files, ($revisions - 1));
            $i = 0;            
            foreach ($files as $file) {
                $filePath = $backupPath . $file;
                if (is_file($filePath)) {
                    unlink($filePath);
                    $i++;
                }
            }
            return $i;
        }
    }
	
}

