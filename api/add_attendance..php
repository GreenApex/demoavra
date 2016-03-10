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

if (!empty($ATT_USER_ID) && !empty($ATT_STR_DATE) && !empty($ATT_END_DATE))
{

  $dteStart = new DateTime($ATT_STR_DATE);
  $dteEnd = new DateTime($ATT_END_DATE);
  $dteDiff = $dteStart->diff($dteEnd);
  $hours = $dteDiff->h;
  $hours = $hours + ($dteDiff->days * 24);

  $ATT_WORKING = $hours . ':' . $dteDiff->format("%I:%S");

  $conn = getConnection();

  $sql = "INSERT INTO ATTENDANCE (`ATT_USER_ID`,`ATT_STR_DATE`, `ATT_END_DATE`, `ATT_WORKING`) VALUES ('$ATT_USER_ID','$ATT_STR_DATE', '$ATT_END_DATE','$ATT_WORKING')";
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
