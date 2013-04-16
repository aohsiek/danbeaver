<?php

defined('_JEXEC') or die('Restricted access');

JToolBarHelper::title(JText::_('wbty_jplayer'), 'generic.png');



$db =&JFactory::getDBO();



$query = "SELECT max(ordering) FROM jos_wbty_jplayer";

$db->SetQuery($query);

$maxorder = $db->loadResult();

	

	$query= "SELECT * FROM #__wbty_jplayer ORDER BY ordering";

	$db->setQuery($query);

	$wbty_jplayerlist = $db->loadAssocList();

	echo "<h2>Songs currently in the database</h2>";

	echo "<table width='90%' align='center'><tr><td width='20%'><h2>Title</h2></td><td width='60%'><h2>Location</h2></td><td colspan='3'><h2>Order</h2><td><h2>Tools</h2></td></tr>";

	

	for ($i=0, $n=count($wbty_jplayerlist); $i<$n; $i++) {

		

		echo "<tr><td>" . $wbty_jplayerlist[$i]['title'] . "</a></td><td>" . $wbty_jplayerlist[$i]['location'] . "</td>";

		if ($wbty_jplayerlist[$i]['ordering']!=1) {

			echo "<td><a href='index.php?option=com_wbty_jplayer&task=orderup&id=" . $wbty_jplayerlist[$i]['id'] . "'>up</a></td>";

		} else {

			echo "<td></td>";

		}

		echo "<td>".$wbty_jplayerlist[$i]['ordering']."</td>";

		if ($wbty_jplayerlist[$i]['ordering']!=$maxorder) {

			echo "<td><a href='index.php?option=com_wbty_jplayer&task=orderdown&id=" . $wbty_jplayerlist[$i]['id'] . "'>down</a></td>";

		} else {

			echo "<td></td>";

		}

		echo "<td><a href='index.php?option=com_wbty_jplayer&task=edit&id=" . $wbty_jplayerlist[$i]['id'] . "'>Edit</a> &nbsp; | &nbsp; <a href='index.php?option=com_wbty_jplayer&task=remove&id=" . $wbty_jplayerlist[$i]['id'] . "'>Remove</a></td></tr>";

	}

	echo "</table>";

?>

<form action = "index.php" method =  "post" id = "adminForm" name = "adminForm" />

 <input type = "hidden" name = "task" value = "" />

 <input type = "hidden" name = "option" value = "com_wbty_jplayer" />

</form>

