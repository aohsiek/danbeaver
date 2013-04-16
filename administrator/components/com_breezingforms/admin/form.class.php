<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.7.3
* @package BreezingForms
* @copyright (C) 2008-2011 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once($ff_admpath.'/admin/form.html.php');

function resetQuickModeDbId( &$dataObject ){
    $db = JFactory::getDBO();

    if (isset($dataObject['attributes']) && isset($dataObject['properties'])) {
        $mdata = $dataObject['properties'];
        if ($mdata['type'] == 'element') {
            //print_r($mdata);
            //exit;
            if (!isset($mdata['validationFunctionName'])) {
                $mdata['validationFunctionName'] = '';
            }
            $db->setQuery("Select id From #__facileforms_scripts Where `name` = " . $db->Quote($mdata['validationFunctionName']) . " Limit 1");
            $id = $db->loadResult();
            if ($id) {
                $mdata['validationId'] = $id;
            } else {
                $mdata['validationId'] = 0;
            }
            if (!isset($mdata['initScript'])) {
                $mdata['initScript'] = '';
            }
            $db->setQuery("Select id From #__facileforms_scripts Where `name` = " . $db->Quote($mdata['initScript']) . " Limit 1");
            $id = $db->loadResult();
            if ($id) {
                $mdata['initId'] = $id;
            } else {
                $mdata['initId'] = 0;
            }
            if (!isset($mdata['actionFunctionName'])) {
                $mdata['actionFunctionName'] = '';
            }
            $db->setQuery("Select id From #__facileforms_scripts Where `name` = " . $db->Quote($mdata['actionFunctionName']) . " Limit 1");
            $id = $db->loadResult();
            if ($id) {
                $mdata['actionId'] = $id;
            } else {
                $mdata['actionId'] = 0;
            }
            $mdata['dbId'] = 0;
            $dataObject['properties'] = $mdata;
        }
    }

    if (isset($dataObject['children']) && count($dataObject['children']) != 0) {
        $childrenAmount = count($dataObject['children']);
        for ($i = 0; $i < $childrenAmount; $i++) {
            resetQuickModeDbId($dataObject['children'][$i]);
        }
    }
}

class facileFormsForm
{
	function edit($option, $tabpane, $pkg, $ids, $caller)
	{
		global $database;
		$database = JFactory::getDBO();
		$row = new facileFormsForms($database);
		if ($ids[0]) {
			$row->load($ids[0]);
		} else {
			$row->package = $pkg;
			$row->class1 = 'content_outline';
			$row->width = 400;
			$row->widthmode = 0;
			$row->height = 500;
			$row->heightmode = 0;
			$row->pages = 1;
			$row->emailntf = 1;
                        $row->mb_emailntf = 1;
			$row->emaillog = 1;
                        $row->mb_emaillog = 1;
			$row->emailxml = 0;
                        $row->mb_emailxml = 0;
			$row->dblog = 1;
			$row->script1cond = 0;
			$row->script2cond = 0;
			$row->piece1cond = 0;
			$row->piece2cond = 0;
			$row->piece3cond = 0;
			$row->piece4cond = 0;
			$row->published = 1;
			$row->runmode = 0;
			$row->prevmode = 2;
			$row->prevwidth = 400;
			$row->custom_mail_subject = '';
                        $row->mb_custom_mail_subject = '';
                        $row->alt_mailfrom = '';
                        $row->mb_alt_mailfrom = '';
                        $row->alt_fromname = '';
                        $row->mb_alt_fromname = '';
                        $row->email_type = 0;
                        $row->mb_email_type = 0;
                        $row->email_custom_html = 0;
                        $row->mb_email_custom_html = 0;
                        $row->email_custom_template = '';
                        $row->mb_email_custom_template = '';
			$database->setQuery("select max(ordering)+1 from #__facileforms_forms");
			$row->ordering = $database->loadResult();
		} // if

		$lists = array();

		$database->setQuery(
			"select id, concat(package,'::',name) as text ".
			"from #__facileforms_scripts ".
			"where published=1 and type='Form Init' ".
			"order by text, id desc"
		);
		$lists['init'] = $database->loadObjectList();

		$database->setQuery(
			"select id, concat(package,'::',name) as text ".
			"from #__facileforms_scripts ".
			"where published=1 and type='Form Submitted' ".
			"order by text, id desc"
		);
		$lists['submitted'] = $database->loadObjectList();

		$database->setQuery(
			"select id, concat(package,'::',name) as text ".
			"from #__facileforms_pieces ".
			"where published=1 and type='Before Form' ".
			"order by text, id desc"
		);
		$lists['piece1'] = $database->loadObjectList();

		$database->setQuery(
			"select id, concat(package,'::',name) as text ".
			"from #__facileforms_pieces ".
			"where published=1 and type='After Form' ".
			"order by text, id desc"
		);
		$lists['piece2'] = $database->loadObjectList();

		$database->setQuery(
			"select id, concat(package,'::',name) as text ".
			"from #__facileforms_pieces ".
			"where published=1 and type='Begin Submit' ".
			"order by text, id desc"
		);
		$lists['piece3'] = $database->loadObjectList();

		$database->setQuery(
			"select id, concat(package,'::',name) as text ".
			"from #__facileforms_pieces ".
			"where published=1 and type='End Submit' ".
			"order by text, id desc"
		);
		$lists['piece4'] = $database->loadObjectList();

		$order =
			JHTML::_('list.genericordering',
				"select ordering as value, title as text ".
				"from #__facileforms_forms ".
				"where package = '$pkg' ".
				"order by ordering"
			);
		$lists['ordering'] =
			JHTML::_('select.genericlist', 
				$order, 'ordering', 'class="inputbox" size="1"',
				'value', 'text', intval($row->ordering)
			);

		HTML_facileFormsForm::edit($option, $tabpane, $pkg, $row, $lists, $caller);
	} // edit

