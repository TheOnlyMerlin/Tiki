<?php

include_once('lib/diff.php');
require_once('lib/Date.php');

class TikiLib {
  var $db;  // The PEAR db object used to access the database
  var $buffer;
  var $flag;
  var $parser;

  function TikiLib($db)
  {
    if(!$db) {
      die("Invalid db object passed to UsersLib constructor");
    }
    $this->db = $db;
  }

  // This is only for performance collection of all queries
  /*
  function query($query) {
    //for performance stats
    list($micro,$sec)=explode(' ',microtime());
    $query_start=$sec+$micro;
    $result = $this->db->query($query);
    list($micro,$sec)=explode(' ',microtime());
    $query_stop=$sec+$micro;
    //$fpq=fopen("/tmp/tikiquerystats",'a');
    //fwrite($fpq,$query_stop-$query_start."\t".$query."\n");
    //fclose($fpq);
    $qdiff=$query_stop-$query_start;
    if(DB::isError($result)) $this->sql_error($query,$result);
    $querystat="insert into tiki_querystats values(1,'".addslashes($query)."',$qdiff)";
    $qresult=$this->db->query($querystat);
    if(DB::isError($qresult)) {
      $querystat="update tiki_querystats set qcount=qcount+1, qtime=qtime+$qdiff where qtext='".addslashes($query)."'";
      $qresult=$this->db->query($querystat);
    }
    return $result;
  }
  */
  // Here comes the original

  function query($query) {
    $result = $this->db->query($query);
    if(DB::isError($result)) $this->sql_error($query,$result);
    return $result;
  }


  function getOne($query) {
    $result = $this->db->getOne($query);
    if(DB::isError($result)) $this->sql_error($query,$result);
    return $result;
  }

  function sql_error($query, $result)
  {
    trigger_error("MYSQL error:  ".$result->getMessage()." in query:<br/>".$query."<br/>",E_USER_WARNING);
    die;
  }
  
  function dir_stats()
  {
    $aux=Array();
    $aux["valid"] = $this->db->getOne("select count(*) from tiki_directory_sites where isValid='y'");
    $aux["invalid"] = $this->db->getOne("select count(*) from tiki_directory_sites where isValid='n'");
    $aux["categs"] = $this->db->getOne("select count(*) from tiki_directory_categories");
    $aux["searches"] = $this->db->getOne("select sum(hits) from tiki_directory_search");
    $aux["visits"] = $this->db->getOne("select sum(hits) from tiki_directory_sites");
    return $aux;
  }
  
  function dir_list_all_valid_sites2($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where isValid='y' and (name like '%".$find."%' or description like '%".$find."%')";  
    } else {
      $mid=" where isValid='y' "; 
    }
    
    $query = "select * from tiki_directory_sites $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_directory_sites $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }
  
  
  function user_unread_messages($user)
  {
    $cant = $this->getOne("select count(*) from messu_messages where user='$user' and isRead='n'");
    return $cant;
  }

