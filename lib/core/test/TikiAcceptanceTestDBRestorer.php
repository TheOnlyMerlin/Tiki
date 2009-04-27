<?php
/*
 * Created on Apr 7, 2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
   class TikiAcceptanceTestDBRestorer {
   		
   		private $host = "localhost";
   		private $tiki_test_db = "tiki_db_for_acceptance_tests";
		private $tiki_test_db_dump = "tiki_db_for_acceptance_tests_dump.sql";
		private $tiki_test_db_user = "tikiadmin";
		private $tiki_test_db_pwd = "tiki";
		private $mysql_data_dir = "";
   		private $tiki_schema_file_start = "dump_schema_tiki_start.txt";
   		private $tiki_restore_db_file_name = "tiki_testdb_restore_file.sql"; 
   		
   		private $test_db_dump_files = array (
   			"AcceptanceTests_ListPagesTest" => "listPagesTestDump.sql",
   			"AcceptanceTests_MultilingualTest" => "multilingualTestDump.sql", 
   			"AcceptanceTests_SearchTest" => "searchTestDump.sql"
   		);
   		
   		function __construct() {
   			$this->mysql_data_dir = $this->set_mysql_data_dir();
   		}
   		
   		function set_mysql_data_dir() {
   			mysql_connect($this->host, $this->tiki_test_db_user, $this->tiki_test_db_pwd) or die(mysql_error());
   			$result = mysql_query("select @@datadir;");
   			while($array = mysql_fetch_array($result)) {
				$datadir = $array[0];
   			}
   			return $datadir; 
   		}
   		
   		function check_if_dump_and_schema_start_files_exist($dump_file) {
   			if (check_if_dump_file_exists($dump_file) && 
   			    check_if_dump_file_exists($dump_file."_".$this->tiki_schema_file_start)) {
   			    	return true;
   			    }
   		}
   		
   		function check_if_dump_file_exists($dump_file){
   			chdir($this->mysql_data_dir);
   			if (file_exists($dump_file)) {
   				return true;
   			}
   		}
   		
   		//This method can be called to create any dump file from a db.
   		//Useful for creating dumps for diffent test db configurations
   		function create_dump_file($dump_file) {
  			chdir($this->mysql_data_dir);
 			echo "\nDumping the whole tiki database: ";
			$begTime = microtime();
		
			$mysqldump_command_line = "mysqldump --user=$this->tiki_test_db_user --password=$this->tiki_test_db_pwd $this->tiki_test_db > $dump_file";
			exec($mysqldump_command_line); 
			
			echo (microtime() -$begTime)." sec\n";
		    return true;  			
   		}
   		
   		//Creates start schema files from the test db
   		function create_start_schema_files() {
  			chdir($this->mysql_data_dir);
 			echo "\n\rDumping start tables and times from information_schema: ";
			$begTime = microtime();
			$mysql_select_from_schema_command = "echo select TABLE_NAME,UPDATE_TIME from information_schema.TABLES WHERE TABLE_SCHEMA='tiki' | mysql --user=$this->tiki_test_db_user --password=$this->tiki_test_db_pwd > $this->tiki_schema_file_start";
		    exec($mysql_select_from_schema_command);
		    echo (microtime() -$begTime)." sec\n";
		    return true;  			
   		}
   		
   		function create_testdb_dump_and_start_schema_files() {
   			$this->create_dump_file($this->tiki_test_db_dump);
   			$this->create_start_schema_files();
   		}
   		
   		function restoreDB($tiki_test) {
			global $last_restored;
			chdir($this->mysql_data_dir);
			$tiki_test_db_dump = $this->test_db_dump_files[$tiki_test];
			if (!file_exists($tiki_test_db_dump)) {
   				die ("The initial database dump was not created. You need to create it first before you can restore it. Call TikiAcceptanceTestDBRestorer::create_dump_file()");
   			}
			if ($last_restored == $tiki_test) {
				//restore only the changed tables
				$date = getdate();
   				$timestamp = $date[0];
   			
   				$tiki_schema_file_end = "dump_schema_tiki_end_$timestamp.txt";
			
		    	//GET THE CURRENT TABLES
		    	echo "\n\rDumping end tables and times from information_schema: ";
				$begTime = microtime();
			
		    	$mysql_select_from_schema_command = "echo select TABLE_NAME,UPDATE_TIME from information_schema.TABLES WHERE TABLE_SCHEMA='tiki' | mysql --user=$this->tiki_test_db_user --password=$this->tiki_test_db_pwd > $tiki_schema_file_end";
		    	exec($mysql_select_from_schema_command);
		    
		   		echo (microtime() -$begTime)." sec";
		    
		    	//COMPARE THE START AND END DUMPS
		    	echo "\n\rCompare start and end tables and times from information_schema: ";
				$begTime = microtime();
			
		    	$start_file_lines = file($this->tiki_schema_file_start, FILE_IGNORE_NEW_LINES);
				$end_file_lines = file($tiki_schema_file_end, FILE_IGNORE_NEW_LINES);
				$diff = array_diff($start_file_lines, $end_file_lines);
			
				//GET ONLY TABLE_NAMES THAT CHANGED
				array_walk($diff, 'TikiAcceptanceTestDBRestorer::get_table_name');
		    
		    	echo (microtime() -$begTime)." sec";
		    
		    	echo "\n\rCreate restore sql file: ";
				$begTime = microtime();
					
		    	$tiki_test_db_dump_as_string = file_get_contents($this->tiki_test_db_dump);
			
				//CREATE SQL FILE THAT WILL RESTORE ONLY THE CHANGED TABLES
				$tiki_restore_db_file = fopen($this->tiki_restore_db_file_name, 'w') or die("can't open file");
				foreach ($diff as $table_name) {
					$match_this = "/(LOCK TABLES `".$table_name."`.+UNLOCK TABLES;)/Us";
					$is_matched = preg_match($match_this, $tiki_test_db_dump_as_string, $matches);
					fwrite($tiki_restore_db_file, "TRUNCATE TABLE `".$table_name."`;\n\n");
					fwrite($tiki_restore_db_file,$matches[0]);
					fwrite($tiki_restore_db_file, "\n\n\n");
				}
				fclose($tiki_restore_db_file);
			
				echo (microtime() -$begTime)." sec";
			
				echo "\n\rRestore original database: ";
				$begTime = microtime();
			
				//RESTORE THE ORIGINAL DATABASE
				$mysql_restore_db_command = "mysql --user=$this->tiki_test_db_user --password=$this->tiki_test_db_pwd $this->tiki_test_db < $this->tiki_restore_db_file_name";
		    	exec($mysql_restore_db_command);
		    	echo (microtime() -$begTime)." sec"; 
				$last_restored = $tiki_test;
			} else {
				//restore the whole database				
				$mysql_restore_db_command = "mysql --user=$this->tiki_test_db_user --password=$this->tiki_test_db_pwd $this->tiki_test_db < $tiki_test_db_dump";
		    	exec($mysql_restore_db_command);
		    	$this->create_testdb_dump_and_start_schema_files();
		    	$last_restored = $tiki_test;
			}
   		}
   		
   		function get_table_name(&$table_name_date_time) {
			preg_match('/([a-zA-Z-_]+)(\s+)/', $table_name_date_time, $matches);
			$table_name_date_time = $matches[1]; 
		}
   		
   		
   }
   
   
   
//   $test_TikiAcceptanceTestDBRestorer = new TikiAcceptanceTestDBRestorer();
//   $test_TikiAcceptanceTestDBRestorer->create_dump_file("searchTestDump.sql");

?>
