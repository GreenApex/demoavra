<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
@ob_start();
@session_start();
@error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED ^ E_STRICT);
ini_set('display_errors', '1');

$USER_ID = $_REQUEST['USER_ID'];
$STR_DATE = $_REQUEST['STR_DATE'] ;
$END_DATE = $_REQUEST['END_DATE'] ;

$UC_DATE = array();
$UC_TIME_DELTA = array();
$UC_ATT_WORKING = array();
$UC_ACTION = array();
$UC_REACTION_TIME = array();
$UC_INCRESE = 0;
$UC_TIME_DELTAS = array();
$UC_ATT_WORKINGS = array();
$UC_INCRESE = array();

if (!empty($USER_ID) && !empty($STR_DATE) && !empty($END_DATE))
{

  $conn = getConnection();

   // Set timezone
      date_default_timezone_set('UTC');

      $i= 0;
  while (strtotime($STR_DATE) <= strtotime($END_DATE)) {
  $i=$i+ 1;
  $sql = "SELECT CAST(UC_DATE AS DATE) as UC_DATE,UC_TIME_DELTA,UC_ATT_WORKING,UC_ACTION,UC_REACTION_TIME,TIME_TO_SEC(UC_TIME_DELTA)/60  AS UC_TIME_DELTAS,TIME_TO_SEC(UC_ATT_WORKING)/60  AS UC_ATT_WORKINGS FROM USER_CONFIRMATION where date(UC_DATE) = '".$STR_DATE."'  AND UC_USER_ID =".$USER_ID;
  $STR_DATE = date ("Y-m-d", strtotime("+1 day", strtotime($STR_DATE)));
  $result = $conn->query($sql);

  if ($result->num_rows > 0)
  {

    // Fetch one and one row
    while ($row = $result->fetch_assoc())
    {
      $UC_DATE[$i] = $row["UC_DATE"];
      $UC_TIME_DELTA[$i] = $row["UC_TIME_DELTA"];
      $UC_ATT_WORKING[$i] = $row["UC_ATT_WORKING"];
      $UC_ACTION[$i] = $row["UC_ACTION"];
      $UC_REACTION_TIME[$i] = $row["UC_REACTION_TIME"];
      $UC_TIME_DELTAS[$i] = $row["UC_TIME_DELTAS"];
      $UC_ATT_WORKINGS[$i] = $row["UC_ATT_WORKINGS"];

      if($UC_TIME_DELTAS[$i] == $UC_ATT_WORKINGS[$i])
      {
       $UC_INCRESE[$i] = "0";
      }
      else if($UC_TIME_DELTAS[$i] > $UC_ATT_WORKINGS[$i] )
      {
            $UC_INCRESE[$i] = (($UC_TIME_DELTAS[$i]-$UC_ATT_WORKINGS[$i])/$UC_ATT_WORKINGS[$i])*100;
       }else if($UC_TIME_DELTAS[$i] < $UC_ATT_WORKINGS[$i] )
            {
                  $UC_INCRESE[$i] = (($UC_TIME_DELTAS[$i]-$UC_ATT_WORKINGS[$i])/$UC_ATT_WORKINGS[$i])*100;
                   }

      else{
            //echo "0 result";
      }
             $whereA [] = array("UC_DATE" => $UC_DATE[$i], "UC_TIME_DELTA" => $UC_TIME_DELTA[$i], "UC_ATT_WORKING" => $UC_ATT_WORKING[$i], "UC_ACTION" => $UC_ACTION[$i], "UC_REACTION_TIME" => $UC_REACTION_TIME[$i], "UC_INCRESE" => round($UC_INCRESE[$i],2));

    }

    }


  }
 $allReturnData = array();
          $allReturnData['USER_INFROMATION'] = $whereA;
          if (!empty($allReturnData))
          {
              jsonResponce(array('status' => 1, 'msg' => "Records found", 'data' => $allReturnData));
          }
          else
          {
             jsonResponce(array('status' => 0, 'msg' => "Records not found"));
          }
}
else
{
  jsonResponce(array('status' => 0, 'msg' => "Error in call api or some paramitter missing"));
}

function jsonResponce($array = array())
{
  echo json_encode($array);
  exit;
}

function getConnection()
{

  $servername = "localhost:3306";
  $username = "root";
  $password = "root";
  $dbname = "AvraQuality";
/*
  $servername = "localhost";
  $username = "dev_avra";
  $password = "green123$";
  $dbname = "AvraQuality";
*/

  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error)
  {
    jsonResponce(array('status' => 0, 'msg' => "Connection failed: " . $conn->connect_error));
  }
  return $conn;
}