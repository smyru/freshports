<?
	# $Id: files.php,v 1.1.2.3 2002-01-07 13:08:40 dan Exp $
	#
	# Copyright (c) 1998-2001 DVL Software Limited

	require("./include/common.php");
	require("./include/freshports.php");
	require("./include/databaselogin.php");
	require("./include/getvalues.php");

	if (!$id || $id != strval(intval($id))) {
		$id = 0;
	}

$sql = "select element_pathname(element.id) as pathname, commit_log_port_elements.commit_log_id, " .
	   "commit_log_port_elements.port_id, " .
       "commit_log_elements.change_type, element.name as filename, categories.name as category, commit_log.committer, commit_log.commit_date, " .
       "commit_log.description, B.name as port, commit_log_elements.revision_name as revision_name " .
       "from commit_log, ports, categories, element, commit_log_port_elements, commit_log_elements, element B " .
       "where commit_log.id                                  = $id " .
	   "  and commit_log_port_elements.commit_log_id         = commit_log.id " .
	   "  and commit_log_port_elements.commit_log_element_id = commit_log_elements.id " .
       "  and commit_log_elements.element_id                 = element.id " .
       "  and commit_log_port_elements.port_id               = ports.id " .
       "  and ports.category_id                              = categories.id " .
       "  and ports.element_id                               = B.id " .
       "order by 1 limit 30";

#echo $sql;

$result = pg_exec($db, $sql);

if (!$result) {
   print pg_errormessage() . "<br>\n";
   exit;
} else {

	$i = 0;
	$NumRows = pg_numrows($result);
	while ($myrow = pg_fetch_array($result, $i)) {
//		echo "<tr><td>" . $myrow["port_id"] . "</td><td>" . $myrow["port"] . "</td></tr>";
		$rows[$i] = $myrow;
		$i++;
        if ($i >  $NumRows - 1) {
            break;
		}
	}

	$myrow = $rows[0];
	$PathNamePrefixToRemove = '/ports/' . $myrow["category"] . '/' . $myrow["port"] . '/';

	freshports_Start($myrow["category"] . '/' . $myrow["port"] . " - commit details",
               "freshports - new ports, applications",
               "FreeBSD, index, applications, ports");

?>

<table width="<? echo $TableWidth ?>" border="0" ALIGN="center">
<tr><td colspan="2">Welcome to the freshports.org where you can find the latest information on your favourite
ports.
</td></tr>
  <tr>
    <td colspan="3">
This page shows the files associated with one port for a given commit.
    </td>
  </tr>
<tr><td valign="top" width="100%">
<?
   echo '<table border="1" width="100%" CELLSPACING="0" CELLPADDING="5"bordercolor="#a2a2a2" bordercolordark="#a2a2a2" bordercolorlight="#a2a2a2">' . "\n";
   echo '<tr height="20"><td colspan="3" bgcolor="#AD0040"><font color="#FFFFFF" size="+1">Commit Details</font></td></tr>' . "\n";
   echo "<tr><td><b>Date</b></td><td><b>Committer</b></td><td><b>Description</b></td></tr>\n";      

   echo "<tr>";
   echo "    <td valign='top'><font size='-1'>" . $myrow["commit_date"]        . "</font></td>\n";
   echo "    <td valign='top'>" . $myrow["committer"]          . "</td>\n";
   echo '    <td valign="top">' . $myrow["description"] . "</td>\n";
   echo "</tr>";
?>

</TABLE>

<BR>

<table border="1" width="100%" CELLSPACING="0" CELLPADDING="5"bordercolor="#a2a2a2" bordercolordark="#a2a2a2" bordercolorlight="#a2a2a2">
<?

   echo '<tr height="20"><td colspan="3" bgcolor="#AD0040"><font color="#FFFFFF"><font size="+1">';

	switch ($NumRows) {
		case 0:
			echo 'no files found';
			break;

		case 1:
			echo '1 file found';
			break;

		default:
			echo $i . ' files found';
	}

	echo  '</font></td></tr>';
   ?>
   <tr>
     <td><b>Action</b></td><TD><B>Revision</B></TD><td colspan="2"><b>File</b></td>
   </tr>
   <?

#   $NumRows = $i;
	for ($i = 0; $i < $NumRows; $i++) {
		$myrow = $rows[$i];
		echo "<tr>\n";

		switch ($myrow["change_type"]) {
			case "M":
				$Change_Type = "modify";
				break;

			case "A":
				$Change_Type = "import"; 
				break;

			case "R":
				$Change_Type = "remove"; 
				break;

			default:
				$Change_Type = $myrow["change_type"] ; 
		}

		echo "  <td>" . $Change_Type . "</td>";
		echo "  <TD>" . $myrow["revision_name"] . "</TD>";
		echo '  <td colspan="2"><a href="' . $freshports_CVS_URL . $myrow["pathname"] . '">' .
              '<img src="/images/logs.gif" alt="Changes to this file" border="0" WIDTH="17" HEIGHT="20" hspace="2"></a>';

		echo str_replace($PathNamePrefixToRemove, '', $myrow["pathname"]) . "</td>";
		echo "</tr>\n";
	}
}

</script>
</table>
</td>
  <td valign="top" width="*">
   <? include("./include/side-bars.php") ?>
 </td>
</tr>
</table>
</tr>
</table>
<? include("./include/footer.php") ?>
</body>
</html>
