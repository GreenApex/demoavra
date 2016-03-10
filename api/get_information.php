<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
@ob_start();
@session_start();
@error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED ^ E_STRICT);
ini_set('display_errors', '1');

$USER_ID = $_REQUEST['USER_ID'] ;
$STR_DATE = $_REQUEST['STR_DATE'] ;
$END_DATE = $_REQUEST['END_DATE'] ;


if (!empty($USER_ID) && !empty($STR_DATE) && !empty($END_DATE))
{

  $whereA = array();
  $whereD = array();
  $whereS = array();

  $whereA[] = "ATT_USER_ID ='" . $USER_ID . "'";
  $whereD[] = "D_USER_ID ='" . $USER_ID . "'";
  $whereS[] = "SAL_USER_ID ='" . $USER_ID . "'";

  if (!empty($STR_DATE) && !empty($END_DATE))
  {
    $STR_DATE = date('Y-m-d', strtotime($STR_DATE));
    $END_DATE = date('Y-m-d', strtotime($END_DATE));
    if (strtotime($STR_DATE) >= strtotime($END_DATE))
    {
      $STR_DATE_TEMP = $STR_DATE;
      $STR_DATE = $END_DATE;
      $END_DATE = $STR_DATE_TEMP;
    }
    $whereA[] = "ATT_STR_DATE BETWEEN '" . $STR_DATE . "' AND '" . $END_DATE . "'";
    $whereA[] = "ATT_END_DATE BETWEEN '" . $STR_DATE . "' AND '" . $END_DATE . "'";

    $whereD[] = "D_STR_DATE BETWEEN '" . $STR_DATE . "' AND '" . $END_DATE . "'";
    $whereD[] = "D_END_DATE BETWEEN '" . $STR_DATE . "' AND '" . $END_DATE . "'";

    $whereS[] = "SAP_STR_DATE BETWEEN '" . $STR_DATE . "' AND '" . $END_DATE . "'";
    $whereS[] = "SAP_END_DATE BETWEEN '" . $STR_DATE . "' AND '" . $END_DATE . "'";
  }
  else if (!empty($STR_DATE))
  {
    $STR_DATE = date('Y-m-d', strtotime($STR_DATE));
    $whereA[] = "ATT_STR_DATE ='" . $STR_DATE . "'";
    $whereA[] = "ATT_END_DATE ='" . $STR_DATE . "'";

    $whereD[] = "D_STR_DATE ='" . $STR_DATE . "'";
    $whereD[] = "D_END_DATE ='" . $STR_DATE . "'";

    $whereS[] = "SAP_STR_DATE ='" . $STR_DATE . "'";
    $whereS[] = "SAP_END_DATE ='" . $STR_DATE . "'";
  }
  else if (!empty($END_DATE))
  {
    $END_DATE = date('Y-m-d', strtotime($END_DATE));
    $whereA[] = "ATT_STR_DATE ='" . $END_DATE . "'";
    $whereA[] = "ATT_END_DATE ='" . $END_DATE . "'";

    $whereD[] = "D_STR_DATE ='" . $END_DATE . "'";
    $whereD[] = "D_END_DATE ='" . $END_DATE . "'";

    $whereS[] = "SAP_STR_DATE ='" . $END_DATE . "'";
    $whereS[] = "SAP_END_DATE ='" . $END_DATE . "'";
  }

  $conn = getConnection();

  $sqlA = "SELECT * FROM `ATTENDANCE` WHERE  " . implode(' AND ', $whereA);
  $attendanceData = getDataFromQuery($conn, $sqlA);

  $sqlD = "SELECT * FROM `DEFECTS` WHERE  " . implode(' AND ', $whereD);
  $deffectsData = getDataFromQuery($conn, $sqlD);


  $sqlS = "SELECT * FROM `SAP_ACTIVITY_LOG` WHERE  " . implode(' AND ', $whereS);
  $sap_activity_logData = getDataFromQuery($conn, $sqlS);


  $allReturnData = array();
  $allReturnData['ATTENDANCE'] = $attendanceData;
  $allReturnData['DEFECTS'] = $deffectsData;
  $allReturnData['SAP_ACTIVITY_LOG'] = $sap_activity_logData;

  if (!empty($allReturnData) && (count($attendanceData) || count($deffectsData) || count($sap_activity_logData) ))
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

  $servername = "localhost";
  $username = "dev_avra";
  $password = "green123$";
  $dbname = "AvraQuality";


  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error)
  {
    jsonResponce(array('status' => 0, 'msg' => "Connection failed: " . $conn->connect_error));
  }
  return $conn;
}

function getDataFromQuery($mysqli, $query)
{
 
  $row = array();
  if ($result = $mysqli->query($query))
  {
    /* fetch object array */
    while ($rowTemp = $result->fetch_assoc())
    {
      $row[] = $rowTemp;
    }
  }
  $result->free();
  return $row;
}
