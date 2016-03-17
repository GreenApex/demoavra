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

$ATT_ID = array();
$ATT_USER_ID = array();
$ATT_DATE = array();
$ATT_STR_DATE = array();
$ATT_END_DATE = array();
$starttime = array();
$endtime = array();
$timediff = array();
$beacon = array();
$beacon_desc = array();
if (!empty($USER_ID) && !empty($STR_DATE) && !empty($END_DATE))
{

  $whereD = array();
  $whereS = array();

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

    $whereD[] = "D_STR_DATE BETWEEN '" . $STR_DATE . "' AND '" . $END_DATE . "'";
    $whereD[] = "D_END_DATE BETWEEN '" . $STR_DATE . "' AND '" . $END_DATE . "'";

    $whereS[] = "SAP_STR_DATE BETWEEN '" . $STR_DATE . "' AND '" . $END_DATE . "'";
    $whereS[] = "SAP_END_DATE BETWEEN '" . $STR_DATE . "' AND '" . $END_DATE . "'";
  }
  else if (!empty($STR_DATE))
  {
    $STR_DATE = date('Y-m-d', strtotime($STR_DATE));

    $whereD[] = "D_STR_DATE ='" . $STR_DATE . "'";
    $whereD[] = "D_END_DATE ='" . $STR_DATE . "'";

    $whereS[] = "SAP_STR_DATE ='" . $STR_DATE . "'";
    $whereS[] = "SAP_END_DATE ='" . $STR_DATE . "'";
  }
  else if (!empty($END_DATE))
  {
    $END_DATE = date('Y-m-d', strtotime($END_DATE));

    $whereD[] = "D_STR_DATE ='" . $END_DATE . "'";
    $whereD[] = "D_END_DATE ='" . $END_DATE . "'";

    $whereS[] = "SAP_STR_DATE ='" . $END_DATE . "'";
    $whereS[] = "SAP_END_DATE ='" . $END_DATE . "'";
  }

  $conn = getConnection();

    // Set timezone
    date_default_timezone_set('UTC');

$i= 0;

    while (strtotime($STR_DATE) <= strtotime($END_DATE)) {
        $i=$i+ 1;
        $sqlA = "SELECT A.ATT_STR_DATE as start,B.ATT_END_DATE as end,CAST(A.ATT_STR_DATE as DATE) as date,timediff(CAST(B.ATT_END_DATE as TIME), CAST(A.ATT_STR_DATE as TIME)) as timediff, B.ATT_UI_BEACON as beacon FROM ATTENDANCE A, ATTENDANCE B where CAST(A.ATT_STR_DATE as DATE)= CAST(B.ATT_END_DATE as DATE) AND A.ATT_ID
                 != B.ATT_ID AND CAST(A.ATT_STR_DATE as DATE)='".$STR_DATE."' AND A.ATT_ID = (B.ATT_ID - 1) AND A.ATT_USER_ID =".$USER_ID;
        $STR_DATE = date ("Y-m-d", strtotime("+1 day", strtotime($STR_DATE)));
        $result = $conn->query($sqlA);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $ATT_DATE[$i] = $row["date"];
                $ATT_STR_DATE[$i]= $row["start"];
                $ATT_END_DATE[$i]= $row["end"];
                $timediff[$i] = $row["timediff"];
                $beacon[$i]= $row["beacon"];
                $sqlB= "SELECT BE_UI_DESCRIPTION as description FROM BEACON_MASTER where BE_UI_ID='".$beacon[$i]."'";
                $result_beacon = $conn->query($sqlB);
                if ($result_beacon->num_rows > 0) {
                            // output data of each row
                            while($row = $result_beacon->fetch_assoc()) {
                                $beacon_desc[$i]= $row["description"];
                            }
                }else
                {
                    $beacon_desc[$i]= null;
                }

            $whereA[] = array("ATT_DATE" => $ATT_DATE[$i],"ATT_STR_DATE" => $ATT_STR_DATE[$i],"ATT_END_DATE" => $ATT_END_DATE[$i] ,"ATT_WORKING" => $timediff[$i]  ,"BEACON_DESCRIPTION" => $beacon_desc[$i]);

            }

            }
        else{
            //echo "0 results";
        }
    }

  $sqlD = "SELECT * FROM `DEFECTS` WHERE  " . implode(' AND ', $whereD);
  $deffectsData = getDataFromQuery($conn, $sqlD);


  $sqlS = "SELECT * FROM `SAP_ACTIVITY_LOG` WHERE  " . implode(' AND ', $whereS);
  $sap_activity_logData = getDataFromQuery($conn, $sqlS);


  $allReturnData = array();
  $allReturnData['ATTENDANCE'] = $whereA;
  $allReturnData['DEFECTS'] = $deffectsData;
  $allReturnData['SAP_ACTIVITY_LOG'] = $sap_activity_logData;

  if (!empty($allReturnData) && (count($whereA) || count($deffectsData) || count($sap_activity_logData) ))
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
