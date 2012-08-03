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
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'toolbar.php' );


/**
 [controller]View[controller]
 */

class userViewuser extends JView
{

	function __construct( $config = array()){
	 
		global $context;
	 	$context = 'user.list.';
	 	parent::__construct( $config );
	}

	/**
	 * Display the view
	 * take data from MODEL and put them into
	 * reference variables
	 *
	 * Go to MODEL, execute Method getData and
	 * result save into reference variable $items
	 * $items		= & $this->get( 'Data');
	 * - getData gets the country list from DB
	 *
	 * variable filter_order specifies what is the order by column
	 * variable filter_order_Dir sepcifies if the ordering is [ascending,descending]
	 */


	function display($tpl = null)
	{
		global $mainframe, $context;
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('user') );
		$document->addStyleSheet ( 'components/com_rubriestav/assets/css/icon.css' );
		$document->addStyleSheet ( 'components/com_rubriestav/assets/css/general.css' );
		$document->addStyleSheet ( 'components/com_rubriestav/assets/css/modal.css' );
		$document->addStyleSheet ( 'components/com_rubriestav/assets/css/menu.css' );
		
		JToolBarHelper::title(   JText::_( 'Rubrica Estav - Utenti' ), 'user' );
		//JToolBarHelper::preferences ( 'com_rubriestav', '450' );
		
		
		//JToolBarHelper::addNewX();
		
		//userHelperToolbar::purge();
		userHelperToolbar::home();
		userHelperToolbar::loader();
		userHelperToolbar::importusers();
		JToolBarHelper::preferences ( 'com_rubriestav', '450' );
	
		$uri	=& JFactory::getURI();
		
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'ordering' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );
		
		$lists['order'] 		= $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		
		$items			= & $this->get( 'Data');
		$total			= & $this->get( 'Total');
		
		$pagination = & $this->get( 'Pagination' );
		
		
		$session = JSession::getInstance('none',array());

		$attivi = JRequest::getVar('attivi');
		$omonimi = JRequest::getVar('omonimi');
		$dimessi = JRequest::getVar('dimessi');
		
		if (!isset($attivi)) $attivi=$session->get('attivi');
			else $session->set('attivi',$attivi);
			
		if (!isset($omonimi)) $omonimi=$session->get('omonimi');
			else $session->set('omonimi',$omonimi);
			
		if (!isset($dimessi)) $dimessi=$session->get('dimessi');
			else $session->set('dimessi',$dimessi);
		
		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('lists',		$lists);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef ('pulsanti', userHelperToolbar::getToolbar () );
		$this->assignRef('request_url',	$uri->toString());
		$this->assignRef('search', JRequest::getVar('search'));
		$this->assignRef('attivi', $attivi);
		$this->assignRef('omonimi', $omonimi);
		$this->assignRef('dimessi', $dimessi);
		
		parent::display($tpl);
	}
}
?>