  // Get online users
  function get_online_users()
  {
    $query = "select user from tiki_sessions where user<>''";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $res['user_information'] = $this->get_user_preference($res['user'],'user_information','public');
      $ret[] = $res;
    }
    return $ret;
  }

  // Validate emails...
  function SnowCheckMail($Email,$Debug=false)
  {
    global $HTTP_HOST;
    $Return =array();
    // Variable for return.
    // $Return[0] : [true|false]
    // $Return[1] : Processing result save.

    if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $Email)) {
        $Return[0]=false;
        $Return[1]="${Email} is E-Mail form that is not right.";
        if ($Debug) echo "Error : {$Email} is E-Mail form that is not right.<br>";
        return $Return;
    }
    else if ($Debug) echo "Confirmation : {$Email} is E-Mail form that is not right.<br>";

    // E-Mail @ by 2 by standard divide. if it is $Email this "lsm@ebeecomm.com"..
    // $Username : lsm
    // $Domain : ebeecomm.com
    // list function reference : http://www.php.net/manual/en/function.list.php
    // split function reference : http://www.php.net/manual/en/function.split.php
    list ( $Username, $Domain ) = split ("@",$Email);

    // That MX(mail exchanger) record exists in domain check .
    // checkdnsrr function reference : http://www.php.net/manual/en/function.checkdnsrr.php
    if ( checkdnsrr ( $Domain, "MX" ) )  {
        if($Debug) echo "Confirmation : MX record about {$Domain} exists.<br>";
        // If MX record exists, save MX record address.
        // getmxrr function reference : http://www.php.net/manual/en/function.getmxrr.php
        if ( getmxrr ($Domain, $MXHost))  {
      if($Debug) {
                echo "Confirmation : Is confirming address by MX LOOKUP.<br>";
              for ( $i = 0,$j = 1; $i < count ( $MXHost ); $i++,$j++ ) {
            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Result($j) - $MXHost[$i]<BR>";
        }
            }
        }
        // Getmxrr function does to store MX record address about $Domain in arrangement form to $MXHost.
        // $ConnectAddress socket connection address.
        $ConnectAddress = $MXHost[0];
    }
    else {
        // If there is no MX record simply @ to next time address socket connection do .
        $ConnectAddress = $Domain;
        if ($Debug) echo "Confirmation : MX record about {$Domain} does not exist.<br>";
    }

    // fsockopen function reference : http://www.php.net/manual/en/function.fsockopen.php
    $Connect = fsockopen ( $ConnectAddress, 25 );

    // Success in socket connection
    if ($Connect)
    {
        if ($Debug) echo "Connection succeeded to {$ConnectAddress} SMTP.<br>";
        // Judgment is that service is preparing though begin by 220 getting string after connection .
        // fgets function reference : http://www.php.net/manual/en/function.fgets.php
        if ( ereg ( "^220", $Out = fgets ( $Connect, 1024 ) ) ) {

            // Inform client's reaching to server who connect.
            fputs ( $Connect, "HELO $HTTP_HOST\r\n" );
                if ($Debug) echo "Run : HELO $HTTP_HOST<br>";
            $Out = fgets ( $Connect, 1024 ); // Receive server's answering cord.

            // Inform sender's address to server.
            fputs ( $Connect, "MAIL FROM: <{$Email}>\r\n" );
                if ($Debug) echo "Run : MAIL FROM: &lt;{$Email}&gt;<br>";
            $From = fgets ( $Connect, 1024 ); // Receive server's answering cord.

            // Inform listener's address to server.
            fputs ( $Connect, "RCPT TO: <{$Email}>\r\n" );
                if ($Debug) echo "Run : RCPT TO: &lt;{$Email}&gt;<br>";
            $To = fgets ( $Connect, 1024 ); // Receive server's answering cord.

            // Finish connection.
            fputs ( $Connect, "QUIT\r\n");
                if ($Debug) echo "Run : QUIT<br>";

            fclose($Connect);

                // Server's answering cord about MAIL and TO command checks.
                // Server about listener's address reacts to 550 codes if there does not exist
                // checking that mailbox is in own E-Mail account.
                if ( !ereg ( "^250", $From ) || !ereg ( "^250", $To )) {
                    $Return[0]=false;
                    $Return[1]="${Email} is address done not admit in E-Mail server.";
                    if ($Debug) echo "{$Email} is address done not admit in E-Mail server.<br>";
                    return $Return;
                }
        }
    }
    // Failure in socket connection
    else {
        $Return[0]=false;
        $Return[1]="Can not connect E-Mail server ({$ConnectAddress}).";
        if ($Debug) echo "Can not connect E-Mail server ({$ConnectAddress}).<br>";
        return $Return;
    }
    $Return[0]=true;
    $Return[1]="{$Email} is E-Mail address that there is no any problem.";
    return $Return;
  }


  /* Shared listing functions used for modules */
  function get_user_items($user)
  {
    $items = Array();
    $query = "select ttf.trackerId, tti.itemId from tiki_tracker_fields ttf, tiki_tracker_items tti, tiki_tracker_item_fields ttif where ttf.fieldId=ttif.fieldId and ttif.itemId=tti.itemId and type='u' and tti.status='o' and value='$user'";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $itemId=$res["itemId"];
      $trackerId=$res["trackerId"];
      // Now get the isMain field for this tracker
      $fieldId=$this->getOne("select fieldId from tiki_tracker_fields ttf where isMain='y' and trackerId=$trackerId");
      // Now get the field value
      $value = $this->getOne("select value from tiki_tracker_item_fields where fieldId=$fieldId and itemId=$itemId");
      $tracker = $this->getOne("select name from tiki_trackers where trackerId=$trackerId");
      $aux["trackerId"]=$trackerId;
      $aux["itemId"]=$itemId;
      $aux["value"]=$value;
      $aux["name"]=$tracker;
      if(!in_array($itemId,$items)) {
        $ret[]=$aux;
        $items[]=$itemId;
      }
    }

    $groups = $this->get_user_groups($user);
    foreach($groups as $group) {
      $query = "select ttf.trackerId, tti.itemId from tiki_tracker_fields ttf, tiki_tracker_items tti, tiki_tracker_item_fields ttif where ttf.fieldId=ttif.fieldId and ttif.itemId=tti.itemId and type='g' and tti.status='o' and value='$group'";
      $result = $this->query($query);
      while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
        $itemId=$res["itemId"];
        $trackerId=$res["trackerId"];
        // Now get the isMain field for this tracker
        $fieldId=$this->getOne("select fieldId from tiki_tracker_fields ttf where isMain='y' and trackerId=$trackerId");
        // Now get the field value
        $value = $this->getOne("select value from tiki_tracker_item_fields where fieldId=$fieldId and itemId=$itemId");
        $tracker = $this->getOne("select name from tiki_trackers where trackerId=$trackerId");
        $aux["trackerId"]=$trackerId;
        $aux["itemId"]=$itemId;
        $aux["value"]=$value;
        $aux["name"]=$tracker;
        if(!in_array($itemId,$items)) {
          $ret[]=$aux;
          $items[]=$itemId;
        }
      }

    }

    return $ret;
  }

  function compute_quiz_stats()
  {
    $query = "select quizId from tiki_user_quizzes";
    $result = $this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $quizId = $res["quizId"];
      $quizName = $this->getOne("select name from tiki_quizzes where quizId=$quizId");
      $timesTaken = $this->getOne("select count(*) from tiki_user_quizzes where quizId=$quizId");
      $avgpoints = $this->getOne("select avg(points) from tiki_user_quizzes where quizId=$quizId");
      $maxPoints = $this->getOne("select max(maxPoints) from tiki_user_quizzes where quizId=$quizId");
      $avgavg = $avgpoints/$maxPoints*100;
      $avgtime = $this->getOne("select avg(timeTaken) from tiki_user_quizzes where quizId=$quizId");
      $query2 = "replace into tiki_quiz_stats_sum(quizId,quizName,timesTaken,avgpoints,avgtime,avgavg)
      values($quizId,'$quizName',$timesTaken,$avgpoints,$avgtime,$avgavg)";
      $result2 = $this->query($query2);
    }
  }

  function list_quizzes($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
    $mid=" where (name like '%".$find."%' or description like '%".$find."%')";
    } else {
      $mid=" ";
    }
    $query = "select * from tiki_quizzes $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_quizzes $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $res["questions"]=$this->getOne("select count(*) from tiki_quiz_questions where quizId=".$res["quizId"]);
      $res["results"]=$this->getOne("select count(*) from tiki_quiz_results where quizId=".$res["quizId"]);
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_quiz_sum_stats($offset,$maxRecords,$sort_mode,$find)
  {
    $this->compute_quiz_stats();
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
    $mid="  (quizName like '%".$find."%'";
    } else {
      $mid="  ";
    }
    $query = "select * from tiki_quiz_stats_sum $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_quiz_stats_sum $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_surveys($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
    $mid=" where (name like '%".$find."%' or description like '%".$find."%')";
    } else {
      $mid=" ";
    }
    $query = "select * from tiki_surveys $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_surveys $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $res["questions"]=$this->getOne("select count(*) from tiki_survey_questions where surveyId=".$res["surveyId"]);
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_tracker_items($trackerId,$offset,$maxRecords,$sort_mode,$fields,$status='')
  {
    $filters=Array();
    if($fields) {
      for($i=0;$i<count($fields["data"]);$i++) {
        $fieldId=$fields["data"][$i]["fieldId"];
        $type=$fields["data"][$i]["type"];
        $value=$fields["data"][$i]["value"];
        $aux["value"]=$value;
        $aux["type"]=$type;
        $filters[$fieldId]=$aux;
      }
    }

    $sort_mode = str_replace("_"," ",$sort_mode);
    $mid=" where trackerId=$trackerId ";
    if($status) {
      $mid.=" and status='$status' ";
    }
    $query = "select * from tiki_tracker_items $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_tracker_items $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $fields=Array();
      $itid=$res["itemId"];
      $query2="select ttif.fieldId,name,value,type,isTblVisible,isMain from tiki_tracker_item_fields ttif, tiki_tracker_fields ttf where ttif.fieldId=ttf.fieldId and itemId=".$res["itemId"]." order by fieldId asc";
      $result2 = $this->query($query2);
      $pass=true;
      while($res2 = $result2->fetchRow(DB_FETCHMODE_ASSOC)) {
        // Check if the field is visible!
        $fieldId=$res2["fieldId"];
        if(count($filters)>0) {
          if($filters["$fieldId"]["value"]) {
            if($filters["$fieldId"]["type"]=='a' || $filters["$fieldId"]["type"]=='t' ) {
              if(!strstr($res2["value"],$filters["$fieldId"]["value"])) $pass=false;
            } else {
              if($res2["value"]!=$filters["$fieldId"]["value"]) $pass=false;
            }
          }
        }
        $fields[]=$res2;
      }
      $res["field_values"]=$fields;
      $res["comments"]=$this->getOne("select count(*) from tiki_tracker_item_comments where itemId=$itid");
      if($pass) $ret[] = $res;
    }
    //$ret=$this->sort_items_by_condition($ret,$sort_mode);
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }
  /* End of shared listing functions */

  function MakeWikiZip()
  {
    $zipname         = "wikidb.zip";
    include_once("tar.class.php");
    $tar = new tar();
    $query = "select pageName from tiki_pages order by pageName asc";
    $result = $this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $page = $res["pageName"];
      $content = $this->export_wiki_page($page, 0);
      $tar->addData($page,$content,date("U"));
    }
    $tar->toTar("dump/export.tar",FALSE);
    return '';
  }

  function export_wiki_page($pageName,$nversions=1)
  {
    $info=$this->get_page_info($pageName);
    $head = '';
    $head .= "Date: " . $this->get_rfc2822_datetime($info["lastModif"]) . "\r\n";
    $head .= sprintf("Mime-Version: 1.0 (Produced by Tiki)\r\n");
    $iter = $this->get_page_history($pageName);
    $parts = array();
    $parts[]=MimeifyPageRevision($info);
    if($nversions>1 || $nversions==0) {
    foreach ($iter as $revision) {
        $parts[] = MimeifyPageRevision($revision);
        if ($nversions > 0 && count($parts) >= $nversions)
            break;
    }
    }
    if (count($parts) > 1)
        return $head . MimeMultipart($parts);
    assert($parts);
    return $head . $parts[0];
  }

  function set_user_avatar($user,$type,$avatarLibName,$avatarName,$avatarSize,$avatarType,$avatarData)
  {
    $avatarData = addslashes($avatarData);
    $avatarName = addslashes($avatarName);
    $query = "update users_users set
      avatarType = '$type',
      avatarLibName = '$avatarLibName',
      avatarName = '$avatarName',
      avatarSize = '$avatarSize',
      avatarFileType = '$avatarType',
      avatarData = '$avatarData'
      where login='$user'";
    $result = $this->query($query);
  }

  function get_user_avatar_img($user)
  {
    $query = "select * from users_users where login='$user'";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function get_user_avatar($user)
  {
    if(empty($user)) return '';
    if(!$this->user_exists($user)) {
      return '';
    }
    $type = $this->getOne("select avatarType from users_users where login='$user'");
    $libname = $this->getOne("select avatarLibName from users_users where login='$user'");
    $ret='';
    switch($type) {
      case 'n':
        $ret = '';
        break;
      case 'l':
        $ret = "<img width='45' height='45' src='".$libname."' />";
        break;
      case 'u':
        $ret = "<img width='45' height='45' src='tiki-show_user_avatar.php?user=$user' />";
        break;
    }
    return $ret;
  }

  /* Sections for forums */
  function get_forum_sections()
  {
    $query = "select distinct section from tiki_forums where section<>''";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[]=$res["section"];
    }
    return $ret;
  }

  /* Webmails */

  /* Referer stats */
  function register_referer($referer)
  {
     $referer = addslashes($referer);
     $now=date("U");
     $cant = $this->getOne("select count(*) from tiki_referer_stats where referer='$referer'");
     if($cant) {
       $query = "update tiki_referer_stats set hits=hits+1,last=$now where referer='$referer'";
     } else {
       $query = "insert into tiki_referer_stats(referer,hits,last) values('$referer',1,$now)";
     }
     $result = $this->query($query);
  }

  function clear_referer_stats()
  {
    $query = "delete from tiki_referer_stats";
    $result = $this->query($query);
  }

  function list_referer_stats($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (referer like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_referer_stats $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_referer_stats $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  /* referer stats */

  // File attachments functions for the wiki ////
  function add_wiki_attachment_hit($id)
  {
    $query = "update tiki_wiki_attachments set downloads=downloads+1 where attId=$id";
    $result = $this->query($query);
    return true;
  }

  function get_attachment_owner($attId)
  {
    return $this->getOne("select user from tiki_wiki_attachments where attId=$attId");
  }

  function list_wiki_attachments($page,$offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where page='$page' and (filename like '%".$find."%')";
    } else {
      $mid=" where page='$page' ";
    }
    $query = "select user,attId,page,filename,filesize,filetype,downloads,created,comment from tiki_wiki_attachments $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_wiki_attachments $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function wiki_attach_file($page,$name,$type,$size, $data, $comment, $user,$fhash)
  {
    $data = addslashes($data);
    $page = addslashes($page);
    $name = addslashes($name);
    $comment = addslashes(strip_tags($comment));
    $now = date("U");
    $query = "insert into tiki_wiki_attachments(page,filename,filesize,filetype,data,created,downloads,user,comment,path)
    values('$page','$name',$size,'$type','$data',$now,0,'$user','$comment','$fhash')";
    $result = $this->query($query);
  }

  function get_wiki_attachment($attId)
  {
    $query = "select * from tiki_wiki_attachments where attId=$attId";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function remove_wiki_attachment($attId)
  {
    global $w_use_dir;
    $path = $this->getOne("select path from tiki_wiki_attachments where attId=$attId");
    if($path) {
      @unlink($w_use_dir.$path);
    }
    $query = "delete from tiki_wiki_attachments where attId='$attId'";
    $result = $this->query($query);
  }

  // End File attachments functions for the wiki ////

  // Batch image uploads ////
    // Batch image uploads ////
    // Fixed by FLO
  function process_batch_image_upload($galleryId,$file,$user)
  {
    global $gal_match_regex;
    global $gal_nmatch_regex;
    global $gal_use_db;
    global $gal_use_dir;
    global $tmpDir;
    $numimages=0;
    include_once('lib/pclzip.lib.php');
    $archive = new PclZip($file);
    // Read Archive contents
    $ziplist=$archive->listContent();
    if (!$ziplist) return(false); // Archive invalid
    for ($i=0; $i<sizeof($ziplist); $i++) {
      $file=$ziplist["$i"]["filename"];
      if (!$ziplist["$i"]["folder"]) {
        //copied
        $gal_info = $this->get_gallery($galleryId);
        $upl=1;
        if(!empty($gal_match_regex)) {
          if(!preg_match("/$gal_match_regex/",$file,$reqs)) $upl=0;
        }
        if(!empty($gal_nmatch_regex)) {
          if(preg_match("/$gal_nmatch_regex/",$file,$reqs)) $upl=0;
        }
        //extract file

        $archive->extractByIndex($ziplist["$i"]["index"],$tmpDir,dirname($file)); //extract and remove (dangerous) pathname
        $file=basename($file);
        //determine filetype and dimensions
        $imageinfo=getimagesize($tmpDir."/".$file);
        if ($imageinfo["0"] > 0 && $imageinfo["1"] > 0 && $imageinfo["2"] > 0 ) {
          if (chkgd2()) {
            $type = $imageinfo["mime"];
          } else {
            $mimetypes=array("1" => "gif", "2" => "jpg", "3" => "png",
                             "4" => "swf", "5" => "psd", "6" => "bmp",
                             "7" => "tiff", "8" => "tiff", "9" => "jpc",
                             "10" => "jp2", "11" => "jpx", "12" => "jb2",
                             "13" => "swc", "14" => "iff");
            $type="image/".$mimetypes[$imageinfo["2"]];
          }
          $exp=substr($file,strlen($file)-3,3);
          $fp = fopen($tmpDir."/".$file,"rb");
          $size=filesize($tmpDir."/".$file);
          $data = fread($fp,$size);
          fclose($fp);
          if(function_exists("ImageCreateFromString")&&(!strstr($type,"gif"))) {
            $img = imagecreatefromstring($data);
            $size_x = imagesx($img);
            $size_y = imagesy($img);
            if ($size_x > $size_y)
              $tscale = ((int)$size_x / $gal_info["thumbSizeX"]);
            else
              $tscale = ((int)$size_y / $gal_info["thumbSizeY"]);
            $tw = ((int)($size_x / $tscale));
            $ty = ((int)($size_y / $tscale));
            if (chkgd2()) {
              $t = imagecreatetruecolor($tw,$ty);
              imagecopyresampled($t, $img, 0,0,0,0, $tw,$ty, $size_x, $size_y);
            } else {
              $t = imagecreate($tw,$ty);
              $this->ImageCopyResampleBicubic( $t, $img, 0,0,0,0, $tw,$ty, $size_x, $size_y);
            }
            // CHECK IF THIS TEMP IS WRITEABLE OR CHANGE THE PATH TO A WRITEABLE DIRECTORY
            //$tmpfname = 'temp.jpg';
            $tmpfname = tempnam ($tmpDir , "FOO").'.jpg';
            imagejpeg($t,$tmpfname);
            // Now read the information
            $fp = fopen($tmpfname,"rb");
            $t_data = fread($fp, filesize($tmpfname));
            fclose($fp);
            unlink($tmpfname);
            $t_pinfo = pathinfo($tmpfname);
            $t_type = $t_pinfo["extension"];
            $t_type='image/'.$t_type;
            $imageId = $this->insert_image($galleryId,$file,'',$file, $type, $data, $size, $size_x, $size_y, $user,$t_data,$t_type);
            $numimages++;
            unlink($tmpDir."/".$file);
          } else {
            $tmpfname='';
            $imageId = $this->insert_image($galleryId,$file,'',$file, $type, $data, $size, 0, 0, $user,'','');
            $numimages++;
            unlink($tmpDir."/".$file);
          }
        }
      }
    }
  return $numimages;
  }



  function process_batch_file_upload($galleryId,$file,$user,$description)
  {

    global $fgal_match_regex;
    global $fgal_nmatch_regex;
    global $fgal_use_db;
    global $fgal_use_dir;
    $description = addslashes($description);
    include_once('lib/pclzip.lib.php');
    $archive = new PclZip($file);
    $archive->extract('temp');
    $files=Array();
    $h = opendir("temp");
    $gal_info = $this->get_gallery($galleryId);
    while (($file = readdir($h)) !== false) {
    if( $file!='.' && $file!='..' && is_file("temp/$file") && $file!='license.txt' ) {
      $files[]=$file;
      // check filters
      $upl=1;
      if(!empty($fgal_match_regex)) {
        if(!preg_match("/$gal_match_regex/",$file,$reqs)) $upl=0;
      }
      if(!empty($fgal_nmatch_regex)) {
        if(preg_match("/$gal_nmatch_regex/",$file,$reqs)) $upl=0;
      }

      $fp = fopen('temp/'.$file,"rb");
      $data = '';
      $fhash='';
      if($fgal_use_db == 'n') {
        $fhash = md5($name = $file);
        @$fw = fopen($fgal_use_dir.$fhash,"w");
        if(!$fw) {
          $smarty->assign('msg',tra('Cannot write to this file:').$fhash);
          $smarty->display("styles/$style_base/error.tpl");
          die;
        }
      }
      while(!feof($fp)) {
        if($fgal_use_db == 'y') {
          $data .= fread($fp,8192*16);
        } else {
          $data = fread($fp,8192*16);
          fwrite($fw,$data);
        }
      }
      fclose($fp);
      if($fgal_use_db == 'n') {
        fclose($fw);
        $data='';
      }
      $size = filesize('temp/'.$file);
      $name = $file;
      $type = '';
      $fileId = $this->insert_file($galleryId,$name,$description,$name, $data, $size, '', $user,$fhash);
      unlink('temp/'.$file);
    }
  }
  closedir($h);
  }

  function register_search($words)
  {
   $words=addslashes($words);
   $words = preg_split("/\s/",$words);
   foreach($words as $word) {
     $word=trim($word);
     $cant = $this->getOne("select count(*) from tiki_search_stats where term='$word'");
     if($cant) {
       $query = "update tiki_search_stats set hits=hits+1 where term='$word'";
     } else {
       $query = "insert into tiki_search_stats(term,hits) values('$word',1)";
     }

     $result = $this->query($query);
   }
  }

  function clear_search_stats()
  {
    $query = "delete from tiki_search_stats";
    $result = $this->query($query);

  }

  function list_search_stats($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (term like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_search_stats $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_search_stats $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  // HTML pages ////
  function list_html_pages($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (name like '%".$find."%' or content like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select pageName,refresh,created,type from tiki_html_pages $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_html_pages $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_html_page_content($pageName,$offset,$maxRecords,$sort_mode,$find)
  {
    $pageName = addslashes($pageName);
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where pageName='$pageName' and (name like '%".$find."%' or content like '%".$find."%')";
    } else {
      $mid=" where pageName='$pageName'";
    }
    $query = "select * from tiki_html_pages_dynamic_zones $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_html_pages_dynamic_zones $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function parse_html_page($pageName,$data)
  {
    //The data is needed because we may be previewing a page...
    preg_match_all("/\{t?ed id=([^\}]+)\}/",$data,$eds);
    for($i=0;$i<count($eds[0]);$i++) {
        $cosa = $this->get_html_page_content($pageName,$eds[1][$i]);
        $data=str_replace($eds[0][$i],'<span id="'.$eds[1][$i].'">'.$cosa["content"].'</span>',$data);
    }
    $data=nl2br($data);
    return $data;
  }

  function replace_html_page($pageName, $type, $content, $refresh)
  {
    $pageName = addslashes($pageName);
    $content = addslashes($content);
    // Check the name
    $now = date("U");

    $query = "replace into tiki_html_pages(pageName,content,type,created,refresh)
              values('$pageName','$content','$type',$now,$refresh)";
    $result = $this->query($query);
     // For dynamic pages update the zones into the dynamic pages zone
    preg_match_all("/\{ed id=([^\}]+)\}/",$content,$eds);
    preg_match_all("/\{ted id=([^\}]+)\}/",$content,$teds);
    $all_eds = array_merge($eds[1],$teds[1]);

    $query = "select zone from tiki_html_pages_dynamic_zones where pageName='$pageName'";
    $result = $this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      if(!in_array($res["zone"],$all_eds)) {
        $query2="delete from tiki_html_pages_dynamic_zones where pageName='$pageName' and zone='".$res["zone"]."'";
        $result2 = $this->query($query2);
      }
    }

    for($i=0;$i<count($eds[0]);$i++) {
      if(!$this->getOne("select count(*) from tiki_html_pages_dynamic_zones where pageName='$pageName' and zone='".$eds[1][$i]."'")) {
      $query = "replace into tiki_html_pages_dynamic_zones(pageName,zone,type) values('$pageName','".$eds[1][$i]."','tx')";
      $result = $this->query($query);
      }
    }

    for($i=0;$i<count($teds[0]);$i++) {
      if(!$this->getOne("select count(*) from tiki_html_pages_dynamic_zones where pageName='$pageName' and zone='".$teds[1][$i]."'")) {
      $query = "replace into tiki_html_pages_dynamic_zones(pageName,zone,type) values('$pageName','".$teds[1][$i]."','ta')";
      $result = $this->query($query);
      }
    }


    return $pageName;
  }

  function replace_html_page_content($pageName, $zone, $content)
  {
    $pageName = addslashes($pageName);
    $content = addslashes($content);
    // Check the name
    $now = date("U");

    $query = "update tiki_html_pages_dynamic_zones set content='$content' where pageName='$pageName' and zone='$zone'";

    $result = $this->query($query);
    return $zone;
  }

  function remove_html_page($pageName)
  {
    $query = "delete from tiki_html_pages where pageName='$pageName'";
    $result = $this->query($query);
    return true;
  }

  function remove_html_page_content($pageName,$zone)
  {
    $pageName = addslashes($pageName);
    $query = "delete from tiki_html_pages_dynamic_zones where pageName='$pageName' and zone='$zone'";
    $result = $this->query($query);
    return true;
  }

  function get_html_page($pageName)
  {
    $pageName = addslashes($pageName);
    $query = "select * from tiki_html_pages where pageName='$pageName'";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function get_html_page_content($pageName,$zone)
  {
    $pageName = addslashes($pageName);
    $query = "select * from tiki_html_pages_dynamic_zones where pageName='$pageName' and zone='$zone'";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }
  // HTML pages ////

  // Last visit module ////
  function get_news_from_last_visit($user)
  {
    if(!$user) return false;
    $last = $this->getOne("select lastLogin from users_users where login='$user'");
    $ret = Array();
    $ret["lastVisit"] = $this->getOne("select lastLogin from users_users where login='$user'");
    $ret["images"] = $this->getOne("select count(*) from tiki_images where created>$last");
    $ret["pages"] = $this->getOne("select count(*) from tiki_pages where lastModif>$last");
    $ret["files"]  = $this->getOne("select count(*) from tiki_files where created>$last");
    $ret["comments"]  = $this->getOne("select count(*) from tiki_comments where commentDate>$last");
    $ret["users"]  = $this->getOne("select count(*) from users_users where registrationDate>$last");
    return $ret;
  }

  // Last visit module ////

  // ShoutBox ////
  function list_shoutbox($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (message like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_shoutbox $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_shoutbox $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      if(!$res["user"]) $res["user"]='Anonymous';
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function replace_shoutbox($msgId,$user,$message)
  {
    $hash = md5($message);
    $cant = $this->getOne("select count(*) from tiki_shoutbox where hash = '$hash' and user='$user'");
    if($cant) return;
    $message=addslashes(strip_tags($message,'<a>'));
    // Check the name
    $now=date("U");
    if($msgId) {
      $query = "update tiki_shoutbox set user='$user', message='$message', hash='$hash' where msgId=$msgId";
    } else {
      $query = "replace into tiki_shoutbox(message,user,timestamp,hash)
                values('$message','$user',$now,'$hash')";
    }
    $result = $this->query($query);
    return true;
  }

  function remove_shoutbox($msgId)
  {
    $query = "delete from tiki_shoutbox where msgId=$msgId";
    $result = $this->query($query);
    return true;
  }

  function get_shoutbox($msgId)
  {
    $query = "select * from tiki_shoutbox where msgId=$msgId";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  // ShoutBox ////

  function wiki_link_structure()
  {
    $query = "select pageName from tiki_pages order by pageName asc";
    $result = $this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      print($res["pageName"]." ");
      $page = $res["pageName"];
      $query2 = "select toPage from tiki_links where fromPage='$page'";
      $result2 = $this->query($query2);
      $pages=Array();
      while($res2 = $result2->fetchRow(DB_FETCHMODE_ASSOC)) {
        if( ($res2["toPage"]<>$res["pageName"]) && (!in_array($res2["toPage"],$pages)) ) {
          $pages[]=$res2["toPage"];
          print($res2["toPage"]." ");
        }
      }
      print("\n");
    }
  }

  function add_suggested_faq_question($faqId,$question, $answer, $user)
  {
    $question = addslashes(strip_tags($question,'<a>'));
    $answer = addslashes(strip_tags($answer,'<a>'));
    $now= date("U");
    $query = "insert into tiki_suggested_faq_questions(faqId,question,answer,user,created)
    values($faqId,'$question','$answer','$user',$now)";
    $result = $this->query($query);
  }

  function list_suggested_questions($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (question like '%".$find."%' or answer like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_suggested_faq_questions $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_suggested_faq_questions $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function get_suggested_question($sfqId)
  {
    $query = "select * from tiki_suggested_faq_questions where sfqId=$sfqId";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function remove_suggested_question($sfqId)
  {
    $query = "delete from tiki_suggested_faq_questions where sfqId=$sfqId";
    $result = $this->query($query);
  }

  function approve_suggested_question($sfqId)
  {
    $info = $this->get_suggested_question($sfqId);
    $this->replace_faq_question($info["faqId"],0, $info["question"], $info["answer"]);
    $this->remove_suggested_question($sfqId);
  }

  // Templates ////

  function list_all_templates($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (content like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select name,created,templateId from tiki_content_templates $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_content_templates $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $query2= "select section from tiki_content_templates_sections where templateId=".$res["templateId"];
      $result2 = $this->query($query2);
      $sections = Array();
      while($res2 = $result2->fetchRow(DB_FETCHMODE_ASSOC)) {
        $sections[] = $res2["section"];
      }
      $res["sections"]=$sections;
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_templates($section,$offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" and (content like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select name,created,tcts.templateId from tiki_content_templates tct, tiki_content_templates_sections tcts where tcts.templateId=tct.templateId and section='$section' $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_content_templates tct, tiki_content_templates_sections tcts where tcts.templateId=tct.templateId and section='$section' $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $query2= "select section from tiki_content_templates_sections where templateId=".$res["templateId"];
      $result2 = $this->query($query2);
      $sections = Array();
      while($res2 = $result2->fetchRow(DB_FETCHMODE_ASSOC)) {
        $sections[] = $res2["section"];
      }
      $res["sections"]=$sections;
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function replace_template($templateId, $name, $content)
  {
    $name = addslashes($name);
    $content = addslashes($content);
    // Check the name
    $now = date("U");
    if($templateId) {
      $query = "update tiki_content_templates set content='$content', name='$name', created=$now where templateId=$templateId";
    } else {
      $query = "replace into tiki_content_templates(content,name,created)
                values('$content','$name',$now)";
    }
    $result = $this->query($query);
    $id  = $this->getOne("select max(templateId) from tiki_content_templates where created=$now and name='$name'");
    return $id;
    return true;
  }

  function add_template_to_section($templateId,$section)
  {
    $query = "replace into tiki_content_templates_sections(templateId,section) values($templateId,'$section')";
    $result = $this->query($query);
  }

  function remove_template_from_section($templateId,$section)
  {
    $query = "delete from tiki_content_templates_sections where templateId=$templateId and section='$section'";
    $result = $this->query($query);
  }

  function template_is_in_section($templateId,$section)
  {
    $cant = $this->getOne("select count(*) from tiki_content_templates_sections where templateId=$templateId and section='$section'");
    return $cant;
  }

  function remove_template($templateId)
  {
    $query = "delete from tiki_content_templates where templateId=$templateId";
    $result = $this->query($query);
    $query = "delete from tiki_content_templates_sections where templateId=$templateId";
    $result = $this->query($query);
    return true;
  }

  function get_template($templateId)
  {
    $query = "select * from tiki_content_templates where templateId=$templateId";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }
  // templates ////

  function add_game_hit($game)
  {
    $cant = $this->getOne("select count(*) from tiki_games where gameName='$game'");
    if($cant) {
      $query = "update tiki_games set hits = hits+1 where gameName='$game'";
    } else {
      $query = "insert into tiki_games(gameName,hits,points,votes) values('$game',1,0,0)";
    }
    $result = $this->query($query);
  }

  function get_game_hits($game)
  {
    $cant = $this->getOne("select count(*) from tiki_games where gameName='$game'");
    if($cant) {
      $hits = $this->getOne("select hits from tiki_games where gameName='$game'");
    } else {
      $hits =0;
    }
    return $hits;
  }

  function list_games($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (gameName like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_games $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_games $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $parts=explode('.',$res["gameName"]);
      $res["thumbName"]=$parts[0];
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_cookies($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (cookie like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_cookies $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_cookies $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function replace_cookie($cookieId, $cookie)
  {
    $cookie=addslashes($cookie);
    // Check the name

    if($cookieId) {
      $query = "update tiki_cookies set cookie='$cookie' where cookieId=$cookieId";
    } else {
      $query = "replace into tiki_cookies(cookie)
                values('$cookie')";
    }
    $result = $this->query($query);
    return true;
  }

  function remove_cookie($cookieId)
  {
    $query = "delete from tiki_cookies where cookieId=$cookieId";
    $result = $this->query($query);
    return true;
  }

  function get_cookie($cookieId)
  {
    $query = "select * from tiki_cookies where cookieId=$cookieId";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function remove_all_cookies()
  {
    $query = "delete from tiki_cookies";
    $result = $this->query($query);
  }

  function pick_cookie()
  {
    $cant = $this->getOne("select count(*) from tiki_cookies");
    if(!$cant) return '';
    $bid = rand(0,$cant-1);
    $cookie = $this->getOne("select cookie from tiki_cookies limit $bid,1");
    $cookie = str_replace("\n","",$cookie);
    return '<i>"'.$cookie.'"</i>';
  }

  // Stats ////
  function add_pageview()
  {
    $dayzero = mktime(0,0,0,date("m"),date("d"),date("Y"));
    $cant = $this->getOne("select count(*) from tiki_pageviews where day=$dayzero");
    if($cant) {
      $query = "update tiki_pageviews set pageviews=pageviews+1 where day=$dayzero";
    } else {
      $query = "replace into tiki_pageviews(day,pageviews) values($dayzero,1)";
    }
    $result = $this->query($query);
  }

  function wiki_stats()
  {
    $stats=Array();
    $stats["pages"]=$this->getOne("select count(*) from tiki_pages");
    $stats["versions"]=$this->getOne("select count(*) from tiki_history");
    if($stats["pages"]) $stats["vpp"]=$stats["versions"]/$stats["pages"]; else $stats["vpp"]=0;
    $stats["visits"]=$this->getOne("select sum(hits) from tiki_pages");
    $or = $this->list_orphan_pages(0,-1, 'pageName_desc','');
    $stats["orphan"]=$or["cant"];
    $links = $this->getOne("select count(*) from tiki_links");
    if($stats["pages"]) $stats["lpp"]=$links/$stats["pages"]; else $stats["lpp"]=0;
    $stats["size"] = $this->getOne("select sum(length(data)) from tiki_pages");
    if($stats["pages"]) $stats["bpp"]=$stats["size"]/$stats["pages"]; else $stats["bpp"]=0;
    $stats["size"] = $stats["size"]/1000000;
    return $stats;
  }

  function quiz_stats()
  {
    $this->compute_quiz_stats();
    $stats=Array();
    $stats["quizzes"]=$this->getOne("select count(*) from tiki_quizzes");
    $stats["questions"]=$this->getOne("select count(*) from tiki_quiz_questions");
    if($stats["quizzes"]) $stats["qpq"]=$stats["questions"]/$stats["quizzes"]; else $stats["qpq"]=0;
    $stats["visits"]=$this->getOne("select sum(timesTaken) from tiki_quiz_stats_sum");
    $stats["avg"]=$this->getOne("select avg(avgavg) from tiki_quiz_stats_sum");
    $stats["avgtime"]=$this->getOne("select avg(avgtime) from tiki_quiz_stats_sum");
    return $stats;
  }

  function image_gal_stats()
  {
    $stats=Array();
    $stats["galleries"]=$this->getOne("select count(*) from tiki_galleries");
    $stats["images"]=$this->getOne("select count(*) from tiki_images");
    $stats["ipg"] = ($stats["galleries"]?$stats["images"]/$stats["galleries"]:0);
    $stats["size"] = $this->getOne("select sum(filesize) from tiki_images_data where type='o'");
    //$stats["bpi"] = ($stats["galleries"]?$stats["size"]/$stats["galleries"]:0);
    $stats["bpi"] = ($stats["images"]?$stats["size"]/$stats["images"]:0);
    $stats["size"] = $stats["size"]/1000000;
    $stats["visits"] = $this->getOne("select sum(hits) from tiki_galleries");
    return $stats;
  }

  function file_gal_stats()
  {
    $stats=Array();
    $stats["galleries"]=$this->getOne("select count(*) from tiki_file_galleries");
    $stats["files"]=$this->getOne("select count(*) from tiki_files");
    $stats["fpg"] = ($stats["galleries"]?$stats["files"]/$stats["galleries"]:0);
    $stats["size"] = $this->getOne("select sum(filesize) from tiki_files");
    $stats["size"] = $stats["size"]/1000000;
    $stats["bpf"] = ($stats["galleries"]?$stats["size"]/$stats["galleries"]:0);
    $stats["visits"] = $this->getOne("select sum(hits) from tiki_file_galleries");
    $stats["downloads"] = $this->getOne("select sum(downloads) from tiki_files");
    return $stats;
  }

  function cms_stats()
  {
    $stats=Array();
    $stats["articles"]=$this->getOne("select count(*) from tiki_articles");
    $stats["reads"]=$this->getOne("select sum(reads) from tiki_articles");
    $stats["rpa"]=($stats["articles"]?$stats["reads"]/$stats["articles"]:0);
    $stats["size"] = $this->getOne("select sum(size) from tiki_articles");
    $stats["bpa"]=($stats["articles"]?$stats["size"]/$stats["articles"]:0);
    $stats["topics"]=$this->getOne("select count(*) from tiki_topics where active='y'");
    return $stats;
  }

  function forum_stats()
  {
    $stats=Array();
    $stats["forums"]=$this->getOne("select count(*) from tiki_forums");
    $stats["topics"]=$this->getOne("select count(*) from tiki_comments,tiki_forums where object=md5(concat('forum',forumId)) and parentId=0");
    $stats["threads"]=$this->getOne("select count(*) from tiki_comments,tiki_forums where object=md5(concat('forum',forumId)) and parentId<>0");
    $stats["tpf"]=($stats["forums"]?$stats["topics"]/$stats["forums"]:0);
    $stats["tpt"]=($stats["topics"]?$stats["threads"]/$stats["topics"]:0);
    $stats["visits"]=$this->getOne("select sum(hits) from tiki_forums");
    return $stats;
  }

  function blog_stats()
  {
    $stats=Array();
    $stats["blogs"]=$this->getOne("select count(*) from tiki_blogs");
    $stats["posts"]=$this->getOne("select count(*) from tiki_blog_posts");
    $stats["ppb"]=($stats["blogs"]?$stats["posts"]/$stats["blogs"]:0);
    $stats["size"]=$this->getOne("select sum(length(data)) from tiki_blog_posts");
    $stats["bpp"]=($stats["posts"]?$stats["size"]/$stats["posts"]:0);
    $stats["visits"]=$this->getOne("select sum(hits) from tiki_blogs");
    return $stats;
  }

  function poll_stats()
  {
    $stats=Array();
    $stats["polls"]=$this->getOne("select count(*) from tiki_polls");
    $stats["votes"]=$this->getOne("select sum(votes) from tiki_poll_options");
    $stats["vpp"]=($stats["polls"]?$stats["votes"]/$stats["polls"]:0);
    return $stats;
  }

  function faq_stats()
  {
    $stats=Array();
    $stats["faqs"]=$this->getOne("select count(*) from tiki_faqs");
    $stats["questions"]=$this->getOne("select count(*) from tiki_faq_questions");
    $stats["qpf"]=($stats["faqs"]?$stats["questions"]/$stats["faqs"]:0);
    return $stats;
  }

  function user_stats()
  {
    $stats=Array();
    $stats["users"]=$this->getOne("select count(*) from users_users");
    $stats["bookmarks"]=$this->getOne("select count(*) from tiki_user_bookmarks_urls");
    $stats["bpu"]=($stats["users"]?$stats["bookmarks"]/$stats["users"]:0);
    return $stats;
  }

  function site_stats()
  {
    $stats=Array();
    $stats["started"] = $this->getOne("select min(day) from tiki_pageviews");
    $stats["days"]=$this->getOne("select count(*) from tiki_pageviews");
    $stats["pageviews"]=$this->getOne("select sum(pageviews) from tiki_pageviews");
    $stats["ppd"]=($stats["days"]?$stats["pageviews"]/$stats["days"]:0);
    $stats["bestpvs"]=$this->getOne("select max(pageviews) from tiki_pageviews");
    $stats["bestday"]=$this->getOne("select day from tiki_pageviews where pageviews=".$stats["bestpvs"]);
    $stats["worstpvs"]=$this->getOne("select min(pageviews) from tiki_pageviews");
    $stats["worstday"]=$this->getOne("select day from tiki_pageviews where pageviews=".$stats["worstpvs"]);
    return $stats;
  }

  function get_pv_chart_data($days)
  {
    $now = mktime(0,0,0,date("m"),date("d"),date("Y"));
    $dfrom = $now-(7*24*60*60);
    $query = "select pageviews from tiki_pageviews where day<=$now and day>=$dfrom";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $data=Array("",$res["pageviews"]);
      $ret[]=$data;
    }

   return $ret;
  }

  function get_usage_chart_data()
  {
    $this->compute_quiz_stats();
    $data=Array();
    $data[]=Array("wiki",$this->getOne("select sum(hits) from tiki_pages"));
    $data[]=Array("img-g",$this->getOne("select sum(hits) from tiki_galleries"));
    $data[]=Array("file-g",$this->getOne("select sum(hits) from tiki_file_galleries"));
    $data[]=Array("faqs",$this->getOne("select sum(hits) from tiki_faqs"));
    $data[]=Array("quizzes",$this->getOne("select sum(timesTaken) from tiki_quiz_stats_sum"));
    $data[]=Array("arts",$this->getOne("select sum(reads) from tiki_articles"));
    $data[]=Array("blogs",$this->getOne("select sum(hits) from tiki_blogs"));
    $data[]=Array("forums",$this->getOne("select sum(hits) from tiki_forums"));
    $data[]=Array("games",$this->getOne("select sum(hits) from tiki_games"));
   return $data;
  }

  // Stats ////

  // User assigned modules ////
  function get_user_id($user)
  {
    $id = $this->db->getOne("select userId from users_users where login='$user'");
    if(DB::isError($id)) return false;
    return $id;
  }

  // not used anymore ??? in userslib!
  function get_user_groups($user)
  {
    $userid = $this->get_user_id($user);
    $query = "select groupName from users_usergroups where userId='$userid'";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res["groupName"];
    }
    $ret[] = "Anonymous";
    return $ret;
  }

  function unassign_user_module($name,$user)
  {
    $query = "delete from tiki_user_assigned_modules where name='$name' and user='$user'";
    $result = $this->query($query);
  }

  function up_user_module($name,$user)
  {
    $query = "update tiki_user_assigned_modules set ord=ord-1 where name='$name' and user='$user'";
    $result = $this->query($query);
  }

  function down_user_module($name,$user)
  {
    $query = "update tiki_user_assigned_modules set ord=ord+1 where name='$name' and user='$user'";
    $result = $this->query($query);
  }

  function set_column_user_module($name,$user,$position)
  {
    $query = "update tiki_user_assigned_modules set position='$position' where name='$name' and user='$user'";
    $result = $this->query($query);
  }

  function assign_user_module($module,$position,$order,$user)
  {
    $query = "select * from tiki_modules where name='$module'";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    $query2 = "replace into tiki_user_assigned_modules(user,name,position,ord,type,title,cache_time,rows,groups)
    values('$user','$module','$position',$order,'${res["type"]}','${res["title"]}','${res["cache_time"]}','${res["rows"]}','${res["groups"]}')";
    $result2 = $this->query($query2);
  }

  function get_user_assigned_modules($user)
  {

    $query = "select * from tiki_user_assigned_modules where user='$user' order by position asc,ord asc";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }

    return $ret;
  }

  function get_assigned_modules_user($user,$position)
  {
    $query = "select * from tiki_user_assigned_modules where user='$user' and position='$position' order by ord asc";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    return $ret;
  }

  function user_has_assigned_modules($user)
  {
    $query = "select name from tiki_user_assigned_modules where user='$user'";
    $result = $this->query($query);
    return $result->numRows();
  }

  // Creates user assigned modules copying from tiki_modules
  function create_user_assigned_modules($user)
  {
    $query = "delete from tiki_user_assigned_modules where user='$user'";
    $result = $this->query($query);
    global $modallgroups;
    $query = "select * from tiki_modules";
    $result = $this->query($query);
    $ret = Array();
    $user_groups = $this->get_user_groups($user);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $mod_ok=0;
      if($res["groups"] && $modallgroups!='y') {
        $groups = unserialize($res["groups"]);
        $ins = array_intersect($groups, $user_groups);
        if(count($ins)>0) {
          $mod_ok =1;
        }
      } else {
          $mod_ok =1;
      }
      if($mod_ok) {
        $query2 = "replace into tiki_user_assigned_modules(user,name,position,ord,type,title,cache_time,rows,groups,params)
        values('$user','${res["name"]}','${res["position"]}','${res["ord"]}','${res["type"]}','${res["title"]}','${res["cache_time"]}','${res["rows"]}','${res["groups"]}','${res["params"]}')";
        $result2 = $this->query($query2);
      }
    }
  }

  // Return the list of modules that CAN be assigned by the user (he may have assigned or not the modules)
  function get_user_assignable_modules($user)
  {
    global $modallgroups;
    $query = "select * from tiki_modules";
    $result = $this->query($query);
    $ret = Array();
    $user_groups = $this->get_user_groups($user);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $mod_ok=0;
      // The module must not be assigned
      $isas = $this->getOne("select count(*) from tiki_user_assigned_modules where name='".$res["name"]."'");
      if(!$isas) {
        if($res["groups"] && $modallgroups!='y' && $user!='admin') {
          $groups = unserialize($res["groups"]);
          $ins = array_intersect($groups, $user_groups);
          if(count($ins)>0) {
            $mod_ok =1;
          }
        } else {
            $mod_ok =1;
        }
        if($mod_ok) {
          $ret[]=$res;
        }
      }
    }
    return $ret;
  }

  // User assigned modules ////

  // User bookmarks ////
  function get_folder_path($folderId,$user)
  {
    $path = '';
    $info = $this->get_folder($folderId,$user);
    $path = '<a class="link" href=tiki-user_bookmarks.php?parentId="'.$info["folderId"].'">'.$info["name"].'</a>';
    while($info["parentId"]!=0) {
      $info = $this->get_folder($info["parentId"],$user);
      $path = $path = '<a class="link" href=tiki-user_bookmarks.php?parentId="'.$info["folderId"].'">'.$info["name"].'</a>'.'>'.$path;
    }
    return $path;
  }

  function get_folder($folderId,$user)
  {
    $query = "select * from tiki_user_bookmarks_folders where folderId=$folderId and user='$user'";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function get_url($urlId)
  {
    $query = "select * from tiki_user_bookmarks_urls where urlId=$urlId";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function remove_url($urlId,$user)
  {
    $query = "delete from tiki_user_bookmarks_urls where urlId=$urlId and user='$user'";
    $result = $this->query($query);
    return true;
  }

  function remove_folder($folderId,$user)
  {
    // Delete the category
    $query = "delete from tiki_user_bookmarks_folders where folderId=$folderId and user='$user'";
    $result = $this->query($query);
    // Remove objects for this category
    $query = "delete from tiki_user_bookmarks_urls where folderId=$folderId and user='$user'";
    $result = $this->query($query);
    // SUbfolders
    $query = "select folderId from tiki_user_bookmarks_folders where parentId=$folderId and user='$user'";
    $result = $this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      // Recursively remove the subcategory
      $this->remove_folder($res["folderId"],$user);
    }
    return true;
  }

  function update_folder($folderId,$name,$user)
  {
    $name = addslashes($name);
    $query = "update tiki_user_bookmarks_folders set name='$name' where folderId=$folderId and user='$user'";
    $result = $this->query($query);
  }

  function add_folder($parentId,$name,$user)
  {
    $name = addslashes($name);
    $query = "insert into tiki_user_bookmarks_folders(name,parentId,user) values('$name',$parentId,'$user')";
    $result = $this->query($query);
  }

  function replace_url($urlId,$folderId,$name,$url,$user)
  {
    $now = date("U");
    $name = addslashes($name);
    if($urlId) {
      $query = "update tiki_user_bookmarks_urls set user='$user',lastUpdated=$now,folderId=$folderId,name='$name',url='$url' where urlId=$urlId";
    } else {
      $query = " insert into tiki_user_bookmarks_urls(name,url,data,lastUpdated,folderId,user)
      values('$name','$url','',$now,$folderId,'$user')";
    }
    $result = $this->query($query);
    $id = $this->getOne("select max(urlId) from tiki_user_bookmarks_urls where url='$url' and lastUpdated=$now");
    return $id;
  }

  function refresh_url($urlId)
  {
    $info = $this->get_url($urlId);
    @$fp = fopen($info["url"],"r");
    if(!$fp) return;
    $data = '';
    while(!feof($fp)) {
      $data .= fread($fp,4096);
    }
    fclose($fp);
    $data = addslashes($data);
    $now = date("U");
    $query = "update tiki_user_bookmarks_urls set lastUpdated=$now, data='$data' where urlId=$urlId";
    $result = $this->query($query);
    return true;
  }

  function list_folder($folderId,$offset,$maxRecords,$sort_mode='name_asc',$find,$user)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" and name like '%".$find."%' or url like '%".$find."% '";
    } else {
      $mid="";
    }
    $query = "select * from tiki_user_bookmarks_urls where folderId=$folderId and user='$user' $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select  * from tiki_user_bookmarks_urls where folderId=$folderId and user='$user' $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $res["datalen"]=strlen($res["data"]);
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function get_child_folders($folderId,$user)
  {
    $ret=Array();
    $query = "select * from tiki_user_bookmarks_folders where parentId=$folderId and user='$user'";
    $result = $this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $cant = $this->getOne("select count(*) from tiki_user_bookmarks_urls where folderId=".$res["folderId"]);
      $res["urls"]=$cant;
      $ret[]=$res;
    }
    return $ret;
  }

  // User bookmarks ////

  // Functions for FAQs ////
  function list_faqs($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (title like '%".$find."%' or description like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_faqs $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_faqs $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $res["suggested"]=$this->getOne("select count(*) from tiki_suggested_faq_questions where faqId=".$res["faqId"]);
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_faq_questions($faqId,$offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where faqId=$faqId and (question like '%".$find."%' or answer like '%".$find."%')";
    } else {
      $mid=" where faqId=$faqId ";
    }
    $query = "select * from tiki_faq_questions $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_faq_questions $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $res['parsed'] = $this->parse_data($res['answer']);
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_all_faq_questions($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (question like '%".$find."%' or answer like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_faq_questions $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_faq_questions $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function add_faq_hit($faqId)
  {
    $query = "update tiki_faqs set hits=hits+1 where faqId=$faqId";
    $result = $this->query($query);
  }

  function replace_faq($faqId, $title, $description,$canSuggest)
  {
    $description = addslashes($description);
    $title = addslashes($title);
    // Check the name

    if($faqId) {
      $query = "update tiki_faqs set title='$title',description='$description',canSuggest='$canSuggest' where faqId=$faqId";
      $result = $this->query($query);
    } else {
      $now = date("U");
      $query = "replace into tiki_faqs(title,description,created,hits,questions,canSuggest)
                values('$title','$description',$now,0,0,'$canSuggest')";
      $result = $this->query($query);
      $faqId = $this->getOne("select max(faqId) from tiki_faqs where title='$title' and created=$now");
    }
    return $faqId;
  }

  function replace_faq_question($faqId,$questionId, $question, $answer)
  {
    $question = addslashes($question);
    $answer = addslashes($answer);
    // Check the name

    if($questionId) {
      $query = "update tiki_faq_questions set question='$question',answer='$answer' where questionId=$questionId";
    } else {
      $query = "update tiki_faqs set questions=questions+1 where faqId=$faqId";
      $result = $this->query($query);
      $query = "replace into tiki_faq_questions(faqId,question,answer)
                values($faqId,'$question','$answer')";
    }

    $result = $this->query($query);
    return true;
  }

  function remove_faq($faqId)
  {
    $query = "delete from tiki_faqs where faqId=$faqId";
    $result = $this->query($query);
    $query = "delete from tiki_faq_questions where faqId=$faqId";
    $result = $this->query($query);
    // Remove comments and/or individual permissions for faqs
    $this->remove_object('faq',$faqId);
    return true;
  }

  function remove_faq_question($questionId)
  {
    $faqId=$this->getOne("select faqId from tiki_faq_questions where questionId=$questionId");
    $query = "delete from tiki_faq_questions where questionId=$questionId";
    $result = $this->query($query);
    $query = "update tiki_faqs set questions=questions-1 where faqId=$faqId";
    $result = $this->query($query);
    return true;
  }

  function get_faq($faqId)
  {
    $query = "select * from tiki_faqs where faqId=$faqId";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function get_faq_question($questionId)
  {
    $query = "select * from tiki_faq_questions where questionId=$questionId";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  // End Faqs ////

  function genPass()
  {
        $vocales="aeiou";
        $consonantes="bcdfghjklmnpqrstvwxyz";
        $r='';
        for($i=0; $i<5; $i++){
                if ($i%2){
                        $r.=$vocales{rand(0,strlen($vocales)-1)};
                }else{
                        $r.=$consonantes{rand(0,strlen($consonantes)-1)};
                }
        }
        return $r;
  }

  function restore_database($filename)
  {
    // Get the password before it's too late
    $query = "select hash from users_users where login='Admin'";
    $pwd = $this->getOne($query);

    // Before anything read tiki.sql from db and run it
    $fp = fopen("db/tiki.sql","r");
    $data = fread($fp,filesize("db/tiki.sql"));
    fclose($fp);
    // Drop all the tables
    preg_match_all("/DROP ([^;]+);/i",$data,$reqs);
    foreach($reqs[0] as $query)
    {
      //print("q: $query<br/>");
      $result = $this->query($query);
    }

    // Create all the tables
    preg_match_all("/create table ([^;]+);/i",$data,$reqs);
    foreach($reqs[0] as $query)
    {
      //print("q: $query<br/>");
      $result = $this->query($query);
    }



    $query = "update users_users set password = '$pwd' where login='admin'";
    $result = $this->query($query);
    @$fp = fopen($filename,"rb");
    if(!$fp) return false;
    while(!feof($fp)) {
      $rlen = fread($fp,4);
      if(feof($fp)) break;
      $len = unpack("L",$rlen);
      $len=array_pop($len);
      //print("leer: $len bytes<br/>");
      $line=fread($fp,$len);
      $line=$this->RC4($pwd,$line);
      // EXECUTE SQL SENTENCE HERE
      //print("q: $line <br/>");
      $result = $this->query($line);
    }
    fclose($fp);
  }

  function RC4($pwd, $data) {
    $key[] = "";
    $box[] = "";
    $temp_swap = "";
    $pwd_length = 0;
    $pwd_length = strlen($pwd);
    for ($i = 0; $i <= 255; $i++) {
      $key[$i] = ord(substr($pwd, ($i % $pwd_length)+1, 1));
      $box[$i] = $i;
    }

    $x = 0;
    for ($i = 0; $i < 255; $i++) {
      $x = ($x + $box[$i] + $key[$i]) % 256;
      $temp_swap = $box[$i];
      $box[$i] = $box[$x];
      $box[$x] = $temp_swap;
    }

    $temp = "";
    $k = "";
    $cipherby = "";
    $cipher = "";
    $a = 0;
    $j = 0;
    for ($i = 0; $i < strlen($data); $i++) {
      $a = ($a + 1) % 256;
      $j = ($j + $box[$a]) % 256;
      $temp = $box[$a];
      $box[$a] = $box[$j];
      $box[$j] = $temp;
      $k = $box[(($box[$a] + $box[$j]) % 256)];
      $cipherby = ord(substr($data, $i, 1)) ^ $k;
      $cipher .= chr($cipherby);
    }
    return $cipher;
  }

  // Functions to backup the database (mysql?)
  function backup_database($filename)
  {
    ini_set("max_execution_time", "3000");
    $query = "select hash from users_users where login='Admin'";
    $pwd = $this->getOne($query);
    @$fp = fopen($filename,"w");
    if(!$fp) return false;

    $query = "show tables";
    $result = $this->query($query);
    $sql='';
    $part = '';
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      list($key,$val)=each($res);
      if(!strstr($val,'babl')){
        // Now dump the table
        $query2 = "select * from $val";
        $result2 = $this->query($query2);
        while($res2 = $result2->fetchRow(DB_FETCHMODE_ASSOC)) {
          $sentence = "values(";
          $first=1;
          foreach($res2 as $field => $value) {
            if($first) {
              $sentence.="'".addslashes($value)."'";
              $first =0;
              $fields = '('.$field;
            } else {
              $sentence.=",'".addslashes($value)."'";
              $fields .= ",$field";
            }
          }
          $fields.= ')';
          $sentence .= ")";
          $part = "insert into $val $fields $sentence;";
          $len = pack("L",strlen($part));
          fwrite($fp,$len);
          $part = $this->RC4($pwd,$part);
          fwrite($fp,$part);
        }
      }

    }
    // And now print!

    fclose($fp);
    return true;
  }

  // Get a listing of orphan pages
  function list_orphan_pages($offset = 0, $maxRecords = -1, $sort_mode = 'pageName_desc',$find='')
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($sort_mode == 'size desc') {
      $sort_mode = ' length(data) desc';
    }
    if($sort_mode == 'size asc') {
      $sort_mode = ' length(data) asc';
    }
    $old_sort_mode ='';
    if(in_array($sort_mode,Array('versions desc','versions asc','links asc','links desc','backlinks asc','backlinks desc'))) {
      $old_offset = $offset;
      $old_maxRecords = $maxRecords;
      $old_sort_mode = $sort_mode;
      $sort_mode ='user desc';
      $offset = 0;
      $maxRecords = -1;
    }

    if($find) {
      $mid=" where pageName like '%".$find."%' ";
    } else {
      $mid="";
    }

    // If sort mode is versions then offset is 0, maxRecords is -1 (again) and sort_mode is nil
    // If sort mode is links then offset is 0, maxRecords is -1 (again) and sort_mode is nil
    // If sort mode is backlinks then offset is 0, maxRecords is -1 (again) and sort_mode is nil
    $query = "select pageName, hits, length(data) as len ,lastModif, user, ip, comment, version, flag from tiki_pages $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_pages $mid";
    $result = $this->query($query);
    $result_cant = $this->query($query_cant);
    $res2 = $result_cant->fetchRow();
    $cant = $res2[0];
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $pageName = $res["pageName"];
      $queryc = "select count(*) from tiki_links where toPage='$pageName'";
      $cant = $this->getOne($queryc);
      if($cant==0) {
        $aux = Array();
        $aux["pageName"] = $pageName;
        $page = $aux["pageName"];
        $aux["hits"] = $res["hits"];
        $aux["lastModif"] = $res["lastModif"];
        $aux["user"] = $res["user"];
        $aux["ip"] = $res["ip"];
        $aux["len"] = $res["len"];
        $aux["comment"] = $res["comment"];
        $aux["version"] = $res["version"];
        $aux["flag"] = $res["flag"] == 'y' ? tra('locked') : tra('unlocked');
        $aux["versions"] = $this->getOne("select count(*) from tiki_history where pageName='$page'");
        $aux["links"] = $this->getOne("select count(*) from tiki_links where fromPage='$page'");
        $aux["backlinks"] = $this->getOne("select count(*) from tiki_links where toPage='$page'");
        $ret[] = $aux;
      }
    }
    // If sortmode is versions, links or backlinks sort using the ad-hoc function and reduce using old_offse and old_maxRecords
    if($old_sort_mode == 'versions asc') {
      usort($ret,'compare_versions');
    }
    if($old_sort_mode == 'versions desc') {
      usort($ret,'r_compare_versions');
    }
    if($old_sort_mode == 'links desc') {
      usort($ret,'compare_links');
    }
    if($old_sort_mode == 'links asc') {
      usort($ret,'r_compare_links');
    }
    if($old_sort_mode == 'backlinks desc') {
      usort($ret,'compare_backlinks');
    }
    if($old_sort_mode == 'backlinks asc') {
      usort($ret,'r_compare_backlinks');
    }
    if(in_array($old_sort_mode,Array('versions desc','versions asc','links asc','links desc','backlinks asc','backlinks desc'))) {
      $ret = array_slice($ret, $old_offset, $old_maxRecords);
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  // This function calculates the pageRanks for the tiki_pages
  // it can be used to compute the most relevant pages
  // according to the number of links they have
  // this can be a very interesting ranking for the Wiki
  // More about this on version 1.3 when we add the pageRank
  // column to tiki_pages
  function pageRank($loops=16)
  {
    $query = "select pageName from tiki_pages";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[]=$res["pageName"];
    }
    // Now calculate the loop
    $pages = Array();
    foreach ($ret as $page) {
      $val = 1/count($ret);
      $pages[$page] = $val;
      $query = "update tiki_pages set pageRank=$val where pageName='$page'";
      $result = $this->query($query);
    }
    for($i=0;$i<$loops;$i++) {
      foreach($pages as $pagename => $rank) {
        // Get all the pages linking to this one
        $query = "select fromPage from tiki_links where toPage = '$pagename'";
        $result = $this->query($query);

        $sum = 0;
        while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
          $linking = $res["fromPage"];
          $q2 = "select count(*) from tiki_links where fromPage='$linking'";
          $cant = $this->getOne($q2);
          if($cant==0) $cant=1;
          $sum += $pages[$linking] / $cant;
        }
         $val = (1-0.85)+0.85 * $sum;
         $pages[$pagename] = $val;
         $query = "update tiki_pages set pageRank=$val where pageName='$pagename'";
         $result = $this->query($query);

        // Update
      }
    }
    arsort($pages);
    return $pages;
  }

  // Spellchecking routine
  // Parameters:
  // what: what to spell check (a text)
  // where: where to replace (maybe the same text)
  // language: language to use
  // element: element where the text is going to be replaced (a textarea or similar)
  function spellcheckreplace($what,$where,$language,$element)
  {
    global $smarty;
    $trl='';
    $words = preg_split("/\s/",$what);
    foreach($words as $word) {
    if(preg_match("/^[A-Z]?[a-z]+$/",$word) && strlen($word)>1) {
      $result = $this->spellcheckword($word,$language);
        if(count($result)>0) {
          // Replace the word with a warning color in the edit_data
          // Prepare the replacement
          $sugs = $result[$word];
          $first=1;
          $repl='';

          $popup_text='';
          //foreach($sugs as $sug=>$lev) {
          //  if($first) {
          //    $repl.=' <span style="color:red;">'.$word.'</span>'.'<a title="'.$sug.'" style="text-decoration: none; color:red;" href="javascript:replaceSome(\'editwiki\',\''.$word.'\',\''.$sug.'\');">.</a>';
          //    $first = 0;
          //  } else {
          //    $repl.='<a title="'.$sug.'" style="text-decoration: none; color:red;" href="javascript:replaceSome(\'editwiki\',\''.$word.'\',\''.$sug.'\');">.</a>';
          //    //$repl.='|'.'<a style="color:red;" href="javascript:replaceSome(\'editwiki\',\''.$word.'\',\''.$sug.'\');">'.$sug.'</a>';
          //  }
          //}
          //if($repl) {
          //  $repl.=' ';
          //}
          if(count($sugs)>0) {
            $asugs = array_keys($sugs);
            for($i=0;$i<count($asugs)&&$i<5;$i++) {
              $sug = $asugs[$i];
              //$repl.="<script>param_${word}_$i = new Array(\\\"$element\\\",\\\"$word\\\",\\\"$sug\\\");</script><a href=\\\"javascript:replaceLimon(param_${word}_$i);\\"."\">$sug</a><br/>";
              $repl.="<a href=\\\"javascript:param=doo_${word}_$i();replaceLimon(param);\\\">$sug</a><br/>";
              $trl.="<script>function doo_${word}_$i(){ aux = new Array(\"$element\",\"$word\",\"$sug\"); return aux;}</script>";

            }
            //$popup_text = " <a title=\"".$sug."\" style=\"text-decoration:none; color:red;\" onClick='"."return overlib(".'"'.$repl.'"'.",STICKY,CAPTION,".'"'."SpellChecker suggestions".'"'.");'>".$word.'</a> ';
            $popup_text = " <a title='$sug' style='text-decoration:none; color:red;' onClick='return overlib(\"".$repl."\",STICKY,CAPTION,\"Spellchecker suggestions\");'>$word</a> ";
          }
          //print("popup: <pre>".htmlentities($popup_text)."</pre><br/>");
          if($popup_text) {
            $where = preg_replace("/\s$word\s/",$popup_text,$where);
          } else {
            $where = preg_replace("/\s$word\s/",' <span style="color:red;">'.$word.'</span> ',$where);
          }
          $smarty->assign('trl',$trl);
          //$parsed = preg_replace("/\s$word\s/",' <a style="color:red;">'.$word.'</a> ',$parsed);
        }
      }
    }
    return $where;
  }

  function spellcheckword($word,$lang)
  {
    include_once("bablotron.php");
    $b = new bablotron($this->db,$lang);
    $result = $b->spellcheck_word($word);
    return $result;
  }

  function diff2($page1,$page2)
  {
      $page1 = split("\n",$page1);
      $page2 = split("\n",$page2);
      $z = new WikiDiff($page1, $page2);
      if ($z->isEmpty()) {
    $html = '<hr><br/>[' . tra("Versions are identical") . ']<br/><br/>';
      } else {
    //$fmt = new WikiDiffFormatter;
    $fmt = new WikiUnifiedDiffFormatter;
    $html = $fmt->format($z, $page1);
      }
      return $html;
  }

  function get_forum($forumId)
  {
    $query = "select * from tiki_forums where forumId='$forumId'";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function list_all_forum_topics($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" and (title like '%".$find."%' or data like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_comments,tiki_forums where object=md5(concat('forum',forumId)) and parentId=0 $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_comments,tiki_forums where object=md5(concat('forum',forumId)) and parentId=0 $mid order by $sort_mode limit $offset,$maxRecords";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $now = date("U");
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_forum_topics($forumId,$offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" and (title like '%".$find."%' or data like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_comments,tiki_forums where object=md5(concat('forum',$forumId)) and parentId=0 $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_comments,tiki_forums where object=md5(concat('forum',$forumId)) and parentId=0 $mid order by $sort_mode limit $offset,$maxRecords";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $now = date("U");
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_forums($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (name like '%".$find."%' or description like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_forums $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_forums $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $now = date("U");
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $forum_age = ceil(($now - $res["created"])/(24*3600));
      $res["age"] = $forum_age;
      if($forum_age) {
        $res["posts_per_day"] = $res["comments"]/$forum_age;
      } else {
        $res["posts_per_day"] =0;
      }
      // Now select users
      $objectId=md5('forum'.$res["forumId"]);
      $query = "select distinct(username) from tiki_comments where object='$objectId'";
      $result2 = $this->query($query);
      $res["users"] = $result2->numRows();
      if($forum_age) {
        $res["users_per_day"] = $res["users"]/$forum_age;
      } else {
        $res["users_per_day"] =0;
      }
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  // Functions for categories ////
  function list_all_categories($offset,$maxRecords,$sort_mode='name_asc',$find,$type,$objid)
  {
    $cats = $this->get_object_categories($type,$objid);
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (name like '%".$find."%' or description like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_categories $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_categories $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
    if(in_array($res["categId"],$cats)) {
      $res["incat"]='y';
    } else {
      $res["incat"]='n';
    }
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }


  function get_category_path_admin($categId)
  {
    $path = '';
    $info = $this->get_category($categId);
    $path = '<a class="categpath" href=tiki-admin_categories.php?parentId="'.$info["categId"].'">'.$info["name"].'</a>';
    while($info["parentId"]!=0) {
      $info = $this->get_category($info["parentId"]);
      $path = $path = '<a class="categpath" href=tiki-admin_categories.php?parentId="'.$info["categId"].'">'.$info["name"].'</a>'.'>'.$path;
    }
    return $path;
  }

  function get_category_path_browse($categId)
  {
    $path = '';
    $info = $this->get_category($categId);
    $path = '<a class="categpath" href=tiki-browse_categories.php?parentId="'.$info["categId"].'">'.$info["name"].'</a>';
    while($info["parentId"]!=0) {
      $info = $this->get_category($info["parentId"]);
      $path = $path = '<a class="categpath" href=tiki-browse_categories.php?parentId="'.$info["categId"].'">'.$info["name"].'</a>'.'>'.$path;
    }
    return $path;
  }

  function get_category($categId)
  {
    $query = "select * from tiki_categories where categId=$categId";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function uncategorize_object($type,$id)
  {
    $query = "select catObjectId from tiki_categorized_objects where type='$type' and objId='$id'";
    $catObjectId = $this->getOne($query);
    if($catObjectId) {
      $query = "delete from tiki_category_objects where catObjectId=$catObjectId";
      $result = $this->query($query);
      $query = "delete from tiki_categorized_objects where catObjectId=$catObjectId";
      $result = $this->query($query);
    }
  }

  function remove_object($type,$id)
  {
    $this->uncategorize_object($type,$id);
    // Now remove comments
    $object = md5($type.$id);
    $query = "delete from tiki_comments where object='$object'";
    $result = $this->query($query);
    // Remove individual permissions for this object if they exist
    $query = "delete from users_objectpermissions where objectId='$object' and objectType='$type'";
    $result = $this->query($query);
    return true;
   }

  function remove_category($categId)
  {
    // Delete the category
    $query = "delete from tiki_categories where categId=$categId";
    $result = $this->query($query);
    // Remove objects for this category
    $query = "select catObjectId from tiki_category_objects where categId=$categId";
    $result = $this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $object = $res["catObjectId"];
      $query2 = "delete from tiki_categorized_objects where catObjectId=$object";
      $result2 = $this->query($query2);

    }
    $query = "delete from tiki_category_objects where categId=$categId";
    $result = $this->query($query);
    $query = "select categId from tiki_categories where parentId=$categId";
    $result = $this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      // Recursively remove the subcategory
      $this->remove_category($res["categId"]);
    }
    return true;
  }

  function update_category($categId,$name,$description)
  {
    $name = addslashes($name);
    $descrption = addslashes($description);
    $query = "update tiki_categories set name='$name', description='$description' where categId=$categId";
    $result = $this->query($query);
  }

  function add_category($parentId,$name,$description)
  {
    $name = addslashes($name);
    $description = addslashes($description);
    $query = "insert into tiki_categories(name,description,parentId,hits) values('$name','$description',$parentId,0)";
    $result = $this->query($query);
  }

  function is_categorized($type,$objId)
  {
    $query = "select catObjectId from tiki_categorized_objects where type='$type' and objId='$objId'";
    $result = $this->query($query);
    if($result->numRows()) {
      $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
      return $res["catObjectId"];
    } else {
      return 0;
    }
  }

  function add_categorized_object($type,$objId,$description,$name,$href)
  {
    $description = addslashes(strip_tags($description));
    $name = addslashes(strip_tags($name));
    $now = date("U");
    $query = "insert into tiki_categorized_objects(type,objId,description,name,href,created,hits)
    values('$type','$objId','$description','$name','$href',$now,0)";
    $result = $this->query($query);
    $query = "select catObjectId from tiki_categorized_objects where created=$now and type='$type' and objId='$objId'";
    $id = $this->getOne($query);
    return $id;
  }

  function categorize($catObjectId,$categId)
  {
    $query = "replace into tiki_category_objects(catObjectId,categId) values($catObjectId,$categId)";
    $result = $this->query($query);
  }

  function get_category_descendants($categId)
  {
    $query = "select categId from tiki_categories where parentId=$categId";
    $result = $this->query($query);
    $ret = Array($categId);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res["categId"];
      $aux = $this->get_category_descendants($res["categId"]);
      $ret = array_merge($ret,$aux);
    }
    $ret=array_unique($ret);
    return $ret;
  }

  function list_category_objects_deep($categId,$offset,$maxRecords,$sort_mode='pageName_asc',$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    $des = $this->get_category_descendants($categId);
    $cond="where (";
    $first=1;
    foreach($des as $ades) {
      if($first) {
        $cond.=" (tbl1.categId=$ades) ";
        $first=0;
      } else {
        $cond.=" or (tbl1.categId=$ades) ";
      }
    }
    $cond.=" )";
    if($find) {
      $mid=" and (name like '%".$find."%' or description like '%".$find."% ')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_category_objects tbl1,tiki_categorized_objects tbl2 $cond and tbl1.catObjectId=tbl2.catObjectId $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select distinct(tbl1.catObjectId) from tiki_category_objects tbl1,tiki_categorized_objects tbl2 $cond and tbl1.catObjectId=tbl2.catObjectId $mid";
    $result = $this->query($query);
    $result2 = $this->query($query_cant);
    $cant = $result2->numRows();
    $cant2 = $this->getOne("select count(*) from tiki_category_objects tbl1,tiki_categorized_objects tbl2 $cond and tbl1.catObjectId=tbl2.catObjectId $mid");
    $ret = Array();
    $objs = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      if(!in_array($res["catObjectId"],$objs)) {
        $ret[] = $res;
        $objs[] = $res["catObjectId"];
      }
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    $retval["cant2"] = $cant2;
    return $retval;
  }

  function list_category_objects($categId,$offset,$maxRecords,$sort_mode='pageName_asc',$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" and (name like '%".$find."%' or description like '%".$find."% ')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_category_objects tbl1,tiki_categorized_objects tbl2 where tbl1.catObjectId=tbl2.catObjectId and categId=$categId $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select distinct(tbl1.catObjectId) from tiki_category_objects tbl1,tiki_categorized_objects tbl2 where tbl1.catObjectId=tbl2.catObjectId and categId=$categId $mid";
    $result = $this->query($query);
    $result2 = $this->query($query_cant);
    $cant = $result2->numRows();
    $cant2 = $this->getOne("select count(*) from tiki_category_objects tbl1,tiki_categorized_objects tbl2 where tbl1.catObjectId=tbl2.catObjectId and categId=$categId $mid");
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    $retval["cant2"] = $cant2;
    return $retval;
  }

  function get_object_categories($type,$objId)
  {
    $query = "select categId from tiki_category_objects tco, tiki_categorized_objects tto
    where tco.catObjectId=tto.catObjectId and type='$type' and objId='$objId'";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res["categId"];
    }
    return $ret;
  }

  function get_category_objects($categId)
  {
    // Get all the objects in a category
    $query = "select * from tiki_category_objects tbl1,tiki_categorized_objects tbl2 where tbl1.catObjectId=tbl2.catObjectId and categId=$categId";
    $result = $this->query($query);
    $ret=Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[]=$res;
    }
    return $ret;
  }

  function remove_object_from_category($catObjectId, $categId)
  {
    $query = "delete from tiki_category_objects where catObjectId=$catObjectId and categId=$categId";
    $result = $this->query($query);
    // If the object is not listed in any category then remove the object
    $query = "select count(*) from tiki_category_objects where catObjectId=$catObjectId";
    $cant = $this->getOne($query);
    if(!$cant) {
      $query = "delete from tiki_categorized_objects where catObjectId=$catObjectId";
      $result = $this->query($query);
    }
  }

  // FUNCTIONS TO CATEGORIZE SPECIFIC OBJECTS ////
  function categorize_page($pageName, $categId)
  {
    // Check if we already have this object in the tiki_categorized_objects page
    $catObjectId=$this->is_categorized('wiki page',$pageName);
    if(!$catObjectId) {
      // The page is not cateorized
      $info = $this->get_page_info($pageName);
      $href = 'tiki-index.php?page='.$pageName;
      $catObjectId = $this->add_categorized_object('wiki page',$pageName,substr($info["data"],0,200),$pageName,$href);
    }
    $this->categorize($catObjectId,$categId);
  }

  function categorize_quiz($quizId, $categId)
  {
    // Check if we already have this object in the tiki_categorized_objects page
    $catObjectId=$this->is_categorized('quiz',$quizId);
    if(!$catObjectId) {
      // The page is not cateorized
      $info = $this->get_quiz($quizId);
      $href = 'tiki-take_quiz.php?quizId='.$quizId;
      $catObjectId = $this->add_categorized_object('quiz',$quizId,substr($info["description"],0,200),$info["name"],$href);    }
      $this->categorize($catObjectId,$categId);
  }

  function categorize_article($articleId, $categId)
  {
    // Check if we already have this object in the tiki_categorized_objects page
    $catObjectId=$this->is_categorized('article',$articleId);
    if(!$catObjectId) {
      // The page is not cateorized
      $info = $this->get_article($articleId);
      $href = 'tiki-read_article.php?articleId='.$articleId;
      $catObjectId = $this->add_categorized_object('article',$articleId,$info["heading"],$info["title"],$href);
    }
    $this->categorize($catObjectId,$categId);
  }

  function categorize_faq($faqId, $categId)
  {
    // Check if we already have this object in the tiki_categorized_objects page
    $catObjectId=$this->is_categorized('faq',$faqId);
    if(!$catObjectId) {
      // The page is not cateorized
      $info = $this->get_faq($faqId);
      $href = 'tiki-view_faq.php?faqId='.$faqId;
      $catObjectId = $this->add_categorized_object('faq',$faqId,$info["description"],$info["title"],$href);
    }
    $this->categorize($catObjectId,$categId);
  }

  function categorize_blog($blogId, $categId)
  {
    // Check if we already have this object in the tiki_categorized_objects page
    $catObjectId=$this->is_categorized('blog',$blogId);
    if(!$catObjectId) {
      // The page is not cateorized
      $info = $this->get_blog($blogId);
      $href = 'tiki-view_blog.php?blogId='.$blogId;
      $catObjectId = $this->add_categorized_object('blog',$blogId,$info["description"],$info["title"],$href);
    }
    $this->categorize($catObjectId,$categId);
  }

  function categorize_gallery($galleryId, $categId)
  {
    // Check if we already have this object in the tiki_categorized_objects page
    $catObjectId=$this->is_categorized('image gallery',$galleryId);
    if(!$catObjectId) {
      // The page is not cateorized
      $info = $this->get_gallery($galleryId);
      $href = 'tiki-browse_gallery.php?galleryId='.$galleryId;
      $catObjectId = $this->add_categorized_object('image gallery',$galleryId,$info["description"],$info["name"],$href);
    }
    $this->categorize($catObjectId,$categId);
  }

  function categorize_file_gallery($galleryId, $categId)
  {
    // Check if we already have this object in the tiki_categorized_objects page
    $catObjectId=$this->is_categorized('file gallery',$galleryId);
    if(!$catObjectId) {
      // The page is not cateorized
      $info = $this->get_file_gallery($galleryId);
      $href = 'tiki-list_file_gallery.php?galleryId='.$galleryId;
      $catObjectId = $this->add_categorized_object('file gallery',$galleryId,$info["description"],$info["name"],$href);
    }
    $this->categorize($catObjectId,$categId);
  }

  function categorize_forum($forumId, $categId)
  {
    // Check if we already have this object in the tiki_categorized_objects page
    $catObjectId=$this->is_categorized('forum',$forumId);
    if(!$catObjectId) {
      // The page is not cateorized
      $info = $this->get_forum($forumId);
      $href = 'tiki-view_forum.php?forumId='.$forumId;
      $catObjectId = $this->add_categorized_object('forum',$forumId,$info["description"],$info["name"],$href);
    }
    $this->categorize($catObjectId,$categId);
  }

  function categorize_poll($pollId, $categId)
  {
    // Check if we already have this object in the tiki_categorized_objects page
    $catObjectId=$this->is_categorized('poll',$pollId);
    if(!$catObjectId) {
      // The page is not cateorized
      $info = $this->get_poll($pollId);
      $href = 'tiki-poll_form.php?pollId='.$pollId;
      $catObjectId = $this->add_categorized_object('poll',$pollId,$info["title"],$info["title"],$href);
    }
    $this->categorize($catObjectId,$categId);
  }

  // FUNCTIONS TO CATEGORIZE SPECIFIC OBJECTS END ////

  function get_child_categories($categId)
  {
    $ret=Array();
    $query = "select * from tiki_categories where parentId=$categId";
    $result = $this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $id = $res["categId"];
      $query = "select count(*) from tiki_categories where parentId=$id";
      $res["children"]=$this->getOne($query);
      $query = "select count(*) from tiki_category_objects where categId=$id";
      $res["objects"]=$this->getOne($query);
      $ret[]=$res;
    }
    return $ret;
  }

  // Functions for categories end ////

  // Functions for the communication center ////

  // This function moves a page from the received pages to the wiki if the page does not exist if the
  // page already exists then the page must be renamed before being inserted in the wiki and this function
  // returns false
  function accept_page($receivedPageId)
  {
    //create_page($name, $hits, $data, $lastModif, $comment, $user='system', $ip='0.0.0.0')
    // CODE HERE
    $info = $this->get_received_page($receivedPageId);
    if($this->page_exists($info["pageName"])) return false;
    $now=date("U");
    $this->create_page($info["pageName"],0,$info["data"],$now,$info["comment"],$info["receivedFromUser"],$info["receivedFromSite"],$info["description"]);
    $query = "delete from tiki_received_pages where receivedPageId = $receivedPageId";
    $result = $this->query($query);
    return true;
  }

  function accept_article($receivedArticleId,$topic)
  {
    $info = $this->get_received_article($receivedArticleId);
    $this->replace_article ($info["title"],$info["authorName"],$topic,$info["useImage"],$info["image_name"],$info["image_size"],$info["image_type"],$info["image_data"],$info["heading"],$info["body"],$info["publishDate"],$info["author"],0,$info["image_x"],$info["image_y"],$info["type"],$info["rating"]);
    $query = "delete from tiki_received_articles where receivedArticleId = $receivedArticleId";
    $result = $this->query($query);
    return true;
  }

  function list_received_pages($offset,$maxRecords,$sort_mode='pageName_asc',$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (pagename like '%".$find."%' or data like '%".$find."% ')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_received_pages $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_received_pages $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      if($this->page_exists($res["pageName"])) {
        $res["exists"]='y';
      } else {
        $res["exists"]='n';
      }
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_received_articles($offset,$maxRecords,$sort_mode='publishDate_desc',$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (heading like '%".$find."%' or title like '%".$find."%' or body like '%".$find."% ')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_received_articles $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_received_articles $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {

      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function remove_received_page($receivedPageId)
  {
    $query = "delete from tiki_received_pages where receivedPageId=$receivedPageId";
    $result = $this->query($query);
  }

  function remove_received_article($receivedArticleId)
  {
    $query = "delete from tiki_received_articles where receivedArticleId=$receivedArticleId";
    $result = $this->query($query);
  }

  function rename_received_page($receivedPageId,$name)
  {
    $query = "update tiki_received_pages set pageName='$name' where receivedPageId=$receivedPageId";
    $result = $this->query($query);
  }

  function get_received_page($receivedPageId)
  {
    $query = "select * from tiki_received_pages where receivedPageId=$receivedPageId";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function get_received_article($receivedArticleId)
  {
    $query = "select * from tiki_received_articles where receivedArticleId=$receivedArticleId";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function update_received_article($receivedArticleId,$title,$authorName,$useImage,$image_x,$image_y,$publishDate,$heading,$body,$type,$rating)
  {
    $title = addslashes($title);
    $authorName = addslashes($authorName);
    $heading = addslashes($heading);
    $body = addslashes($body);
    $size = strlen($body);
    $hash = md5($title.$heading.$body);
    $query = "update tiki_received_articles set
      title = '$title',
      authorName = '$authorName',
      heading = '$heading',
      body = '$body',
      size = $size,
      hash = '$hash',
      useImage = '$useImage',
      image_x = $image_x,
      image_y = $image_y,
      publishDate = $publishDate,
      type = '$type',
      rating = $rating
      where receivedArticleId=$receivedArticleId";
    $result = $this->query($query);
  }

  function update_received_page($receivedPageId, $pageName, $data, $comment)
  {
    $data = addslashes($data);
    $pageName = addslashes($pageName);
    $comment = addslashes($comment);
    $query = "update tiki_received_pages set pageName='$pageName', data='$data', comment='$comment' where receivedPageId=$receivedPageId";
    $result = $this->query($query);
  }

  function receive_article($site,$user,$title,$authorName,$size,$use_image,$image_name,$image_type,$image_size,$image_x,$image_y,$image_data,$publishDate,$created,$heading,$body,$hash,$author,$type,$rating)
  {
    $title = addslashes($title);
    $authorName = addslashes($authorName);
    $image_data = addslashes($image_data);
    $heading = addslashes($heading);
    $body = addslashes($body);
    $now = date("U");
    $query = "delete from tiki_received_articles where title='$title' and receivedFromsite='$site' and receivedFromUser='$user'";
    $result = $this->query($query);
    $query = "insert into tiki_received_articles(receivedDate,receivedFromSite,receivedFromUser,title,authorName,size,useImage,image_name,image_type,image_size,image_x,image_y,image_data,publishDate,created,heading,body,hash,author,type,rating)
    values($now,'$site','$user','$title','$authorName',$size,'$use_image','$image_name','$image_type',$image_size,$image_x,$image_y,'$image_data',$publishDate,$created,'$heading','$body','$hash','$author','$type',$rating)";
    $result = $this->query($query);

  }

  function receive_page($pageName,$data,$comment,$site,$user,$description)
  {
    $data = addslashes($data);
    $pageNAme = addslashes($pageName);
    $comment = addslashes($comment);
    $description = addslashes($description);
    $now = date("U");
    // Remove previous page sent from the same site-user (an update)
    $query = "delete from tiki_received_pages where pageName='$pageName' and receivedFromsite='$site' and receivedFromUser='$user'";
    $result = $this->query($query);
    // Now insert the page
    $query = "insert into tiki_received_pages(pageName,data,comment,receivedFromSite, receivedFromUser, receivedDate,description)
              values('$pageName','$data','$comment','$site','$user',$now,'$description')";
    $result = $this->query($query);

  }

  // Functions for the communication center end ////

  // Functions for polls ////
  function list_polls($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (title like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_polls $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_polls $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $query = "select count(*) from tiki_poll_options where pollId=".$res["pollId"];
      $res["options"]=$this->getOne($query);
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_active_polls($offset,$maxRecords,$sort_mode,$find)
  {
    $now = date("U");
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (active='a' or active='c') and publishDate<=$now and (title like '%".$find."%)'";
    } else {
      $mid=" where (active='a' or active='c') and publishDate<=$now ";
    }
    $query = "select * from tiki_polls $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_polls $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_current_polls($offset,$maxRecords,$sort_mode,$find)
  {
    $now = date("U");
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where active='c' and publishDate<=$now and (title like '%".$find."%')";
    } else {
      $mid=" where active='c' and publishDate<=$now ";
    }
    $query = "select * from tiki_polls $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_polls $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_all_polls($offset,$maxRecords,$sort_mode,$find)
  {
    $now = date("U");
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where publishDate<=$now and (title like '%".$find."%')";
    } else {
      $mid=" where publishDate<=$now ";
    }
    $query = "select * from tiki_polls $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_polls $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_poll_options($pollId,$offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where pollId=$pollId and (title like '%".$find."%')";
    } else {
      $mid=" where pollId=$pollId ";
    }
    $query = "select * from tiki_poll_options $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_poll_options $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function remove_poll($pollId)
  {
    $query = "delete from tiki_polls where pollId=$pollId";
    $result = $this->query($query);
    $query = "delete from tiki_poll_options where pollId=$pollId";
    $result = $this->query($query);
    $this->remove_object('poll',$pollId);
    return true;
  }

  function set_last_poll()
  {
    $now = date("U");
    $query = "select max(publishDate) from tiki_polls where publishDate<=$now";
    $last = $this->getOne($query);
    $query = "update tiki_polls set active='c' where publishDate=$last";
    $result = $this->query($query);
  }

  function close_all_polls()
  {
    $now = date("U");
    $query = "select max(publishDate) from tiki_polls where publishDate<=$now";
    $last = $this->getOne($query);
    $query = "update tiki_polls set active='x' where publishDate<$last and publishDate<=$now";
    $result = $this->query($query);
  }

  function active_all_polls()
  {
    $now = date("U");
    $query = "update tiki_polls set active='a' where publishDate<=$now";
    $result = $this->query($query);
  }

  function remove_poll_option($optionId)
  {
    $query = "delete from tiki_poll_options where optionId=$optionId";
    $result = $this->query($query);
    return true;
  }

  function get_poll($pollId)
  {
    $query = "select * from tiki_polls where pollId=$pollId";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function get_poll_option($optionId)
  {
    $query = "select * from tiki_poll_options where optionId=$optionId";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function replace_poll($pollId, $title, $active, $publishDate)
  {
    $title = addslashes($title);
    // Check the name
    if($pollId) {
      $query = "update tiki_polls set title='$title',active='$active',publishDate=$publishDate where pollId=$pollId";
      $result = $this->query($query);
    } else {
      $query = "replace into tiki_polls(title,active,publishDate,votes)
                values('$title','$active',$publishDate,0)";
      $result = $this->query($query);
      $pollId=$this->getOne("select max(pollId) from tiki_polls where title='$title' and publishDate=$publishDate");
    }

    return $pollId;
  }

  function replace_poll_option($pollId,$optionId, $title)
  {
    $title = addslashes($title);
    // Check the name
    if($optionId) {
      $query = "update tiki_poll_options set title='$title' where optionId=$optionId";
    } else {
      $query = "replace into tiki_poll_options(pollId,title,votes)
                values($pollId,'$title',0)";
    }

    $result = $this->query($query);
    return true;
  }

  function get_random_active_poll()
  {
    // Get pollid from polls where active = 'y' and publishDate is less than now
    $res = $this->list_current_polls(0,-1,'title_desc','');
    $data = $res["data"];
    $bid = rand(0,count($data)-1);
    $pollId  = $data[$bid]["pollId"];
    return $pollId;
  }

  function poll_vote($pollId,$optionId)
  {
    $query = "update tiki_poll_options set votes=votes+1 where optionId=$optionId";
    $result = $this->query($query);
    $query = "update tiki_polls set votes=votes+1 where pollId=$pollId";
    $result = $this->query($query);
  }

  // end polls ////

  // Functions for email notifications ////
  function list_mail_events($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (event like '%".$find."%' or email like '%".$find."%')";
    } else {
      $mid=" ";
    }
    $query = "select * from tiki_mail_events $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_mail_events $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function add_mail_event($event,$object,$email)
  {
    $query = "replace into tiki_mail_events(event,object,email) values('$event','$object','$email')";
    $result = $this->query($query);
  }

  function remove_mail_event($event,$object,$email)
  {
    $query = "delete from tiki_mail_events where event='$event' and object='$object' and email='$email'";
    $result = $this->query($query);
  }

  function get_mail_events($event,$object)
  {
    $query = "select email from tiki_mail_events where event='$event' and object='$object'";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res["email"];
    }
    return $ret;
  }

  // End email notification functions ////

  // Functions for the RSS modules ////
  function list_rss_modules($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (name like '%".$find."%' or description like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_rss_modules $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_rss_modules $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $res["minutes"]=$res["refresh"]/60;
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function replace_rss_module($rssId, $name, $description, $url, $refresh)
  {
    //if($this->rss_module_name_exists($name)) return false;
    $description = addslashes($description);
    $name = addslashes($name);
    // Check the name

    $refresh = 60*$refresh;
    if($rssId) {
      $query = "update tiki_rss_modules set name='$name',description='$description',refresh=$refresh,url='$url' where rssId=$rssId";
    } else {
      $query = "replace into tiki_rss_modules(name,description,url,refresh,content,lastUpdated)
                values('$name','$description','$url',$refresh,'',1000000)";
    }
    $result = $this->query($query);
    return true;
  }

  function remove_rss_module($rssId)
  {
    $query = "delete from tiki_rss_modules where rssId=$rssId";
    $result = $this->query($query);
    return true;
  }

  function get_rss_module($rssId)
  {
    $query = "select * from tiki_rss_modules where rssId=$rssId";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function startElementHandler($parser, $name,$attribs) {
    if($this->flag) {
      $this->buffer.='<'.$name.'>';
    }
    if($name=='item' || $name=='items') {
      $this->flag=1;
    }

  }

  function endElementHandler($parser, $name) {
    if($name=='item' || $name=='items') {
      $this->flag=0;
    }
    if($this->flag) {
      $this->buffer.='</'.$name.'>';
    }
  }

  function characterDataHandler($parser, $data) {
    if($this->flag) {
      $this->buffer.=$data;
    }
  }

  function NewsFeed ($data) {
    $news = Array();
    $this->buffer = '';
    $this->flag=0;
    $this->parser=xml_parser_create();
    xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);
    xml_set_object($this->parser,$this);
    xml_set_element_handler($this->parser,"startElementHandler","endElementHandler");
    xml_set_character_data_handler($this->parser,"characterDataHandler");
    if (!xml_parse($this->parser, $data, 1)) {
                    return $news;
    }
    xml_parser_free($this->parser);
    preg_match_all("/<title>(.*)<\/title>/",$this->buffer,$titles);
    preg_match_all("/<link>(.*)<\/link>/",$this->buffer,$links);
    for($i=0;$i<count($titles[1]);$i++) {
      $anew["title"]=$titles[1][$i];
      if(isset($links[1][$i])) {
        $anew["link"] = $links[1][$i];
      } else {
        $anew["link"]='';
      }
      $news[]=$anew;
    }
    return $news;
  }

  function parse_rss_data($rssdata)
  {
    return $this->NewsFeed($rssdata);
  }

  function refresh_rss_module($rssId)
  {
    $info = $this->get_rss_module($rssId);
    @$fp = fopen($info["url"],"r");
    if(!$fp) return false;
    $data = '';
    while(!feof($fp)) {
      $data .= fread($fp,4096);
    }
    $datai = addslashes($data);
    $now = date("U");
    $query = "update tiki_rss_modules set content='$datai', lastUpdated=$now where rssId=$rssId";
    $result = $this->query($query);
    return $data;
  }

  function rss_module_name_exists($name)
  {
    $query = "select name from tiki_rss_modules where name='$name'";
    $result = $this->query($query);
    return $result->numRows();
  }

  function get_rss_module_id($name)
  {
    $query = "select rssId from tiki_rss_modules where name='$name'";
    $id = $this->getOne($query);
    return $id;
  }

  function get_rss_module_content($rssId)
  {

   $info = $this->get_rss_module($rssId);
   $now = date("U");
   if($info["lastUpdated"]+$info["refresh"]<$now) {
     $data = $this->refresh_rss_module($rssId);
   }
   $info = $this->get_rss_module($rssId);
   return $info["content"];
  }

  // rSS modules end ////

  // Functions for the menubuilder and polls////
  function list_menus($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (name like '%".$find."%' or description like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_menus $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_menus $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $query = "select count(*) from tiki_menu_options where menuId=".$res["menuId"];
      $res["options"]=$this->getOne($query);
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_menu_options($menuId,$offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where menuId=$menuId and (name like '%".$find."%' or url like '%".$find."%')";
    } else {
      $mid=" where menuId=$menuId ";
    }
    $query = "select * from tiki_menu_options $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_menu_options $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function replace_menu($menuId, $name, $description, $type)
  {
    $description = addslashes($description);
    $name = addslashes($name);
    // Check the name


    if($menuId) {
      $query = "update tiki_menus set name='$name',description='$description',type='$type' where menuId=$menuId";
    } else {
      $query = "replace into tiki_menus(name,description,type)
                values('$name','$description','$type')";
    }
    $result = $this->query($query);
    return true;
  }

  function get_max_option($menuId)
  {
    $query = "select max(position) from tiki_menu_options where menuId=$menuId";
    $max = $this->getOne($query);
    return $max;
  }

  function replace_menu_option($menuId,$optionId, $name, $url, $type, $position)
  {


    $name = addslashes($name);
    // Check the name

    if($optionId) {
      $query = "update tiki_menu_options set name='$name',url='$url',type='$type',position=$position where optionId=$optionId";
    } else {
      $query = "replace into tiki_menu_options(menuId,name,url,type,position)
                values($menuId,'$name','$url','$type',$position)";
    }

    $result = $this->query($query);
    return true;
  }

  function remove_menu($menuId)
  {
    $query = "delete from tiki_menus where menuId=$menuId";
    $result = $this->query($query);
    $query = "delete from tiki_menu_options where menuId=$menuId";
    $result = $this->query($query);
    return true;
  }

  function remove_menu_option($optionId)
  {
    $query = "delete from tiki_menu_options where optionId=$optionId";
    $result = $this->query($query);
    return true;
  }

  function get_menu($menuId)
  {
    $query = "select * from tiki_menus where menuId=$menuId";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function get_menu_option($optionId)
  {
    $query = "select * from tiki_menu_options where optionId=$optionId";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  // Menubuilder ends ////

  // Functions for the chat system ////
  function send_message($user, $channelId, $data)
  {
    $data = addslashes(strip_tags($data));
    $now= date("U");
    $info = $this->get_channel($channelId);
    $name = $info["name"];
    // Check if the user is registered in the channel or update the
    // user timestamp
    $query = "replace into tiki_chat_users(nickname,channelId,timestamp) values('$user',$channelId,$now)";
    $result = $this->query($query);

    // :TODO: If logging is used then log the message
    //$log = fopen("logs/${name}.txt","a");
    //fwrite($log,"$posterName: $data\n");
    //fclose($log);
    $query = "insert into tiki_chat_messages(channelId,poster,timestamp,data) values($channelId,'$user',$now,'$data')";
    $result = $this->query($query);
    return true;
  }

  function send_private_message($user, $toNickname, $data)
  {
    $data = addslashes(strip_tags($data));
    $now= date("U");

    // :TODO: If logging is used then log the message
    //$log = fopen("logs/${name}.txt","a");
    //fwrite($log,"$posterName: $data\n");
    //fclose($log);
    $query = "insert into tiki_private_messages(poster,timestamp,data,toNickname) values('$user',$now,'$data','$toNickname')";
    $result = $this->query($query);
    return true;
  }

  function user_to_channel($user,$channelId)
  {
    $now= date("U");
    $query = "delete from tiki_chat_users where nickname='$user'";
    $result = $this->query($query);
    $query = "replace into tiki_chat_users(nickname,channelId,timestamp) values('$user',$channelId,$now)";
    $result = $this->query($query);
  }

  function get_chat_users($channelId)
  {
    $now = date("U") - (5*60);
    $query = "delete from tiki_chat_users where timestamp<$now";
    $result = $this->query($query);
    $query = "select nickname from tiki_chat_users where channelId=$channelId";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    return $ret;
  }

  function get_messages($channelId,$last,$from)
  {
    $query = "select messageId,poster, data from tiki_chat_messages where timestamp>$from and channelId=$channelId and messageId>$last order by timestamp asc";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux = Array();
      $aux["poster"] = $res["poster"];
      $aux["posterName"] = $res["poster"];
      $aux["data"] = $res["data"];
      $aux["messageId"] = $res["messageId"];
      $ret[] = $aux;
    }
    $num = count($ret);
    return $ret;
  }

  function get_private_messages($user)
  {
    $query = "select messageId,poster, data from tiki_private_messages where toNickname='$user' order by timestamp asc";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux = Array();
      $aux["poster"] = $res["poster"];
      $aux["posterName"] = $res["poster"];
      $aux["data"] = $res["data"];
      $aux["messageId"] = $res["messageId"];
      $ret[] = $aux;
    }
    $query = "delete from tiki_private_messages where toNickname='$user'";
    $result = $this->query($query);
    $num = count($ret);
    return $ret;
  }

  function purge_messages($minutes)
  {
    // :TODO: pass old messages to the message log table
    $secs = $minutes * 60;
    $last = date("U") - $secs;
    $query = "delete from tiki_chat_messages where timestamp<$last";
    $result = $this->query($query);
    // :TODO: delete from modMessages y privateMessages
    return true;
  }

  function list_channels($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (name like '%".$find."%' or description like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select * from tiki_chat_channels $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_chat_channels $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_active_channels($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where active='y' and (name like '%".$find."%' or description like '%".$find."%')";
    } else {
      $mid=" where active='y' ";
    }
    $query = "select * from tiki_chat_channels $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_chat_channels $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function replace_channel($channelId, $name, $description, $max_users, $mode, $active,$refresh)
  {
    if($channelId) {
      $query = "update tiki_chat_channels set name='$name',description='$description',refresh=$refresh,max_users=$max_users,mode='$mode',active='$active' where channelId=$channelId";
    } else {
      $query = "replace into tiki_chat_channels(name,description,max_users,mode,moderator,active,refresh)
                values('$name','$description',$max_users,'$mode','','$active',$refresh)";
    }
    $result = $this->query($query);
    return true;
  }

  function remove_channel($channelId)
  {
    $query = "delete from tiki_chat_channels where channelId=$channelId";
    $result = $this->query($query);
    return true;
  }

  function get_channel($channelId)
  {
    $query = "select * from tiki_chat_channels where channelId=$channelId";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  // End of chat functions ////

  // User voting system ////
  // Used to vote everything (polls,comments,files,submissions,etc) ////
  // Checks if a user has voted
  function user_has_voted($user,$id)
  {
    // If user is not logged in then check the session
    if(!$user) {
      $votes = $_SESSION["votes"];
      if(in_array($id,$votes)) {
        $ret = true;
      } else {
        $ret = false;
      }
    } else {
      $query = "select user from tiki_user_votings where user='$user' and id='$id'";
      $result = $this->query($query);
      if($result->numRows()) {
        $ret = true;
      } else {
        $ret = false;
      }
    }
    return $ret;
  }

  // Registers a user vote
  function register_user_vote($user,$id)
  {
    // If user is not logged in then register in the session
    if(!$user) {
      $_SESSION["votes"][]=$id;
    } else {
      $query = "replace into tiki_user_votings(user,id) values('$user','$id')";
      $result = $this->query($query);

    }
  }

  // FILE GALLERIES ////
  function list_files($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (name like '%".$find."%' or description like '%".$find."%')";
    } else {
      $mid="";
    }
    $query = "select fileId,name,description,created,filename,filesize,user,downloads from tiki_files $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_files $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function get_file($id)
  {
    $query = "select path,galleryId,filename,filetype,data from tiki_files where fileId='$id'";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function get_files($offset,$maxRecords,$sort_mode,$find,$galleryId)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where galleryId=$galleryId and (name like '%".$find."%' or description like '%".$find."%')";
    } else {
      $mid="where galleryId=$galleryId";
    }
    $query = "select fileId,name,description,created,filename,filesize,user,downloads from tiki_files $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_files $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function remove_file($id)
  {
    global $fgal_use_dir;
    $path = $this->getOne("select path from tiki_files where fileId=$id");
    if($path) {
      unlink($fgal_use_dir.$path);
    }
    $query = "delete from tiki_files where fileId=$id";
    $result = $this->query($query);
    return true;
  }

  function add_file_hit($id)
  {
    $query = "update tiki_files set downloads=downloads+1 where fileId=$id";
    $result = $this->query($query);
    return true;
  }

  function add_file_gallery_hit($id)
  {
    $query = "update tiki_file_galleries set hits=hits+1 where galleryId=$id";
    $result = $this->query($query);
    return true;
  }

  function insert_file($galleryId,$name,$description,$filename,  $data, $size,$type ,$user,$path)
  {
    $name = addslashes(strip_tags($name));
    $path = addslashes($path);
    $description = addslashes(strip_tags($description));
    $data = addslashes($data);
    $now = date("U");
    $query = "insert into tiki_files(galleryId,name,description,filename,filesize,filetype,data,user,created,downloads,path)
                          values($galleryId,'$name','$description','$filename',$size,'$type','$data','$user',$now,0,'$path')";
    $result = $this->query($query);
    $query = "update tiki_file_galleries set lastModif=$now where galleryId=$galleryId";
    $result = $this->query($query);
    $query = "select max(fileId) from tiki_files where created=$now";
    $fileId = $this->getOne($query);
    return $fileId;
  }

  function get_file_gallery($id)
  {
    $query = "select * from tiki_file_galleries where galleryId='$id'";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function list_file_galleries($offset = 0, $maxRecords = -1, $sort_mode = 'name_desc', $user, $find)
  {
    global $tiki_p_admin_file_galleries;
    // If $user is admin then get ALL galleries, if not only user galleries are shown
    $sort_mode = str_replace("_"," ",$sort_mode);
    $old_sort_mode ='';
    if(in_array($sort_mode,Array('files desc','files asc'))) {
      $old_offset = $offset;
      $old_maxRecords = $maxRecords;
      $old_sort_mode = $sort_mode;
      $sort_mode ='user desc';
      $offset = 0;
      $maxRecords = -1;
    }

    // If the user is not admin then select it's own galleries or public galleries
    if (($tiki_p_admin_file_galleries == 'y') or ($user == 'admin')) {
       $whuser = "";
    } else {
      $whuser = "where user='$user' or public='y'";
    }

    if($find) {
      if(empty($whuser)) {
        $whuser = "where name like '%".$find."%' or description like '%".$find.".%'";
      } else {
        $whuser .= " and name like '%".$find."%' or description like '%".$find.".%'";
      }
    }

    $query = "select * from tiki_file_galleries $whuser order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_file_galleries $whuser";
    $result = $this->query($query);
    $result_cant = $this->query($query_cant);
    $res2 = $result_cant->fetchRow();
    $cant = $res2[0];
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux = Array();
      $aux["name"] = $res["name"];
      $gid = $res["galleryId"];
      $aux["id"] = $gid;
      $aux["visible"] = $res["visible"];
      $aux["galleryId"] = $res["galleryId"];
      $aux["description"] = $res["description"];
      $aux["created"] = $res["created"];
      $aux["lastModif"] = $res["lastModif"];
      $aux["user"] = $res["user"];
      $aux["hits"] = $res["hits"];
      $aux["public"] = $res["public"];
      $aux["files"] = $this->getOne("select count(*) from tiki_files where galleryId='$gid'");
      $ret[] = $aux;
    }
    if($old_sort_mode == 'files asc') {
      usort($ret,'compare_files');
    }
    if($old_sort_mode == 'files desc') {
      usort($ret,'r_compare_files');
    }

    if(in_array($old_sort_mode,Array('files desc','files asc'))) {
      $ret = array_slice($ret, $old_offset, $old_maxRecords);
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_visible_file_galleries($offset = 0, $maxRecords = -1, $sort_mode = 'name_desc', $user, $find)
  {
    // If $user is admin then get ALL galleries, if not only user galleries are shown
    $sort_mode = str_replace("_"," ",$sort_mode);
    $old_sort_mode ='';
    if(in_array($sort_mode,Array('files desc','files asc'))) {
      $old_offset = $offset;
      $old_maxRecords = $maxRecords;
      $old_sort_mode = $sort_mode;
      $sort_mode ='user desc';
      $offset = 0;
      $maxRecords = -1;
    }

    // If the user is not admin then select it's own galleries or public galleries
    if($user != 'admin') {
      $whuser = " and (user='$user' or public='y')";
    } else {
      $whuser = "";
    }

    if($find) {
      if(empty($whuser)) {
        $whuser = " and (name like '%".$find."%' or description like '%".$find.".%')";
      } else {
        $whuser .= " and (name like '%".$find."%' or description like '%".$find.".%')";
      }
    }

    $query = "select * from tiki_file_galleries where visible='y' $whuser order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_file_galleries where visible='y' $whuser";
    $result = $this->query($query);
    $result_cant = $this->query($query_cant);
    $res2 = $result_cant->fetchRow();
    $cant = $res2[0];
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux = Array();
      $aux["name"] = $res["name"];
      $gid = $res["galleryId"];
      $aux["id"] = $gid;
      $aux["visible"] = $res["visible"];
      $aux["galleryId"] = $res["galleryId"];
      $aux["description"] = $res["description"];
      $aux["created"] = $res["created"];
      $aux["lastModif"] = $res["lastModif"];
      $aux["user"] = $res["user"];
      $aux["hits"] = $res["hits"];
      $aux["public"] = $res["public"];
      $aux["files"] = $this->getOne("select count(*) from tiki_files where galleryId='$gid'");
      $ret[] = $aux;
    }
    if($old_sort_mode == 'files asc') {
      usort($ret,'compare_files');
    }
    if($old_sort_mode == 'files desc') {
      usort($ret,'r_compare_files');
    }

    if(in_array($old_sort_mode,Array('files desc','files asc'))) {
      $ret = array_slice($ret, $old_offset, $old_maxRecords);
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function remove_file_gallery($id)
  {
    global $fgal_use_dir;
    $query = "select path from tiki_files where galleryId='$id'";
    $result = $this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) { 
      $path = $res["path"];
      if($path) {
        @unlink($fgal_use_dir.$path);
      }
    }
    $query = "delete from tiki_file_galleries where galleryId='$id'";
    $result = $this->query($query);
    $query = "delete from tiki_files where galleryId='$id'";
    $result = $this->query($query);
    $this->remove_object('file gallery',$id);
    return true;
  }

  function get_file_gallery_info($id)
  {
    $query = "select * from tiki_file_galleries where galleryId='$id'";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function replace_file_gallery($galleryId, $name, $description, $user,$maxRows,$public,$visible='y')
  {
    // if the user is admin or the user is the same user and the gallery exists then replace if not then
    // create the gallary if the name is unused.
    $name = addslashes(strip_tags($name));
    $description = addslashes(strip_tags($description));
    $now = date("U");
    if($galleryId>0) {
      $query = "update tiki_file_galleries set name='$name', maxRows=$maxRows, description='$description',lastModif=$now, public='$public', visible='$visible' where galleryId=$galleryId";
      $result = $this->query($query);
    } else {
      // Create a new record
      $query =  "insert into tiki_file_galleries(name,description,created,user,lastModif,maxRows,public,hits,visible)
                                    values ('$name','$description',$now,'$user',$now,$maxRows,'$public',0,'$visible')";
      $result = $this->query($query);
      $galleryId=$this->getOne("select max(galleryId) from tiki_file_galleries where name='$name' and lastModif=$now");
    }
    return $galleryId;
  }

  function logui($line) {
    $fw=fopen("log.txt","a+");
    fputs($fw,$line."\n");
    fclose($fw);
  }

  // Semaphore functions ////
  function semaphore_is_set($semName,$limit)
  {

    $now=date("U");
    $lim=$now-$limit;
    $query = "delete from tiki_semaphores where semName='$semName' and timestamp<$lim";
    $result = $this->query($query);
    $query = "select semName from tiki_semaphores where semName='$semName'";
    $result = $this->query($query);
    return $result->numRows();
   }

  function semaphore_set($semName)
  {
    $now=date("U");
    $cant=$this->getOne("select count(*) from tiki_semaphores where semName='$semName'");
    if($cant) {
      $query = "update tiki_semaphores set timestamp='$now' where semName='$semName'";
    } else {
      $query = "insert into tiki_semaphores(semName,timestamp) values('$semName',$now)";
    }
    $result = $this->query($query);
    return $now;
  }

  function semaphore_unset($semName,$lock)
  {
    $query = "delete from tiki_semaphores where semName='$semName' and timestamp=$lock";
    $result = $this->query($query);
  }

  // Dynamic content generation system ////
  function remove_contents($contentId)
  {
    $query = "delete from tiki_programmed_content where contentId=$contentId";
    $result = $this->query($query);
    $query = "delete from tiki_content where contentId=$contentId";
    $result = $this->query($query);
  }

  function list_content($offset = 0,$maxRecords = -1,$sort_mode = 'contentId_desc', $find='')
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (description like '%".$find."%')";
    } else {
      $mid='';
    }
    $query = "select * from tiki_content $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_content $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      // Add actual version
      // Add number of programmed versions
      // Add next programmed version
      // Add number of old versions
      $now = date("U");
      $id = $res["contentId"];
      $query = "select count(*) from tiki_programmed_content where publishDate>$now and contentId=$id";
      $res["future"] = $this->getOne($query);
      $query = "select max(publishDate) from tiki_programmed_content where contentId=$id and publishDate<=$now";
      $res["actual"] = $this->getOne($query);
      $query = "select min(publishDate) from tiki_programmed_content where contentId=$id and publishDate=$now";
      $res["next"] = $this->getOne($query);
      $query = "select count(*) from tiki_programmed_content where contentId = $id and publishdate<$now";
      $res["old"] = $this->getOne($query);
      if($res["old"]>0) $res["old"]--;
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function get_actual_content_date($contentId)
  {
    $now = date("U");
    $query = "select max(publishDate) from tiki_programmed_content where contentId=$contentId and publishDate<=$now";
    $res = $this->getOne($query);
    return $res;
  }

  function get_random_content($contentId)
  {
    $now = date("U");
    $query = "select data from tiki_programmed_content where contentId=$contentId and publishDate<=$now";
    $result = $this->query($query);
    $cant = $result->numRows();
    if(!$cant) return '';
    $x = rand(0,$cant-1);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC,$x);
    return $res["data"];
  }

  function get_actual_content($contentId)
  {
    $data ='';
    $now = date("U");
    $query = "select max(publishDate) from tiki_programmed_content where contentId=$contentId and publishDate<=$now";
    $res = $this->getOne($query);
    if(!$res) return '';
    $query = "select data from tiki_programmed_content where contentId=$contentId and publishDate=$res";
    $data = $this->getOne($query);
    return $data;
  }

  function get_next_content($contentId)
  {
    $now = date("U");
    $query = "select min(publishDate) from tiki_programmed_content where contentId=$contentId and publishDate>$now";
    $res = $this->getOne($query);
    return $res;
  }

  function list_programmed_content($contentId,$offset = 0,$maxRecords = -1,$sort_mode = 'publishDate_desc', $find='')
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where contentId=$contentId and (data like '%".$find."%') ";
    } else {
      $mid=" where contentId=$contentId";
    }
    $query = "select * from tiki_programmed_content $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_programmed_content $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function replace_programmed_content($pId,$contentId,$publishDate, $data)
  {
    $data = addslashes($data);
    if(!$pId) {
      $query = "replace into tiki_programmed_content(contentId,publishDate,data) values($contentId,$publishDate, '$data')";
      $result = $this->query($query);
      $query = "select max(pId) from tiki_programmed_content where publishDate=$publishDate and data='$data'";
      $id = $this->getOne($query);
    } else {
      $query = "update tiki_programmed_content set contentId=$contentId, publishDate=$publishDate, data='$data' where pId=$pId";
      $result = $this->query($query);
      $id = $pId;
    }
    return $id;
  }

  function remove_programmed_content($id)
  {
    $query = "delete from tiki_programmed_content where pId=$id";
    $result = $this->query($query);
    return true;
  }

  function get_content($id)
  {
    $query = "select * from tiki_content where contentId=$id";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function get_programmed_content($id)
  {
    $query = "select * from tiki_programmed_content where pId=$id";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function replace_content($contentId,$description)
  {
    $description = addslashes($description);
    if($contentId>0) {
      $query = "update tiki_content set description='$description' where contentId=$contentId";
      $result = $this->query($query);
      return $contentId;
    } else {
      $query = "insert into tiki_content(description) values('$description')";
      $result = $this->query($query);
      $query = "select max(contentId) from tiki_content where description = '$description'";
      $id = $this->getOne($query);
      return $id;
    }
  }

  function remove_unused_pictures()
  {
    $query = "select data from tiki_pages";
    $result = $this->query($query);
    $pictures=Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      preg_match_all("/\{picture file=([^\}]+)\}/",$res["data"],$pics);
      foreach(array_unique($pics[1]) as $pic) {
        $pictures[]=$pic;
      }
    }
    $h = opendir("img/wiki_up");
    while (($file = readdir($h)) !== false) {
      if(is_file("img/wiki_up/$file")&&($file!='license.txt')) {
        $filename="img/wiki_up/$file";
        if(!in_array($filename,$pictures)) {
          @unlink($filename);
        }
      }
    }
    closedir($h);
  }

  function remove_orphan_images()
  {
    $merge  = Array();
    // Find images in tiki_pages
    $query = "select data from tiki_pages";
    $result = $this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      preg_match_all("/src=\"([^\"]+)\"/",$res["data"],$reqs1);
      preg_match_all("/src=\'([^\']+)\'/",$res["data"],$reqs2);
      preg_match_all("/src=([A-Za-z0-9:\?\=\/\.\-\_]+)\}/",$res["data"],$reqs3);
      $merge = array_merge($merge, $reqs1[1],$reqs2[1],$reqs3[1]);
      $merge = array_unique($merge);
    }

    // Find images in Tiki articles
    $query = "select body from tiki_articles";
    $result = $this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      preg_match_all("/src=\"([^\"]+)\"/",$res["body"],$reqs1);
      preg_match_all("/src=\'([^\']+)\'/",$res["body"],$reqs2);
      preg_match_all("/src=([A-Za-z0-9:\?\=\/\.\-\_]+)\}/",$res["body"],$reqs3);
      $merge = array_merge($merge, $reqs1[1],$reqs2[1],$reqs3[1]);
      $merge = array_unique($merge);
    }

    // Find images in tiki_submissions
    $query = "select body from tiki_submissions";
    $result = $this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      preg_match_all("/src=\"([^\"]+)\"/",$res["body"],$reqs1);
      preg_match_all("/src=\'([^\']+)\'/",$res["body"],$reqs2);
      preg_match_all("/src=([A-Za-z0-9:\?\=\/\.\-\_]+)\}/",$res["body"],$reqs3);
      $merge = array_merge($merge, $reqs1[1],$reqs2[1],$reqs3[1]);
      $merge = array_unique($merge);
    }

    // Find images in tiki_blog_posts
    $query = "select data from tiki_blog_posts";
    $result = $this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      preg_match_all("/src=\"([^\"]+)\"/",$res["data"],$reqs1);
      preg_match_all("/src=\'([^\']+)\'/",$res["data"],$reqs2);
      preg_match_all("/src=([A-Za-z0-9:\?\=\/\.\-\_]+)\}/",$res["data"],$reqs3);
      $merge = array_merge($merge, $reqs1[1],$reqs2[1],$reqs3[1]);
      $merge = array_unique($merge);
    }

    $positives = Array();
    foreach($merge as $img) {
      if(strstr($img,'show_image')) {
        preg_match("/id=([0-9]+)/",$img,$rq);
        $positives[] = $rq[1];
      }
    }

    $query = "select imageId from tiki_images where galleryId=0";
    $result = $this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $id = $res["imageId"];
      if(!in_array($id,$positives)) {
        $this->remove_image($id);
      }
    }

  }

  // Banner functions
  function select_banner($zone)
  {
    // Things to check
    // UseDates and dates
    // Hours
    // weekdays
    // zone
    // maxImpressions and impressions
    # TODO localize
    $dw = strtolower(date("D"));
    $hour = date("H").date("i");
    $now = date("U");
    $raw='';
    //
    //
    $query = "select * from tiki_banners where $dw = 'y' and  hourFrom<=$hour and hourTo>=$hour and
    ( ((useDates = 'y') and (fromDate<=$now and toDate>=$now)) or (useDates = 'n') ) and
    impressions<maxImpressions and zone='$zone'";
    $result = $this->query($query);
    $rows = $result->numRows();
    if(!$rows) return false;
    $bid = rand(0,$rows-1);
    //print("Rows: $rows bid: $bid");
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC,$bid);
    $id= $res["bannerId"];
    switch($res["which"]) {
    case 'useHTML':
      $raw = $res["HTMLData"];
      break;
    case 'useImage':
      $raw = "<div align='center'><a target='_blank' href='banner_click.php?id="
             .$res["bannerId"]
             ."&amp;url="
             .urlencode($res["url"])
             ."'><img alt='banner' border='0' src=\"banner_image.php?id="
             .$res["bannerId"]
             ."\" /></a></div>";
      break;
    case 'useFixedURL':
      @$fp = fopen($res["fixedURLData"],"r");
      if ($fp) {
        $raw = '';
        while(!feof($fp)) {
          $raw .= fread($fp,4096);
        }
      }
      fclose($fp);
      break;
    case 'useText':
      $raw = "<a target='_blank' class='bannertext' href='banner_click.php?id=".$res["bannerId"]."&amp;url=".urlencode($res["url"])."'>".$res["textData"]."</a>";
      break;
    }
    // Increment banner impressions here
    $id = $res["bannerId"];
    if($id) {
      $query = "update tiki_banners set impressions = impressions + 1 where bannerId = $id";
      $result = $this->query($query);
    }
    return $raw;
  }

  function add_click($bannerId)
  {
    $query = "update tiki_banners set clicks = clicks + 1 where bannerId=$bannerId";
    $result = $this->query($query);
  }

  function list_banners($offset = 0,$maxRecords = -1,$sort_mode = 'created_desc', $find='', $user)
  {
    if($user == 'admin') {
      $mid = '';
    } else {
      $mid = "where client = '$user'";
    }
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      if($mid) {
        $mid.=" and url like '%".$find."%' ";
      } else {
        $mid.=" where url like '%".$find."%' ";
      }
    }
    $query = "select * from tiki_banners $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_banners $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_zones()
  {
    $query = "select zone from tiki_zones";
    $query_cant = "select count(*) from tiki_zones";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function remove_banner($bannerId)
  {
    $query = "delete from tiki_banners where bannerId=$bannerId";
    $result = $this->query($query);
  }

  function get_banner($bannerId)
  {
    $query = "select * from tiki_banners where bannerId=$bannerId";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }
  function replace_banner($bannerId, $client, $url, $title='', $alt='', $use, $imageData,$imageType,$imageName,
                          $HTMLData, $fixedURLData, $textData, $fromDate, $toDate, $useDates,
                          $mon, $tue, $wed, $thu, $fri, $sat, $sun,
                          $hourFrom, $hourTo, $maxImpressions, $zone)
  {
    $url = addslashes($url);
    $title = addslashes($title);
    $alt = addslashes($alt);
    $imageData = addslashes(urldecode($imageData));
    //$imageData = '';
    $imageName = addslashes($imageName);
    $HTMLData = addslashes($HTMLData);
    $fixedURLData = addslashes($fixedURLData);
    $textData = addslashes($textData);
    $zone = addslashes($zone);
    $now = date("U");
    if($bannerId) {
      $query = "update tiki_banners set
                client = '$client',
                url = '$url',
                title = '$title',
                alt = '$alt',
                which = '$use',
                imageData = '$imageData',
                imageType = '$imageType',
                imageName = '$imageName',
                HTMLData = '$HTMLData',
                fixedURLData = '$fixedURLData',
                textData = '$textData',
                fromDate = $fromDate,
                toDate = $toDate,
                useDates = '$useDates',
                created = $now,
                zone = '$zone',
                hourFrom = '$hourFrom',
                hourTo = '$hourTo',
                maxImpressions = $maxImpressions where bannerId=$bannerId";
       $result = $this->query($query);
    } else {
      $query = "insert into tiki_banners(client, url, title, alt, which, imageData, imageType, HTMLData,
                fixedURLData, textData, fromDate, toDate, useDates, mon, tue, wed, thu, fri, sat, sun,
                hourFrom, hourTo, maxImpressions,created,zone,imageName,impressions,clicks)
                values('$client','$url','$title','$alt','$use','$imageData','$imageType','$HTMLData',
                '$fixedURLData', '$textData', $fromDate, $toDate, '$useDates', '$mon','$tue','$wed','$thu',
                '$fri','$sat','$sun','$hourFrom','$hourTo',$maxImpressions,$now,'$zone','$imageName',0,0)";
      $result = $this->query($query);
      $query = "select max(bannerId) from tiki_banners where created=$now";
      $bannerId = $this->getOne($query);
    }
    return $bannerId;
  }


  function banner_add_zone($zone)
  {
    $zone = addslashes($zone);
    $query = "replace into tiki_zones(zone) values('$zone')";
    $result = $this->query($query);
    return true;
  }

  function banner_get_zones()
  {
    $query = "select * from tiki_zones";
    $result = $this->query($query);
    $ret= Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[]=$res;
    }
    return $ret;
  }

  function banner_remove_zone($zone)
  {
    $query = "delete from tiki_zones where zone='$zone'";
    $result = $this->query($query);
    if(0) {
    $query = "delete from tiki_banner_zones where zoneName='$zone'";
    $result = $this->query($query);
    }

    return true;
  }

  // Hot words methods ////
  function get_hotwords()
  {
    $query = "select * from tiki_hotwords";
    $result = $this->query($query);
    $ret= Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[$res["word"]]=$res["url"];
    }
    return $ret;
  }

  function list_hotwords($offset = 0,$maxRecords = -1,$sort_mode = 'word_desc', $find='')
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (word like '%".$find."%') ";
    } else {
      $mid='';
    }
    $query = "select * from tiki_hotwords $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_hotwords $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function add_hotword($word,$url)
  {
    $word=addslashes($word);
    $url=addslashes($url);
    $query = "replace into tiki_hotwords(word,url) values('$word','$url')";
    $result = $this->query($query);
    return true;
  }

  function remove_hotword($word)
  {
    $query = "delete from tiki_hotwords where word='$word'";
    $result = $this->query($query);

  }

  // BLOG METHODS ////
  function list_blogs($offset = 0,$maxRecords = -1,$sort_mode = 'created_desc', $find='')
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (title like '%".$find."%' or description like '%".$find."%') ";
    } else {
      $mid='';
    }
    $query = "select * from tiki_blogs $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_blogs $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function add_blog_hit($blogId)
  {
    $query = "update tiki_blogs set hits = hits+1 where blogId=$blogId";
    $result = $this->query($query);
    return true;
  }

  function replace_blog($title,$description,$user,$public,$maxPosts,$blogId)
  {
    $title = addslashes($title);
    $description = addslashes($description);
    $now = date("U");
    if($blogId) {
      $query = "update tiki_blogs set title='$title',description='$description',user='$user',public='$public',lastModif=$now,maxPosts=$maxPosts where blogId=$blogId";
      $result = $this->query($query);
    } else {
      $query = "insert into tiki_blogs(created,lastModif,title,description,user,public,posts,maxPosts,hits)
                       values($now,$now,'$title','$description','$user','$public',0,$maxPosts,0)";
      $result = $this->query($query);
      $query2 = "select max(blogId) from tiki_blogs where lastModif=$now";
      $blogId=$this->getOne($query2);
    }

    return $blogId;
  }

  function get_blog($blogId)
  {
    $query = "select * from tiki_blogs where blogId=$blogId";
    $result = $this->query($query);

    if($result->numRows()) {
      $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    } else {
      return false;
    }
    return $res;
  }

  function list_blog_posts($blogId, $offset = 0,$maxRecords = -1,$sort_mode = 'created_desc', $find='', $date='')
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where blogId=$blogId and (data like '%".$find."%') ";
    } else {
      $mid=" where blogId=$blogId ";
    }
    if($date) {
      $mid.=" and  created<=$date ";
    }
    $query = "select * from tiki_blog_posts $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_blog_posts $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $hash=md5('post'.$res["postId"]);
      $cant_com = $this->getOne("select count(*) from tiki_comments where object='$hash'");
      $res["comments"]=$cant_com;
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_all_blog_posts($offset = 0,$maxRecords = -1,$sort_mode = 'created_desc', $find='', $date='')
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (data like '%".$find."%') ";
    } else {
      $mid="";
    }
    if($date) {
      if($mid) {
      $mid.=" and  created<=$date ";
      } else {
      $mid.=" where created<=$date ";
      }
    }
    $query = "select * from tiki_blog_posts $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_blog_posts $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $query2 = "select title from tiki_blogs where blogId=".$res["blogId"];
      $title = $this->getOne($query2);
      $res["blogtitle"]=$title;
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function blog_post($blogId,$data,$user)
  {
    // update tiki_blogs and call activity functions
    $data = strip_tags($data, '<a><b><i><h1><h2><h3><h4><h5><h6><ul><li><ol><br><p><table><tr><td><img><pre>');
    $data=addslashes($data);
    $now = date("U");
    $query = "insert into tiki_blog_posts(blogId,data,created,user) values($blogId,'$data',$now,'$user')";
    $result = $this->query($query);
    $query = "select max(postId) from tiki_blog_posts where created=$now and user='$user'";
    $id = $this->getOne($query);
    $query = "update tiki_blogs set lastModif=$now,posts=posts+1 where blogId=$blogId";
    $result = $this->query($query);
    $this->add_blog_activity($blogId);
    return $id;
  }

  function list_user_blogs($user,$include_public=false)
  {
    $query = "select * from tiki_blogs where user='$user'";
    if($include_public) {
      $query.=" or public='y'";
    }
    $result = $this->query($query);
    $ret=Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[]=$res;
    }
    return $ret;
  }

  function remove_blog($blogId)
  {
    $query = "delete from tiki_blogs where blogId=$blogId";
    $result = $this->query($query);
    $query = "delete from tiki_blog_posts where blogId=$blogId";
    $result = $this->query($query);
    $this->remove_object('blog',$blogId);
    return true;
  }

  function remove_post($postId)
  {
    $query = "select blogId from tiki_blog_posts where postId=$postId";
    $blogId = $this->getOne($query);
    if($blogId) {
      $query = "delete from tiki_blog_posts where postId=$postId";
      $result = $this->query($query);
      $query = "update tiki_blogs set posts=posts-1 where blogId=$blogId";
      $result = $this->query($query);
    }
    return true;
  }

  function get_post($postId)
  {
    $query = "select * from tiki_blog_posts where postId=$postId";
    $result = $this->query($query);

    if($result->numRows()) {
      $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    } else {
      return false;
    }
    return $res;
  }

  function update_post($postId,$data,$user)
  {
    $data = addslashes($data);
    $query = "update tiki_blog_posts set data='$data',user='$user' where postId=$postId";
    $result = $this->query($query);

  }

  function list_posts($offset = 0,$maxRecords = -1,$sort_mode = 'created_desc', $find='')
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (data like '%".$find."%') ";
    } else {
      $mid='';
    }
    $query = "select * from tiki_blog_posts $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_blog_posts $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $blogId=$res["blogId"];
      $query = "select title from tiki_blogs where blogId=$blogId";
      $hash=md5('postId'.$res["postId"]);
      $cant_com = $this->getOne("select count(*) from tiki_comments where object='$hash'");
      $res["comments"]=$cant_com;
      $res["blogTitle"]=$this->getOne($query);
      $res["size"]=strlen($res["data"]);
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_user_posts($user,$offset = 0,$maxRecords = -1,$sort_mode = 'created_desc', $find='')
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where user=$user and (data like '%".$find."%') ";
    } else {
      $mid=' where user=$user ';
    }
    $query = "select * from tiki_blog_posts $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_blog_posts $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function add_blog_activity($blogId)
  {

    //Caclulate activity, update tiki_blogs and purge activity table
    $today = mktime(0,0,0,date("m"),date("d"),date("Y"));
    $day0 = $today - (24*60*60);
    $day1 = $today - (2*24*60*60);
    $day2 = $today - (3*24*60*60);
    // Purge old activity
    $query="delete from tiki_blog_activity where day<$day2";
    $result = $this->query($query);
    // Register new activity
    $query = "select * from tiki_blog_activity where blogId=$blogId and day=$today";
    $result = $this->query($query);
    if($result->numRows()) {
      $query = "update tiki_blog_activity set posts=posts+1 where blogId=$blogId and day=$today";
    } else {
      $query = "insert into tiki_blog_activity(blogId,day,posts) values($blogId,$today,1)";
    }
    $result = $this->query($query);
    // Calculate activity
    $query = "select posts from tiki_blog_activity where blogId=$blogId and day=$today";
    $vtoday = $this->getOne($query);
    $query = "select posts from tiki_blog_activity where blogId=$blogId and day=$day0";
    $day0 = $this->getOne($query);
    $query = "select posts from tiki_blog_activity where blogId=$blogId and day=$day1";
    $day1 = $this->getOne($query);
    $query = "select posts from tiki_blog_activity where blogId=$blogId and day=$day2";
    $day2 = $this->getOne($query);
    $activity = (2 * $vtoday) + ($day0) + (0.5 * $day1) + (0.25 * $day2);
    // Update tiki_blogs with activity information
    $query = "update tiki_blogs set activity=$activity where blogId=$blogId";
    $result = $this->query($query);
  }

  // CMS functions -ARTICLES- & -SUBMISSIONS- ////
  function list_articles($offset = 0,$maxRecords = -1,$sort_mode = 'publishDate_desc', $find='', $date='',$user,$type='',$topicId='')
  {
    global $userlib;
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (title like '%".$find."%' or heading like '%".$find."%' or body like '%".$find."%') ";
    } else {
      $mid='';
    }
    if($date) {
      if($mid) {
        $mid.=" and  publishDate<=$date ";
      } else {
        $mid=" where publishDate<=$date ";
      }
    }
    if($type) {
      if($mid) {
        $mid.=" and type='$type'";
      } else {
        $mid=" where type='$type'";
      }
    }
    if($topicId) {
      if($mid) {
        $mid.=" and topicId=$topicId";
      } else {
        $mid=" where topicId=$topicId";
      }
    }
    $query = "select * from tiki_articles $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_articles $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $res["entrating"]=floor($res["rating"]);
      $add=1;
      if($userlib->object_has_one_permission($res["topicId"],'topic')) {
        if(!$userlib->object_has_permission($user,$res["topicId"],'topic','tiki_p_topic_read')) {
          $add=0;
        }
      }
      if(empty($res["body"])) {
        $res["isEmpty"] = 'y';
      } else {
        $res["isEmpty"] = 'n';
      }
      if(strlen($res["image_data"])>0) {
        $res["hasImage"] = 'y';
      } else {
        $res["hasImage"] = 'n';
      }
      if($add) {
         $ret[] = $res;
      }
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_submissions($offset = 0,$maxRecords = -1,$sort_mode = 'publishDate_desc', $find='', $date='')
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (title like '%".$find."%' or heading like '%".$find."%' or body like '%".$find."%') ";
    } else {
      $mid='';
    }
    if($date) {
      if($mid) {
        $mid.=" and  publishDate<=$date ";
      } else {
        $mid=" where publishDate<=$date ";
      }
    }
    $query = "select * from tiki_submissions $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_submissions $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $res["entrating"]=floor($res["rating"]);
      if(empty($res["body"])) {
        $res["isEmpty"] = 'y';
      } else {
        $res["isEmpty"] = 'n';
      }
      if(strlen($res["image_data"])>0) {
        $res["hasImage"] = 'y';
      } else {
        $res["hasImage"] = 'n';
      }
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function get_article($articleId)
  {
    $query = "select * from tiki_articles where articleId=$articleId";
    $result = $this->query($query);

    if($result->numRows()) {
      $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
      $res["entrating"]=floor($res["rating"]);
    } else {
      return false;
    }
    return $res;
  }

  function get_submission($subId)
  {
    $query = "select * from tiki_submissions where subId=$subId";
    $result = $this->query($query);

    if($result->numRows()) {
      $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
      $res["entrating"]=floor($res["rating"]);
    } else {
      return false;
    }
    return $res;
  }

  function approve_submission($subId)
  {
    $data = $this->get_submission($subId);
    if(!$data) return false;
    if(!$data["image_x"]) $data["image_x"]=0;
    if(!$data["image_y"]) $data["image_y"]=0;
    $this->replace_article ($data["title"],$data["authorName"],$data["topicId"],$data["useImage"],$data["image_name"],$data["image_size"],$data["image_type"],$data["image_data"],$data["heading"],$data["body"],$data["publishDate"],$data["author"],0,$data["image_x"],$data["image_y"],$data["type"],$data["rating"]);
    $this->remove_submission($subId);
  }

  function add_article_hit($articleId)
  {
    $query = "update tiki_articles set reads=reads+1 where articleId=$articleId";
    $result = $this->query($query);

    return true;
  }

  function remove_article($articleId)
  {
    if($articleId) {
      $query = "delete from tiki_articles where articleId=$articleId";
      $result = $this->query($query);

      return true;
    }
    $this->remove_object('article',$articleId);
  }

  function remove_submission($subId)
  {
    if($subId) {
      $query = "delete from tiki_submissions where subId=$subId";
      $result = $this->query($query);

      return true;
    }
  }

  function replace_article ($title,$authorName,$topicId,$useImage,$imgname,$imgsize,$imgtype,$imgdata,$heading,$body,$publishDate,$user,$articleId,$image_x,$image_y,$type,$rating=0)
  {
    $title = addslashes($title);
    $heading = addslashes($heading);
    $authorName = addslashes($authorName);
    $imgdata = addslashes($imgdata);
    $imgname = addslashes($imgname);
    $body = addslashes($body);
    $hash = md5($title.$heading.$body);
    $now = date("U");
    $query = "select name from tiki_topics where topicId = $topicId";
    $topicName = $this->getOne($query);
    $topicName = addslashes($topicName);
    $size = strlen($body);
    if($articleId) {
      // Update the article
      $query = "update tiki_articles set
                title = '$title',
                authorName = '$authorName',
                topicId = $topicId,
                topicName = '$topicName',
                size = $size,
                useImage = '$useImage',
                image_name = '$imgname',
                image_type = '$imgtype',
                image_size = '$imgsize',
                image_data = '$imgdata',
                image_x = $image_x,
                image_y = $image_y,
                heading = '$heading',
                body = '$body',
                publishDate = $publishDate,
                created = $now,
                author = '$user',
                type = '$type',
                rating = $rating
                where articleId = $articleId";
      $result = $this->query($query);

    } else {
      // Insert the article
      $query = "insert into tiki_articles(title,authorName,topicId,useImage,image_name,image_size,image_type,image_data,publishDate,created,heading,body,hash,author,reads,votes,points,size,topicName,image_x,image_y,type,rating)
                         values('$title','$authorName',$topicId,'$useImage','$imgname','$imgsize','$imgtype','$imgdata',$publishDate,$now,'$heading','$body','$hash','$user',0,0,0,$size,'$topicName',$image_x,$image_y,'$type',$rating)";
      $result = $this->query($query);

      $query2 = "select max(articleId) from tiki_articles where created = $now and title='$title' and hash='$hash'";
      $articleId=$this->getOne($query2);
    }
    return $articleId;
  }

  function replace_submission ($title,$authorName,$topicId,$useImage,$imgname,$imgsize,$imgtype,$imgdata,$heading,$body,$publishDate,$user,$subId,$image_x,$image_y,$type,$rating=0)
  {
    global $smarty;
    $title = addslashes($title);
    $heading = addslashes($heading);
    $authorName = addslashes($authorName);
    $imgdata = addslashes($imgdata);
    $imgname = addslashes($imgname);
    $body = addslashes($body);
    $hash = md5($title.$heading.$body);
    $now = date("U");
    $query = "select name from tiki_topics where topicId = $topicId";
    $topicName = $this->getOne($query);
    $topicName = addslashes($topicName);
    $size = strlen($body);
    if($subId) {
      // Update the article
      $query = "update tiki_submissions set
                title = '$title',
                authorName = '$authorName',
                topicId = $topicId,
                topicName = '$topicName',
                size = $size,
                useImage = '$useImage',
                image_name = '$imgname',
                image_type = '$imgtype',
                image_size = '$imgsize',
                image_data = '$imgdata',
                image_x = $image_x,
                image_y = $image_y,
                heading = '$heading',
                body = '$body',
                publishDate = $publishDate,
                created = $now,
                author = '$user' ,
                type = '$type',
                rating = $rating
                where subId = $subId";
      $result = $this->query($query);

    } else {
      // Insert the article
      $query = "insert into tiki_submissions(title,authorName,topicId,useImage,image_name,image_size,image_type,image_data,publishDate,created,heading,body,hash,author,reads,votes,points,size,topicName,image_x,image_y,type,rating)
                         values('$title','$authorName',$topicId,'$useImage','$imgname','$imgsize','$imgtype','$imgdata',$publishDate,$now,'$heading','$body','$hash','$user',0,0,0,$size,'$topicName',$image_x,$image_y,'$type',$rating)";
      $result = $this->query($query);

    }
    $query = "select max(subId) from tiki_submissions where created = $now and title='$title' and hash='$hash'";
    $id=$this->getOne($query);
    $emails = $this->get_mail_events('article_submitted','*');
    $foo = parse_url($_SERVER["REQUEST_URI"]);
    $machine =httpPrefix().$foo["path"];
    foreach ($emails as $email)
    {
      $smarty->assign('mail_site',$_SERVER["SERVER_NAME"]);
      $smarty->assign('mail_user',$user);
      $smarty->assign('mail_title',$title);
      $smarty->assign('mail_heading',$heading);
      $smarty->assign('mail_body',$body);
      $smarty->assign('mail_date',date("U"));
      $smarty->assign('mail_machine',$machine);
      $smarty->assign('mail_subId',$id);
      $mail_data=$smarty->fetch('mail/submission_notification.tpl');
      @mail($email, tra('New article submitted at ').$_SERVER["SERVER_NAME"],$mail_data);
    }
    return $id;
  }

  // CMS functions -TOPICS -////
  function add_topic($name,$imagename,$imagetype,$imagesize,$imagedata)
  {
    $now=date("U");
    $imagename=addslashes($imagename);
    $name=addslashes($name);
    $imagedata=addslashes($imagedata);
    $query = "insert into tiki_topics(name,image_name,image_type,image_size,image_data,active,created)
                     values('$name','$imagename','$imagetype',$imagesize,'$imagedata','y',$now)";
    $result = $this->query($query);

    $query = "select max(topicId) from tiki_topics where created=$now and name='$name'";
    $topicId = $this->getOne($query);
    return $topicId;
  }

  function remove_topic($topicId)
  {
    $query = "delete from tiki_topics where topicId=$topicId";
    $result = $this->query($query);

    $query = "delete from tiki_articles where topicId=$topicId";
    $result = $this->query($query);

    return true;
  }

  function activate_topic($topicId)
  {
    $query = "update tiki_topics set active='y' where topicId=$topicId";
    $result = $this->query($query);

  }

  function deactivate_topic($topicId)
  {
    $query = "update tiki_topics set active='n' where topicId=$topicId";
    $result = $this->query($query);

  }

  function get_topic_image($topicId)
  {
    $query = "select image_name,image_size,image_type,image_data from tiki_topics where topicId=$topicId";
    $result = $this->query($query);

    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function get_topic($topicId)
  {
    $query = "select topicId,name,image_name,image_size,image_type from tiki_topics where topicId=$topicId";
    $result = $this->query($query);

    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function list_topics()
  {
    $query = "select topicId,name,image_name,image_size,image_type,active from tiki_topics order by name";
    $result = $this->query($query);

    $ret=Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $res["subs"]=$this->getOne("select count(*) from tiki_submissions where topicId=".$res["topicId"]);
      $res["arts"]=$this->getOne("select count(*) from tiki_articles where topicId=".$res["topicId"]);
      $ret[]=$res;
    }
    return $ret;
  }

  function list_active_topics()
  {
    $query = "select * from tiki_topics where active='y'";
    $result = $this->query($query);

    $ret=Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[]=$res;
    }
    return $ret;
  }

  function add_featured_link($url,$title,$description='',$position=0,$type='f')
  {
    $title=addslashes($title);
    $url=addslashes($url);
    $description=addslashes($description);
    $query = "replace tiki_featured_links(url,title,description,position,hits,type) values('$url','$title','$description',$position,0,'$type')";
    $result = $this->query($query);
  }

  function remove_featured_link($url)
  {
    $query = "delete from tiki_featured_links where url='$url'";
    $result = $this->query($query);
  }

  function update_featured_link($url, $title, $description, $position=0,$type='f')
  {
    $query = "update tiki_featured_links set title='$title', type='$type', description='$description', position=$position where url='$url'";
    $result = $this->query($query);
  }

  function add_featured_link_hit($url)
  {
    $query = "update tiki_featured_links set hits = hits + 1 where url = '$url'";
    $result = $this->query($query);
  }

  function get_featured_link($url)
  {
    $query = "select * from tiki_featured_links where url='$url'";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function generate_featured_links_positions()
  {
    $query = "select url from tiki_featured_links order by hits desc";
    $result = $this->query($query);
    $position = 1;
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $url = $res["url"];
      $query2="update tiki_featured_links set position=$position where url='$url'";
      $result2 = $this->query($query2);
      $position++;
    }
    return true;
  }

  function get_featured_links($max=10)
  {
    $query = "select * from tiki_featured_links where position>0 order by position asc limit 0,$max";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    return $ret;
  }

  function capture_images($data)
  {
    $cacheimages = $this->get_preference("cacheimages",'y');
    if($cacheimages != 'y') return $data;
    preg_match_all("/src=\"([^\"]+)\"/",$data,$reqs1);
    preg_match_all("/src=\'([^\']+)\'/",$data,$reqs2);
    preg_match_all("/src=([A-Za-z0-9\:\?\=\/\\\.\-\_]+)\}/",$data,$reqs3);
    preg_match_all("/src=([A-Za-z0-9\:\?\=\/\\\.\-\_]+) /",$data,$reqs4);
    $merge = array_merge($reqs1[1],$reqs2[1],$reqs3[1],$reqs4[1]);
    $merge = array_unique($merge);
    //print_r($merge);
    // Now for each element in the array capture the image and
    // if the capture was succesful then change the reference to the
    // internal image
    $page_data = $data;
    foreach($merge as $img) {
      // This prevents caching images
      if(!strstr($img,"show_image.php") && !strstr($img,"nocache")) {
      //print("Procesando: $img<br/>");
      @$fp = fopen($img,"r");
      if($fp) {
        $data = '';
        while(!feof($fp)) {
          $data .= fread($fp,4096);
        }
        //print("Imagen leida:".strlen($data)." bytes<br/>");
        fclose($fp);
        if(strlen($data)>0) {
          $url_info = parse_url($img);
          $pinfo = pathinfo($url_info["path"]);
          $type = "image/".$pinfo["extension"];
          $name = $pinfo["basename"];
          $size = strlen($data);
          $url = $img;

          if(function_exists("ImageCreateFromString")&&(!strstr($type,"gif"))) {

            $img = imagecreatefromstring($data);
            $size_x = imagesx($img);
            $size_y = imagesy($img);
      // Fix the ratio values for system gallery
      $gal_info["thumbSizeX"]=90;
      $gal_info["thumbSizeY"]=90;
            if ($size_x > $size_y)
              $tscale = ((int)$size_x / $gal_info["thumbSizeX"]);
            else
              $tscale = ((int)$size_y / $gal_info["thumbSizeY"]);
            $tw = ((int)($size_x / $tscale));
            $ty = ((int)($size_y / $tscale));
            if (chkgd2()) {
              $t = imagecreatetruecolor($tw,$ty);
              imagecopyresampled($t, $img, 0,0,0,0, $tw,$ty, $size_x, $size_y);
            } else {
              $t = imagecreate($tw,$ty);
              $this->ImageCopyResampleBicubic( $t, $img, 0,0,0,0, $tw,$ty, $size_x, $size_y);
            }
            // CHECK IF THIS TEMP IS WRITEABLE OR CHANGE THE PATH TO A WRITEABLE DIRECTORY
            //$tmpfname = 'temp.jpg';
            $tmpfname = tempnam ("/tmp", "FOO").'.jpg';
            imagejpeg($t,$tmpfname);
            // Now read the information
            $fp = fopen($tmpfname,"rb");
            $t_data = fread($fp, filesize($tmpfname));
            fclose($fp);
            unlink($tmpfname);
            $t_pinfo = pathinfo($tmpfname);
            $t_type = $t_pinfo["extension"];
            $t_type='image/'.$t_type;

            $imageId = $this->insert_image(0,'','',$name, $type, $data, $size, $size_x, $size_y, 'admin',$t_data,$t_type);
            //print("Imagen generada en $imageId<br/>");
          } else {
            //print("No GD detected generating image without thumbnail<br/>");
            $imageId = $this->insert_image(0,'','',$name, $type, $data, $size, 100, 100, 'admin','','');
      //print("Imagen en $imageId<br/>");
          }
          // Now change it!
          //print("Changing $url to imageId: $imageId");
          $uri = parse_url($_SERVER["REQUEST_URI"]);
          $path=str_replace("tiki-editpage","show_image",$uri["path"]);
          $page_data = str_replace($url,httpPrefix().$path.'?id='.$imageId,$page_data);
        } // if strlen
      } // if $fp
      }
    } // foreach
    return $page_data;
  }

  function update_session($sessionId)
  {
    global $user;
    $now = date("U");
    $oldy = $now-(5*60);
    $query = "replace into tiki_sessions(sessionId,timestamp,user) values('$sessionId',$now,'$user')";
    $result = $this->query($query);
    $query = "delete from tiki_sessions where timestamp<$oldy";
    $result = $this->query($query);
    return true;
  }

  function count_sessions()
  {
    $query = "select count(*) from tiki_sessions";
    $cant = $this->getOne($query);
    return $cant;
  }

  function replace_user_module($name,$title,$data)
  {
    $name = addslashes($name);
    $title = addslashes($title);
    $data = addslashes($data);
    if( (!empty($name)) && (!empty($title)) && (!empty($data)) ) {
      $query = "replace into tiki_user_modules(name,title,data) values('$name','$title','$data')";
      $result = $this->query($query);
      return true;
    }
  }

  function assign_module($name,$title,$position,$order,$cache_time=0,$rows=10,$groups,$params)
  {
    $params=addslashes($params);
    $name = addslashes($name);
    $groups = addslashes($groups);
    $query = "delete from tiki_modules where name='$name'";
    $result = $this->query($query);
    $query = "insert into tiki_modules(name,title,position,ord,cache_time,rows,groups,params) values('$name','$title','$position',$order,$cache_time,$rows,'$groups','$params')";
    $result = $this->query($query);
    return true;
  }

  function get_assigned_module($name)
  {
    $query = "select * from tiki_modules where name='$name'";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    if($res["groups"]) {
      $grps = unserialize($res["groups"]);
      $res["module_groups"]='';
      foreach($grps as $grp) {
        $res["module_groups"].=" $grp ";
      }
    }
    return $res;
  }

  function unassign_module($name)
  {
    $query = "delete from tiki_modules where name='$name'";
    $result = $this->query($query);
    $query = "delete from tiki_user_assigned_modules where name='$name'";
    $result = $this->query($query);
    return true;
  }

  function get_rows($name)
  {
    $query = "select rows from tiki_modules where name='$name'";
    $rows = $this->getOne($query);
    if($rows==0) $rows=10;
    return $rows;
  }

  function module_up($name)
  {
    $query = "update tiki_modules set ord=ord-1 where name='$name'";
    $result = $this->query($query);
    return true;
  }

  function module_down($name)
  {
    $query = "update tiki_modules set ord=ord+1 where name='$name'";
    $result = $this->query($query);
    return true;
  }

  function get_assigned_modules($position)
  {
    $query = "select params,name,title,position,ord,cache_time,rows,groups from tiki_modules where position='$position' order by ord asc";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      if($res["groups"]) {
        $grps = unserialize($res["groups"]);
        $res["module_groups"]='';
        foreach($grps as $grp) {
          $res["module_groups"].=" $grp ";
        }
      } else {
        $res["module_groups"]='&nbsp;';
      }
      $ret[] = $res;
    }
    return $ret;
  }

  function get_all_modules()
  {
    $user_modules = $this->list_user_modules();
    $all_modules=Array();
    foreach($user_modules["data"] as $um) {
      $all_modules[]=$um["name"];
    }
    // Now add all the system modules
    $h = opendir("templates/modules");
    while (($file = readdir($h)) !== false) {
      if(substr($file,0,3)=='mod') {
        if(!strstr($file,"nocache")){
          $name = substr($file,4,strlen($file)-8);
          $all_modules[]=$name;
        }
      }
    }
    closedir($h);
    return $all_modules;
  }

  function is_user_module($name)
  {
    $name=addslashes($name);
    $query = "select name from tiki_user_modules where name='$name'";
    $result = $this->query($query);
    return $result->numRows();
  }

  function remove_user_module($name)
  {
    $name=addslashes($name);
    $this->unassign_module($name);
    $query = " delete from tiki_user_modules where name='$name'";
    $result = $this->query($query);
    return true;
  }

  function get_user_module($name)
  {
    $name=addslashes($name);
    $query = "select * from tiki_user_modules where name='$name'";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function list_user_modules()
  {
    $query = "select * from tiki_user_modules";
    $result = $this->query($query);
    $query_cant = "select count(*) from tiki_user_modules";
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function cache_links($links)
  {
    $cachepages = $this->get_preference("cachepages",'y');
    if($cachepages != 'y') return false;
    foreach($links as $link) {
      if(!$this->is_cached($link)) {
        $this->cache_url($link);
      }
    }
  }

  function get_links($data)
  {
    $links = Array();
    if(preg_match_all("/\[([^\[\|\]]+)(\||\])/",$data,$r1)) {

      $res = $r1[1];
      $links = array_unique($res);
    }

    return $links;
  }

  function get_links_nocache($data)
  {
    $links = Array();
    if(preg_match_all("/\[([^\]]+)/",$data,$r1)) {
      $res = Array();
      foreach($r1[1] as $alink) {
        $parts = explode('|',$alink);
        if(isset($parts[1])&& $parts[1] == 'nocache' ) {
          $res[] = $parts[0];
        } else {
          if(isset($parts[2]) && $parts[2] == 'nocache') {
            $res[] = $parts[0];
          }
        }
      }

      $links = array_unique($res);
    }

    return $links;
  }

  function is_cached($url)
  {
    if(strstr($url,"tiki-index")) {
      return false;
    }
    if(strstr($url,"tiki-edit")) {
      return false;
    }
    $query = "select cacheId from tiki_link_cache where url='$url'";
    $result = $this->query($query);
    $cant = $result->numRows();
    return $cant;
  }

  function list_cache($offset,$maxRecords,$sort_mode,$find)
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" where (url like '%".$find."%') ";
    } else {
      $mid="";
    }
    $query = "select cacheId,url,refresh from tiki_link_cache $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_link_cache $mid";
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function cache_url($url)
  {
    $url=addslashes($url);
    // This function stores a cached representation of a page in the cache
    // Check if the URL is not already cached
    //if($this->is_cached($url)) return false;
    @$fp = fopen($url,"r");
    if(!$fp) return false;
    $data = '';
    while(!feof($fp)) {
      $data .= fread($fp,4096);
    }
    fclose($fp);
    // Check for META tags with equiv
    if(0){
    print("Len: ".strlen($data)."<br/>");
    preg_match_all("/\<meta([^\>\<\n\t]+)/i",$data,$reqs);
    foreach($reqs[1] as $meta)
    {
      print("Un meta: $meta<br/>");
      if(stristr($meta,'refresh')) {
        print("Es refresh<br/>");
        preg_match("/url=([^ \"\'\n\t]+)/i",$meta,$urls);
        if(strlen($urls[1])) {
          $urli=$urls[1];
          print("URL: $urli<br/>");
        }
      }
    }
    print("pepe");
    }
    $data = addslashes($data);
    $refresh = date("U");
    $query = "insert into tiki_link_cache(url,data,refresh) values('$url','$data',$refresh)";
    $result = $this->query($query);
    return true;
  }

  function refresh_cache($cacheId)
  {
    $query = "select url from tiki_link_cache where cacheId=$cacheId";
    $url = $this->getOne($query);
    @$fp = fopen($url,"r");
    if(!$fp) return false;
    $data = '';
    while(!feof($fp)) {
      $data .= fread($fp,4096);
    }
    fclose($fp);
    $data = addslashes($data);
    $refresh = date("U");
    $query = "update tiki_link_cache set data='$data', refresh=$refresh where cacheId=$cacheId";
    $result = $this->query($query);
    return true;
  }

  function remove_cache($cacheId)
  {
    $query = "delete from tiki_link_cache where cacheId=$cacheId";
    $result = $this->query($query);
    return true;
  }

  function get_cache($cacheId)
  {
    $query = "select * from tiki_link_cache where cacheId=$cacheId";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function get_cache_id($url)
  {
    if(!$this->is_cached($url)) return false;
    $query = "select cacheId from tiki_link_cache where url='$url'";
    $id = $this->getOne($query);
    return $id;
  }

  function add_image_hit($id)
  {
    $query = "update tiki_images set hits=hits+1 where imageId=$id";
    $result = $this->query($query);
    return true;
  }

  function add_gallery_hit($id)
  {
    $query = "update tiki_galleries set hits=hits+1 where galleryId=$id";
    $result = $this->query($query);
    return true;
  }

  function ImageCopyResampleBicubic (&$dst_img, &$src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
// port to PHP by John Jensen July 10 2001 (updated 4/21/02) -- original code (in C, for the PHP GD Module) by jernberg@fairytale.se////
{
$palsize = ImageColorsTotal ($src_img);
for ($i = 0; $i < $palsize; $i++) { // get palette.
$colors = ImageColorsForIndex ($src_img, $i);
ImageColorAllocate ($dst_img, $colors['red'], $colors['green'], $colors['blue']);
}

$scaleX = ($src_w - 1) / $dst_w;
$scaleY = ($src_h - 1) / $dst_h;
$scaleX2 = (int) ($scaleX / 2);
$scaleY2 = (int) ($scaleY / 2);
for ($j = $src_y; $j < $dst_h; $j++) {
$sY = (int) ($j * $scaleY);
$y13 = $sY + $scaleY2;
for ($i = $src_x; $i < $dst_w; $i++) {
$sX = (int) ($i * $scaleX);
$x34 = $sX + $scaleX2;
$color1 = ImageColorsForIndex ($src_img, ImageColorAt ($src_img, $sX, $y13));
$color2 = ImageColorsForIndex ($src_img, ImageColorAt ($src_img, $sX, $sY));
$color3 = ImageColorsForIndex ($src_img, ImageColorAt ($src_img, $x34, $y13));
$color4 = ImageColorsForIndex ($src_img, ImageColorAt ($src_img, $x34, $sY));
$red = ($color1['red'] + $color2['red'] + $color3['red'] + $color4['red']) / 4;
$green = ($color1['green'] + $color2['green'] + $color3['green'] + $color4['green']) / 4;
$blue = ($color1['blue'] + $color2['blue'] + $color3['blue'] + $color4['blue']) / 4;
ImageSetPixel ($dst_img, $i + $dst_x - $src_x, $j + $dst_y - $src_y, ImageColorClosest ($dst_img, $red, $green, $blue));
}
}
}

  function store_image_data($imageid,&$data,$itype,$filename,$filetype,$xsize=0,$ysize=0,$replace=0)
  {
    // todo: gallerie loeschen: scales wegmachen
    // todo: replace
    global $gal_use_dir;
    global $gal_use_db;

    $size=sizeof($data);
    $fhash="";

    if($gal_use_db == 'y') {
      // Prepare to store data in database
      $data = addslashes($data);
    } else {
      // Store data in directory
      switch ($itype) {
        case 't':
          $ext=".thumb";
	  break;
        case 's':
          $ext=".scaled_".$xsize."x".$ysize;
	  break;
	case 'b':
	  // for future use
	  $ext=".backup";
	  break;
	default:
	  $ext='';
	}
      $fhash = md5($filename).$ext; //Path+extension
      @$fw = fopen($gal_use_dir.$fhash,"wb");
      if(!$fw) {
        return false;
      }
      fwrite($fw,$data);
      fclose($fw);
      $data='';
    }
    $filename=$xsize."x".$ysize."_".$filename; // rebuild filename for downloading images
    // insert data
    $query = "insert into tiki_images_data(imageId,xsize,ysize,
                                type,filesize,filetype,filename,data)
                        values ($imageid,$xsize,$ysize,'$itype',$size,
                                '$filetype','$filename','$data')";
    $result = $this->query($query);
    return true;
  }



  function rebuild_image($imageid,$itype,$xsize,$ysize)
  {
    $galid=$this->get_gallery_from_image($imageid);
    //we don't rebuild original images
    if ($itype == 'o') return false;

    //if it is a scaled image, test the gallery settings
    if ($itype == 's') 
    {
      $scaleinfo=$this->get_gallery_scale_info($galid);
      $hasscale=false;
      while (list ($num, $sci) = each ($scaleinfo)) {
        if ($sci["xsize"] == $xsize && $sci["ysize"] == $ysize) {
	  $hasscale=true;
	  $newx=$sci["xsize"];
	  $newy=$sci["ysize"];
	}
      }
      if (!$hasscale) return false;
    }

    // now we can start rebuilding the image
    global $gal_use_dir;
    global $gal_use_db;
    if(!function_exists("ImageCreateFromString")) return false;
    #get image and other infos
    $data=$this->get_image($imageid);
    $galinfo=$this->get_gallery_info($galid);

    // todo: is this necessary?
    // get data if images are stored in filesystem
    if($data["path"]){
      $data["data"]='';
      $fp=fopen($gal_use_dir.$data["path"],"rb");
      if(!$fp) die;
      while(!feof($fp)) {
        $data["data"].=fread($fp,8192*16);
      }
      fclose($fp);
    }

    $img=imagecreatefromstring($data["data"]);

    // determine new size
    if ($itype == 't') {
      $newx=$galinfo["thumbSizeX"];
      $newy=$galinfo["thumbSizeY"];
    } 

    if($data["xsize"] > $data["ysize"])
    {
      $tscale = ((int)$data["xsize"] / $newx);
    } else {
      $tscale = ((int)$data["ysize"] / $newy);
    }
    $xsize=((int)($data["xsize"] / $tscale));
    $ysize=((int)($data["ysize"] / $tscale));
    
    if(chkgd2()) {
      $t = imagecreatetruecolor($xsize,$ysize);
      imagecopyresampled($t, $img, 0,0,0,0, $xsize,$ysize, $data["xsize"], $data["ysize"]);
     } else {
       $t = imagecreate($xsize,$ysize);
       $this->ImageCopyResampleBicubic( $t, $img, 0,0,0,0, $xsize,$ysize, $data["xsize"], $data["ysize"]);
     }

    //fetch the image
    ob_start();
    imagejpeg($t);
    $t_data = ob_get_contents();
    ob_end_clean();
    // we always rescale to jpegs.
    $t_type='image/jpeg';

    // some more infos
    $filename=$data["filename"]; // filename of original image

    $this->store_image_data($imageid,$t_data,$itype,$filename,$t_type,$xsize,$ysize);
    $newsize["xsize"]=$xsize;
    $newsize["ysize"]=$ysize;
    return $newsize;
  }

  function rebuild_thumbnails($galleryId)
  {
    global $gal_use_dir;
    global $gal_use_db;

    // rewritten by flo
    $query = "select imageId from tiki_images where galleryId=$galleryId";
    $result = $this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $query2="delete from tiki_images_data where imageId=".$res["imageId"]." and type='t'";
      $result2 = $this->query($query2);
    }
    return true;
  }

  function edit_image($id,$name,$description) {
   $name = addslashes(strip_tags($name));
   $description = addslashes(strip_tags($description));
    $query = "update tiki_images set name='$name', description='$description' where imageId = $id";
    $result = $this->query($query);
    return true;
  }

  function get_random_image($galleryId = -1)
  {
    $whgal = "";
    if (((int)$galleryId) != -1) { $whgal = " where galleryId = " . $galleryId; }
    $query = "select count(*) from tiki_images" . $whgal;
    $cant = $this->getOne($query);
    $pick = rand(0,$cant-1);
    $ret = Array();
    $query = "select imageId,galleryId,name from tiki_images" . $whgal . " limit $pick,1";
    $result=$this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    $ret["galleryId"] = $res["galleryId"];
    $ret["imageId"] = $res["imageId"];
    $ret["name"] = $res["name"];
    $query = "select name from tiki_galleries where galleryId = " . $res["galleryId"];
    $ret["gallery"] = $this->getOne($query);
    return($ret);
  }

  function insert_image($galleryId,$name,$description,$filename, $filetype, $data, $size, $xsize, $ysize, $user,$t_data,$t_type)
  {
    global $gal_use_db;
    global $gal_use_dir;
    $name = addslashes(strip_tags($name));
    $description = addslashes(strip_tags($description));
    $now = date("U");
    $path='';
    if($gal_use_db == 'y') {
      // Prepare to store data in database
      $data = addslashes($data);
      $t_data = addslashes($t_data);
    } else {
      // Store data in directory
      $fhash = md5($filename);
      @$fw = fopen($gal_use_dir.$fhash,"wb");
      if(!$fw) {
        return false;
      }
      fwrite($fw,$data);
      fclose($fw);
      @$fw = fopen($gal_use_dir.$fhash.'.thumb',"wb");
      if(!$fw) {
        return false;
      }
      fwrite($fw,$t_data);
      fclose($fw);
      $t_data='';
      $data='';
      $path=$fhash;
    }
    $query = "insert into tiki_images(galleryId,name,description,user,created,hits,path)
                          values($galleryId,'$name','$description','$user',$now,0,'$path')";
    $result = $this->query($query);
    $query = "select max(imageId) from tiki_images where created=$now";
    $imageId = $this->getOne($query);
    // insert data
    $query = "insert into tiki_images_data(imageId,xsize,ysize,
    				type,filesize,filetype,filename,data)
			values ($imageId,$xsize,$ysize,'o',$size,
			        '$filetype','$filename','$data')";
    $result = $this->query($query);
    // insert thumb
    if (sizeof($t_data) >0)
    {
      $query = "insert into tiki_images_data(imageId,xsize,ysize,
                                type,filesize,filetype,filename,data)
                        values ($imageId,$xsize,$ysize,'t',$size,
                                '$t_type','$filename','$t_data')";
      $result = $this->query($query);
    }

    $query = "update tiki_galleries set lastModif=$now where galleryId=$galleryId";
    $result = $this->query($query);
    return $imageId;
  }


  function rotate_image($id,$angle)
  {
    //get image
    global $gal_use_dir;
    global $gal_use_db;
    $data=$this->getOne("select data from tiki_images_data where imageId=$id and type='o'");
    //$data = $this->get_image($id);
    $data = imagecreatefromstring($data);

    $sx=imagesx($data);
    $sy=imagesy($data);
    $data=imagerotate($data,$angle,0);
    ob_start();
    imagejpeg($data);
    $data = ob_get_contents();
    ob_end_clean();
    // Prepare to store data in database
    $data= addslashes($data);
    $query = "update tiki_images_data set data='$data' where imageId=$id and type='o'";
    $result = $this->query($query);
    if (DB::isError($result)) $this->sql_error($query,$result);
    // delete all scaled images. Will be rebuild when requested
    $query = "delete from tiki_images_data where imageId=$id and type !='o'";
    $result = $this->query($query);
  }

  function rotate_right_image($id)
  {
    $this->rotate_image($id,270);
  }

  function rotate_left_image($id)
  {
    $this->rotate_image($id,90);
  }


  function remove_image($id)
  {
    global $gal_use_dir;
    $path = $this->getOne("select path from tiki_images where imageId=$id");
    if($path) {
      @unlink($gal_use_dir.$path);
      @unlink($gal_use_dir.$path.'.thumb');
      //todo: remove scaled images
    }
    $query = "delete from tiki_images where imageId=$id";
    $result = $this->query($query);
    $query = "delete from tiki_images_data where imageId=$id";
    $result = $this->query($query);
    return true;
  }

  function get_images($offset,$maxRecords,$sort_mode,$find,$galleryId=-1)
  {
   
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($find) {
      $mid=" and (name like '%".$find."%' or description like '%".$find."%')";
    } else {
      $mid="";
    }
    
    $midcant="";
    if ($galleryId != -1 && is_numeric($galleryId)) 
    {  
      $mid .= " and i.galleryId=$galleryId ";
      $midcant = "where galleryId=$galleryId ";
    }

    $query = "select i.path ,i.imageId,i.name,i.description,i.created,
    		d.filename,d.filesize,d.xsize,d.ysize,
		i.user,i.hits 
		from tiki_images i , tiki_images_data d 
		 where i.imageID=d.imageID
		 and d.type='o'
		$mid 
		order by $sort_mode limit $offset,$maxRecords";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $query_cant = "select count(*) from tiki_images $midcant";
    $cant = $this->getOne($query_cant);
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_images($offset,$maxRecords,$sort_mode,$find)
  {
    return $this->get_images($offset,$maxRecords,$sort_mode,$find);
  }

  function get_gallery_owner($galleryId)
  {
    $query = "select user from tiki_galleries where galleryId=$galleryId";
    $user = $this->getOne($query);
    return $user;
  }

  function get_gallery_from_image($imageid)
  {
    $query = "select galleryId from tiki_images where imageId=$imageid";
    $galid=$this->getOne($query);
    return $galid;

  }

  function get_gallery($id)
  {
    $query = "select * from tiki_galleries where galleryId='$id'";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function move_image($imgId,$galId)
  {
    $query = "update tiki_images set galleryId=$galId where imageId=$imgId";
    $result = $this->query($query);
    return true;
  }

  function get_image_info($id,$itype='o',$xsize=0,$ysize=0)
  {
    // code may be merged with get_image
    $mid="";
    if ($xsize!=0) {$mid="and d.xsize=$xsize ";}
    if ($ysize!=0) {$mid.="and d.ysize=$ysize ";}
    if ($xsize!=0 && $ysize==$xsize)
      {
        // we don't know yet.
        $mid="and greatest(d.xsize,d.ysize) = greatest($xsize,$ysize) ";
      }
    $query = "select i.imageId, i.galleryId, i.name,
                     i.description, i.created, i.user,
                     i.hits, i.path,
                     d.xsize,d.ysize,d.type,d.filesize,
                     d.filetype,d.filename
                 from tiki_images i, tiki_images_data d where
                     i.imageId='$id' and d.imageId=i.imageId
                     and d.type='$itype'
                     $mid";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  // Add an option to stablish Image size (x,y)
  function get_image($id,$itype='o',$xsize=0,$ysize=0)
  {
    // todo: get image von fs
    global $gal_use_db;
    global $gal_use_dir;
    $mid="";
    if ($xsize!=0) {$mid="and d.xsize=$xsize ";}
    if ($ysize!=0) {$mid.="and d.ysize=$ysize ";}
    if ($xsize!=0 && $ysize==$xsize) 
      {
        // we don't know yet.
        $mid="and greatest(d.xsize,d.ysize) = greatest($xsize,$ysize) ";
      }
    $query = "select i.imageId, i.galleryId, i.name,
                     i.description, i.created, i.user,
		     i.hits, i.path,
		     d.xsize,d.ysize,d.type,d.filesize,
		     d.filetype,d.filename,d.data
		 from tiki_images i, tiki_images_data d where 
		     i.imageId='$id' and d.imageId=i.imageId 
		     and d.type='$itype'
		     $mid";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    # build scaled images or thumb if not availible
    if ($itype != 'o' && !isset($res["imageId"]))
      {
        if($newsize=$this->rebuild_image($id,$itype,$xsize,$ysize)) {
	  return $this->get_image($id,$itype,$newsize["xsize"],$newsize["ysize"]);
	}
      }
    // get image data from fs
    if ($res["data"]=='')
    {
      switch ($itype) {
        case 't':
          $ext=".thumb";
          break;
        case 's':
          $ext=".scaled_".$res["xsize"]."x".$res["ysize"];
          break;
        case 'b':
          // for future use
          $ext=".backup";
          break;
        default:
          $ext='';
        }
      // If the image was a .gif then the thumbnail has 0 bytes if the thumbnail
      // is empty then use the full image as thumbnail
      if($ext==".thumb" && filesize($gal_use_dir.$res["path"].$ext)==0 ) {
        $ext='';
      }
      @$fp = fopen($gal_use_dir.$res["path"].$ext,'rb');
      if(!$fp) {die;}
      while(!feof($fp)) {
        $res["data"].=fread($fp,8192*16);
      }
      fclose($fp);
    }
    return $res;
  }

  function get_image_thumb($id)
  {
    return $this->get_image($id,'t');
  }

  
  function replace_gallery($galleryId, $name, $description, $theme, $user,$maxRows,$rowImages,$thumbSizeX,$thumbSizeY,$public,$visible='y')
  {
    // if the user is admin or the user is the same user and the gallery exists then replace if not then
    // create the gallary if the name is unused.
    $name = addslashes(strip_tags($name));
    $description = addslashes(strip_tags($description));
    $now = date("U");
    if($galleryId>0) {
      //$res = $result->fetchRow(DB_FETCHMODE_ASSOC);
      //if( ($user == 'admin') || ($res["user"]==$user) ) {
      $query = "update tiki_galleries set name='$name',visible='$visible', maxRows=$maxRows, rowImages=$rowImages, thumbSizeX=$thumbSizeX, thumbSizeY=$thumbSizeY, description='$description', theme='$theme', lastModif=$now, public='$public' where galleryId=$galleryId";
      $result = $this->query($query);
    } else {
      // Create a new record
      $query =  "insert into tiki_galleries(name,description,theme,created,user,lastModif,maxRows,rowImages,thumbSizeX,thumbSizeY,public,hits,visible)
                                    values ('$name','$description','$theme',$now,'$user',$now,$maxRows,$rowImages,$thumbSizeX,$thumbSizeY,'$public',0,'$visible')";
      $result = $this->query($query);
      $galleryId = $this->getOne("select max(galleryId) from tiki_galleries where name='$name' and created=$now");
    }
    return $galleryId;
  }
  
  function add_gallery_scale($galleryId,$xsize,$ysize)
  {
    $query="insert into tiki_galleries_scales(galleryId,xsize,ysize)
    	    values($galleryId,$xsize,$ysize)";
    $result = $this->query($query);
  }

  function remove_gallery_scale($galleryId,$xsize=0,$ysize=0)
  {
    $mid="";
    if ($xsize!=0) $mid=" and xsize=$xsize ";
    if ($ysize!=0) $mid.=" and ysize=$ysize";
    $query="delete from tiki_galleries_scales where
            galleryId=$galleryId $mid";
    $result = $this->query($query);
  }

  function remove_gallery($id)
  {
    global $gal_use_dir;
    $query = "select path from tiki_images where galleryId='$id'";
    $result = $this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) { 
      $path = $res["path"];
      if($path) {
        @unlink($gal_use_dir.$path);
        @unlink($gal_use_dir.$path.'.thumb');
      }
    }
    $query = "delete from tiki_galleries where galleryId='$id'";
    $result = $this->query($query);
    $query = "delete from tiki_images where galleryId='$id'";
    $result = $this->query($query);
    $this->remove_gallery_scale($id);
    $this->remove_object('image gallery',$id);
    return true;
  }

  function get_gallery_info($id)
  {
    $query = "select * from tiki_galleries where galleryId='$id'";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function get_gallery_scale_info($id)
  {
    $query = "select * from tiki_galleries_scales where galleryId='$id'
              order by xsize*ysize asc";
    $result = $this->query($query);
    $resa=Array();
    while ($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $resa[]=$res;
    }
    return $resa;
  }
  
  function get_gallery_next_scale($id,$xsize=0,$ysize=0)
  {
    $xy=$xsize*$ysize;
    $query = "select * from tiki_galleries_scales where galleryId='$id'
              and xsize*ysize > $xy order by xsize*ysize asc";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function vote_page($page, $points)
  {
    $page = addslashes($page);
    $query = "update pages set points=points+$points, votes=votes+1 where pageName='$page'";
    $result = $this->query($query);
  }

  function get_votes($page)
  { 
    $page = addslashes($page);
    $query = "select points,votes from pages where pageName='$page'";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  function tag_exists($tag)
  {
    $query = "select distinct tagName from tiki_tags where tagName = '$tag'";
    $result = $this->query($query);
    return $result->numRows($result);
  }

  function remove_tag($tagname)
  {
    $query = "delete from tiki_tags where tagName='$tagname'";
    $result = $this->query($query);
    $action = "removed tag: $tagname";
    $t = date("U");
    $query = "insert into tiki_actionlog(action,pageName,lastModif,user,ip,comment) values('$action','HomePage',$t,'admin','".$_SERVER["REMOTE_ADDR"]."','')";
    $result = $this->query($query);
    return true;
  }

  function get_tags()
  {
    $query = "select distinct tagName from tiki_tags";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res["tagName"];
    }
    return $ret;
  }

  // This function can be used to store the set of actual pages in the "tags"
  // table preserving the state of the wiki under a tag name.
  function create_tag($tagname,$comment='')
  {
    $tagname = addslashes($tagname);
    $comment = addslashes($comment);
    $query = "select * from tiki_pages";
    $result=$this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $query = "replace into tiki_tags(tagName,pageName,hits,data,lastModif,comment,version,user,ip,flag,description)
                values('$tagname','".$res["pageName"]."',".$res["hits"].",'".addslashes($res["data"])."',".$res["lastModif"].",'".$res["comment"]."',".$res["version"].",'".$res["user"]."','".$res["ip"]."','".$res["flag"]."','".$res["description"]."')";
      $result2=$this->query($query);
    }
    $action = "created tag: $tagname";
    $t = date("U");
    $query = "insert into tiki_actionlog(action,pageName,lastModif,user,ip,comment) values('$action','HomePage',$t,'admin','".$_SERVER["REMOTE_ADDR"]."','$comment')";
    $result = $this->query($query);
    return true;
  }

  // This funcion recovers the state of the wiki using a tagName from the
  // tags table
  function restore_tag($tagname)
  {
    $query = "update tiki_pages set cache_timestamp=0";
    $this->query($query);
    $query = "select * from tiki_tags where tagName='$tagname'";
    $result=$this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $query = "replace into tiki_pages(pageName,hits,data,lastModif,comment,version,user,ip,flag,description)
                values('".$res["pageName"]."',".$res["hits"].",'".addslashes($res["data"])."',".$res["lastModif"].",'".$res["comment"]."',".$res["version"].",'".$res["user"]."','".$res["ip"]."','".$res["flag"]."','".$res["description"]."')";
      $result2=$this->query($query);
    }
    $action = "recovered tag: $tagname";
    $t = date("U");
    $query = "insert into tiki_actionlog(action,pageName,lastModif,user,ip,comment) values('$action','HomePage',$t,'admin','".$_SERVER["REMOTE_ADDR"]."','')";
    $result = $this->query($query);
    return true;
  }

  // This funcion return the $limit most accessed pages
  // it returns pageName and hits for each page
  function get_top_pages($limit)
  {
    $query = "select pageName, hits from tiki_pages order by hits desc limit 0,$limit";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux["pageName"] = $res["pageName"];
      $aux["hits"] = $res["hits"];
      $ret[] = $aux;
    }
    return $ret;
  }

  // Returns the name of "n" random pages
  function get_random_pages($n)
  {
    $query = "select count(*) from tiki_pages";
    $cant = $this->getOne($query);
    // Adjust the limit if there are not enough pages
    if($cant<$n) $n=$cant;
    // Now that we know the number of pages to pick select n random positions from 0 to cant
    $positions = Array();
    for ($i=0;$i<$n;$i++)
    {
      $pick = rand(0,$cant-1);
      if(!in_array($pick,$positions)) $positions[]=$pick;
    }
    // Now that we have the positions we just build the data
    $ret = Array();
    for ($i=0; $i<count($positions);$i++) {
      $index = $positions[$i];
      $query = "select pageName from tiki_pages limit $index,1";
      $name = $this->getOne($query);
      $ret[]=$name;
    }
    return $ret;
  }

  function wiki_ranking_top_pages($limit)
  {
    $query = "select pageName, hits from tiki_pages order by hits desc limit 0,$limit";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux["name"] = $res["pageName"];
      $aux["hits"] = $res["hits"];
      $aux["href"] = 'tiki-index.php?page='.$res["pageName"];
      $ret[] = $aux;
    }
    $retval["data"]=$ret;
    $retval["title"]=tra("Wiki top pages");
    $retval["y"]=tra("Hits");
    return $retval;
  }

  function wiki_ranking_top_pagerank($limit)
  {
    $this->pageRank();
    $query = "select pageName, pageRank from tiki_pages order by pageRank desc limit 0,$limit";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux["name"] = $res["pageName"];
      $aux["hits"] = $res["pageRank"];
      $aux["href"] = 'tiki-index.php?page='.$res["pageName"];
      $ret[] = $aux;
    }
    $retval["data"]=$ret;
    $retval["title"]=tra("Most relevant pages");
    $retval["y"]=tra("Relevance");
    return $retval;
  }

  function wiki_ranking_last_pages($limit)
  {
    $query = "select pageName,lastModif,hits from tiki_pages order by lastModif desc limit 0,$limit";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux["name"] = $res["pageName"];
      $aux["hits"] = $this->get_long_datetime($res["lastModif"]);
      $aux["href"] = 'tiki-index.php?page='.$res["pageName"];
      $ret[] = $aux;
    }
    $ret["data"]=$ret;
    $ret["title"]=tra("Wiki last pages");
    $ret["y"]=tra("Modified");
    return $ret;
  }

  function forums_ranking_last_topics($limit)
  {
    $query = "select * from tiki_comments,tiki_forums where object=md5(concat('forum',forumId)) and parentId=0 order by commentDate desc limit 0,$limit";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux["name"] = $res["name"].': '.$res["title"];
      $aux["hits"] = $this->get_long_datetime($res["commentDate"]);
      $aux["href"] = 'tiki-view_forum_thread.php?forumId='.$res["forumId"].'&amp;comments_parentId='.$res["threadId"];
      $ret[] = $aux;
    }
    $ret["data"]=$ret;
    $ret["title"]=tra("Forums last topics");
    $ret["y"]=tra("Topic date");
    return $ret;
  }

  function forums_ranking_most_read_topics($limit)
  {
    $query = "select tc.hits,tc.title,tf.name,tf.forumId,tc.threadId,tc.object from tiki_comments tc,tiki_forums tf where object=md5(concat('forum',forumId)) and parentId=0 order by tc.hits desc limit 0,$limit";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {

      $aux["name"] = $res["name"].': '.$res["title"];
      $aux["hits"] = $res["hits"];
      $aux["href"] = 'tiki-view_forum_thread.php?forumId='.$res["forumId"].'&amp;comments_parentId='.$res["threadId"];
      $ret[] = $aux;
    }
    $ret["data"]=$ret;
    $ret["title"]=tra("Forums most read topics");
    $ret["y"]=tra("Reads");
    return $ret;
  }

  function forums_ranking_top_topics($limit)
  {
    $query = "select tc.average,tc.title,tf.name,tf.forumId,tc.threadId,tc.object from tiki_comments tc,tiki_forums tf where object=md5(concat('forum',forumId)) and parentId=0 order by tc.average desc limit 0,$limit";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {

      $aux["name"] = $res["name"].': '.$res["title"];
      $aux["hits"] = $res["average"];
      $aux["href"] = 'tiki-view_forum_thread.php?forumId='.$res["forumId"].'&amp;comments_parentId='.$res["threadId"];
      $ret[] = $aux;
    }
    $ret["data"]=$ret;
    $ret["title"]=tra("Forums best topics");
    $ret["y"]=tra("Score");
    return $ret;
  }

  function forums_ranking_most_visited_forums($limit)
  {
    $query = "select * from tiki_forums order by hits desc limit 0,$limit";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {

      $aux["name"] = $res["name"];
      $aux["hits"] = $res["hits"];
      $aux["href"] = 'tiki-view_forum.php?forumId='.$res["forumId"];
      $ret[] = $aux;
    }
    $ret["data"]=$ret;
    $ret["title"]=tra("Forums most visited forums");
    $ret["y"]=tra("Visits");
    return $ret;
  }

  function forums_ranking_most_commented_forum($limit)
  {
    $query = "select * from tiki_forums order by comments desc limit 0,$limit";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {

      $aux["name"] = $res["name"];
      $aux["hits"] = $res["comments"];
      $aux["href"] = 'tiki-view_forum.php?forumId='.$res["forumId"];
      $ret[] = $aux;
    }
    $ret["data"]=$ret;
    $ret["title"]=tra("Forums with most posts");
    $ret["y"]=tra("Posts");
    return $ret;
  }

  function gal_ranking_top_galleries($limit)
  {
    $query = "select * from tiki_galleries where visible='y' order by hits desc limit 0,$limit";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux["name"] = $res["name"];
      $aux["hits"] = $res["hits"];
      $aux["href"] = 'tiki-browse_gallery.php?galleryId='.$res["galleryId"];
      $ret[] = $aux;
    }
    $retval["data"]=$ret;
    $retval["title"]=tra("Wiki top galleries");
    $retval["y"]=tra("Visits");
    return $retval;
  }

  function filegal_ranking_top_galleries($limit)
  {
    $query = "select * from tiki_file_galleries where visible='y' order by hits desc limit 0,$limit";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux["name"] = $res["name"];
      $aux["hits"] = $res["hits"];
      $aux["href"] = 'tiki-list_file_gallery.php?galleryId='.$res["galleryId"];
      $ret[] = $aux;
    }
    $retval["data"]=$ret;
    $retval["title"]=tra("Wiki top file galleries");
    $retval["y"]=tra("Visits");
    return $retval;
  }

  function gal_ranking_top_images($limit)
  {
    $query = "select imageId,name,hits from tiki_images order by hits desc limit 0,$limit";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux["name"] = $res["name"];
      $aux["hits"] = $res["hits"];
      $aux["href"] = 'tiki-browse_image.php?imageId='.$res["imageId"];
      $ret[] = $aux;
    }
    $retval["data"]=$ret;
    $retval["title"]=tra("Wiki top images");
    $retval["y"]=tra("Hits");
    return $retval;
  }

  function filegal_ranking_top_files($limit)
  {
    $query = "select fileId,filename,downloads from tiki_files order by downloads desc limit 0,$limit";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux["name"] = $res["filename"];
      $aux["hits"] = $res["downloads"];
      $aux["href"] = 'tiki-download_file.php?fileId='.$res["fileId"];
      $ret[] = $aux;
    }
    $retval["data"]=$ret;
    $retval["title"]=tra("Wiki top files");
    $retval["y"]=tra("Downloads");
    return $retval;
  }

  function gal_ranking_last_images($limit)
  {
    $query = "select imageId,name,created from tiki_images order by created desc limit 0,$limit";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux["name"] = $res["name"];
      $aux["hits"] = $this->get_long_datetime($res["created"]);
      $aux["href"] = 'tiki-browse_image.php?imageId='.$res["imageId"];
      $ret[] = $aux;
    }
    $retval["data"]=$ret;
    $retval["title"]=tra("Wiki last images");
    $retval["y"]=tra("Upload date");
    return $retval;
  }

  function filegal_ranking_last_files($limit)
  {
    $query = "select fileId,filename,created from tiki_files order by created desc limit 0,$limit";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux["name"] = $res["filename"];
      $aux["hits"] = $this->get_long_datetime($res["created"]);
      $aux["href"] = 'tiki-download_file.php?fileId='.$res["fileId"];
      $ret[] = $aux;
    }
    $retval["data"]=$ret;
    $retval["title"]=tra("Wiki last files");
    $retval["y"]=tra("Upload date");
    return $retval;
  }

  function cms_ranking_top_articles($limit)
  {
    $query = "select * from tiki_articles order by reads desc limit 0,$limit";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux["name"] = $res["title"];
      $aux["hits"] = $res["reads"];
      $aux["href"] = 'tiki-read_article.php?articleId='.$res["articleId"];
      $ret[] = $aux;
    }
    $retval["data"]=$ret;
    $retval["title"]=tra("Wiki top articles");
    $retval["y"]=tra("Reads");
    return $retval;
  }

  function blog_ranking_top_blogs($limit)
  {
    $query = "select * from tiki_blogs order by hits desc limit 0,$limit";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux["name"] = $res["title"];
      $aux["hits"] = $res["hits"];
      $aux["href"] = 'tiki-view_blog.php?blogId='.$res["blogId"];
      $ret[] = $aux;
    }
    $retval["data"]=$ret;
    $retval["title"]=tra("Most visited blogs");
    $retval["y"]=tra("Visits");
    return $retval;
  }

  function blog_ranking_top_active_blogs($limit)
  {
    $query = "select * from tiki_blogs order by activity desc limit 0,$limit";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux["name"] = $res["title"];
      $aux["hits"] = $res["activity"];
      $aux["href"] = 'tiki-view_blog.php?blogId='.$res["blogId"];
      $ret[] = $aux;
    }
    $retval["data"]=$ret;
    $retval["title"]=tra("Most active blogs");
    $retval["y"]=tra("Activity");
    return $retval;
  }

  function blog_ranking_last_posts($limit)
  {
    $query = "select * from tiki_blog_posts order by created desc limit 0,$limit";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $q = "select title from tiki_blogs where blogId=".$res["blogId"];
      $name = $this->getOne($q);
      $aux["name"] = $name;
      $aux["hits"] = $this->get_long_datetime($res["created"]);
      $aux["href"] = 'tiki-view_blog.php?blogId='.$res["blogId"];
      $ret[] = $aux;
    }
    $retval["data"]=$ret;
    $retval["title"]=tra("Blogs last posts");
    $retval["y"]=tra("Post date");
    return $retval;
  }

  function wiki_ranking_top_authors($limit)
  {
    $query = "select distinct user from tiki_pages";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res["user"];
    }
    $retu = Array();
    foreach($ret as $author) {
      $query = "select count(*) from tiki_pages where user='$author'";
      $cant = $this->getOne($query);
      $aux["name"] = $author;
      $aux["hits"] = $cant;
      $aux["href"] = '';
      $retu[] = $aux;
    }
    $retval["data"]=$retu;
    $retval["title"]=tra("Wiki top authors");
    $retval["y"]=tra("Pages");
    return $retval;
  }

  function cms_ranking_top_authors($limit)
  {
    $query = "select distinct author from tiki_articles";
    $result=$this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res["author"];
    }
    $retu = Array();
    foreach($ret as $author) {
      $query = "select count(*) from tiki_articles where author='$author'";
      $cant = $this->getOne($query);
      $aux["name"] = $author;
      $aux["hits"] = $cant;
      $aux["href"] = '';
      $retu[] = $aux;
    }
    $retval["data"]=$retu;
    $retval["title"]=tra("Top article authors");
    $retval["y"]=tra("Articles");
    return $retval;
  }

  // Sets the admin password to $pass

  // Dumps the database to dump/new.tar
  function dump()
  {
    unlink("dump/new.tar");
    $tar = new tar();
    $tar->addFile("styles/main.css");
    // Foreach page
    $query = "select * from tiki_pages";
    $result = $this->query($query);
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $pageName = $res["pageName"].'.html';
      $dat = $this->parse_data($res["data"]);
      // Now change index.php?page=foo to foo.html
      // and index.php to HomePage.html
      $dat = preg_replace("/tiki-index.php\?page=([^\'\" ]+)/","$1.html",$dat);
      $dat = preg_replace("/tiki-editpage.php\?page=([^\'\" ]+)/","",$dat);
      //preg_match_all("/tiki-index.php\?page=([^ ]+)/",$dat,$cosas);
      //print_r($cosas);
      $data = "<html><head><title>".$res["pageName"]."</title><link rel='StyleSheet' href='styles/main.css' type='text/css'></head><body><a class='wiki' href='HomePage.html'>home</a><br/><h1>".$res["pageName"]."</h1><div class='wikitext'>".$dat.'</div></body></html>';
      $tar->addData($pageName,$data,$res["lastModif"]);
    }
    $tar->toTar("dump/new.tar",FALSE);
    unset($tar);
    $action = "dump created";
    $t = date("U");
    $query = "insert into tiki_actionlog(action,pageName,lastModif,user,ip,comment) values('$action','HomePage',$t,'admin','".$_SERVER["REMOTE_ADDR"]."','')";
    $result=$this->query($query);
  }

  // Removes a specific version of a page
  function remove_version($page,$version,$comment='')
  {
    $page = addslashes($page);
    $query="delete from tiki_history where pageName='$page' and version='$version'";
    $result=$this->query($query);
    $action="Removed version $version";
    $t = date("U");
    $query = "insert into tiki_actionlog(action,pageName,lastModif,user,ip,comment) values('$action','$page',$t,'admin','".$_SERVER["REMOTE_ADDR"]."','$comment')";
    $result = $this->query($query);
    return true;
  }

  // Removes all the versions of a page and the page itself
  function remove_all_versions($page,$comment='')
  {
    $page = addslashes($page);
    $this->invalidate_cache($page);
    $query = "delete from tiki_pages where pageName = '$page'";
    $result = $this->query($query);
    $query = "delete from tiki_history where pageName = '$page'";
    $result = $this->query($query);
    $query = "delete from tiki_links where fromPage = '$page'";
    $result = $this->query($query);
    $action="Removed";
    $t = date("U");
    $query = "insert into tiki_actionlog(action,pageName,lastModif,user,ip,comment) values('$action','$page',$t,'admin','".$_SERVER["REMOTE_ADDR"]."','$comment')";
    $result = $this->query($query);
    $this->remove_object('wiki page',$page);
    return true;
  }

  function use_version($page,$version,$comment='')
  {
    $page = addslashes($page);
    $this->invalidate_cache($page);
    $query = "select * from tiki_history where pageName='$page' and version='$version'";
    $result=$this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    $query = "update tiki_pages set data='".addslashes($res["data"])."',lastModif=".$res["lastModif"].",user='".$res["user"]."',comment='".$res["comment"]."',version=version+1,ip='".$res["ip"]."' where pageName='$page'";
    $result=$this->query($query);
    $query = "delete from tiki_links where fromPage = '$page'";
    $result=$this->query($query);
    $this->clear_links($page);
    $pages = $this->get_pages($res["data"]);
    foreach($pages as $a_page) {
      $this->replace_link($page,$a_page);
    }
    //$query="delete from tiki_history where pageName='$page' and version='$version'";
    //$result=$this->query($query);
    //
    $action="Changed actual version to $version";
    $t = date("U");
    $query = "insert into tiki_actionlog(action,pageName,lastModif,user,ip,comment) values('$action','$page',$t,'admin','".$_SERVER["REMOTE_ADDR"]."','$comment')";
    $result = $this->query($query);
    return true;
  }

  // Removes last version of the page (from pages) if theres some
  // version in the tiki_history then the last version becomes the actual version
  function remove_last_version($page,$comment='')
  {
    $page = addslashes($page);
    $this->invalidate_cache($page);
    $query = "select * from tiki_history where pageName='$page' order by lastModif desc";
    $result = $this->query($query);
    if($result->numRows()) {
      // We have a version
      $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
      $this->use_version($res["pageName"],$res["version"]);
      $this->remove_version($res["pageName"],$res["version"]);
    } else {
      $this->remove_all_versions($page);
    }
    $action="Removed last version";
    $t = date("U");
    $query = "insert into tiki_actionlog(action,pageName,lastModif,user,ip,comment) values('$action','$page',$t,'admin','".$_SERVER["REMOTE_ADDR"]."','$comment')";
    $result = $this->query($query);
  }

  function get_user_versions($user)
  {
    $query = "select pageName,version, lastModif, user, ip, comment from tiki_history where user='$user' order by lastModif desc";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux = Array();
      $aux["pageName"] = $res["pageName"];
      $aux["version"] = $res["version"];
      $aux["lastModif"] = $res["lastModif"];
      $aux["ip"] = $res["ip"];
      $aux["comment"] = $res["comment"];
      $ret[]=$aux;
    }
    return $ret;
  }

  function remove_user($user)
  {
    $query = "delete from users_users where login = '$user'";
    $result =  $this->query($query);
    return true;
  }

  function user_exists($user)
  {
    $query = "select login from users_users where login='$user'";
    $result = $this->query($query);
    if($result->numRows()) return true;
    return false;
  }

  function add_user($user, $pass, $email)
  {
    $user = addslashes($user);
    $pass = addslashes($pass);
    $email = addslashes($email);
    if(user_exists($user)) return false;
    $query = "insert into users_users(login,password,email) values('$user','$pass','$email')";
    $result = $this->query($query);
    $action = "user $user added";
    $t = date("U");
    $query = "insert into tiki_actionlog(action,pageName,lastModif,user,ip,comment) values('$action','HomePage',$t,'admin','".$_SERVER["REMOTE_ADDR"]."','')";
    $result=$this->query($query);
    return true;
  }

  function get_user_password($user)
  {
    return $this->getOne("select password from users_users where login='$user'");
  }

  function get_user_email($user)
  {
    return $this->getOne("select email from users_users where login='$user'");
  }

  function get_user_info($user)
  {
    $query = "select login, email, lastLogin from tiki_users where user='$user'";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    $aux = Array();
    $aux["user"] = $res["user"];
    $user = $aux["user"];
    $aux["email"] = $res["email"];
    $aux["lastLogin"] = $res["lastLogin"];
    // Obtain lastChanged
    $query2 = "select count(*) from tiki_pages where user='$user'";
    $result2 = $this->query($query2);
    $res2 = $result2->fetchRow();
    $aux["versions"] = $res2[0];
    // Obtain versions
    $query3 = "select count(*) from tiki_history where user='$user'";
    $result3 = $this->query($query3);
    $res3 = $result3->fetchRow();
    $aux["lastChanged"] = $res3[0];
    $ret[] = $aux;
    return $aux;
  }

  function list_galleries($offset = 0, $maxRecords = -1, $sort_mode = 'name_desc', $user, $find)
  {
    // If $user is admin then get ALL galleries, if not only user galleries are shown
    global $tiki_p_admin_galleries;
    $sort_mode = str_replace("_"," ",$sort_mode);
    $old_sort_mode ='';
    if(in_array($sort_mode,Array('images desc','images asc'))) {
      $old_offset = $offset;
      $old_maxRecords = $maxRecords;
      $old_sort_mode = $sort_mode;
      $sort_mode ='user desc';
      $offset = 0;
      $maxRecords = -1;
    }

    // If the user is not admin then select it's own galleries or public galleries
     if (($tiki_p_admin_galleries == 'y') or ($user == 'admin')) {
       $whuser = "";
    } else {
      $whuser = "where user='$user' or public='y'";
     }

    if($find) {
      if(empty($whuser)) {
        $whuser = "where name like '%".$find."%' or description like '%".$find.".%'";
      } else {
        $whuser .= " and name like '%".$find."%' or description like '%".$find.".%'";
      }
    }
    // If sort mode is versions then offset is 0, maxRecords is -1 (again) and sort_mode is nil
    // If sort mode is links then offset is 0, maxRecords is -1 (again) and sort_mode is nil
    // If sort mode is backlinks then offset is 0, maxRecords is -1 (again) and sort_mode is nil
    $query = "select * from tiki_galleries $whuser order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_galleries $whuser";
    $result = $this->query($query);
    $result_cant = $this->query($query_cant);
    $res2 = $result_cant->fetchRow();
    $cant = $res2[0];
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux = Array();
      $aux["name"] = $res["name"];
      $gid = $res["galleryId"];
      $aux["visible"]=$res["visible"];
      $aux["id"] = $gid;
      $aux["galleryId"] = $res["galleryId"];
      $aux["description"] = $res["description"];
      $aux["created"] = $res["created"];
      $aux["lastModif"] = $res["lastModif"];
      $aux["user"] = $res["user"];
      $aux["hits"] = $res["hits"];
      $aux["public"] = $res["public"];
      $aux["theme"] = $res["theme"];
      $aux["images"] = $this->getOne("select count(*) from tiki_images where galleryId='$gid'");
      $ret[] = $aux;
    }
    if($old_sort_mode == 'images asc') {
      usort($ret,'compare_images');
    }
    if($old_sort_mode == 'images desc') {
      usort($ret,'r_compare_images');
    }

    if(in_array($old_sort_mode,Array('images desc','images asc'))) {
      $ret = array_slice($ret, $old_offset, $old_maxRecords);
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_visible_galleries($offset = 0, $maxRecords = -1, $sort_mode = 'name_desc', $user, $find)
  {
    // If $user is admin then get ALL galleries, if not only user galleries are shown
    $sort_mode = str_replace("_"," ",$sort_mode);
    $old_sort_mode ='';
    if(in_array($sort_mode,Array('images desc','images asc'))) {
      $old_offset = $offset;
      $old_maxRecords = $maxRecords;
      $old_sort_mode = $sort_mode;
      $sort_mode ='user desc';
      $offset = 0;
      $maxRecords = -1;
    }

    // If the user is not admin then select it's own galleries or public galleries
    if($user != 'admin') {
      $whuser = " and (user='$user' or public='y') ";
    } else {
      $whuser = "";
    }

    if($find) {
      if(empty($whuser)) {
        $whuser = " and (name like '%".$find."%' or description like '%".$find.".%')";
      } else {
        $whuser .= " and (name like '%".$find."%' or description like '%".$find.".%')";
      }
    }
    // If sort mode is versions then offset is 0, maxRecords is -1 (again) and sort_mode is nil
    // If sort mode is links then offset is 0, maxRecords is -1 (again) and sort_mode is nil
    // If sort mode is backlinks then offset is 0, maxRecords is -1 (again) and sort_mode is nil
    $query = "select * from tiki_galleries where visible='y' $whuser order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_galleries where visible='y' $whuser";
    $result = $this->query($query);
    $result_cant = $this->query($query_cant);
    $res2 = $result_cant->fetchRow();
    $cant = $res2[0];
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux = Array();
      $aux["name"] = $res["name"];
      $gid = $res["galleryId"];
      $aux["visible"]=$res["visible"];
      $aux["id"] = $gid;
      $aux["galleryId"] = $res["galleryId"];
      $aux["description"] = $res["description"];
      $aux["created"] = $res["created"];
      $aux["lastModif"] = $res["lastModif"];
      $aux["user"] = $res["user"];
      $aux["hits"] = $res["hits"];
      $aux["public"] = $res["public"];
      $aux["theme"] = $res["theme"];
      $aux["images"] = $this->getOne("select count(*) from tiki_images where galleryId='$gid'");
      $ret[] = $aux;
    }
    if($old_sort_mode == 'images asc') {
      usort($ret,'compare_images');
    }
    if($old_sort_mode == 'images desc') {
      usort($ret,'r_compare_images');
    }

    if(in_array($old_sort_mode,Array('images desc','images asc'))) {
      $ret = array_slice($ret, $old_offset, $old_maxRecords);
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function list_pages($offset = 0, $maxRecords = -1, $sort_mode = 'pageName_desc',$find='')
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    if($sort_mode == 'size desc') {
      $sort_mode = ' length(data) desc';
    }
    if($sort_mode == 'size asc') {
      $sort_mode = ' length(data) asc';
    }
    $old_sort_mode ='';
    if(in_array($sort_mode,Array('versions desc','versions asc','links asc','links desc','backlinks asc','backlinks desc'))) {
      $old_offset = $offset;
      $old_maxRecords = $maxRecords;
      $old_sort_mode = $sort_mode;
      $sort_mode ='user desc';
      $offset = 0;
      $maxRecords = -1;
    }

    if($find) {
      $mid=" where pageName like '%".$find."%' ";
    } else {
      $mid="";
    }

    // If sort mode is versions then offset is 0, maxRecords is -1 (again) and sort_mode is nil
    // If sort mode is links then offset is 0, maxRecords is -1 (again) and sort_mode is nil
    // If sort mode is backlinks then offset is 0, maxRecords is -1 (again) and sort_mode is nil
    $query = "select pageName, hits, length(data) as len ,lastModif, user, ip, comment, version, flag from tiki_pages $mid order by $sort_mode limit $offset,$maxRecords";
    $query_cant = "select count(*) from tiki_pages $mid";
    $result = $this->query($query);
    $result_cant = $this->query($query_cant);
    $res2 = $result_cant->fetchRow();
    $cant = $res2[0];
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux = Array();
      $aux["pageName"] = $res["pageName"];
      $page = $aux["pageName"];
      $aux["hits"] = $res["hits"];
      $aux["lastModif"] = $res["lastModif"];
      $aux["user"] = $res["user"];
      $aux["ip"] = $res["ip"];
      $aux["len"] = $res["len"];
      $aux["comment"] = $res["comment"];
      $aux["version"] = $res["version"];
      $aux["flag"] = $res["flag"] == 'L' ? tra('locked') : tra('unlocked');
      $aux["versions"] = $this->getOne("select count(*) from tiki_history where pageName='$page'");
      $aux["links"] = $this->getOne("select count(*) from tiki_links where fromPage='$page'");
      $aux["backlinks"] = $this->getOne("select count(*) from tiki_links where toPage='$page'");
      $ret[] = $aux;
    }
    // If sortmode is versions, links or backlinks sort using the ad-hoc function and reduce using old_offse and old_maxRecords
    if($old_sort_mode == 'versions asc') {
      usort($ret,'compare_versions');
    }
    if($old_sort_mode == 'versions desc') {
      usort($ret,'r_compare_versions');
    }
    if($old_sort_mode == 'links desc') {
      usort($ret,'compare_links');
    }
    if($old_sort_mode == 'links asc') {
      usort($ret,'r_compare_links');
    }
    if($old_sort_mode == 'backlinks desc') {
      usort($ret,'compare_backlinks');
    }
    if($old_sort_mode == 'backlinks asc') {
      usort($ret,'r_compare_backlinks');
    }
    if(in_array($old_sort_mode,Array('versions desc','versions asc','links asc','links desc','backlinks asc','backlinks desc'))) {
      $ret = array_slice($ret, $old_offset, $old_maxRecords);
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function get_users($offset = 0,$maxRecords = -1,$sort_mode = 'user_desc')
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
    $old_sort_mode ='';
    if(in_array($sort_mode,Array('versions desc','versions asc','changed asc','changed desc'))) {
      $old_offset = $offset;
      $old_maxRecords = $maxRecords;
      $old_sort_mode = $sort_mode;
      $sort_mode ='user desc';
      $offset = 0;
      $maxRecords = -1;
    }
    // Return an array of users indicating name, email, last changed pages, versions, lastLogin
    $query = "select user, email, lastLogin from tiki_users order by $sort_mode limit $offset,$maxRecords";
    $cant = $this->getOne("select count(*) from tiki_users");
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux = Array();
      $aux["user"] = $res["user"];
      $user = $aux["user"];
      $aux["email"] = $res["email"];
      $aux["lastLogin"] = $res["lastLogin"];
      // Obtain lastChanged
      $aux["versions"] = $this->getOne("select count(*) from tiki_pages where user='$user'");
      // Obtain versions
      $aux["lastChanged"] = $this->getOne("select count(*) from tiki_history where user='$user'");
      $ret[] = $aux;
    }
    if($old_sort_mode == 'changed asc') {
      usort($ret,'compare_changed');
    }
    if($old_sort_mode == 'changed desc') {
      usort($ret,'r_compare_changed');
    }
    if($old_sort_mode == 'versions asc') {
      usort($ret,'compare_versions');
    }
    if($old_sort_mode == 'versions desc') {
      usort($ret,'r_compare_versions');
    }
    if(in_array($old_sort_mode,Array('versions desc','versions asc','changed asc','changed desc'))) {
      $ret = array_slice($ret, $old_offset, $old_maxRecords);
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  function get_all_preferences()
  {
    $query = "select name,value from tiki_preferences";
    $result = $this->query($query);
    $ret=Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[$res["name"]] = $res["value"];
    }
    return $ret;
  }

  function get_preference($name, $default='')
  {
    static $preferences;

    if (!isset($preferences[$name])) {
      $query = "select value from tiki_preferences where name='$name'";
      $result = $this->query($query);
      if($result->numRows()) {
        $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
        $preferences[$name] = $res["value"];
      } else {
        $preferences[$name] = $default;
      }
    }

    return $preferences[$name];
  }

  function set_preference($name, $value)
  {
    @unlink('templates_c/preferences.php');
    $name = addslashes($name);
    $value = addslashes($value);
    $query = "replace into tiki_preferences(name,value) values('$name','$value')";
    $result = $this->query($query);
    return true;
  }

  function get_user_preference($user, $name, $default='')
  {
    static $preferences;

    if (!isset($preferences[$user][$name])) {
      $query = "select value from tiki_user_preferences where prefName='$name' and user='$user'";
      $result = $this->query($query);
      if($result->numRows()) {
        $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
        $preferences[$user][$name] = $res["value"];
      } else {
        $preferences[$user][$name] = $default;
      }
    }

    return $preferences[$user][$name];
  }

  function set_user_preference($user, $name, $value)
  {
    $name = addslashes($name);
    $value = addslashes($value);
    $query = "replace into tiki_user_preferences(user,prefName,value) values('$user','$name','$value')";
    $result = $this->query($query);
    return true;
  }

  function validate_user($user,$pass)
  {
    $query = "select user from tiki_users where user='$user' and password='$pass'";
    $result = $this->query($query);
    if($result->numRows()) {
      $t = date("U");
      $query = "update tiki_users set lastLogin='$t' where user='$user'";
      $result = $this->query($query);
      return true;
    }
    return false;
  }

  // Like pages are pages that share a word in common with the current page
  function get_like_pages($page)
  {
    preg_match_all("/([A-Z])([a-z]+)/",$page,$words);
    // Add support to ((x)) in either strict or full modes
    preg_match_all("/(([A-Za-z]|[\x80-\xFF])+)/",$page,$words2);
    $words=array_unique(array_merge($words[0], $words2[0]));
    $exps = Array();
    foreach($words as $word) {
      $exps[] = "pageName like '%$word%'";
    }
    $exp = implode(" or ",$exps);
    $query = "select pageName from tiki_pages where $exp";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[] = $res["pageName"];
    }
    return $ret;
  }

  // Returns information about a specific version of a page
  function get_version($page, $version)
  {
    $page = addslashes($page);
    $query = "select * from tiki_history where pageName='$page' and version=$version";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    return $res;
  }

  // Returns all the versions for this page
  // without the data itself
  function get_page_history($page)
  {
    $page = addslashes($page);
    $query = "select pageName, description, version, lastModif, user, ip, data, comment from tiki_history where pageName='$page' order by version desc";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux = Array();
      $aux["version"] = $res["version"];
      $aux["lastModif"] = $res["lastModif"];
      $aux["user"] = $res["user"];
      $aux["ip"] = $res["ip"];
      $aux["data"] = $res["data"];
      $aux["pageName"] = $res["pageName"];
      $aux["description"] = $res["description"];
      $aux["comment"] = $res["comment"];
      //$aux["percent"] = levenshtein($res["data"],$actual);
      $ret[]=$aux;
    }
    return $ret;
  }

  function is_locked($page)
  {
    $page = addslashes($page);
    $query = "select flag from tiki_pages where pageName='$page'";
    $result = $this->query($query);
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    if($res["flag"]=='L') return true;
    return false;
  }

  function lock_page($page)
  {
    $page = addslashes($page);
    $query = "update tiki_pages set flag='L' where pageName='$page'";
    $result = $this->query($query);
    return true;
  }

  function unlock_page($page)
  {
    $page = addslashes($page);
    $query = "update tiki_pages set flag='' where pageName='$page'";
    $result = $this->query($query);
    return true;
  }

  // Returns backlinks for a given page
  function get_backlinks($page)
  {
    $query = "select fromPage from tiki_links where toPage = '$page'";
    $result = $this->query($query);
    $ret = Array();
    while($res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $aux["fromPage"] = $res["fromPage"];
      $ret[] = $aux;
    }
    return $ret;
  }

  // tikilib.php a Library to access the Tiki's Data Model
  // This implements all the functions needed to use Tiki
  function page_exists($pageName)
  {
    $pageName = addslashes($pageName);
    $query = "select pageName from tiki_pages where pageName = '$pageName'";
    $result = $this->query($query);
    return $result->numRows();
  }

  function page_exists_desc($pageName)
  {
    $pageName = addslashes($pageName);
    $query = "select description from tiki_pages where pageName = '$pageName'";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    if(!$res["description"]) $res["description"]=tra('no description');
    return $res["description"];
  }

  function version_exists($pageName, $version)
  {
    $pageName = addslashes($pageName);
    $query = "select pageName from tiki_history where pageName = '$pageName' and version='$version'";
    $result = $this->query($query);
    return $result->numRows();
  }

  function add_hit($pageName) {
    $pageName = addslashes($pageName);
    $query = "update tiki_pages set hits=hits+1 where pageName = '$pageName'";
    $result = $this->query($query);
    return true;
  }

  function create_page($name, $hits, $data, $lastModif, $comment, $user='system', $ip='0.0.0.0',$description='')
  {
    // Collect pages before modifying data
    $pages = $this->get_pages($data);
    $name = addslashes($name);
    $description = addslashes($description);
    $data = addslashes($data);
    $comment = addslashes($comment);
    if($this->page_exists($name)) return false;
    $query = "insert into tiki_pages(pageName,hits,data,lastModif,comment,version,user,ip,description) values('$name',$hits,'$data',$lastModif,'$comment',1,'$user','$ip','$description')";
    $result = $this->query($query);
    $this->clear_links($name);
    // Pages are collected before adding slashes
    foreach($pages as $a_page) {
      $this->replace_link($name,$a_page);
    }
    // Update the log
    if($name != 'SandBox') {
      $action = "Created";
      $query = "insert into tiki_actionlog(action,pageName,lastModif,user,ip,comment) values('$action','$name',$lastModif,'$user','$ip','$comment')";
      $result = $this->query($query);
    }
    return true;
  }

  function get_user_pages($user,$max)
  {
    $query = "select pageName from tiki_pages where user='$user' limit 0,$max";
    $result = $this->query($query);
    $ret=Array();
    while( $res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[]=$res;
    }
    return $ret;
  }

  function get_user_galleries($user,$max)
  {
    $query = "select name,galleryId from tiki_galleries where user='$user' limit 0,$max";
    $result = $this->query($query);
    $ret=Array();
    while( $res = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $ret[]=$res;
    }
    return $ret;
  }

  function get_page_info($pageName)
  {
    $pageName = addslashes($pageName);
    $query = "select * from tiki_pages where pageName='$pageName'";
    $result = $this->query($query);
    if(!$result->numRows()) return false;
    $ret = $res = $result->fetchRow(DB_FETCHMODE_ASSOC);
    $ret["pageName"] = $pageName;
    return $ret;
  }

  function how_many_at_start($str,$car)
  {
    $cant =0;
    $i=0;
    while(($i<strlen($str)) && (isset($str{$i})) && ($str{$i}==$car)){
      $i++;
      $cant++;
    }
    return $cant;
  }

  function parse_data_raw($data)
  {
    $data = $this->parse_data($data);
    $data=str_replace("tiki-index","tiki-index_raw",$data);
    return $data;
  }

