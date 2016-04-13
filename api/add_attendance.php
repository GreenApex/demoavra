<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
@ob_start();
@session_start();
@error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED ^ E_STRICT);
ini_set('display_errors', '1');

$ATT_USER_ID = $_REQUEST['ATT_USER_ID'];
$ATT_STR_DATE = $_REQUEST['ATT_STR_DATE'];
$ATT_END_DATE = $_REQUEST['ATT_END_DATE'];
$ATT_LB_DATE = $_REQUEST['ATT_LB_DATE'];
$ATT_UI_DEVICE = $_REQUEST['ATT_UI_DEVICE'];
$ATT_UI_BEACON = $_REQUEST['ATT_UI_BEACON'];
$street_number = $_REQUEST['street_number'];
$route = $_REQUEST['route'];
$postal_code = $_REQUEST['postal_code'];
$country = $_REQUEST['country'];

if (!empty($ATT_USER_ID) && !empty($ATT_STR_DATE) && !empty($ATT_END_DATE) && !empty($ATT_LB_DATE) &&
!empty($ATT_UI_DEVICE) && !empty($ATT_UI_BEACON))
{

    $dteStart = new DateTime($ATT_STR_DATE);
    $dteEnd = new DateTime($ATT_END_DATE);
    $dteDiff = $dteStart->diff($dteEnd);
    $hours = $dteDiff->h;
    $hours = $hours + ($dteDiff->days * 24);

    $ATT_WORKING = $hours . ':' . $dteDiff->format("%I:%S");

    $conn = getConnection();

    $sql = "INSERT INTO ATTENDANCE (`ATT_USER_ID`,`ATT_STR_DATE`, `ATT_END_DATE`, `ATT_WORKING`, `ATT_LB_DATE`,
      `ATT_UI_DEVICE`, `ATT_UI_BEACON`, `street_number`, `route`, `postal_code`, `country`)
      VALUES ('$ATT_USER_ID','$ATT_STR_DATE', '$ATT_END_DATE', '$ATT_WORKING', '$ATT_LB_DATE',
        '$ATT_UI_DEVICE', '$ATT_UI_BEACON', '$street_number', '$route', '$postal_code', '$country')";

    if ($conn->query($sql) === TRUE)
    {
        $last_id = $conn->insert_id;
        jsonResponce(array('status' => 1, 'msg' => "Record has been saved successfully", 'data' => $last_id));
    }
    else
    {
        jsonResponce(array('status' => 0, 'msg' => "Record not saved!"));
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
