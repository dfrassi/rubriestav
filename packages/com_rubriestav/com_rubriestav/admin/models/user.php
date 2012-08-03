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
jimport ( 'joomla.application.component.model' );

class userModeluser extends JModel {
	
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_table_prefix = null;
	
	function __construct() {
		parent::__construct ();
		global $mainframe, $context;
		$this->_table_prefix = '#__rubriestav_';
		
		//DEVNOTE: Parametri di paginazione
		$limit = $mainframe->getUserStateFromRequest ( $context . 'limit', 'limit', $mainframe->getCfg ( 'list_limit' ), 0 );
		$limitstart = $mainframe->getUserStateFromRequest ( $context . 'limitstart', 'limitstart', 0 );
		$this->setState ( 'limit', $limit );
		$this->setState ( 'limitstart', $limitstart );
	}
	
	/**
	 * Method to get a user data
	 *
	 * questo metodo è chiamato da ogni proprietario della vista
	 */
	
	function getData() {
		if (empty ( $this->_data )) {
			$query = $this->_buildQuery ();
			$this->_data = $this->_getList ( $query, $this->getState ( 'limitstart' ), $this->getState ( 'limit' ) );
		}
		return $this->_data;
	}
	
	/**
	 * Il metodo restituisce il numero totale di righe del modulo
	 */
	
	function getTotal() {
		if (empty ( $this->_total )) {
			$query = $this->_buildQuery ();
			$this->_total = $this->_getListCount ( $query );
		}
		return $this->_total;
	}
	
	/**
	 * Method to get a pagination object for the user
	 */
	
	function getPagination() {
		if (empty ( $this->_pagination )) {
			jimport ( 'joomla.html.pagination' );
			$this->_pagination = new JPagination ( $this->getTotal (), $this->getState ( 'limitstart' ), $this->getState ( 'limit' ) );
		}
		return $this->_pagination;
	}
	
	/**
	 * Metodo che effettua la query vera e propria sul db
	 */
	
	function purgeUsers() {
		$query = ' delete from #__rubriestav_user where idjusers not in (select id from #__users) ';
		$this->_db->setQuery ( $query );
		if (! $this->_db->query ()) {
			$this->setError ( $this->_db->getErrorMsg () );
			return false;
		}
		return true;
	}
	
	function importJUser() {
		

		
		$risul = utility::getArray ( "select id from #__core_acl_aro_groups where name = 'Strutture'" );
		
		foreach ( $risul as $risulto ) {
			$gruppo_rubriestav = $risulto->id;
		}
		
		/*$sql = "insert into #__users 
			 	select ele.id, 
				concat(ele.struttura,\" - \", ele.ruolo), 
				ele.id, 
				null,
				'ad4b26a94fa142f812f2c5b3f49cfbce:6lzHgnPDkBU57JFdsydjeoUTTfLqrX3y', 
				'Registred', 
				0, 
				0, 
				" . $gruppo_rubriestav . ", 
				null,
			 	null, 
			 	null, 
			 	null 
			 	from elenco ele 
				where ele.id > 500000 and ele.id not in (select id from #__users)";
		
			$this->_db->setQuery ( $sql );
			
			if (! $this->_db->query ()) {
				$this->setError ( $this->_db->getErrorMsg () );
				return false;
			}
	*/
		$query = 'INSERT INTO 
				#__rubriestav_user (idjusers, password, username, firstname, surname, datesignin, email)
				select id, 0, username, name, "", registerDate, email  from #__users c 
				where gid<>25 and !exists 
				(select 1 FROM #__users a, #__rubriestav_user b where a.id = b.idjusers and c.id = a.id)';
		
		$this->_db->setQuery ( $query );
		
		if (! $this->_db->query ()) {
			$this->setError ( $this->_db->getErrorMsg () );
			return false;
		}
		
		$query = ' update #__rubriestav_user juser, 
				         #__rubriestav_tb_utenti sutenti
				set juser.firstname = sutenti.nome,
				juser.surname = sutenti.cognome
				where juser.idjusers = sutenti.matricola ';
		
		$this->_db->setQuery ( $query );
		
		if (! $this->_db->query ()) {
			$this->setError ( $this->_db->getErrorMsg () );
			return false;
		}
		$query = ' update #__rubriestav_user juser, #__users ju, #__rubriestav_tb_utenti sutenti
				set ju.block = 1 where juser.idjusers = ju.id and 
				sutenti.dt_dimissione < current_date() and dt_dimissione > "0000-00-00 00:00:00"
				and sutenti.matricola = ju.id';
		
		$this->_db->setQuery ( $query );
		
		if (! $this->_db->query ()) {
			$this->setError ( $this->_db->getErrorMsg () );
			return false;
		}
		/*$query = '
			update `elenco` ele,
			 #__rubriestav_user joo
			set
			joo.ufficiostruttura = ele.struttura,
			joo.telephone = ele.Cell,
			joo.mobile = ele.Numero,
			joo.address = ele.ubicazione
			joo.fax = ele.fax
			where ele.id = joo.idjusers
			
			and joo.ufficiostruttura is null
			and joo.telephone is null
			and joo.mobile is null
			and joo.address is null
			and joo.fax is null
			
			';
		
		$this->_db->setQuery ( $query );
		
		
		
		if (! $this->_db->query ()) {
		}*/
		
		$query = ' update #__rubriestav_user juser, 
				         #__rubriestav_tb_utenti sutenti
				set juser.birthday = sutenti.dt_nascita
				where juser.idjusers = sutenti.matricola 
				and juser.birthday ="0000-00-00 00:00:00"';
		
		$this->_db->setQuery ( $query );
		
		if (! $this->_db->query ()) {
			$this->setError ( $this->_db->getErrorMsg () );
			return false;
		}
		
		return true;
	
	}
	function purgeLogUsers() {
		
		$query = ' delete from #__rubriestav_idr_log_user where iduser not in (select id from #__rubriestav_user) ';
		
		$this->_db->setQuery ( $query );
		if (! $this->_db->query ()) {
			$this->setError ( $this->_db->getErrorMsg () );
			return false;
		}
		$query = ' delete from #__rubriestav_idr_log_user where idlottery not in (select id from #__rubriestav_idr_lottery) ';
		$this->_db->setQuery ( $query );
		if (! $this->_db->query ()) {
			$this->setError ( $this->_db->getErrorMsg () );
			return false;
		}
		return true;
	}
	
	function _buildQuery() {
		
		$orderby = $this->_buildContentOrderBy (); // costruisce l'order by (vedi sotto)

		$search = "";
		$post = JRequest::get ( 'post' );
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$post ['id'] = $cid [0];
		
		$query = ' 
		SELECT h.*
		FROM 
				#__rubriestav_user AS h '.$orderby;
		//echo($query);
		return $query;
	}
	
	/**
	 * Costruisce l'order by automatico su colonna
	 */
	
	function _buildContentOrderBy() {
		global $mainframe, $context;
		
		$filter_order = $mainframe->getUserStateFromRequest ( $context . 'filter_order', 'filter_order', '1' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest ( $context . 'filter_order_Dir', 'filter_order_Dir', '' );
		
		if ($filter_order == 'h.ordering' || $filter_order == 'ordering') {
			$orderby = ' ORDER BY 1 ';
		} else {
			$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;
		}
		return $orderby;
	}

}

?>

