<?php


class AIOGDPR_Log{

	public static $tableName = 'aiogpr_logs';	
	public $ID;
	public $date;
	public $content;
	

	public function __construct($content = ''){
		$this->content = $content;
		$this->date    = date("Y-m-d H:i:s");
	}

	public function boot(){
		global $wpdb;
		$tableName = $wpdb->prefix.AIOGDPR_Log::$tableName;
		$result = $wpdb->get_results("SELECT * from {$tableName} WHERE `ID` = {$this->ID}");

		if(isset($result[0]->content)){
			$this->date = $result[0]->created_at;
			$this->content = $result[0]->content;
		}
	}

	public static function migrate(){
        global $wpdb;
        
        try{
            $tableName = $wpdb->prefix.AIOGDPR_Log::$tableName;

            $wpdb->get_var("
                CREATE TABLE `{$tableName}` (
                    `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                    `content` longtext NOT NULL,
                    PRIMARY KEY (`ID`)
                )
            ");

            $error = $wpdb->last_error;
            return ($error)? $error : TRUE;
        }catch(Exception $e){
            return FALSE;
        }   
	}

	public static function tableExists(){
		global $wpdb;

		$tableName = $wpdb->prefix.AIOGDPR_Log::$tableName;

		return $wpdb->get_var("SHOW TABLES LIKE '$tableName'") == $tableName;
	}

	public static function insert($content){
        if(AIOGDPR_Settings::get('logging_enabled') == '1'){
		    $log = new AIOGDPR_Log($content);
		    $log->save();
		    return $log;
        }
	}

	public static function find($ID){
		$log = new AIOGDPR_Log;
		$log->ID = $ID;
		$log->boot();
		return $log;
	}

	public static function all(){
		global $wpdb;
		$tableName = $wpdb->prefix.AIOGDPR_Log::$tableName;

		$result = $wpdb->get_results("SELECT * FROM {$tableName} ORDER BY ID DESC");

		$logs = array();
        foreach($result as $key => $row){
            array_push($logs, AIOGDPR_Log::find($row->ID));
        }

		return $logs;
	}

	public static function mostRecent($limit = 100){
		global $wpdb;
		$tableName = $wpdb->prefix.AIOGDPR_Log::$tableName;

		$result = $wpdb->get_results("SELECT * FROM {$tableName} ORDER BY ID DESC LIMIT {$limit}");

		$logs = array();
        foreach($result as $key => $row){
            array_push($logs, AIOGDPR_Log::find($row->ID));
        }

		return $logs;
	}

	public function save(){
		global $wpdb;
		$tableName = $wpdb->prefix.AIOGDPR_Log::$tableName;

		$wpdb->get_results("
			INSERT INTO {$tableName} (created_at, content) VALUES('{$this->date}', '$this->content')
		");
	}
}