<?
	# $Id: missing-port.php,v 1.1.2.14 2002-02-21 23:13:54 dan Exp $
	#
	# Copyright (c) 2001 DVL Software Limited


function freshports_Parse404CategoryPort($REQUEST_URI, $db) {

$Debug=0;


	if ($Debug) echo "you asked for $REQUEST_URI<BR>";

	$result = "";
	$url_Array = explode('/', $REQUEST_URI);
	if (array_count_values($url_Array) >= 1) {
		$CategoryName = AddSlashes($url_Array[1]);
		if (array_count_values($url_Array) >= 2) {
			$PortName = AddSlashes($url_Array[2]);
		}

		if ($Debug) {
			echo "\$CategoryName = '$CategoryName'<BR>";
			echo "\$PortName     = '$PortName'<BR>";
		}


		$CategoryID = freshports_CategoryId($CategoryName, $db);

		if ($Debug) {
			echo "\$CategoryName = '$CategoryName' ($CategoryID)<BR>";
			echo "\$PortName     = '$PortName'<BR>";
		}

		if (IsSet($PortName) && $PortName != '') {
			$element = new Element($db);
			$element->FetchByName("/ports/$CategoryName/$PortName");

			if (IsSet($element->id)) {
				$port = new Port($db);
				GLOBAL $WatchListID;
				$port->FetchByPartialName("/ports/$CategoryName/$PortName", $WatchListID);

				if ($Debug) {
					if (IsSet($port->id)) {
						echo "port was found with id = $port->id<BR>";
					} else {
						echo "that port not found<BR>";
					}
				}
			}
		}

		if (IsSet($CategoryID)) {
#			echo "<A HREF=\"/category.php?category=$CategoryID\">this link</A> should take you to the category details<BR>";
			if (IsSet($port->id)) {
#				echo "This is where you'd see details for port = '$port->id'<BR>";
#				echo "<A HREF=\"/port-description.php?port=$port->id\">this link</A> should take you to the port details<BR>";
#				echo "and short_description = $port->short_description";

				freshports_PortDescription($port);

			} else {
#				if (IsSet($PortName)) {
#					echo "no port found like that in this category";
#				}
				if ($PortName != '' && !IsSet($port->id)) {
					$result = "The <A HREF=\"/$CategoryName/\">category you specified</A> exists but not the port <I>$PortName</I>.";
				} else {
					require("missing-category.php");
					freshports_Category($CategoryID, $db);
				}
			}
		} else {
#			echo "no category '$CategoryName' found";
			$result = "There is no document by that name ('$REQUEST_URI')";
		}
	}

	return $result;
}


function freshports_PortDescription($port) {
	GLOBAL $TableWidth;
	GLOBAL $FreshPortsTitle;

	header("HTTP/1.1 200 OK");
	$Title = $port->category . "/" . $port->port;
	freshports_Start($Title,
	        		"$FreshPortsTitle - new ports, applications",
					"FreeBSD, index, applications, ports");

?>

<TABLE WIDTH="<? echo $TableWidth ?>" BORDER="0" ALIGN="center" VALIGN="top">
<tr><TD VALIGN="top" width="100%">
<TABLE BORDER="1" WIDTH="100%" CELLSPACING="0" CELLPADDING="5" BORDERCOLOR="#a2a2a2" BORDERCOLORDARK="#a2a2a2" BORDERCOLORLIGHT="#a2a2a2">


<? freshports_PageBannerText("Port details"); ?>

<tr><td valign="top" width="100%">

<?
	GLOBAL $DaysMarkedAsNew, $DaysMarkedAsNew, $GlobalHideLastChange, $HideCategory, $HideDescription, $ShowChangesLink, $ShowDescriptionLink, $ShowDownloadPortLink, $ShowEverything, $ShowHomepageLink, $ShowLastChange, $ShowMaintainedBy, $ShowPortCreationDate, $ShowPackageLink, $ShowShortDescription;


$ShowCategories			= 1;
GLOBAL	$ShowDepends;
$ShowDepends			= 1;
$DaysMarkedAsNew= $DaysMarkedAsNew= $GlobalHideLastChange= $ShowChangesLink= $ShowDescriptionLink= $ShowDownloadPortLink= $ShowHomepageLink= $ShowLastChange= $ShowMaintainedBy= $ShowPortCreationDate= $ShowPackageLink= $ShowShortDescription =1;
$HideDescription		= 1;
$ShowEverything			= 1;
$ShowShortDescription	= "Y";
$ShowMaintainedBy		= "Y";
$GlobalHideLastChange	= "Y";
$ShowDescriptionLink	= "N";

	$HTML .= freshports_PortDetails($port, $port->dbh, $DaysMarkedAsNew, $DaysMarkedAsNew, $GlobalHideLastChange, $HideCategory, $HideDescription, $ShowChangesLink, $ShowDescriptionLink, $ShowDownloadPortLink, $ShowEverything, $ShowHomepageLink, $ShowLastChange, $ShowMaintainedBy, $ShowPortCreationDate, $ShowPackageLink, $ShowShortDescription);
	echo $HTML;

	echo '<DL><DD>';
    echo '<PRE CLASS="code">' . convertAllLinks(htmlspecialchars($port->long_description)) . '</PRE>';
	echo "\n</DD>\n</DL>\n";

	echo '</TD></TR></TABLE>';
#	echo 'about to call freshports_PortCommits #############################';

	freshports_PortCommits($port);

?>

</TD>
<TD>
   <? include("./include/side-bars.php") ?>
</TD>
</TR>

</TABLE>

<TABLE WIDTH="<? echo $TableWidth; ?>" BORDER="0" ALIGN="center">
<TR><TD>
<? include("./include/footer.php") ?>
</TD></TR>
</TABLE>

</body>
</html>

<?
}

function freshports_CategoryId($category, $database) {
	#
	# we could improve efficiency here with a cache
	# if we had need...
	#
	$sql = "select * from categories where name = '$category'";
	$result = pg_exec($database, $sql);
	if ($result) {
		$numrows = pg_numrows($result);
		if ($numrows == 1) {
			$myrow = pg_fetch_array ($result, 0);
			$CategoryID = $myrow["id"];
		}
	} else {
		echo 'pg_exec failed: ' . $sql;
	}

	return $CategoryID;
}

?>