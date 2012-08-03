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

//DEVNOTE: import VIEW object class
jimport ( 'joomla.application.component.view' );
jimport ( 'joomla.application.component.helper' );
require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'toolbar.php');

/**
 [controller]View[controller]
 */

class importusersViewimportusers extends JView {

	function __construct($config = array()) {
		
		global $context;
		$context = 'importusers.list.';
		parent::__construct ( $config );
	}
	
	function display($tpl = null)
	{
		
		global $mainframe, $context;

		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('jtask') );

		$document->addStyleSheet ( 'components/com_rubriestav/assets/css/icon.css' );
		$document->addStyleSheet ( 'components/com_rubriestav/assets/css/general.css' );
		$document->addStyleSheet ( 'components/com_rubriestav/assets/css/modal.css' );
		$document->addStyleSheet ( 'components/com_rubriestav/assets/css/menu.css' );
		
		JToolBarHelper::title ( JText::_ ( 'Rubrica Estav - ').JText::_ ( 'Importa Utenti da file CSV' ), 'importusers' );
		userHelperToolbar::home();
		userHelperToolbar::loader();
		userHelperToolbar::importusers();
		
		$uri = & JFactory::getURI ();

		$this->assignRef ( 'user', JFactory::getUser () );
		$this->assignRef ( 'lists', $lists );
		$this->assignRef ( 'items', $items );
		$this->assignRef ( 'pagination', $pagination );
		$this->assignRef ( 'request_url', $uri->toString () );
		$this->assignRef ( 'esito', $risultato );

		parent::display ( $tpl );
	}
}
?>
