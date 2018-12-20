<?php
$rec_limit = 10;

$conn = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
if(! $conn ) { die('Could not connect: ' . mysql_errno() . '<br>' . mysql_error()); }

$db_selected = mysql_select_db(DB_DATABASE, $conn);
if(! $db_selected ) { die('Could not select database: ' . mysql_error()); }

/* Get total number of records */
$sql = "SELECT count(*) FROM setting ";
$retval = mysql_query( $sql, $conn );
if(! $retval ) { die('Could not get data: ' . mysql_errno() . '<br>' . mysql_error()); }

$row = mysql_fetch_array($retval, MYSQL_NUM );
$rec_count = $row[0];

if( isset($_GET{'page'} ) )
{
   $page = $_GET{'page'} + 1;
   $offset = $rec_limit * $page ;
}
else
{
   $page = 0;
   $offset = 0;
}
$left_rec = $rec_count - ($page * $rec_limit);

$sql = "SELECT * FROM setting ORDER BY `group` ASC, `key` ASC LIMIT $offset, $rec_limit";

$retval = mysql_query( $sql, $conn );
if(! $retval ) { die('Could not get data due to error: ' . mysql_errno() . '<BR>' . mysql_error()); }

// DISPLAY THE DATA IN TABLE
echo '<center><table border="1" style="width: 95%;background:#4080B0;word-wrap: break-word;" cellspacing="5" cellpadding="10">';
echo '<font color="#4080B0"><b>Page ' . ($page+1) . ' out of ' . ceil($rec_count/10) . '</b></font><br><br>';
echo "<td style='background-color:blue;color:#FFF;'><b>Group</b></td><td style='background-color:blue;color:#FFF;'><b>Key</b></td><td style='background-color:blue;color:#FFF;'><b>Value</b></td></tr>";

while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
{
	echo "<tr><td style='background-color:#FFF;color:#000;'>{$row['group']}</td><td style='background-color:#FFF;color:#000;'>{$row['key']}</td><td style='background-color:#FFF;color:#000;max-width:400px;'>{$row['value']}</td></tr>";
} 

if(( $page > 0 ) and ( $page !==  round(($rec_count/10)-2)))
{
   $last = $page - 2;
   //
   $link_string = '<a href="' . $_SERVER['PHP_SELF'] . '?route=' . $_REQUEST['route'] . '&page=-1&token='  . $this->session->data['token'] . '">First 10 records</a>&nbsp;&nbsp;&nbsp;';
   echo $link_string;
   //
   $link_string = '<a href="' . $_SERVER['PHP_SELF'] . '?route=' . $_REQUEST['route'] . '&page=' . $last . '&token='  . $this->session->data['token'] . '">Back 10 records</a>&nbsp;&nbsp;&nbsp;';
   echo $link_string;
	//
   $link_string = '<a href="' . $_SERVER['PHP_SELF'] . '?route=' . $_REQUEST['route'] . '&page=' . $page . '&token='  . $this->session->data['token'] . '">Next 10 records</a>&nbsp;&nbsp;&nbsp;';
   echo $link_string;
   //
   $link_string = '<a href="' . $_SERVER['PHP_SELF'] . '?route=' . $_REQUEST['route'] . '&page=' . round(($rec_count/10)-2) . '&token='  . $this->session->data['token'] . '">Last 10 records</a>&nbsp;&nbsp;&nbsp;';
   echo $link_string;
}
else if( $page == 0 )
{
   $link_string = '<a href="' . $_SERVER['PHP_SELF'] . '?route=' . $_REQUEST['route'] . '&page=' . $page . '&token='  . $this->session->data['token'] . '">Next 10 records</a>&nbsp;&nbsp;&nbsp;';
   echo $link_string;
   //
   $link_string = '<a href="' . $_SERVER['PHP_SELF'] . '?route=' . $_REQUEST['route'] . '&page=' . round(($rec_count/10)-2) . '&token='  . $this->session->data['token'] . '">Last 10 records</a>&nbsp;&nbsp;&nbsp;';
   echo $link_string;
}
else if( $left_rec < $rec_limit )
{
   $last = $page - 2;
   //
   $link_string = '<a href="' . $_SERVER['REQUEST_URI'] . '&page=' . $last . '">Last 10 Records</a>&nbsp;';
   echo $link_string;
   
}
echo '<br><br>';
echo '</table></center>';
?>