function parse_data($data)
  {
    global $page_regex;
    global $slidemode;
    global $feature_hotwords;
    global $cachepages;
    global $ownurl_father;
    global $feature_drawings;
    global $tiki_p_admin_drawings;
    global $tiki_p_edit_drawings;
    global $feature_hotwords_nw;
    global $feature_wiki_pictures;
    global $tiki_p_upload_picture;
    global $page; 
    global $dbTiki;
    global $structlib;

    if($feature_hotwords_nw == 'y') {
      $hotw_nw = "target='_blank'";
    } else {
      $hotw_nw = '';
    }

    //Extract preparse sections before anything
    $preparsed=Array();
    preg_match_all("/\~pp\~((.|\n)*?)\~\/pp\~/",$data,$preparse);
    foreach(array_unique($preparse[1]) as $pp) {
      $key=md5($this->genPass());
      $aux["key"]=$key;
      $aux["data"]=$pp;
      $preparsed[]=$aux;
      $data=str_replace("~pp~$pp~/pp~",$key,$data);
    }

    //Extract noparse sections almost before anything
    $noparsed=Array();
    preg_match_all("/\~np\~((.|\n)*?)\~\/np\~/",$data,$noparse);
    foreach(array_unique($noparse[1]) as $np) {
      $key=md5($this->genPass());
      $aux["key"]=$key;
      $aux["data"]=$np;
      $noparsed[]=$aux;
      $data=str_replace("~np~$np~/np~",$key,$data);
    }

    // Now replace a TOC
    preg_match_all("/\{toc\}/",$data,$tocs);
    if(count($tocs[0])>0) {
      include_once("lib/structures/structlib.php");
      if($structlib->page_is_in_structure($page)) {
        $html='';
        if($slidemode) {
        $toc=$structlib->get_subtree_toc_slide($page,$page,$html);
        } else {
        $toc=$structlib->get_subtree_toc($page,$page,$html);
        } 
        $data=str_replace('{toc}',$html,$data);
      }
    }
    //$page='';

    // Now search for plugins
    $smc = new Smarty_Compiler();
    preg_match_all("/\{([A-Z]+)\(([^\)]*)\)\}/",$data,$plugins);
    
    for($i=0;$i<count($plugins[0]);$i++) {
      $plugin_start = $plugins[0][$i];
      $plugin_start_base = '{'.$plugins[1][$i].'(';
      $plugin_end = '{'.$plugins[1][$i].'}';
      // Find first occurrence of start tag
      // Build start tag using 


      $pos = strpos($data,$plugin_start);
      // And now find the LAST occurrence of the end tag
      $pos_end = strpos($data,$plugin_end,$pos);
      
      
      
      if($pos_end>$pos) {
        $plugin_data_len=$pos_end-$pos-strlen($plugins[0][$i]);
        $plugin_data = substr($data,$pos+strlen($plugin_start),$plugin_data_len);
        $php_name = 'lib/wiki-plugins/wikiplugin_'.strtolower($plugins[1][$i]).'.php';
        $func_name = 'wikiplugin_'.strtolower($plugins[1][$i]);
        $params = split(',',trim($plugins[2][$i]));
        $arguments=Array();
        foreach($params as $param) {
          $parts=explode('=>',$param);
          if(isset($parts[0])&&isset($parts[1])) {
            $name=trim($parts[0]);
            $arguments[$name]=trim($parts[1]);
          }
        }
        if(file_exists($php_name)) {
          include_once($php_name);
          $ret = $func_name($plugin_data,$arguments);
          $data = substr_replace($data,$ret,$pos,$pos_end - $pos + strlen($plugin_end));
          // Re-entrant plugins
          $data = $this->parse_data($data);
          
        }
      }
    }
    unset($smc);


    // Now search for images uploaded by users
    if($feature_wiki_pictures=='y') {
      preg_match_all("/\{picture file=([^\}]+)\}/",$data,$pics);
      for($i=0;$i<count($pics[0]);$i++) {
        // Check if the image exists
        $name=$pics[1][$i];
        if(file_exists($name)) {
          // Replace by the img tag to show the image
         $repl = "<img src='$name?nocache=1' alt='$name' />";
        } else {
          $repl=tra('picture not found');
        }
        // Replace by $repl
        $data = str_replace($pics[0][$i],$repl,$data);
      }
    }


    $data = stripslashes($data);
    if($feature_hotwords == 'y') {
      $words = $this->get_hotwords();
      foreach($words as $word=>$url) {
        //print("Replace $word by $url<br/>");
        $data  = preg_replace("/ $word /i"," <a class=\"wiki\" href=\"$url\" $hotw_nw>$word</a> ",$data);
      }
    }

    //$data = strip_tags($data);
    // BiDi markers
    $bidiCount = 0;
    $bidiCount = preg_match_all("/(\{l2r\})/",$data,$pages);
    $bidiCount += preg_match_all("/(\{r2l\})/",$data,$pages);

    $data = preg_replace("/\{l2r\}/", "<div dir='ltr'>", $data);
    $data = preg_replace("/\{r2l\}/", "<div dir='rtl'>", $data);
    $data = preg_replace("/\{lm\}/", "&lrm;", $data);
    $data = preg_replace("/\{rm\}/", "&rlm;", $data);
    // smileys
    $data = $this->parse_smileys($data);


    // Replace rss modules
    if(preg_match_all("/\{rss +id=([0-9]+) *(max=([0-9]+))? *\}/",$data,$rsss)) {
      for($i=0;$i<count($rsss[0]);$i++) {
        $id = $rsss[1][$i];
        $max = $rsss[3][$i];
        if(empty($max)) $max=99;
        $rssdata = $this->get_rss_module_content($id);
        $items = $this->parse_rss_data($rssdata);
        $repl='';
        for($j=0;$j<count($items) && $j<$max;$j++) {
         $repl.='<li><a target="_blank" href="'.$items[$j]["link"].'" class="wiki">'.$items[$j]["title"].'</a></li>';
        }
        $repl='<ul>'.$repl.'</ul>';
        $data = str_replace($rsss[0][$i],$repl,$data);
      }
    }

    // Replace links to slideshows

    if($feature_drawings == 'y') {
    // Replace drawings
    // Replace rss modules
    $pars=parse_url($_SERVER["REQUEST_URI"]);
    $pars_parts=split('/',$pars["path"]);
    $pars=Array();
    for($i=0;$i<count($pars_parts)-1;$i++) {
      $pars[]=$pars_parts[$i];
    }
    $pars=join('/',$pars);
    if(preg_match_all("/\{draw +name=([A-Za-z_\-0-9]+) *\}/",$data,$draws)) {
      for($i=0;$i<count($draws[0]);$i++) {
        $id = $draws[1][$i];
        $repl='';
        $name=$id.'.gif';
        if(file_exists("img/wiki/$name")) {
          if($tiki_p_edit_drawings == 'y' || $tiki_p_admin_drawings == 'y') {
            $repl="<a href='#' onClick=\"javascript:window.open('tiki-editdrawing.php?path=$pars&amp;drawing={$id}','','menubar=no,width=252,height=25');\"><img border='0' src='img/wiki/$name' alt='click to edit' /></a>";
          } else {
            $repl="<img border='0' src='img/wiki/$name' alt='a drawing' />";
          }
        } else {
          if($tiki_p_edit_drawings == 'y' || $tiki_p_admin_drawings == 'y') {
            $repl="<a class='wiki' href='#' onClick=\"javascript:window.open('tiki-editdrawing.php?path=$pars&amp;drawing={$id}','','menubar=no,width=252,height=25');\">click here to create draw $id</a>";
          } else {
            $repl=tra('drawing not found');
          }
        }
        $data = str_replace($draws[0][$i],$repl,$data);
      }
    }
    }

    // Replace cookies
    if(preg_match_all("/\{cookie\}/",$data,$rsss)) {
      for($i=0;$i<count($rsss[0]);$i++) {
        $cookie = $this->pick_cookie();
        $data = str_replace($rsss[0][$i],$cookie,$data);
      }
    }


    // Replace dynamic content occurrences
    if(preg_match_all("/\{content +id=([0-9]+)\}/",$data,$dcs)) {
      for($i=0;$i<count($dcs[0]);$i++) {
        $repl = $this->get_actual_content($dcs[1][$i]);
        $data = str_replace($dcs[0][$i],$repl,$data);
      }
    }
    // Replace Dynamic content with random selection
    if(preg_match_all("/\{rcontent +id=([0-9]+)\}/",$data,$dcs)) {
      for($i=0;$i<count($dcs[0]);$i++) {
        $repl = $this->get_random_content($dcs[1][$i]);
        $data = str_replace($dcs[0][$i],$repl,$data);
      }
    }



    // Replace boxes
    $data = preg_replace("/\^([^\^]+)\^/","<div class='simplebox' align='center'>$1</div>",$data);
    // Replace colors ~~color:text~~
    $data = preg_replace("/\~\~([^\:]+):([^\~]+)\~\~/","<span style='color:$1;'>$2</span>",$data);
    // Underlined text
    $data = preg_replace("/===([^\=]+)===/","<span style='text-decoration:underline;'>$1</span>",$data);
    // Center text
    $data = preg_replace("/::([^\:]+)::/","<div align='center'>$1</div>",$data);
    // Links to internal pages
    // If they are parenthesized then don't treat as links
    // Prevent ))PageName(( from being expanded    \"\'

    //[A-Z][a-z0-9_\-]+[A-Z][a-z0-9_\-]+[A-Za-z0-9\-_]*
    // The first part is now mandatory to prevent [Foo|MyPage] from being converted!
    preg_match_all("/([ \n\t\r\,\;]|^)([A-Z][a-z0-9_\-]+[A-Z][a-z0-9_\-]+[A-Za-z0-9\-_]*)($|[ \n\t\r\,\;\.])/",$data,$pages);
    //print_r($pages);
    foreach(array_unique($pages[2]) as $page_parse) {
      if($desc = $this->page_exists_desc($page_parse)) {
        $repl = "<a title='".$desc."' href='tiki-index.php?page=$page_parse' class='wiki'>$page_parse</a>";
      } else {
        $repl = "$page_parse<a href='tiki-editpage.php?page=$page_parse' class='wiki'>?</a>";
      }
      $data = preg_replace("/([ \n\t\r\,\;]|^)$page_parse($|[ \n\t\r\,\;\.])/","$1"."$repl"."$2",$data);
      //$data = str_replace($page_parse,$repl,$data);
    }

    $data = preg_replace("/([ \n\t\r\,\;]|^)\)\)([^\(]+)\(\(($|[ \n\t\r\,\;\.])/","$1"."$2"."$3",$data);
    // New syntax for wiki pages ((name|desc)) Where desc can be anything
    preg_match_all("/\(\(($page_regex)\|(.+?)\)\)/",$data,$pages);
    for($i=0;$i<count($pages[1]);$i++) {
      if($desc = $this->page_exists_desc($pages[1][$i])) {
      	$uri_ref = "tiki-index.php?page=".urlencode($pages[1][$i]);
        $repl = "<a title='$desc' href='$uri_ref' class='wiki'>".$pages[5][$i]."</a>";
      } else {
      	$uri_ref = "tiki-editpage.php?page=".urlencode($pages[1][$i]);
        $repl = $pages[5][$i]."<a href='$uri_ref' class='wiki'>?</a>";
      }

      $pattern = "/".$pages[0][$i]."/";
      $pattern=str_replace('|','\|',$pattern);
      $pattern=str_replace('(','\(',$pattern);
      $pattern=str_replace(')','\)',$pattern);
      $data = preg_replace($pattern,"$repl",$data);
    }

    // New syntax for wiki pages ((name)) Where name can be anything
    preg_match_all("/\(\(($page_regex)\)\)/",$data,$pages);
    foreach(array_unique($pages[1]) as $page_parse) {
      if($desc = $this->page_exists_desc($page_parse)) {
        $repl = "<a title='$desc' href='tiki-index.php?page=$page_parse' class='wiki'>$page_parse</a>";
      } else {
        $repl = "$page_parse<a href='tiki-editpage.php?page=$page_parse' class='wiki'>?</a>";
      }
      $data = preg_replace("/\(\($page_parse\)\)/","$repl",$data);
      //$data = str_replace($page_parse,$repl,$data);
    }

    // Replace ))Words((
    $data = preg_replace("/\(\(([^\)]+)\)\)/","$1",$data);

    // Images
    preg_match_all("/(\{img [^\}]+})/",$data,$pages);
    foreach(array_unique($pages[1]) as $page_parse) {
      $parts = explode(" ",$page_parse);
      $imgdata = Array();
      $imgdata["src"]='';
      $imgdata["height"]='';
      $imgdata["width"]='';
      $imgdata["link"]='';
      $imgdata["align"]='';
      $imgdata["desc"]='';
      foreach($parts as $part) {
        $part = str_replace('}','',$part);
        $part = str_replace('{','',$part);
        $part = str_replace('\'','',$part);
        $part = str_replace('"','',$part);
        if(strstr($part,'=')) {
            $subs = explode("=",$part,2);
            $imgdata[$subs[0]]=$subs[1];
        }
      }
      //print("todo el tag es: ".$page_parse."<br/>");
      //print_r($imgdata);
      $repl = "<div class=\"innerimg\"><img alt='an image' src='".$imgdata["src"]."' border='0' ";
      if($imgdata["width"]) $repl.=" width='".$imgdata["width"]."'";
      if($imgdata["height"]) $repl.=" height='".$imgdata["height"]."'";
      $repl.= " /></div>";
      if($imgdata["link"]) {
        $repl ="<a href='".$imgdata["link"]."'>".$repl."</a>";
      }
      if($imgdata["desc"]) {
        $repl="<table cellpadding='0' cellspacing='0'><tr><td>".$repl."</td></tr><tr><td class='mini'>".$imgdata["desc"]."</td></tr></table>";
      }
      if($imgdata["align"]) {
        $repl ="<div align='".$imgdata["align"]."'>".$repl."</div>";
      }
      $data = str_replace($page_parse,$repl,$data);
    }

    $target='';
    if($this->get_preference('popupLinks','n')=='y') {
      $target='target="_blank"';
    }

    $links = $this->get_links($data);
    // Note that there're links that are replaced



    foreach($links as $link) {
      if( $this->is_cached($link) && $cachepages == 'y') {
        $cosa="<a class=\"wikicache\" target=\"_blank\" href=\"tiki-view_cache.php?url=$link\">(cache)</a>";
        $link2 = str_replace("/","\/",$link);
        $link2 = str_replace("?","\?",$link2);
        $link2 = str_replace("&","\&",$link2);
        $pattern = "/\[$link2\|([^\]\|]+)\|([^\]]+)\]/";
        $data = preg_replace($pattern,"<a class='wiki' $target href='$link'>$1</a>",$data);
        $pattern = "/\[$link2\|([^\]\|]+)\]/";
        $data = preg_replace($pattern,"<a class='wiki' $target href='$link'>$1</a> $cosa",$data);
        $pattern = "/\[$link2\]/";
        $data = preg_replace($pattern,"<a class='wiki' $target href='$link'>$link</a> $cosa",$data);
      } else {
        $link2 = str_replace("/","\/",$link);
        $link2 = str_replace("?","\?",$link2);
        $link2 = str_replace("&","\&",$link2);
        $pattern = "/\[$link2\|([^\]\|]+)([^\]])*\]/";
        $data = preg_replace($pattern,"<a class='wiki' $target href='$link'>$1</a>",$data);
        $pattern = "/\[$link2\]/";
        $data = preg_replace($pattern,"<a class='wiki' $target href='$link'>$link</a>",$data);
      }
    }
    // Title bars
    $data = preg_replace("/-=([^=]+)=-/","<div class='titlebar'>$1</div>",$data);



    // New syntax for tables
    if (preg_match_all("/\|\|(.*)\|\|/", $data, $tables)) {
     $maxcols = 1;
      $cols = array();
      for($i = 0; $i < count($tables[0]); $i++) {
        $rows = explode('||', $tables[0][$i]);
        $col[$i] = array();
        for ($j = 0; $j < count($rows); $j++) {
          $cols[$i][$j] = explode('|', $rows[$j]);
          if (count($cols[$i][$j]) > $maxcols)
            $maxcols = count($cols[$i][$j]);
        }
      }
      for ($i = 0; $i < count($tables[0]); $i++) {
        $repl = '<table border=1>';
        for ($j = 0; $j < count($cols[$i]); $j++) {
          $ncols = count($cols[$i][$j]);
          if ($ncols == 1 && !$cols[$i][$j][0])
            continue;
          $repl .= '<tr>';
          for ($k = 0; $k < $ncols; $k++) {
            $repl .= '<td';
            if ($k == $ncols - 1 && $ncols < $maxcols)
              $repl .= ' colspan=' . ($maxcols-$k);
            $repl .= '>' . $cols[$i][$j][$k] . '</td>';
          }
          $repl.='</tr>';
        }
        $repl.='</table>';
        $data = str_replace($tables[0][$i],$repl,$data);
      }
    }


    // tables
    /*
    preg_match_all("/(\%[^\%]+\%)/",$data,$pages);
    foreach(array_unique($pages[1]) as $page_parse) {
      $pagex=substr($page_parse,1,strlen($page_parse)-2);
      $repl='<table cellpadding="0" cellspacing="0" border="1">';
      // First split by lines
      $lines = explode("\\",$pagex);
      foreach ($lines as $line) {
        $repl.='<tr>';
        $columns = explode("&",$line);
        foreach($columns as $column) {
          $repl.='<td valign="top">'.$column.'</td>';
        }
        $repl.='</tr>';
      }
      $repl.='</table>';
      $data = str_replace($page_parse, $repl, $data);
    }
    */




    // Now tokenize the expression and process the tokens
    // Use tab and newline as tokenizing characters as well  ////
    $lines = explode("\n",$data);
    $data = ''; $listbeg='';
    $listlevel = 0;
    $oldlistlevel = 0;
    $listbeg='';
    foreach ($lines as $line) {

      // If the first character is ' ' and we are not in pre then we are in pre
      if(substr($line,0,1)==' ') {
        if($listbeg) {
          while($listlevel>0) {
            $data.=$listbeg;
            $listlevel--;
            $oldlistlevel=0;
          }
          $listbeg='';
        }
        // If the first character is space then
        // change spaces for &nbsp;
        $line = '<font face="courier" size="2">'.str_replace(' ','&nbsp;',substr($line,1)).'</font>';
        $line.='<br/>';
      } else {
        // Reemplazar las bold
        $line = preg_replace("/__([^_]+)__/","<b>$1</b>",$line);
        $line = preg_replace("/\'\'([^']+)\'\'/","<i>$1</i>",$line);
        // Reemplazar las definiciones
        $line = preg_replace("/^;([^:]+):([^\n]+)/","<dl><dt>$1</dt><dd>$2</dd></dl>",$line);
        if(0) {
        $line = preg_replace("/\[([^\|]+)\|([^\]]+)\]/","<a class='wiki' $target href='$1'>$2</a>",$line);
        // Segundo intento reemplazar los [link] comunes
        $line = preg_replace("/\[([^\]]+)\]/","<a class='wiki' $target href='$1'>$1</a>",$line);
        $line = preg_replace("/\-\=([^=]+)\=\-/","<div class='wikihead'>$1</div>",$line);
        }

        // This line is parseable then we have to see what we have
        if(substr($line,0,3)=='---') {
          if($listbeg) {
            while($listlevel>0) {
            $data.=$listbeg;
            $listlevel--;
            $oldlistlevel=0;
          }
          $listbeg='';
          }
          $line='<hr/>';
        } else {
          if(substr($line,0,1)=='*') {
            // Get the list level examining the number of asterisks

            // If another list had started then end it
            if($listbeg && $listbeg!='</ul>') {
              while($listlevel>0) {
                $data.=$listbeg;
                $listlevel--;
                $oldlistlevel=0;
              }
            }

            $listlevel=$this->how_many_at_start($line,'*');

            // If the list level is new add ul's
            while($listlevel>$oldlistlevel) {
              $data.='<ul>';
              $listbeg='</ul>';
              $oldlistlevel++;
            }

            // If the list level is lower
            while($listlevel<$oldlistlevel) {
              $data.='</ul>';
              $oldlistlevel--;
            }

            $line = '<li>'.substr($line,$listlevel).'</li>';

          } elseif(substr($line,0,1)=='#') {
                    // If another list had started then end it
            if($listbeg && $listbeg!='</ol>') {
              while($listlevel>0) {
                $data.=$listbeg;
                $listlevel--;
                $oldlistlevel=0;
              }
            }

            $listlevel=$this->how_many_at_start($line,'#');

            // If the list level is new add ul's
            while($listlevel>$oldlistlevel) {

              $data.='<ol>';
              $listbeg='</ol>';
              $oldlistlevel++;
            }

            // If the list level is lower
            while($listlevel<$oldlistlevel) {
              $data.='</ol>';
              $oldlistlevel--;
            }

            //

            $line = '<li>'.substr($line,$listlevel).'</li>';

          } elseif(substr($line,0,3)=='!!!') {
            $line = '<h3>'.substr($line,3).'</h3>';
          } elseif(substr($line,0,2)=='!!') {
            $line = '<h2>'.substr($line,2).'</h2>';
          } elseif(substr($line,0,1)=='!') {
            $line = '<h1>'.substr($line,1).'</h1>';
          } else {
            if($listbeg) {
              while($listlevel>0) {
              $data.=$listbeg;
              $listlevel--;
              $oldlistlevel=0;
              }
              $listbeg='';
            } else {
              $line.='<br/>';
            }
          }
        }
      }
      $data.=$line;
    }


    // Close BiDi DIVs if any
    for ($i = 0; $i < $bidiCount; $i++) {
      $data.="</div>";
    }

    foreach($noparsed as $np) {
      $data = str_replace($np["key"],$np["data"],$data);
    }

    foreach($preparsed as $pp) {
      $data = str_replace($pp["key"],"<pre>".$pp["data"]."</pre>",$data);
    }

    return $data;
  }


  function parse_data_old($data)
  {
    global $page_regex;
    global $feature_hotwords;
    global $cachepages;
    global $ownurl_father;
    global $feature_drawings;
    global $tiki_p_admin_drawings;
    global $tiki_p_edit_drawings;
    global $feature_hotwords_nw;
    global $feature_wiki_pictures;
    global $tiki_p_upload_picture;
    global $page; 
    global $dbTiki;
    global $structlib;

    if($feature_hotwords_nw == 'y') {
      $hotw_nw = "target='_blank'";
    } else {
      $hotw_nw = '';
    }

    //Extract preparse sections before anything
    $preparsed=Array();
    preg_match_all("/\~pp\~((.|\n)*?)\~\/pp\~/",$data,$preparse);
    foreach(array_unique($preparse[1]) as $pp) {
      $key=md5($this->genPass());
      $aux["key"]=$key;
      $aux["data"]=$pp;
      $preparsed[]=$aux;
      $data=str_replace("~pp~$pp~/pp~",$key,$data);
    }

    //Extract noparse sections almost before anything
    $noparsed=Array();
    preg_match_all("/\~np\~((.|\n)*?)\~\/np\~/",$data,$noparse);
    foreach(array_unique($noparse[1]) as $np) {
      $key=md5($this->genPass());
      $aux["key"]=$key;
      $aux["data"]=$np;
      $noparsed[]=$aux;
      $data=str_replace("~np~$np~/np~",$key,$data);
    }

    // Now replace a TOC
    preg_match_all("/\{toc\}/",$data,$tocs);
    if(count($tocs[0])>0) {
      include_once("lib/structures/structlib.php");
      if($structlib->page_is_in_structure($page)) {
        $html='';
        $toc=$structlib->get_subtree_toc($page,$page,$html);
        $data=str_replace('{toc}',$html,$data);
      }
    }
    //$page='';

    // Now search for plugins
    preg_match_all("/\{([A-Z]+)\(([^\)]*)\)\}/",$data,$plugins);
    //print_r($plugins);
    for($i=0;$i<count($plugins[0]);$i++) {
      $plugin_start = $plugins[0][$i];
      $plugin_end = '{'.$plugins[1][$i].'}';
      $pos = strpos($data,$plugin_start);
      $pos_end = strpos($data,$plugin_end);
      if($pos_end>$pos) {
        $plugin_data_len=$pos_end-$pos-strlen($plugins[0][$i]);
        $plugin_data = substr($data,$pos+strlen($plugin_start),$plugin_data_len);
        $php_name = 'lib/wiki-plugins/wikiplugin_'.strtolower($plugins[1][$i]).'.php';
        $func_name = 'wikiplugin_'.strtolower($plugins[1][$i]);
        $params = split(',',trim($plugins[2][$i]));
        $arguments=Array();
        foreach($params as $param) {
          $parts=explode('=>',$param);
          if(isset($parts[0])&&isset($parts[1])) {
            $name=trim($parts[0]);
            $arguments[$name]=trim($parts[1]);
          }
        }
        if(file_exists($php_name)) {
          include_once($php_name);
          $ret = $func_name($plugin_data,$arguments);
          $data = substr_replace($data,$ret,$pos,$pos_end - $pos + strlen($plugin_end));
          // Allow plugins to generate plugin code... re-parse plugin output here
          // This allows recursive plugins
          $data = $this->parse_data($data);
        }
      }

    }



    // Now search for images uploaded by users
    if($feature_wiki_pictures=='y') {
      preg_match_all("/\{picture file=([^\}]+)\}/",$data,$pics);
      for($i=0;$i<count($pics[0]);$i++) {
        // Check if the image exists
        $name=$pics[1][$i];
        if(file_exists($name)) {
          // Replace by the img tag to show the image
         $repl = "<img src='$name?nocache=1' alt='$name' />";
        } else {
          $repl=tra('picture not found');
        }
        // Replace by $repl
        $data = str_replace($pics[0][$i],$repl,$data);
      }
    }


    $data = stripslashes($data);
    if($feature_hotwords == 'y') {
      $words = $this->get_hotwords();
      foreach($words as $word=>$url) {
        //print("Replace $word by $url<br/>");
        $data  = preg_replace("/ $word /i"," <a class=\"wiki\" href=\"$url\" $hotw_nw>$word</a> ",$data);
      }
    }

    //$data = strip_tags($data);
    // BiDi markers
    $bidiCount = 0;
    $bidiCount = preg_match_all("/(\{l2r\})/",$data,$pages);
    $bidiCount += preg_match_all("/(\{r2l\})/",$data,$pages);

    $data = preg_replace("/\{l2r\}/", "<div dir='ltr'>", $data);
    $data = preg_replace("/\{r2l\}/", "<div dir='rtl'>", $data);
    $data = preg_replace("/\{lm\}/", "&lrm;", $data);
    $data = preg_replace("/\{rm\}/", "&rlm;", $data);
    // smileys
    $data = $this->parse_smileys($data);


    // Replace rss modules
    if(preg_match_all("/\{rss +id=([0-9]+) *(max=([0-9]+))? *\}/",$data,$rsss)) {
      for($i=0;$i<count($rsss[0]);$i++) {
        $id = $rsss[1][$i];
        $max = $rsss[3][$i];
        if(empty($max)) $max=99;
        $rssdata = $this->get_rss_module_content($id);
        $items = $this->parse_rss_data($rssdata);
        $repl='';
        for($j=0;$j<count($items) && $j<$max;$j++) {
         $repl.='<li><a target="_blank" href="'.$items[$j]["link"].'" class="wiki">'.$items[$j]["title"].'</a></li>';
        }
        $repl='<ul>'.$repl.'</ul>';
        $data = str_replace($rsss[0][$i],$repl,$data);
      }
    }

    // Replace links to slideshows


    if($feature_drawings == 'y') {
    // Replace drawings
    // Replace rss modules
    $pars=parse_url($_SERVER["REQUEST_URI"]);
    $pars_parts=split('/',$pars["path"]);
    $pars=Array();
    for($i=0;$i<count($pars_parts)-1;$i++) {
      $pars[]=$pars_parts[$i];
    }
    $pars=join('/',$pars);
    if(preg_match_all("/\{draw +name=([A-Za-z_\-0-9]+) *\}/",$data,$draws)) {
      for($i=0;$i<count($draws[0]);$i++) {
        $id = $draws[1][$i];
        $repl='';
        $name=$id.'.gif';
        if(file_exists("img/wiki/$name")) {
          if($tiki_p_edit_drawings == 'y' || $tiki_p_admin_drawings == 'y') {
            $repl="<a href='#' onClick=\"javascript:window.open('tiki-editdrawing.php?path=$pars&amp;drawing={$id}','','menubar=no,width=252,height=25');\"><img border='0' src='img/wiki/$name' alt='click to edit' /></a>";
          } else {
            $repl="<img border='0' src='img/wiki/$name' alt='a drawing' />";
          }
        } else {
          if($tiki_p_edit_drawings == 'y' || $tiki_p_admin_drawings == 'y') {
            $repl="<a class='wiki' href='#' onClick=\"javascript:window.open('tiki-editdrawing.php?path=$pars&amp;drawing={$id}','','menubar=no,width=252,height=25');\">click here to create draw $id</a>";
          } else {
            $repl=tra('drawing not found');
          }
        }
        $data = str_replace($draws[0][$i],$repl,$data);
      }
    }
    }

    // Replace cookies
    if(preg_match_all("/\{cookie\}/",$data,$rsss)) {
      for($i=0;$i<count($rsss[0]);$i++) {
        $cookie = $this->pick_cookie();
        $data = str_replace($rsss[0][$i],$cookie,$data);
      }
    }


    // Replace dynamic content occurrences
    if(preg_match_all("/\{content +id=([0-9]+)\}/",$data,$dcs)) {
      for($i=0;$i<count($dcs[0]);$i++) {
        $repl = $this->get_actual_content($dcs[1][$i]);
        $data = str_replace($dcs[0][$i],$repl,$data);
      }
    }
    // Replace Dynamic content with random selection
    if(preg_match_all("/\{rcontent +id=([0-9]+)\}/",$data,$dcs)) {
      for($i=0;$i<count($dcs[0]);$i++) {
        $repl = $this->get_random_content($dcs[1][$i]);
        $data = str_replace($dcs[0][$i],$repl,$data);
      }
    }

    // Replace boxes
    $data = preg_replace("/\^([^\^]+)\^/","<div class='simplebox' align='center'>$1</div>",$data);
    // Replace colors ~~color:text~~
    $data = preg_replace("/\~\~([^\:]+):([^\~]+)\~\~/","<span style='color:$1;'>$2</span>",$data);
    // Underlined text
    $data = preg_replace("/===([^\=]+)===/","<span style='text-decoration:underline;'>$1</span>",$data);
    // Center text
    $data = preg_replace("/::([^\:]+)::/","<div align='center'>$1</div>",$data);
    // Links to internal pages
    // If they are parenthesized then don't treat as links
    // Prevent ))PageName(( from being expanded    \"\'

    //[A-Z][a-z0-9_\-]+[A-Z][a-z0-9_\-]+[A-Za-z0-9\-_]*
    // The first part is now mandatory to prevent [Foo|MyPage] from being converted!
    preg_match_all("/([ \n\t\r\,\;]|^)([A-Z][a-z0-9_\-]+[A-Z][a-z0-9_\-]+[A-Za-z0-9\-_]*)($|[ \n\t\r\,\;\.])/",$data,$pages);
    //print_r($pages);
    foreach(array_unique($pages[2]) as $page) {
      if($desc = $this->page_exists_desc($page)) {
        $repl = "<a title='".$desc."' href='tiki-index.php?page=$page' class='wiki'>$page</a>";
      } else {
        $repl = "$page<a href='tiki-editpage.php?page=$page' class='wiki'>?</a>";
      }
      $data = preg_replace("/([ \n\t\r\,\;]|^)$page($|[ \n\t\r\,\;\.])/","$1"."$repl"."$2",$data);
      //$data = str_replace($page,$repl,$data);
    }

    $data = preg_replace("/([ \n\t\r\,\;]|^)\)\)([^\(]+)\(\(($|[ \n\t\r\,\;\.])/","$1"."$2"."$3",$data);
    // New syntax for wiki pages ((name|desc)) Where desc can be anything
    preg_match_all("/\(\(($page_regex)\|(.+?)\)\)/",$data,$pages);
    for($i=0;$i<count($pages[1]);$i++) {
      if($desc = $this->page_exists_desc($pages[1][$i])) {
        $repl = "<a title='$desc' href='tiki-index.php?page=".$pages[1][$i]."' class='wiki'>".$pages[5][$i]."</a>";
      } else {
        $repl = $pages[5][$i]."<a href='tiki-editpage.php?page=".$pages[1][$i]."' class='wiki'>?</a>";
      }

      $pattern = "/".$pages[0][$i]."/";
      $pattern=str_replace('|','\|',$pattern);
      $pattern=str_replace('(','\(',$pattern);
      $pattern=str_replace(')','\)',$pattern);
      $data = preg_replace($pattern,"$repl",$data);
    }

    // New syntax for wiki pages ((name)) Where name can be anything
    preg_match_all("/\(\(($page_regex)\)\)/",$data,$pages);
    foreach(array_unique($pages[1]) as $page) {
      if($desc = $this->page_exists_desc($page)) {
        $repl = "<a title='$desc' href='tiki-index.php?page=$page' class='wiki'>$page</a>";
      } else {
        $repl = "$page<a href='tiki-editpage.php?page=$page' class='wiki'>?</a>";
      }
      $data = preg_replace("/\(\($page\)\)/","$repl",$data);
      //$data = str_replace($page,$repl,$data);
    }

    // Replace ))Words((
    $data = preg_replace("/\(\(([^\)]+)\)\)/","$1",$data);

    // Images
    preg_match_all("/(\{img [^\}]+})/",$data,$pages);
    foreach(array_unique($pages[1]) as $page) {
      $parts = explode(" ",$page);
      $imgdata = Array();
      $imgdata["src"]='';
      $imgdata["height"]='';
      $imgdata["width"]='';
      $imgdata["link"]='';
      $imgdata["align"]='';
      $imgdata["desc"]='';
      foreach($parts as $part) {
        $part = str_replace('}','',$part);
        $part = str_replace('{','',$part);
        $part = str_replace('\'','',$part);
        $part = str_replace('"','',$part);
        if(strstr($part,'=')) {
            $subs = explode("=",$part,2);
            $imgdata[$subs[0]]=$subs[1];
        }
      }
      //print("todo el tag es: ".$page."<br/>");
      //print_r($imgdata);
      $repl = "<div class=\"innerimg\"><img alt='an image' src='".$imgdata["src"]."' border='0' ";
      if($imgdata["width"]) $repl.=" width='".$imgdata["width"]."'";
      if($imgdata["height"]) $repl.=" height='".$imgdata["height"]."'";
      $repl.= " /></div>";
      if($imgdata["link"]) {
        $repl ="<a href='".$imgdata["link"]."'>".$repl."</a>";
      }
      if($imgdata["desc"]) {
        $repl="<table cellpadding='0' cellspacing='0'><tr><td>".$repl."</td></tr><tr><td class='mini'>".$imgdata["desc"]."</td></tr></table>";
      }
      if($imgdata["align"]) {
        $repl ="<div align='".$imgdata["align"]."'>".$repl."</div>";
      }
      $data = str_replace($page,$repl,$data);
    }

    $target='';
    if($this->get_preference('popupLinks','n')=='y') {
      $target='target="_blank"';
    }

    $links = $this->get_links($data);
    // Note that there're links that are replaced

    foreach($links as $link) {
      if( $this->is_cached($link) && $cachepages == 'y') {
        $cosa="<a class=\"wikicache\" target=\"_blank\" href=\"tiki-view_cache.php?url=$link\">(cache)</a>";
        $link2 = str_replace("/","\/",$link);
        $link2 = str_replace("?","\?",$link2);
        $link2 = str_replace("&","\&",$link2);
        $pattern = "/\[$link2\|([^\]\|]+)\|([^\]]+)\]/";
        $data = preg_replace($pattern,"<a class='wiki' $target href='$link'>$1</a>",$data);
        $pattern = "/\[$link2\|([^\]\|]+)\]/";
        $data = preg_replace($pattern,"<a class='wiki' $target href='$link'>$1</a> $cosa",$data);
        $pattern = "/\[$link2\]/";
        $data = preg_replace($pattern,"<a class='wiki' $target href='$link'>$link</a> $cosa",$data);
      } else {
        $link2 = str_replace("/","\/",$link);
        $link2 = str_replace("?","\?",$link2);
        $link2 = str_replace("&","\&",$link2);
        $pattern = "/\[$link2\|([^\]\|]+)([^\]])*\]/";
        $data = preg_replace($pattern,"<a class='wiki' $target href='$link'>$1</a>",$data);
        $pattern = "/\[$link2\]/";
        $data = preg_replace($pattern,"<a class='wiki' $target href='$link'>$link</a>",$data);
      }
    }

    // Title bars
    $data = preg_replace("/-=([^=]+)=-/","<div class='titlebar'>$1</div>",$data);

    // New syntax for tables
    if (preg_match_all("/\|\|(.*)\|\|/", $data, $tables)) {
     $maxcols = 1;
      $cols = array();
      for($i = 0; $i < count($tables[0]); $i++) {
        $rows = explode('||', $tables[0][$i]);
        $col[$i] = array();
        for ($j = 0; $j < count($rows); $j++) {
          $cols[$i][$j] = explode('|', $rows[$j]);
          if (count($cols[$i][$j]) > $maxcols)
            $maxcols = count($cols[$i][$j]);
        }
      }
      for ($i = 0; $i < count($tables[0]); $i++) {
        $repl = '<table border=1>';
        for ($j = 0; $j < count($cols[$i]); $j++) {
          $ncols = count($cols[$i][$j]);
          if ($ncols == 1 && !$cols[$i][$j][0])
            continue;
          $repl .= '<tr>';
          for ($k = 0; $k < $ncols; $k++) {
            $repl .= '<td';
            if ($k == $ncols - 1 && $ncols < $maxcols)
              $repl .= ' colspan=' . ($maxcols-$k);
            $repl .= '>' . $cols[$i][$j][$k] . '</td>';
          }
          $repl.='</tr>';
        }
        $repl.='</table>';
        $data = str_replace($tables[0][$i],$repl,$data);
      }
    }


    // tables
    preg_match_all("/(\%[^\%]+\%)/",$data,$pages);
    foreach(array_unique($pages[1]) as $page) {
      $pagex=substr($page,1,strlen($page)-2);
      $repl='<table cellpadding="0" cellspacing="0" border="1">';
      // First split by lines
      $lines = explode("\\",$pagex);
      foreach ($lines as $line) {
        $repl.='<tr>';
        $columns = explode("&",$line);
        foreach($columns as $column) {
          $repl.='<td valign="top">'.$column.'</td>';
        }
        $repl.='</tr>';
      }
      $repl.='</table>';
      $data = str_replace($page, $repl, $data);
    }


    // Now tokenize the expression and process the tokens
    // Use tab and newline as tokenizing characters as well  ////
    $lines = explode("\n",$data);
    $data = ''; $listbeg='';
    $listlevel = 0;
    $oldlistlevel = 0;
    $listbeg='';
    foreach ($lines as $line) {

      // If the first character is ' ' and we are not in pre then we are in pre
      if(substr($line,0,1)==' ') {
        if($listbeg) {
          while($listlevel>0) {
            $data.=$listbeg;
            $listlevel--;
            $oldlistlevel=0;
          }
          $listbeg='';
        }
        // If the first character is space then
        // change spaces for &nbsp;
        $line = '<font face="courier" size="2">'.str_replace(' ','&nbsp;',substr($line,1)).'</font>';
        $line.='<br/>';
      } else {
        // Reemplazar las bold
        $line = preg_replace("/__([^_]+)__/","<b>$1</b>",$line);
        $line = preg_replace("/\'\'([^']+)\'\'/","<i>$1</i>",$line);
        // Reemplazar las definiciones
        $line = preg_replace("/^;([^:]+):([^\n]+)/","<dl><dt>$1</dt><dd>$2</dd></dl>",$line);
        if(0) {
        $line = preg_replace("/\[([^\|]+)\|([^\]]+)\]/","<a class='wiki' $target href='$1'>$2</a>",$line);
        // Segundo intento reemplazar los [link] comunes
        $line = preg_replace("/\[([^\]]+)\]/","<a class='wiki' $target href='$1'>$1</a>",$line);
        $line = preg_replace("/\-\=([^=]+)\=\-/","<div class='wikihead'>$1</div>",$line);
        }

        // This line is parseable then we have to see what we have
        if(substr($line,0,3)=='---') {
          if($listbeg) {
            while($listlevel>0) {
            $data.=$listbeg;
            $listlevel--;
            $oldlistlevel=0;
          }
          $listbeg='';
          }
          $line='<hr/>';
        } else {
          if(substr($line,0,1)=='*') {
            // Get the list level examining the number of asterisks

            // If another list had started then end it
            if($listbeg && $listbeg!='</ul>') {
              while($listlevel>0) {
                $data.=$listbeg;
                $listlevel--;
                $oldlistlevel=0;
              }
            }

            $listlevel=$this->how_many_at_start($line,'*');

            // If the list level is new add ul's
            while($listlevel>$oldlistlevel) {
              $data.='<ul>';
              $listbeg='</ul>';
              $oldlistlevel++;
            }

            // If the list level is lower
            while($listlevel<$oldlistlevel) {
              $data.='</ul>';
              $oldlistlevel--;
            }

            $line = '<li>'.substr($line,$listlevel).'</li>';

          } elseif(substr($line,0,1)=='#') {
                    // If another list had started then end it
            if($listbeg && $listbeg!='</ol>') {
              while($listlevel>0) {
                $data.=$listbeg;
                $listlevel--;
                $oldlistlevel=0;
              }
            }

            $listlevel=$this->how_many_at_start($line,'#');

            // If the list level is new add ul's
            while($listlevel>$oldlistlevel) {

              $data.='<ol>';
              $listbeg='</ol>';
              $oldlistlevel++;
            }

            // If the list level is lower
            while($listlevel<$oldlistlevel) {
              $data.='</ol>';
              $oldlistlevel--;
            }

            //

            $line = '<li>'.substr($line,$listlevel).'</li>';

          } elseif(substr($line,0,3)=='!!!') {
            $line = '<h3>'.substr($line,3).'</h3>';
          } elseif(substr($line,0,2)=='!!') {
            $line = '<h2>'.substr($line,2).'</h2>';
          } elseif(substr($line,0,1)=='!') {
            $line = '<h1>'.substr($line,1).'</h1>';
          } else {
            if($listbeg) {
              while($listlevel>0) {
              $data.=$listbeg;
              $listlevel--;
              $oldlistlevel=0;
              }
              $listbeg='';
            } else {
              $line.='<br/>';
            }
          }
        }
      }
      $data.=$line;
    }


    // Close BiDi DIVs if any
    for ($i = 0; $i < $bidiCount; $i++) {
      $data.="</div>";
    }

    foreach($noparsed as $np) {
      $data = str_replace($np["key"],$np["data"],$data);
    }

    foreach($preparsed as $pp) {
      $data = str_replace($pp["key"],"<pre>".$pp["data"]."</pre>",$data);
    }

    return $data;
  }




  function parse_smileys($data)
  {
    global $feature_smileys;
    if($feature_smileys == 'y') {
    $data = preg_replace("/\(:([^:]+):\)/","<img alt=\"$1\" src=\"img/smiles/icon_$1.gif\" />",$data);
    }
    return $data;
  }

  function parse_comment_data($data)
  {
     $data = preg_replace("/\[([^\|\]]+)\|([^\]]+)\]/","<a class=\"commentslink\" href=\"$1\">$2</a>",$data);
      // Segundo intento reemplazar los [link] comunes
     $data = preg_replace("/\[([^\]\|]+)\]/","<a class=\"commentslink\" href=\"$1\">$1</a>",$data);
     // Llamar aqui a parse smileys
     $data = $this->parse_smileys($data);
     $data = preg_replace("/---/","<hr/>",$data);
     // Reemplazar --- por <hr/>
     return $data;
  }

  function get_pages($data) {
    global $page_regex;
    preg_match_all("/([ \n\t\r\,\;]|^)?([A-Z][a-z0-9_\-]+[A-Z][a-z0-9_\-]+[A-Za-z0-9\-_]*)($|[ \n\t\r\,\;\.])/",$data,$pages);
    preg_match_all("/\(\(($page_regex)\)\)/",$data,$pages2);
    preg_match_all("/\(\(($page_regex)\|(.+?)\)\)/",$data,$pages3);
    $pages = array_unique(array_merge($pages[2],$pages2[1],$pages3[1]));
    return $pages;
  }

  function clear_links($page) {
    $query = "delete from tiki_links where fromPage='$page'";
    $result = $this->query($query);
  }

  function replace_link($pageFrom, $pageTo) {
    $query = "replace into tiki_links(fromPage,toPage) values('$pageFrom','$pageTo')";
    $result = $this->query($query);
  }

  function invalidate_cache($page) {
    $page = addslashes($page);
    $query = "update tiki_pages set cache_timestamp=0 where pageName='$page'";
    $this->query($query);
  }

  function update_page($pageName,$edit_data,$edit_comment, $edit_user, $edit_ip,$description='')
  {
    global $smarty;
    $this->invalidate_cache($pageName);
    // Collect pages before modifying edit_data (see update of links below)
    $pages = $this->get_pages($edit_data);
    $edit_data = addslashes($edit_data);
    $description = addslashes($description);
    $edit_comment = addslashes($edit_comment);
    if(!$this->page_exists($pageName)) return false;
    $t = date("U");
    // Get this page information
    $info = $this->get_page_info($pageName);
    // Store the old version of this page in the history table
    $version = $info["version"];
    $lastModif = $info["lastModif"];
    $user = $info["user"];
    $ip = $info["ip"];
    $comment = $info["comment"];
    $data = addslashes($info["data"]);
    // WARNING: POTENTIAL BUG
    // The line below is not consistent with the rest of Tiki
    // (I commented it out so it can be further examined by CVS change control)
    //$pageName=addslashes($pageName);
    $comment=addslashes($comment);
    $query = "insert into tiki_history(pageName, version, lastModif, user, ip, comment, data, description)
              values('$pageName',$version,$lastModif,'$user','$ip','$comment','$data','$description')";
    if($pageName != 'SandBox') {
      $result = $this->query($query);
    }
    // Update the pages table with the new version of this page
    $version += 1;
    //$edit_data = addslashes($edit_data);
    $emails = $this->get_mail_events('wiki_page_changes','wikipage'.$pageName);
    foreach($emails as $email) {
      $smarty->assign('mail_site',$_SERVER["SERVER_NAME"]);
      $smarty->assign('mail_page',$pageName);
      $smarty->assign('mail_date',date("U"));
      $smarty->assign('mail_user',$edit_user);
      $smarty->assign('mail_data',$edit_data);
      $smarty->assign('mail_machine',$_SERVER["REQUEST_URI"]);
      $smarty->assign('mail_pagedata',$edit_data);
      $mail_data = $smarty->fetch('mail/wiki_change_notification.tpl');
      @mail($email, tra('Wiki page').' '.$pageName.' '.tra('changed'), $mail_data);
    }

    $query = "update tiki_pages set description='$description', data='$edit_data', comment='$edit_comment', lastModif=$t, version=$version, user='$edit_user', ip='$edit_ip' where pageName='$pageName'";
    $result = $this->query($query);
    // Parse edit_data updating the list of links from this page
    $this->clear_links($pageName);
    // Pages collected above
    foreach($pages as $page) {
      $this->replace_link($pageName,$page);
    }
    // Update the log
    if($pageName != 'SandBox') {
      $action = "Updated";
      $query = "insert into tiki_actionlog(action,pageName,lastModif,user,ip,comment) values('$action','$pageName',$t,'$edit_user','$edit_ip','$edit_comment')";
      $result = $this->query($query);
      $maxversions = $this->get_preference("maxVersions",0);
      if($maxversions) {
        // Select only versions older than keep_versions days
        $keep = $this->get_preference('keep_versions',0);
        $now = date("U");
        $oktodel = $now - ($keep * 24 * 3600);
        $query = "select pageName,version from tiki_history where pageName='$pageName' and lastModif<=$oktodel order by lastModif desc limit $maxversions,-1";
        $result = $this->query($query);
        $toelim = $result->numRows();
        while($res= $result->fetchRow(DB_FETCHMODE_ASSOC)) {
          $page = $res["pageName"];
          $version = $res["version"];
          $query = "delete from tiki_history where pageName='$pageName' and version='$version'";
          $this->query($query);
        }
      }
    }
  }

  function update_page_version($pageName,$version,$edit_data,$edit_comment, $edit_user, $edit_ip,$lastModif,$description='')
  {
    global $smarty;
    $pageName = addslashes($pageName);
    if($pageName=='SandBox') return;
    // Collect pages before modifying edit_data
    $pages = $this->get_pages($edit_data);
    $edit_data = addslashes($edit_data);
    $description = addslashes($description);
    $edit_comment = addslashes($edit_comment);
    if(!$this->page_exists($pageName)) return false;
    $t = date("U");
    $query = "delete from tiki_history where pageName='$pageName' and version=$version";
    $result = $this->query($query);
    $query = "insert into tiki_history(pageName, version, lastModif, user, ip, comment, data,description)
              values('$pageName',$version,$lastModif,'$edit_user','$edit_ip','$edit_comment','$edit_data','$description')";
    $result = $this->query($query);

    //print("version: $version<br/>");
    // Get this page information
    $info = $this->get_page_info($pageName);
    if($version>=$info["version"]) {
      $query = "update tiki_pages set data='$edit_data', comment='$edit_comment', lastModif=$t, version=$version, user='$edit_user', ip='$edit_ip', description='$description' where pageName='$pageName'";
      $result = $this->query($query);
      // Parse edit_data updating the list of links from this page
      $this->clear_links($pageName);
      // Pages are collected at the top of the function before adding slashes
      foreach($pages as $page) {
         $this->replace_link($pageName,$page);
      }
    }
  }

  // This function get the last changes from pages from the last $days days
  // if days is 0 this gets all the registers
  // function parameters modified by ramiro_v on 11/03/2002
  function get_last_changes($days, $offset=0, $limit=-1, $sort_mode = 'lastModif_desc', $findwhat='')
  {
    $sort_mode = str_replace("_"," ",$sort_mode);
  // section added by ramiro_v on 11/03/2002 begins here
    if($findwhat == '') {
      $where=" where 1";
    } else {
      $where=" where pageName like '%" . $findwhat . "%' or user like '%" . $findwhat . "%' or comment like '%" . $findwhat . "%'";
    }
  // section added by ramiro_v on 11/03/2002 ends here

    if($days) {
      $toTime = mktime(23,59,59,date("m"),date("d"),date("Y"));
      $fromTime = $toTime - (24*60*60*$days);
      $where = $where . " and lastModif>=$fromTime and lastModif<=$toTime";
    }

    $query = "select action, lastModif, user, ip, pageName,comment from tiki_actionlog " . $where . " order by $sort_mode limit $offset,$limit";
    $query_cant = "select count(*) from tiki_actionlog " . $where;
    $result = $this->query($query);
    $cant = $this->getOne($query_cant);
    $ret = Array(); $r=Array();
    while($res=$result->fetchRow(DB_FETCHMODE_ASSOC)) {
      $r["action"] = $res["action"];
      $r["lastModif"] = $res["lastModif"];
      $r["user"] = $res["user"];
      $r["ip"] = $res["ip"];
      $r["pageName"] = $res["pageName"];
      $r["comment"] = $res["comment"];
      $ret[]=$r;
    }
    $retval = Array();
    $retval["data"] = $ret;
    $retval["cant"] = $cant;
    return $retval;
  }

  # TODO move all of these date/time functions to a static class: TikiDate

  function get_timezone_list($use_default = false) {
    static $timezone_options;

    if (!$timezone_options) {
    $timezone_options = array();
    if ($use_default)
      $timezone_options['default'] = '-- Use Default Time Zone --';
    foreach ($GLOBALS['_DATE_TIMEZONE_DATA'] as $tz_key => $tz) {
      $offset = $tz['offset'];
      $absoffset = abs($offset /= 60000);
      $plusminus = $offset < 0 ? '-' : '+';
      $gmtoff = sprintf("GMT%1s%02d:%02d", $plusminus, $absoffset / 60, $absoffset - (intval($absoffset / 60) * 60));
      $tzlongshort = $tz['longname'] . ' (' . $tz['shortname'] . ')';
      $timezone_options[$tz_key] = sprintf('%-28.28s: %-36.36s %s', $tz_key, $tzlongshort, $gmtoff);
    }
  }

    return $timezone_options;
  }

  function get_server_timezone() {
    static $server_timezone;

    if (!$server_timezone) {
      $server_time = new Date();
      $server_timezone = $server_time->tz->getID();
    }

    return $server_timezone;
  }

  # TODO rename get_site_timezone()
  function get_display_timezone($user = false) {
    static $display_timezone = false;

    if (!$display_timezone) {
      $server_time = $this->get_server_timezone();
      if ($user) {
        $display_timezone = $this->get_user_preference($user, 'display_timezone');
        if (!$display_timezone || $display_timezone == 'default') {
          $display_timezone = $this->get_preference('display_timezone', $server_time);
        }
      } else {
        $display_timezone = $this->get_preference('display_timezone', $server_time);
      }
    }

    return $display_timezone;
  }

  function get_long_date_format() {
    static $long_date_format = false;

    if (!$long_date_format)
      $long_date_format = $this->get_preference('long_date_format', '%A %d ' . tra('of') . ' %B, %Y');
    return $long_date_format;
  }

  function get_short_date_format() {
    static $short_date_format = false;

    if (!$short_date_format)
      $short_date_format = $this->get_preference('short_date_format', '%a %d ' . tra('of') . ' %b, %Y');
    return $short_date_format;
  }

  function get_long_time_format() {
    static $long_time_format = false;

    if (!$long_time_format)
      $long_time_format = $this->get_preference('long_time_format', '%H:%M:%S %Z');
    return $long_time_format;
  }

  function get_short_time_format() {
    static $short_time_format = false;

    if (!$short_time_format)
      $short_time_format = $this->get_preference('short_time_format', '%H:%M %Z');
    return $short_time_format;
  }

  function get_long_datetime_format() {
    static $long_datetime_format = false;

    if (!$long_datetime_format)
      $long_datetime_format = $this->get_long_date_format() . ' [' . $this->get_long_time_format() . ']';
    return $long_datetime_format;
  }

  function get_short_datetime_format() {
    static $short_datetime_format = false;

    if (!$short_datetime_format)
      $short_datetime_format = $this->get_short_date_format() . ' [' . $this->get_short_time_format() . ']';
    return $short_datetime_format;
  }

  function server_time_to_site_time($timestamp, $user = false) {
  $date = new Date($timestamp);
  $date->setTZbyID($this->get_server_timezone());
  $date->convertTZbyID($this->get_display_timezone($user));
    return $date->getTime();
  }

  /**

  */
  function get_site_date($timestamp, $user = false) {
  static $localed = false;

  if (!$localed) {
    # breaks the RFC 2822 code
#   @setlocale(LC_TIME, $tikilib->get_locale($user));
    $localed = true;
  }

  $original_tz = date('T', $timestamp);

#$rv = "\n<pre>\n";
#$rv .= strftime($format, $timestamp);
#$rv .= " =timestamp\n";
#$rv .= strftime('%Z', $timestamp);
#$rv .= " =strftime('%Z')\n";
#$rv .= date('T', $timestamp);
#$rv .= " =date('T')\n";

  $date =& new Date($timestamp);

# Calling new Date() changes the timezone of the $timestamp var!
# so we only change the timezone to UTC if the original TZ wasn't UTC
# to begin with.
# This seems really buggy, but I don't have time to delve into right now.

#$rv .= date('T', $timestamp);
#$rv .= " =date('T')\n";

#$rv .= $date->format($format);
#$rv .= " =new Date()\n";

#$rv .= date('T', $timestamp);
#$rv .= " =date('T')\n";

  if ($original_tz == 'UTC') {
    $date->setTZbyID('UTC');
#$rv .= $date->format($format);
#$rv .= " =setTZbyID('UTC')\n";
  }

  $tz_id = $this->get_display_timezone($user);
  if ($date->tz->getID() != $tz_id) {
    # let's convert to the displayed timezone
    $date->convertTZbyID($tz_id);
#$rv .= $date->format($format);
#$rv .= " =convertTZbyID($tz_id)\n";
  }

#return $rv;

# if ($format == "%b %e, %Y")
#   $format = $tikilib->get_short_date_format();
    return $date;
  }

  # TODO rename to server_time_to_site_time()

  function get_site_time($timestamp, $user = false) {
    $date = $this->get_site_date($timestamp, $user);
    return $date->getTime();
  }

  function date_format($format, $timestamp, $user = false) {
    $date = $this->get_site_date($timestamp, $user);
    return $date->format($format);
  }

  function get_long_date($timestamp, $user = false) {
    return $this->date_format($this->get_long_date_format(), $timestamp, $user);
  }

  function get_short_date($timestamp, $user = false) {
    return $this->date_format($this->get_short_date_format(), $timestamp, $user);
  }

  function get_long_time($timestamp, $user = false) {
    return $this->date_format($this->get_long_time_format(), $timestamp, $user);
  }

  function get_short_time($timestamp, $user = false) {
    return $this->date_format($this->get_short_time_format(), $timestamp, $user);
  }

  function get_long_datetime($timestamp, $user = false) {
    return $this->date_format($this->get_long_datetime_format(), $timestamp, $user);
  }

  function get_short_datetime($timestamp, $user = false) {
    return $this->date_format($this->get_short_datetime_format(), $timestamp, $user);
  }

  function get_site_timezone_shortname($user = false) {
    static $timezone_shortname;

    if (!$timezone_shortname) {
      $date = $this->get_site_date(date('U'), $user);
      $timezone_shortname = $date->format('%Z');
    }

    return $timezone_shortname;
  }

  function get_server_timezone_shortname($user = false) {
    static $timezone_shortname;

    if (!$timezone_shortname) {
      $timezone_shortname = date('%Z');
    }

    return $timezone_shortname;
  }

  /**
    get_site_time_difference - Return the number of seconds needed to add to a
    'system' time to return a 'site' time.
  */
  function get_site_time_difference($user = false) {
    static $difference = false;

    if ($difference === false) {
      $server_tzid  = $this->get_server_timezone();
      $site_tzid  = $this->get_display_timezone($user);
#print "<pre>";
#printf("server_tzid='%s', site_tzid='%s'", $server_tzid, $site_tzid);
      $server_tz  =& new Date_TimeZone($server_tzid);
      $site_tz    =& new Date_TimeZone($site_tzid);
#printf("server_tz=");
#print_r($server_tz);
#printf("site_tz=");
#print_r($site_tz);
      $now =& new Date();
      $server_offset = $server_tz->getOffset($now);
      $site_offset = $site_tz->getOffset($now);
#printf("server_offset='%s', site_offset='%s'", $server_offset, $site_offset);
      $difference = intval(($site_offset - $server_offset) / 1000);
#printf("difference=%s", $difference);
    }

    return $difference;
  }


  /**
    Timezone saavy replacement for mktime()
  */
  function make_time($hour, $minute, $second, $month, $day, $year, $timezone_id = false) {
    global $user; # ugh!

  if ($year <= 69)
    $year += 2000;
  if ($year <= 99)
    $year += 1900;

  $date = new Date();
    $date->setHour($hour);
    $date->setMinute($minute);
    $date->setSecond($second);
    $date->setMonth($month);
    $date->setDay($day);
    $date->setYear($year);
#$rv = sprintf("make_time(): $date->format(%D %T %Z)=%s<br/>\n", $date->format('%D %T %Z'));
#print "<pre> make_time() start";
#print_r($date);
    if ($timezone_id)
      $date->setTZbyID($timezone_id);
#print_r($date);
#$rv .= sprintf("make_time(): $date->format(%D %T %Z)=%s<br/>\n", $date->format('%D %T %Z'));
#print $rv;
    return $date->getTime();
  }

  /**
    Timezone saavy replacement for mktime()
  */
  function make_server_time($hour, $minute, $second, $month, $day, $year, $timezone_id = false) {
    global $user; # ugh!

  if ($year <= 69)
    $year += 2000;
  if ($year <= 99)
    $year += 1900;

  $date = new Date();
    $date->setHour($hour);
    $date->setMinute($minute);
    $date->setSecond($second);
    $date->setMonth($month);
    $date->setDay($day);
    $date->setYear($year);
#print "<pre> make_server_time() start\n";
#print_r($date);
    if ($timezone_id)
      $date->setTZbyID($timezone_id);
#print_r($date);
  $date->convertTZbyID($this->get_server_timezone());
#print_r($date);
#print "make_server_time() end\n</pre>";

    return $date->getTime();
  }

  /**
  Per http://www.w3.org/TR/NOTE-datetime
  */
  function get_iso8601_datetime($timestamp, $user = false) {
    return $this->date_format('%Y-%m-%dT%H:%M:%S%O', $timestamp, $user);
  }

  function get_rfc2822_datetime($timestamp = false, $user = false) {
    if (!$timestamp)
        $timestamp = time();

    # can't be localized!
    #return date('D, j M Y H:i:s ', $time) . $this->timezone_offset($time, 'no colon');
    return $this->date_format('%a, %e %b %Y %H:%M:%S', $timestamp, $user) .
      $this->get_rfc2822_timezone_offset($timestamp, $user);
  }

  function get_rfc2822_timezone_offset($time = false, $no_colon = false, $user = false) {
    if ($time === false)
        $time = time();
    $secs = $this->date_format('%Z', $time, $user);
    if ($secs < 0) {
        $sign = '-';
        $secs = -$secs;
    }
    else {
        $sign = '+';
    }
    $colon = $no_colon ? '' : ':';
    $mins = intval(($secs + 30) / 60);
    return sprintf("%s%02d%s%02d", $sign, $mins / 60, $colon, $mins % 60);
  }

  function get_language($user = false) {
    static $language = false;

    if (!$language) {
      if ($user) {
        $language = $this->get_user_preference($user, 'language', 'en');
        if (!$language || $language == 'default')
          $language = $this->get_preference('language', 'en');
      } else
        $language = $this->get_preference('language', 'en');
  }

    return $language;
  }

  function get_locale($user = false) {
    static $locale = false;

    static $locales = array(
      'en' => 'en_US',
      'de' => 'de_DE',
      'fr' => 'fr_FR',
      'sp' => 'es_ES',
    );
    if (!$locale)
      $locale = $locales[$this->get_language($user)];
    return $locale;
  }

} //end of class