	function save($option, $pkg, $caller, $nonclassic, $quickmode)
	{
		global $database;
		$database = JFactory::getDBO();
		$row = new facileFormsForms($database);

		// bind it to the table
		if (!$row->bind($_POST)) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		} // if

		// store it in the db
		if (!$row->store()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		} // if
		$row->reorder( "" );
                
                if (trim($caller) == '') $caller = "index.php?option=$option&act=manageforms&pkg=$pkg";
		
		if(!$nonclassic){
			JFactory::getApplication()->redirect($caller, BFText::_('COM_BREEZINGFORMS_FORMS_SAVED'));
		} else {
			
			ob_start();
			ob_end_clean();
			if(!$quickmode){
				echo '<html><head><script>parent.SqueezeBox.close();</script></head><body></body></html>';
			} else {
				echo '<html><head><script>parent.location.href="index.php?option=com_breezingforms&act=quickmode&formName='.$row->name.'&form='.$row->id.'"</script></head><body></body></html>';
			}
			exit;
		}
	} // save

	function cancel($option, $pkg, $caller)
	{
		if (trim($caller) == '') $caller = "index.php?option=$option&act=manageforms&pkg=$pkg";
		JFactory::getApplication()->redirect($caller);
	} // cancel

	function copy($option, $pkg, $ids)
	{
		global $database;
                
                require_once(JPATH_SITE.'/administrator/components/com_breezingforms/admin/quickmode.class.php');
                require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Decoder.php');
                require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Encoder.php');
                
                $database = JFactory::getDBO();
		$total = count($ids);
		$row = new facileFormsForms($database);
		$elem = new facileFormsElements($database);
		if (count($ids)) foreach ($ids as $id) {
			$row->load(intval($id));
			$row->id       = NULL;
			$row->ordering = 999999;
                        $row->title    = 'Copy of ' . $row->title;
                        $row->name     = 'copy_' . $row->name;
			$row->store();
			$row->reorder('');
                        
                        $database->setQuery("select id from #__facileforms_elements where form=$id");
			$eids = $database->loadObjectList();
			for($i = 0; $i < count($eids); $i++) {
				$eid = $eids[$i];
				$elem->load(intval($eid->id));
				$elem->id      = NULL;
				$elem->form    = $row->id;
				$elem->store();
			} // for
                        
                        // resetting easy and quickmode database ids
                        JFactory::getDBO()->setQuery("Select template_areas, template_code_processed, template_code From #__facileforms_forms Where id = " . intval($row->$id));
                        $row_ = JFactory::getDBO()->loadObject();
                        
                        if (trim($row_->template_code) != '') {
                            $areas = Zend_Json::decode($row_->template_areas);
                            $i = 0;
                            foreach ($areas As $area) {
                                $j = 0;
                                foreach ($area['elements'] As $element) {
                                    $areas[$i]['elements'][$j]['dbId'] = 0;
                                    $j++;
                                }
                                $i++;
                            }

                            $template_areas = Zend_Json::encode($areas);
                            $template_code = $row_->template_code;

                            if ($row_->template_code_processed == 'QuickMode') {
                                $dataObject = Zend_Json::decode(base64_decode($row_->template_code));
                                resetQuickModeDbId($dataObject);
                                $template_code = base64_encode(Zend_Json::encode($dataObject));
                            }

                            JFactory::getDBO()->setQuery("Update #__facileforms_forms Set template_code = " . JFactory::getDBO()->Quote($template_code) . ", template_areas = " . JFactory::getDBO()->Quote($template_areas) . " Where id = " . intval($id));
                            JFactory::getDBO()->query();

                            if ($row_ && $row_->template_code_processed == 'QuickMode') {
                                $quickMode = new QuickMode();
                                $quickMode->save(
                                        $id, Zend_Json::decode(base64_decode($template_code))
                                );
                            }
                        }
                        // reset end
		} // foreach
		$msg = $total.' '.BFText::_('COM_BREEZINGFORMS_FORMS_SUCOPIED');
		JFactory::getApplication()->redirect("index.php?option=$option&act=manageforms&pkg=$pkg&mosmsg=$msg");
	} // copy

        function del($option, $pkg, $ids)
	{
		global $database;
		$database = JFactory::getDBO();
		if (count($ids)) {
			$ids = implode(',', $ids);
			$database->setQuery("delete from #__facileforms_elements where form in ($ids)");
			if (!$database->query()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			} // if
			$database->setQuery("delete from #__facileforms_forms where id in ($ids)");
			if (!$database->query()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			} // if
		} // if
		JFactory::getApplication()->redirect("index.php?option=$option&act=manageforms&pkg=$pkg");
	} // del

	function order($option, $pkg, $ids, $inc)
	{
		global $database;
		$database = JFactory::getDBO();
		$row = new facileFormsForms($database);
		$row->load($ids[0]);
		$row->move($inc, "package=".$database->Quote($pkg)."" );
		JFactory::getApplication()->redirect("index.php?option=$option&act=manageforms&pkg=$pkg");
	} // order

	function publish($option, $pkg, $ids, $publish)
	{
		global $database, $my;
		$database = JFactory::getDBO();
		$ids = implode( ',', $ids );
		$database->setQuery(
			"update #__facileforms_forms set published='$publish' where id in ($ids)"
		);
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		} // if
		JFactory::getApplication()->redirect( "index.php?option=$option&act=manageforms&pkg=$pkg" );
	} // publish

	function listitems($option, $pkg)
	{
		global $database;
		$database = JFactory::getDBO();
		$database->setQuery(
			"select distinct package as name ".
			"from #__facileforms_forms ".
			"where package is not null and package!='' ".
			"order by name"
		);
		$pkgs = $database->loadObjectList();
		if ($database->getErrorNum()) { echo $database->stderr(); return false; }
		$pkgok = $pkg=='';
		if (!$pkgok && count($pkgs)) foreach ($pkgs as $p) if ($p->name==$pkg) { $pkgok = true; break; }
		if (!$pkgok) $pkg = '';
		$pkglist = array();
		$pkglist[] = array($pkg=='', '');
		if (count($pkgs)) foreach ($pkgs as $p) $pkglist[] = array($p->name==$pkg, $p->name);

		$database->setQuery(
			"select * from #__facileforms_forms ".
			"where package = ".$database->Quote($pkg)." ".
			"order by ordering, id"
		);
		$rows = $database->loadObjectList();
		if ($database->getErrorNum()) {
			echo $database->stderr();
			return false;
		} // if
		HTML_facileFormsForm::listitems($option, $rows, $pkglist);
	} // listitems

} // class facileFormsForm
?>