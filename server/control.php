<?php
// Define variables
$mqtt_server	= '127.0.0.1';
$mqtt_port		= 1883;
$mqtt_user		= 'user';
$mqtt_pass		= 'pass';
$max_act		= 3;	// Maximum number of actions allowed per user
$time_act		= 24;	// Time frame for the limit of actions
$topic			= 'devices/cmd';
$warn_msg		= 'You have reached the maximum allowed actions!';

/* Note : Clients and/or actions can be changed/added at line 138 */

// Begin
include ('dbcon.php');
session_start();
if (!isset($_SESSION['user_id']) || (trim($_SESSION['user_id']) == '')) {
    header("location: index.php");
    exit();
}
$session_id = $_SESSION['user_id'];
// Log out
if (isset($_GET["logout"])) {
    session_destroy();
    header('location:index.php');
}
$result	= mysqli_query($con, "SELECT * FROM users WHERE user_id='$session_id'") or die('Error In Session');
$row	= mysqli_fetch_array($result);
$un		= $row['username'];

// Update log via ajax after action
if (isset($_GET["log"])) {
	$lr		= getLimit($con, $un, $time_act);
	$warn	= ($lr >= $max_act) ? $warn_msg : '';
    echo getLog($con, $warn);
    exit;
}

if (!empty($_POST['cid']) && !empty($_POST['cmd'])) {
	$cid	 = filter_input(INPUT_POST, 'cid', FILTER_SANITIZE_STRING);
	$cmd	 = filter_input(INPUT_POST, 'cmd', FILTER_SANITIZE_STRING);
    $payload = $cid . '_' . $cmd;
    // Client logic
		/* todo ... */
    //
	$limcount = getLimit($con, $un, $time_act);
	if  ($limcount < $max_act) {
    	require ("MQTTClient.php");
    	$client = new MQTTClient($mqtt_server, $mqtt_port);
    	$client->setAuthentication($mqtt_user, $mqtt_pass);
    	$success = $client->sendConnect("phpKontrol");
    	if ($success) {
    	    $client->sendPublish($topic, $payload);
    	    $client->sendDisconnect();
    	}
    	$client->close();
    	// Store action to DB
    	$sql = 'INSERT INTO `action_log`(`data`, `client`, `user`, `cmd`) VALUES (' .
    	    time() . ',"' . $cid . '","' . $un . '","' . $cmd . '")';
    	$salveaza = mysqli_query($con, $sql) or die('Error In Session');
	}
}
// Read limits
function getLimit($con, $user, $time_act) {
	$lq = mysqli_query($con, 'SELECT COUNT(*) AS total FROM `action_log` WHERE `user` = "'. $user .'" AND `data` > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL '. $time_act .' HOUR))');
	$limcount = mysqli_fetch_assoc($lq);
	return $limcount['total'];
}

// Read log by reusing a function
function getLog($con, $msg)
{
    $log_data = mysqli_query($con, "SELECT * FROM `action_log` WHERE `data` > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 MONTH)) ORDER BY id DESC");
    while ($ld = mysqli_fetch_array($log_data)) {
        $data[] = $ld;
    }

    $logdata = '<span id="log">Log</span>' . PHP_EOL . '<ul>' . PHP_EOL;
    if (!empty($msg)) {
    	$logdata .= '<ul style="text-align:center;"><b>' . $msg . '</b></ul>' . PHP_EOL;
    }
    if (!empty($data)) {
        foreach ($data as $logline) {
            $logdata .= '<ul>[' . date("d M y H:i:s", $logline['data']) . '] - ' .
                strtoupper($logline['user']) . ' - ' . $logline['client'] . ' - ' . $logline['cmd'] .
                '</ul>' . PHP_EOL;
        }
        $logdata .= '</ul>' . PHP_EOL;
    } else {
    	$logdata .= '<center>-=[ nothing logged ]=-</center>'. PHP_EOL .'</ul>'. PHP_EOL;
    }
    return $logdata;
}
$logdata = getLog($con, $warn = '');
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=0.8">
	<title>phpKontrol</title>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="web/chosen.jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="web/style.css">
	<link rel="stylesheet" type="text/css" href="web/chosen.min.css">
	<script type="text/javascript">
		$(document).ready(function() {
			$("select").chosen()
			$("#expbtn").click(function() {
			    $(this).val("Please wait...");
			    $(this).prop('disabled', true);
			    $(this).fadeTo("fast", 0.15);
			    setTimeout(function() {
			        $('#expbtn').val("Execute!");
			        $('#expbtn').prop('disabled', false);
			        $('#expbtn').fadeTo("fast", 1);
			    }, 6000);
			    $.ajax({
			        type: 'POST',
			        url: "control.php",
			        data: {cid: $('#cid').val(), cmd: $('#cmd').val()},
			        success: function(){
    					$(".log").load("control.php?log", function() {
							$(".log").fadeIn("slow");
						});
  					},
			    });
			});
		});
	</script>
</head>
<body>
<div class="main-wrapper">
    <center><h3><?php echo $row['name'];?></h3></center>
<div class="center">
	<div id="selector">
		<select id="cid">
			<option value="" selected disabled hidden>Select a client</option>
			<option value="cid_1">Client 1</option>
			<option value="cid_2">Client 2</option>
			<option value="cid_3">Client 3</option>
		</select>
	</div>
	<div id="selector">
		<select id="cmd">
			<option value="" selected disabled hidden>Select a command</option>
			<option value="ON">Start</option>
			<option value="OFF">Stop</option>
		</select>
	</div>
	<div id="btn">
		<input type="button" id="expbtn" value="Execute!">
	</div>
	</div>
	<div class="reminder">
		<p id="lo"><a href="control.php?logout">Log out</a></p>
		<p id="warn"><?php echo $warn; ?></p>
	</div>
<div class="log">
<?php
echo $logdata;
?>
</div>
</div>
</body>
</html>
