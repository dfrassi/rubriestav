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
defined( '_JEXEC' ) or die( 'Restricted access' );

//DEVNOTE: import VIEW object class
jimport( 'joomla.application.component.view' );
jimport ( 'joomla.application.component.helper' );

require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'toolbar.php');



class user_detailVIEWuser_detail extends JView
{

	function display($tpl = null)
	{
		global $mainframe, $option;
			
		$document = & JFactory::getDocument ();
		$document->addStyleSheet ( 'components/com_rubriestav/assets/css/icon.css' );
		$document->addStyleSheet ( 'components/com_rubriestav/assets/css/general.css' );
		$document->addScript('includes/js/joomla.javascript.js');
		$document->setTitle ( JText::_ ( 'Area utente' ) );
		
		userHelperToolbar::title(   JText::_( 'Rubrica Estav - Dettaglio utente' ), 'generic.png' );
	
		$uri 	=& JFactory::getURI();
		$user 	=& JFactory::getUser();
		
		$model	=& $this->getModel();
		$this->setLayout('form');
		$detail	=&$this->get('data');
		
		$items			= & $this->get( 'Data');
		$total			= & $this->get( 'Total');
		
		$this->assignRef('items',		$items);
		$this->assignRef('note_numeri_esterni',		$this->getNoteNumeriEsterni($detail->codice_ente));
		$this->assignRef ( 'pulsanti', userHelperToolbar::getToolbar () );
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());
	
		parent::display($tpl);
	}		
	
	
	function getNoteNumeriEsterni($azienda){
		$stringUscita = "";
		
		if ($azienda == "105"){
			$stringUscita = '
			<b>USL 5 ed  ESTAV</b><br>     
        	0587 273xxx 8000-8900<br>
        	0587 098xxx 1000-1999<br>
        	050 959xxx 9000-9999<br>
        	050 954xxx 4000-4999<br>
        	0588 da 915xx a 919xx 7500-7599<br>
        	0588 070xxx (Auxilium Vitae) 7200-7299<br>
        	050 8662xxx tutti<br>';
		}
		if ($azienda == "901"){
			$stringUscita = '
			<b>AOUP</b><br>         
        	050 99xxxx  tutti<br>';
		}
		if ($azienda == "112"){
			$stringUscita = '
			<b>USL 12</b><br>         
        	0584 605xxxx  tutti<br>';
		}
		if ($azienda == "106"){
			$stringUscita = '
			<b>USL 6</b><br>         
        	0586 223xxx  3000-3999<br>
        	0586 614xxx  4000-4999<br>
        	0565 67xxx   7000-7999<br>
        	0565 926xxx  6000-6999<br>
        	0586 726xxx Non inserire<br>';
		}
		if ($azienda == "101"){
			$stringUscita='
			<b>USL 1</b><br>        
        	0585 655xxx  5000-5999<br>
        	0585 657xxx  7000-7999<br>
        	0585 493xxx  3000-3999<br>
        	0585 498xxx  8000-8999<br>
        	0585 940xxx Non inserire<br>
        	0187 462xxx Non inserire<br>
        	0187 406xxx Non inserire<br>';
		}
		if ($azienda == "102"){
			$stringUscita='
			<b>USL 2</b><br>         
        	0583 970xxx  2000-2999<br>
        	0583 449xxx  3000-3999<br>
        	0583 669xxx  5000-5999<br>
        	0583 729xxx  5000-5999<br>';
		}
		return $stringUscita;
	}
	
		
}


?>
			
