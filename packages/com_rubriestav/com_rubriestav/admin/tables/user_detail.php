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

//DEVNOTE: iInclude library dependencies
jimport ( 'joomla.application.component.model' );

/**
 * helloworld Table class
 *
 * @package		Joomla
 * @subpackage	helloworlds
 * @since 1.0
 */
class Tableuser_detail extends JTable {
	
	var $id = null; // int
	var $codice_ente = null; // int
	var $matricola = null; // int
	var $cognome = null; // int
	var $nome = null; // int
	var $qualifica = null; // int
	var $azienda = null; // int
	var $unita_operativa = null; // int
	var $sede_descrizione = null; // int
	var $sede_via = null; // int
	var $sede_cap = null; // int
	var $sede_citta = null; // int
	var $sede_provincia= null; // int
	var $fototessera_url = null; // int
	var $tele_1_prefisso = null; // int
	var $tele_1_numero = null; // int
	var $tele_altri_numeri = null; // int
	var $voip = null; // int
	var $email = null; // int
	var $note = null; // int
	var $datesignin = '0000-00-00 00:00:00'; // datetime
	var $datemodify = '0000-00-00 00:00:00'; // datetime
	var $datelastaccess = '0000-00-00 00:00:00'; // datetime
	var $checked_out = 0; // int
	var $checked_out_time = '0000-00-00 00:00:00'; // datetime
	var $published = 0; // int
	

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function Tableuser_detail(& $db) {
		//initialize class property
		$this->_table_prefix = '#__rubriestav_';
		
		parent::__construct ( $this->_table_prefix . 'user', 'id', $db );
	}
	
	
	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 * @since 1.0
	 */
	function check() {
		$this->default_con = intval ( $this->default_con );
		
		if (JFilterInput::checkAttribute ( array ('href', $this->website ) )) {
			$this->setError ( JText::_ ( 'Please provide a valid URL' ) );
			return false;
		}
		
		// check for http on webpage
		if (strlen ( $this->website ) > 0 && (! (preg_match ( '#http://#i', $this->website ) || (preg_match ( '#https://#i', $this->website )) || (preg_match ( '#ftp://#i', $this->website ))))) {
			$this->website = 'http://' . $this->website;
		}

		
		return true;
	}
}
?>
