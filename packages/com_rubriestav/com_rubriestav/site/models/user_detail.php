<?php
/**
 * @package HelloWorld
 * @version 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * Joomla! is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

//DEVNOTE: import MODEL object class
jimport ( 'joomla.application.component.model' );

class user_detailModeluser_detail extends JModel {
	
	var $_id = null;
	var $_data = null;
	var $_table_prefix = null;
	
	function __construct() {
		parent::__construct ();
		$this->_table_prefix = '#__rubriestav_';
		$array = JRequest::getVar ( 'cid', 0, '', 'array' );
		$this->setId ( ( int ) $array [0] );
	}
	
	function setId($id) {
		$this->_id = $id;
		$this->_data = null;
	}
	
	function getId() {
		return $this->_id;
	}
	
	function &getData() {
		if ($this->_loadData ()) {
		} else
			$this->_initData ();
		return $this->_data;
	}
	
	/**
	 * Il metodo check serve per vedere se il record � gi� occupato da un altro utente
	 */
	
	function checkout($uid = null) {
		$this->_loadData ();
		
		if ($this->_id) {
			if (is_null ( $uid )) {
				$user = & JFactory::getUser ();
				$uid = $user->get ( 'id' );
			}
			$user_detail = & $this->getTable ();
			if (! $user_detail->checkout ( $uid, $this->_id )) {
				$this->setError ( $this->_db->getErrorMsg () );
				return false;
			}
			return true;
		}
		return false;
	}
	
	/**
	 * Il metodo check serve per vedere se il record � gi� occupato da un altro utente
	 */
	
	function checkin() {
		if ($this->_id) {
			$user_detail = & $this->getTable ();
			if (! $user_detail->checkin ( $this->_id )) {
				$this->setError ( $this->_db->getErrorMsg () );
				return false;
			}
		}
		return false;
	}
	
	/**
	 * Tests if user_detail is checked out
	 */
	
	function isCheckedOut($uid = 0) {
		if ($this->_loadData ()) {
			if ($uid) {
				return ($this->_data->checked_out && $this->_data->checked_out != $uid);
			} else {
				return $this->_data->checked_out;
			}
		}
	}
	
	/**
	 * Method to load content user_detail data
	 */
	
	function _loadData() {
		
		$params = JComponentHelper::getParams ( 'com_rubriestav' );
		$rubriestav = $params->get ( 'rubriestav' );
		
		
			if (empty ( $this->_data )) {
				$query = '
				SELECT
				
				distinct h.*
				FROM 
				
				#__rubriestav_user AS h
				WHERE
				h.id = ' . $this->_id;
				$this->_db->setQuery ( $query );
				//echo($query);
				$this->_data = $this->_db->loadObject ();
				return ( boolean ) $this->_data;
			
		}
		return true;
	}
	
	/**
	 * Method to initialise the user_detail data
	 */
	
	function _initData() {
		if (empty ( $this->_data )) {
			$detail = new stdClass ( );
			$detail->id = 0;
			$detail->checked_out = 0;
			$detail->checked_out_time = 0;
			$detail->idjusers = 0;
			$detail->typology = 2;
			$detail->params = null;
			$this->_data = $detail;
			return ( boolean ) $this->_data;
		}
		return true;
	}
	
	/**
	 * Method to store the modules text
	 */
	
	function store($data) {
		
		$row = & $this->getTable ();
		
		if (! $row->bind ( $data )) {
			$this->setError ( $this->_db->getErrorMsg () );
			return false;
		}
		$row->datemodify = gmdate ( 'Y-m-d H:i:s' );
		
		// se il risutato attesso � soltanto uno altrimenti sotto tra le graffe svolgere il ciclo
		foreach ( $this->getJUser ( $row->idjusers ) as $juser ) {
		}
		
		$row->datesignin = $juser->registerDate;
		$row->datelastaccess = $juser->lastvisitDate;
		$this->updateUser ( $row->idjusers, 'username', $row->username );
		$this->updateUser ( $row->idjusers, 'name', $row->firstname . " " . $row->surname );
		$this->updateUser ( $row->idjusers, 'email', $row->email );
		$row->password = '0';
		//echo($row->images);
		//exit();
		if (! $row->store ()) {
			
			$this->setError ( $this->_db->getErrorMsg () );
			echo ($this->_db->getErrorMsg ());
			
			return false;
		}
		echo($row->images);
	
		return true;
	}
	/**
	 * Method to remove a user_detail
	 */
	
	function delete($cid = array()) {
		$result = false;
		if (count ( $cid )) {
			$cids = implode ( ',', $cid );
			$query = 'DELETE FROM ' . $this->_table_prefix . 'user WHERE id IN ( ' . $cids . ' )';
			$this->_db->setQuery ( $query );
			if (! $this->_db->query ()) {
				$this->setError ( $this->_db->getErrorMsg () );
				return false;
			}
		}
		return true;
	}
	
	function getUsers($order = 'name') {
		global $mainframe;
		$query = 'SELECT id AS value, name AS text FROM #__users
		where id not in (select idjusers from #__rubriestav_user) and id<>62
		ORDER BY ' . $order;
		$this->_db->setQuery ( $query );
		return $this->_db->loadObjectList ();
	}
	
	function getState($order = 'description') {
		global $mainframe;
		$query = 'SELECT id AS value, description AS text FROM #__rubriestav_idr_nation
		ORDER BY ' . $order;
		$this->_db->setQuery ( $query );
		
		return $this->_db->loadObjectList ();
	}
	
	function getJUser($id) {
		global $mainframe;
		$query = 'SELECT * FROM #__users where id = ' . $id;
		$this->_db->setQuery ( $query );
		return $this->_db->loadObjectList ();
	}
	
	function getrubriestavUserFromJuser($id) {
		global $mainframe;
		$query = '		SELECT
				distinct h.*,
				  tb_uo.codice_funzionale,
				  tb_uo.descrizione as uo,
				  pc.parent_name as codice_padre,
				  (select de.sym_desc_e from jos_rubriestav_tb_symbol as de where de.sym_name = codice_padre),
				
				  u.dt_nascita as nascita,
				  u.dt_assunzione as assunzione,
				  tb_zone.descrizione as zona,
				  tb_qualifiche.descrizione as qualifica,
				  tb_ruoli.descrizione as ruolo,
				  tb_catgiuridiche.descrizione as categoria_giuridica
				
				FROM 
				
				#__rubriestav_user AS h,
				#__rubriestav_tb_utenti as u 
				left join #__rubriestav_tb_uo as tb_uo on (u.id_struttura = tb_uo.id)
				left join #__rubriestav_tb_zone as tb_zone on (u.cod_zona = tb_zone.id)
				left join #__rubriestav_tb_tipiassunzione as tb_tipiassunzione on (u.id = tb_tipiassunzione.id)
				left join #__rubriestav_tb_ruoli as tb_ruoli on (u.cod_ruolo = tb_ruoli.id)
				left join #__rubriestav_tb_qualifiche as tb_qualifiche on (u.cod_qualifica = tb_qualifiche.id)
				left join #__rubriestav_tb_catgiuridiche as tb_catgiuridiche on (u.cod_catg = tb_catgiuridiche.id)				
				left join jos_rubriestav_tb_symbol as sym on (trim(upper(sym.sym_name)) like tb_uo.codice_funzionale)
			    left join jos_rubriestav_tb_child as pc on (
          		sym.dim_index = pc.dim_index
          		and sym.sym_index = pc.sym_index
          		and pc.dim_index = 2
          		and trim(upper(pc.parent_name)) like "BD%"
          		and trim(upper(pc.parent_name)) <> "BDXXX"
          		and trim(upper(pc.parent_name)) <> "BDYYY"
          		and trim(upper(pc.parent_name)) not like "BDA%")
				WHERE
				
			
				h.idjusers = ' . $id;
		
		//echo($query);
		$this->_db->setQuery ( $query );
		return $this->_db->loadObjectList ();
	}
	
	function updateUser($id, $field, $value) {
		global $mainframe;
		$query = 'update #__users set ' . $field . ' = "' . $value . '" where id = ' . $id;
		return ($this->_db->setQuery ( $query ));
	}

}

?>
