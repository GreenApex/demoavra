<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
@ob_start();
@session_start();
@error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED ^ E_STRICT);
ini_set('display_errors', '1');

$Detected_Hours = $_REQUEST['DETECTED_HOURS'];
$DEFECTS= $_REQUEST['DEFECTS'];
$STR_DATE = $_REQUEST['STR_DATE'] ;
$END_DATE = $_REQUEST['END_DATE'] ;

$UC_DATE = array();
$D_USER_ID = array();
$D_USER_COUNT = array();
$UC_ACTION = array();
$UC_REACTION_TIME = array();
$UC_INCRESE = 0;
$UC_TIME_DELTAS = array();
$UC_ATT_WORKINGS = array();
$UC_USER_ID = array();

if ( !empty($STR_DATE) && !empty($END_DATE))
{

  $conn = getConnection();
   // Set timezone
      date_default_timezone_set('UTC');

      $i= 0;

$Detected_Hours = $Detected_Hours*3600;

  $sql = "SELECT UC_USER_ID as userid FROM AvraQuality.USER_CONFIRMATION where (TIME_TO_SEC( UC_TIME_DELTA )-TIME_TO_SEC( UC_ATT_WORKING ))>".$Detected_Hours." AND CAST(UC_DATE as DATE) between '".$STR_DATE."' and '".$END_DATE."' group by UC_USER_ID";
  $result = $conn->query($sql);

  if ($result->num_rows > 0)
  {

    // Fetch one and one row
    while ($row = $result->fetch_assoc())
    {
        $i=$i+ 1;
        $UC_USER_ID[$i] = $row["userid"];
        $whereA [] = array("UC_USER_ID" => $UC_USER_ID[$i]);

    }

    }
    $sqlA= "SELECT  count(DEFECT_ID) as count,D_USER_ID as userid FROM DEFECTS where D_ACTION='R'  and CAST(D_STR_DATE as DATE) between '".$STR_DATE."' and '".$END_DATE."' group by D_USER_ID";
    $resultA = $conn->query($sqlA);
if ($resultA->num_rows > 0)
  {

    // Fetch one and one row
    while ($row = $resultA->fetch_assoc())
    {
       $i=$i+ 1;
        $D_USER_ID[$i] = $row["userid"];
        $D_USER_COUNT[$i]= $row["count"];

        if($D_USER_COUNT[$i]<$DEFECTS){
        $whereA [] = array("UC_USER_ID" => $D_USER_ID[$i]);
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

  /*$servername = "localhost";
  $username = "dev_avra";
  $password = "green123$";
  $dbname = "AvraQuality";*/


  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error)
  {
    jsonResponce(array('status' => 0, 'msg' => "Connection failed: " . $conn->connect_error));
  }
  return $conn;
}