function compare_links($ar1,$ar2) {
  return $ar1["links"] - $ar2["links"];
}

function compare_backlinks($ar1,$ar2) {
  return $ar1["backlinks"] - $ar2["backlinks"];
}

function r_compare_links($ar1,$ar2) {
  return $ar2["links"] - $ar1["links"];
}

function r_compare_backlinks($ar1,$ar2) {
  return $ar2["backlinks"] - $ar1["backlinks"];
}

function compare_images($ar1,$ar2) {
  return $ar1["images"] - $ar2["images"];
}

function r_compare_images($ar1,$ar2) {
  return $ar2["images"] - $ar1["images"];
}
function compare_files($ar1,$ar2) {
  return $ar1["files"] - $ar2["files"];
}

function r_compare_files($ar1,$ar2) {
  return $ar2["files"] - $ar1["files"];
}

function compare_versions($ar1,$ar2) {
  return $ar1["versions"] - $ar2["versions"];
}

function r_compare_versions($ar1,$ar2) {
  return $ar2["versions"] - $ar1["versions"];
}

function compare_changed($ar1, $ar2) {
  return $ar1["lastChanged"] - $ar2["lastChanged"];
}

function r_compare_changed($ar1, $ar2) {
  return $ar2["lastChanged"] - $ar1["lastChanged"];
}


function chkgd2() {
  if (!isset($_SESSION['havegd2'])) {
#   TODO test this logic in PHP 4.3
#   if (version_compare(phpversion(), "4.3.0") >= 0) {
#     $_SESSION['havegd2'] = true;
#   } else {
      ob_start();
      phpinfo(INFO_MODULES);
      $_SESSION['havegd2'] = preg_match('/GD Version.*2.0/', ob_get_contents());
      ob_end_clean();
#    }
  }
  return $_SESSION['havegd2'];
}


function httpScheme() {
  return 'http' . ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? 's' : '');
}

function httpPrefix() {
  if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) {
    $rv = 'https://' . $_SERVER['SERVER_NAME'];
    if ($_SERVER['SERVER_PORT'] != 443)
      $rv .= ':' . $_SERVER['SERVER_PORT'];
  } else {
    $rv = 'http://' . $_SERVER['SERVER_NAME'];
    if ($_SERVER['SERVER_PORT'] != 80)
      $rv .= ':' . $_SERVER['SERVER_PORT'];
  }
  return $rv;
}

?>
