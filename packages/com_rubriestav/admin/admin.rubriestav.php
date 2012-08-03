<?php
/**
* @package HelloWorld 02
* @version 1.5
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software and parts of it may contain or be derived from the
* GNU General Public License or other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/


// controllo che il componente venga chiamato soltanto da joomla
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once (JPATH_PLUGINS.DS.'system'.DS.'djflibraries'.DS.'utility.php');
// questo è il controller di default se non ne viene selezionato alcuno
global $mainframe;
$mainframe = JFactory::getApplication();
$controller = JRequest::getVar('controller','user' );

// indirizza il controller giusto
require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');

// Create the controller
$classname  = $controller.'controller';

//create a new class of classname and set the default task:display
$controller = new $classname( array('default_task' => 'display') );

// Perform the Request task
$controller->execute( JRequest::getVar('task' ));

// Redirect if set by the controller
$controller->redirect();
?>
