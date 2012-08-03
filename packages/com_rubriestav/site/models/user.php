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
		
		$config = JFactory::getConfig ();
		$this->setState ( 'limit', $mainframe->getUserStateFromRequest ( 'com_weblinks.limit', 'limit', $config->getValue ( 'config.list_limit' ), 'int' ) );
		
		$limitstart = JRequest::getVar('limitstart');
		$limit = JRequest::getVar('limit');
		
		if ($limit!="")$this->setState ( 'limitstart', 0);
		else 
		$this->setState ( 'limitstart', JRequest::getVar ( 'limitstart',0, '', 'int' ) );
		//$this->setState ( 'limitstart', ($this->getState ( 'limit' ) != 0 ? (floor ( $this->getState ( 'limitstart' ) / $this->getState ( 'limit' ) ) * $this->getState ( 'limit' )) : 0) );
	
		
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
	
	function _buildQuery() {
		
		
		$orderby = $this->_buildContentOrderBy (); // costruisce l'order by (vedi sotto)
		
		$post = JRequest::get ( 'post' );
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$post ['id'] = $cid [0];
		
		$search = utility::getDjfVar("search","");
		
		
		$mode = utility::getDjfVar("mode","semplice");
		//echo("<h1>$mode</h1>");
		if ($mode == "avanzata") $search = "";
		
		$query_nome_search="";
		$search_nome = utility::getDjfVar("search_nome","");
		if ($mode == "semplice") $search_nome = "";
		if ($search_nome != "") $query_nome_search = " and h.firstname like '%".$search_nome."%'";
		
		$query_cognome_search="";
		$search_cognome = utility::getDjfVar("search_cognome","");
		if ($mode == "semplice") $search_cognome = "";
		if ($search_cognome != "") $query_cognome_search = " and h.surname like '%".$search_cognome."%'";
		
		$query_uo_search="";
		$search_uo = utility::getDjfVar("search_uo","");
		if ($search_uo=="") $search_uo = JRequest::getVar('search_uo');
		if ($mode == "semplice") $search_uo = "";
		if ($search_uo != "") $query_uo_search = " and 
		(tb_uo.descrizione like '%".$search_uo."%' or tb_uo.id = " . $search_uo . ")";

		$query_us_search="";
		$search_us = utility::getDjfVar("search_us","");
		if ($mode == "semplice") $search_us = "";
		if ($search_us != "") $query_us_search = " and h.ufficiostruttura like '%".$search_us."%' ";
		
		$query_qualifica_search="";
		$search_qualifica = utility::getDjfVar("search_qualifica","");
		if ($mode == "semplice") $search_qualifica = "";
		if ($search_qualifica != "") $query_qualifica_search = " and tb_qualifiche.descrizione like '%".$search_qualifica."%'";
		
		$query_zona_search="";
		$search_zona = utility::getDjfVar("search_zona","");
		if ($mode == "semplice") $search_zona = "";
		if ($search_zona != "") $query_zona_search = " and tb_zone.descrizione like '%".$search_zona."%'";
		
		$query_xplode="";
		if ($search != "") {
			$search = str_replace("'","%",$search);
			$searchblock = explode(" ",$search);
			$i=0;
			foreach ($searchblock as $thisblock){
				$search = str_replace(" ","%",$search);
				$query_xplode .= " or concat(h.nome,' ',h.cognome) like '%" . $search . "%'";	
				$query_xplode .= " or concat(h.cognome,' ',h.nome) like '%" . $search . "%'";
				$i++;
			}
			if ($i==1){
				$query_xplode =" or h.nome like '%" . $search . "%' or h.cognome like '%" . $search . "%'"; 
			}
			
			$query_search = " 
		and (false ".$query_xplode."
		or h.tele_1_prefisso like '%" . $search . "%'
		or h.tele_1_numero like '%" . $search . "%'
		or h.tele_altri_numeri like '%" . $search . "%'
		or h.voip like '%" . $search . "%'
		or h.qualifica like '%" . $search . "%'
		or h.unita_operativa like '%" . $search . "%'
		or h.sede_descrizione like '%" . $search . "%'
		or h.sede_provincia like '%" . $search . "%'
		or h.azienda like '%" . $search . "%'
		) 		
		";}
		else
			$query_search = "";
		
			
			
			if (utility::contains($orderby,"ordering")) $orderby = " ORDER BY 1 ";
		$query = ' 	
		
				SELECT
				distinct h.*,
				concat("0",h.tele_1_prefisso," ",h.tele_1_numero) as numero_completo,
				h.tele_altri_numeri
				FROM 
				#__rubriestav_user AS h
				
				where 1=1'. 
				$query_search . 
				$query_nome_search.
				$query_cognome_search.
				$query_uo_search.
				$query_qualifica_search.
				$query_zona_search.
				$query_us_search.
				$orderby;
				
				//echo ($query);

				return $query;
	
	}
	
	/**
	 * Costruisce l'order by automatico su colonna
	 */
	
	function _buildContentOrderBy() {
		global $mainframe, $context;
		
		$filter_order = $mainframe->getUserStateFromRequest ( $context . 'filter_order', 'filter_order', '1' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest ( $context . 'filter_order_Dir', 'filter_order_Dir', '' );
		
		if ($filter_order == '1' || $filter_order == '1') {
			$orderby = ' ORDER BY h.cognome ';
		} else {
			$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir ;
		}
		return $orderby;
	}

}

?>

