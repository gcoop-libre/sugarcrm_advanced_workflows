<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/SugarObjects/SugarConfig.php');

class GcoopLogger
{
    private $logfile = 'gcoop';
    private $ext = '.log';
    private $dateFormat = '%c';
    private $fp = false;
    private $logSize = '10MB';
    private $maxLogs = 10;
    private $filesuffix = "";

    private $initialized = false;
    public  $verbose = false;

    public static $log_levels = array('debug'=>'Debug', 'info'=>'Info', 'error'=>'Error', 'fatal'=>'Fatal' , 'security'=>'Security', 'off'=>'Off');
    public static $filename_suffix= array("%m_%Y"=>"Month_Year", "%w_%m"=>"Week_Month","%m_%d_%y"=>"Month_Day_Year");

    public function getLogFileNameWithPath()
    {
        return $this->full_log_file;
    }

    public function getLogFileName()
    {
        return ltrim($this->full_log_file, "./");
    }

    function __construct($logfile, $ext = '.log' )
    {
        $config = SugarConfig::getInstance();
        $this->ext = $ext;
        $this->logfile = $logfile;
        $this->dateFormat = $config->get('logger.file.dateFormat', $this->dateFormat);
        $this->logSize = $config->get('logger.file.maxSize', $this->logSize);
        $this->maxLogs = $config->get('logger.file.maxLogs', $this->maxLogs);
        $this->filesuffix = $config->get('logger.file.suffix', $this->filesuffix);
        unset($config);
        $this->doInitialization();

        $is_writable = file_exists($this->full_log_file) && is_writable($this->full_log_file);
    }

    private function doInitialization()
    {
        $this->full_log_file = $this->logfile . $this->ext;

        $this->initialized = $this->fileCanBeCreatedAndWrittenTo();

        if ( ! $this->initialized )
            $GLOBALS['log']->fatal("No se pudo crear archivo log  $this->full_log_file");

        $this->rollLog();
    }

    private function fileCanBeCreatedAndWrittenTo()
    {
        $this->attemptToCreateIfNecessary();
        return file_exists($this->full_log_file) && is_writable($this->full_log_file);
    }

    private function attemptToCreateIfNecessary()
    {
        if (file_exists($this->full_log_file))
            return;

        @touch($this->full_log_file);
    }



    public function logDebug( $message )
    {
        $defaultLogLevel = 'fatal';

        $config = SugarConfig::getInstance();
        $level = $config->get( 'logger.level',  $defaultLogLevel );

        if ($level == 'debug')
            $this->log($message);
    }



    public function log($message, $printTimestamp = true  )
    {
        if (!$this->initialized)
            return;

        //lets get the current user id or default to -none- if it is not set yet
        $userID = (!empty($GLOBALS['current_user']->id))?$GLOBALS['current_user']->id:'-none-';

        //if we haven't opened a file pointer yet let's do that
        if (! $this->fp)$this->fp = fopen ($this->logfile . $this->ext , 'a' );
        $message = str_replace("\n"," ",$message);
        $message = str_replace("\t"," ",$message);
        //write out to the file including the time in the dateFormat as well as the message

        if ( $printTimestamp )
            $message = strftime ('%Y/%m/%d %H:%M:%S') . " " . $message ;
        fwrite ( $this->fp, $message . "\n" );
        
        if ($this->verbose) {
            echo $message."\n";
        }
    }

    private function rollLog($force = false)
    {
        if (!$this->initialized || empty($this->logSize))
            return;

        $megs = substr ( $this->logSize, 0, strlen ( $this->logSize ) - 2 );
        $rollAt = ( int ) $megs * 1024 * 1024;
        if ($force || filesize ( $this->logfile . $this->ext ) >= $rollAt)
        {
            for($i = $this->maxLogs - 2; $i > 0; $i --)
            {
                if (file_exists ( $this->logfile . $i . $this->ext ))
                {
                    $to = $i + 1;
                    $old_name = $this->logfile . $i . $this->ext;
                    $new_name = $this->logfile . $to . $this->ext;
                    sugar_rename($old_name, $new_name);
                }
            }

            sugar_rename ($this->logfile . $this->ext, $this->logfile . '1' . $this->ext);
        }
    }

    function __destruct()
    {
        if ($this->fp)
            fclose($this->fp);
    }

}
