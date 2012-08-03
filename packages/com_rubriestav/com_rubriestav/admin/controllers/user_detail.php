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

//DEVNOTE: import CONTROLLER object class
jimport ( 'joomla.application.component.controller' );

/**
 * user_detail  Controller
 *
 * @package		Joomla
 * @subpackage	user
 * @since 1.5
 */
class user_detailController extends JController {
	
	/**
	 * Custom Constructor
	 */
	function __construct($default = array()) {
		parent::__construct ( $default );
		
		// Register Extra tasks
		$this->registerTask ( 'add', 'edit' );
	
	}
	
	function edit() {
		
		JRequest::setVar ( 'view', 'user_detail' );
		JRequest::setVar ( 'layout', 'form' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();
		// give me  the user
		$model = $this->getModel ( 'user_detail' );
		$model->checkout ();
	
	}
	
	function saveFile() {
		$file = JRequest::getVar ( 'upload', null, 'files', 'array' );
		jimport ( 'joomla.filesystem.file' );
		
		$params = JComponentHelper::getParams ( 'com_rubriestav' );
		$maxsize = $params->get ( 'image_maxsize_upload' );
		
		if ($_FILES ['upload'] ['size'] > $maxsize) {
			echo ("Dimensione errata");
			return false;
		}
		//Clean up filename to get rid of strange characters like spaces etc
		$filename = JFile::makeSafe ( $file ['name'] );
		
		//Set up the source and destination of the file
		$src = $file ['tmp_name'];
		$dest = JPATH_ROOT . DS . 'images' . DS . 'stories' . DS . 'rubriestav_folder' . DS . 'uploads' . DS . $filename;
		$dest_thumb = JPATH_ROOT . DS . 'images' . DS . 'stories' . DS . 'rubriestav_folder' . DS . 'uploads' . DS . 'thumb' . DS . $filename;
		echo ($dest);
		
		//First check if the file has the right extension, we need jpg only
		if (strtolower ( JFile::getExt ( $filename ) ) == 'jpg') {
			if (JFile::upload ( $src, $dest )) {
				JFolder::create ( JPATH_ROOT . DS . 'images' . DS . 'stories' . DS . 'rubriestav_folder' . DS . 'uploads' . DS . 'thumb' );
				//JFile::upload ( $src, $dest_thumb );
				//JFile::copy($dest, $dest_thumb );
				$image = new SimpleImage ( );
				$image->load ( $dest );
				$height = $image->getHeight ();
				$width = $image->getWidth ();
				
				$y = 150 * $height / $width;
				$image->resize ( 150, $y );
				$image->save ( $dest_thumb );
				return true;
			} else {
				return false;
			}
		} else {
			echo ("estensione errata");
			return false;
		}
	}
	
	/**
	 * Funzione di salvataggio
	 *
	 */
	
	function save() {
		global $mainframe;
		jimport ( 'joomla.filesystem.file' );
		$post = JRequest::get ( 'post' );
		$file = JRequest::getVar ( 'upload', null, 'files', 'array' );
		$filename = $file ['name'];
		jimport ( 'joomla.user.helper' );
		
		$urlSelf = 'index.php?option=com_rubriestav&controller=user_detail&task=edit&cid[]=' . $post ['id'];
		if ($filename != "") {
			if ($this->saveFile ()) {
				//Clean up filename to get rid of strange characters like spaces etc
				$filename = JFile::makeSafe ( $file ['name'] );
				$post ['images'] = $filename;
				echo ("<h1>images = $filename</h1>");
				//exit();
			} else {
				$params = JComponentHelper::getParams ( 'com_rubriestav' );
				$maxsize = $params->get ( 'image_maxsize_upload' );
				$msg = "Impossibile caricare il file - Controllare la dimensione, deve essere inferiore a Byte: " . $maxsize;
				$this->setRedirect ( $urlSelf, JText::_ ( $msg ) );
				return false;
				//exit ();
			}
		} else {
			echo ("filename = " . $filename);
			//exit();
		}
		

		if (isset ( $post ['oldpassword'] )) {
			
			$password = $post ['password'];
			
			if ($password != "" && $password != null && $password != "0") {
				
				$oldpassword = $post ['oldpassword'];
				$password = $post ['passwordjoomla'];
				
				$parts = explode ( ':', $password );
				$crypt = $parts [0];
				$salt = @$parts [1];
				
				$testcrypt = JUserHelper::getCryptedPassword ( $oldpassword, $salt );
				
				//echo("<br> = ".$crypt);
				//echo("<br> = ".$testcrypt);
				if ($crypt != $testcrypt) {
					$msg = "Attenzione inserisci correttamente la vecchia password!!";
					//echo($msg);
					$this->setRedirect ( 'index.php?option=com_rubriestav&controller=user_detail&task=edit', JText::_ ( $msg ) );
					return false;
				
				}
			}
		}
		
		$db = & JFactory::getDBO ();
		$me = & JFactory::getUser ();
		$acl = & JFactory::getACL ();
		
		$MailFrom = $mainframe->getCfg ( 'mailfrom' );
		$FromName = $mainframe->getCfg ( 'fromname' );
		$SiteName = $mainframe->getCfg ( 'sitename' );
		
		if (isset ( $post ['password'] )) {
			$password = $post ['password'];
			$password_in_chiaro = $post ['password'];
		}
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if ($password == "" || $password == null || $password == "0") {
			$query = 'SELECT password ' . ' FROM #__rubriestav_user' . ' WHERE id = ' . $cid [0];
			$db->setQuery ( $query );
			$rows = $db->loadObjectList (); // recupero delle righe del risultato
			foreach ( $rows as $row ) {
				$password = $row->password; // lista dei destinatari
				$password_in_chiaro = $password;
				
				echo ("<h1>" . $password . "</h1>");
			
			}
		}
		
		if (isset ( $post ['idjusers'] ))
			$idjusers = $post ['idjusers'];
		else
			$idjusers = 0;
		
		$user = new JUser ( $idjusers );
		$user->set ( 'username', $post ['username'] );
		$user->set ( 'name', $post ['firstname'] . ' ' . $post ['surname'] );
		$user->set ( 'email', $post ['email'] );
		if ($idjusers == 0) {
			$user->set ( 'usertype', 'Registered' );
			$user->set ( 'gid', 18 );
		}
		
		$original_gid = $user->get ( 'gid' );
		
			
		
		
		$post ['id'] = $cid [0];
		$model = $this->getModel ( 'user_detail' );
		
		$post ['password'] = JRequest::getVar ( 'password', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$post ['password2'] = JRequest::getVar ( 'password2', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		$return = JURI::base ();
		
		if (strlen ( $post ['password'] ) || strlen ( $post ['password2'] )) { // so that "0" can be used as password e.g.
			if ($post ['password'] != $post ['password2']) {
				$msg = JText::_ ( 'PASSWORDS_DO_NOT_MATCH' );
				$return = str_replace ( array ('"', '<', '>', "'" ), '', @$_SERVER ['HTTP_REFERER'] );
				if (empty ( $return ) || ! JURI::isInternal ( $return )) {
					$return = JURI::base ();
				}
				$this->setRedirect ( $return, $msg, 'error' );
				return false;
			}
		
		}
		
		$usersConfig = &JComponentHelper::getParams ( 'com_users' );
		//exit();
		/*if ($usersConfig->get('allowUserRegistration') == '0') {
			JError::raiseError( 403, JText::_( 'Access Forbidden' ));
			return;
		}*/
		$useractivation = $usersConfig->get ( 'useractivation' );
		$randomnumber = JUserHelper::genRandomPassword ();
		
		$isNew = ($user->get ( 'id' ) < 1);
		if ($isNew) {
			if ($useractivation == '1') {
				jimport ( 'joomla.user.helper' );
				$user->set ( 'activation', JUtility::getHash ( $randomnumber ) );
				$user->set ( 'block', '1' );
			}
		}
		
		$salt = $randomnumber;
		$crypt = JUserHelper::getCryptedPassword ( $password, $salt );
		$password = $crypt . ':' . $salt;
		
		if (isset ( $post ['password'] )) {
			$passwordFromUrl = $post ['password'];
		
		}
		if (! ($passwordFromUrl == "" || $passwordFromUrl == null || $passwordFromUrl == "0")) {
			$user->set ( 'password', $password );
		}
		
		if (! $isNew) {
			if ($user->get ( 'gid' ) != $original_gid && $original_gid == 25) {
				$query = 'SELECT COUNT( id )' . ' FROM #__users' . ' WHERE gid = 25' . ' AND block = 0';
				$db->setQuery ( $query );
				$count = $db->loadResult ();
				if ($count <= 1) {
					$this->setRedirect ( 'index.php?option=com_users', JText::_ ( 'WARN_ONLY_SUPER' ) );
					return false;
				}
			}
		}
		
		if (! $user->save ()) {
			$mainframe->enqueueMessage ( JText::_ ( 'CANNOT SAVE THE USER INFORMATION' ), 'message' );
			$mainframe->enqueueMessage ( $user->getError (), 'error' );
			return $this->execute ( 'edit' );
		}
		
		if ($isNew) {
			$adminEmail = $me->get ( 'email' );
			$adminName = $me->get ( 'name' );
			$post ['idjusers'] = $user->get ( 'id' );
			
			$subject = JText::_ ( 'NEW_USER_MESSAGE_SUBJECT' );
			$message = sprintf ( JText::_ ( 'NEW_USER_MESSAGE' ), $user->get ( 'name' ), $SiteName, JURI::root (), $user->get ( 'username' ), $user->password_clear );
			
			if ($MailFrom != '' && $FromName != '') {
				$adminName = $FromName;
				$adminEmail = $MailFrom;
			}
			JUtility::sendMail ( $adminEmail, $adminName, $user->get ( 'email' ), $subject, $message );
		}
		
		$post ['password'] = $password_in_chiaro;
		
		if ($model->store ( $post )) {
			$msg = JText::_ ( 'Modifica parametri utente effettuata' );
		} else {
			$msg = JText::_ ( 'Errore nel salvataggio del modulo acl' );
		}
		
		// Send registration confirmation mail
		$password = JRequest::getVar ( 'password', '', 'post', JREQUEST_ALLOWRAW );
		$password = preg_replace ( '/[\x00-\x1F\x7F]/', '', $password ); //Disallow control chars in the email
		

		if ($isNew) {
			user_detailController::_sendMail ( $user, $password );
		}
		
		if ($useractivation == 1 && $user->block == "1") {
			$msg = JText::_ ( 'REG_COMPLETE_ACTIVATE' );
		}
		if ($useractivation == 0 && $user->block == "1") {
			
			$msg = JText::_ ( 'REG_COMPLETE' );
		}
		
		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin ();
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$queryUs = "select id from #__rubriestav_user where idjusers = " . $user->id;
		
		$arrayUs = utility::getArray ( $queryUs );
		$id2Redirect = $arrayUs [0]->id;
		echo ($id2Redirect);
		
		$urlSelf = 'index.php?option=com_rubriestav&controller=user';
		
		$this->setRedirect ( $urlSelf, $msg );
	
	}
	
	/**
	 * function remove
	 */
	
	function remove() {
		global $mainframe;
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'Select an item to delete' ) );
		}
		
		// count number of active super admins
		$cids = implode ( ',', $cid );
		$query = 'SELECT idjusers FROM #__rubriestav_user WHERE id in (' . $cids . ')';
		$db = & JFactory::getDBO ();
		$db->setQuery ( $query );
		$rows = $db->loadObjectList ();
		$auto = "";
		if ($rows) {
			foreach ( $rows as $row ) {
				$auto = $row->idjusers;
			}
		}
		//echo("utente auto = ".$auto);
		$user = new JUser ( $auto );
		$user->delete ();
		
		$model = $this->getModel ( 'user_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		
		$this->setRedirect ( 'index.php?option=com_rubriestav&controller=user' );
	}
	
	/**
	 * function cancel
	 */
	
	function cancel() {
		// Checkin the detail
		$model = $this->getModel ( 'user_detail' );
		$model->checkin ();
		$this->setRedirect ( 'index.php?option=com_rubriestav&controller=user' );
	}
	
	function _sendMail(&$user, $password) {
		global $mainframe;
		
		$db = & JFactory::getDBO ();
		
		$name = $user->get ( 'name' );
		$email = $user->get ( 'email' );
		$username = $user->get ( 'username' );
		
		$usersConfig = &JComponentHelper::getParams ( 'com_users' );
		$sitename = $mainframe->getCfg ( 'sitename' );
		$useractivation = $usersConfig->get ( 'useractivation' );
		$mailfrom = $mainframe->getCfg ( 'mailfrom' );
		$fromname = $mainframe->getCfg ( 'fromname' );
		$siteURL = JURI::base ();
		
		$subject = sprintf ( JText::_ ( 'Account details for' ), $name, $sitename );
		$subject = html_entity_decode ( $subject, ENT_QUOTES );
		
		if ($useractivation == 1) {
			$message = sprintf ( JText::_ ( 'SEND_MSG_ACTIVATE' ), $name, $sitename, $siteURL . "index.php?option=com_user&task=activate&activation=" . $user->get ( 'activation' ), $siteURL, $username, $password );
		} else {
			$message = sprintf ( JText::_ ( 'SEND_MSG' ), $name, $sitename, $siteURL );
		}
		
		$message = html_entity_decode ( $message, ENT_QUOTES );
		
		//get all super administrator
		$query = 'SELECT name, email, sendEmail' . ' FROM #__users' . ' WHERE LOWER( usertype ) = "super administrator"';
		$db->setQuery ( $query );
		$rows = $db->loadObjectList ();
		
		// Send email to user
		if (! $mailfrom || ! $fromname) {
			$fromname = $rows [0]->name;
			$mailfrom = $rows [0]->email;
		}
		
		JUtility::sendMail ( $mailfrom, $fromname, $email, $subject, $message );
		
		// Send notification to all administrators
		$subject2 = sprintf ( JText::_ ( 'Account details for' ), $name, $sitename );
		$subject2 = html_entity_decode ( $subject2, ENT_QUOTES );
		
		// get superadministrators id
		foreach ( $rows as $row ) {
			if ($row->sendEmail) {
				$message2 = sprintf ( JText::_ ( 'SEND_MSG_ADMIN' ), $row->name, $sitename, $name, $email, $username );
				$message2 = html_entity_decode ( $message2, ENT_QUOTES );
				JUtility::sendMail ( $mailfrom, $fromname, $row->email, $subject2, $message2 );
			}
		}
	}

}
