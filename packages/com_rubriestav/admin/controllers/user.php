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

//DEVNOTE: import CONTROLLER object class
jimport( 'joomla.application.component.controller' );


class userController extends JController
{

	function __construct( $default = array())
	{
		parent::__construct( $default );
	}

		function cancel()
	{
		$this->setRedirect( 'index.php' );
	}

	/**
	 * Method display
	 * 
	 * 1) crea il controller
	 * 2) passa il modello alla vista
	 * 3) carica il template e lo renderizza  	  	 	 
	 */

	function publish(){
		$id = JRequest::getVar('id');
		utility::executeQuery("update #__users set block = 0 where id = $id");
		//echo("update #__users set block = 0 where id = $id");
		parent::display();
	}
	
	function unpublish(){
		$id = JRequest::getVar('id');
		utility::executeQuery("update #__users set block = 1 where id = $id");
		//echo("update #__users set block = 1 where id = $id");
		parent::display();
	}
	
	
	function display() {
		//$model = $this->getModel ( 'user' );
		//$model->purgeUsers();
		//$model->importJUser();
		parent::display();
	}	
	
	
	function purge(){
		$model = $this->getModel ( 'user' );
		echo($model->purgeUsers());
		echo($model->purgeLogUsers());
		$this->setRedirect( 'index.php?option=com_rubriestav&controller=user','Pulizia della tabelle eseguita con successo!' );
	}
	
	function importJUser(){
		$model = $this->getModel ( 'user' );		
		echo($model->importJUser());
		$model->purgeUsers();		
		$this->setRedirect( 'index.php?option=com_rubriestav&controller=user','Importazione utenti Joomla eseguita!' );
	}
	function importusers(){
		$model = $this->getModel ( 'user' );		
		echo($model->importusers());
		$model->purgeUsers();		
		$this->setRedirect( 'index.php?option=com_rubriestav&controller=user','Importazione utenti Joomla eseguita!' );
	}
}	
?>
