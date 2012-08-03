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
jimport ( 'joomla.filesystem.archive' );
jimport ( 'joomla.filesystem.file' );

class importusersController extends JController {
	
	function __construct($default = array()) {
		parent::__construct ( $default );
	}
	
	function cancel() {
		$this->setRedirect ( 'index.php' );
	}
	
	function display() {
		parent::display ();
	}
	
	function saveFile() {
		global $mainframe;
		$file = JRequest::getVar ( 'upload', null, 'files', 'array' );
		jimport ( 'joomla.filesystem.file' );
		
		//Clean up filename to get rid of strange characters like spaces etc
		if ($_FILES ['upload'] ['size'] > 2500000) {
			$mainframe->enqueueMessage ( JText::_ ( 'DIMENSIONE_ERRATA' ), 'error' );
			return false;
		}
		$filename = JFile::makeSafe ( $file ['name'] );
		if (strtolower ( JFile::getExt ( $filename ) ) == 'zip' || 
			strtolower ( JFile::getExt ( $filename ) ) == 'csv') {
			//Set up the source and destination of the file
			$src = $file ['tmp_name'];
			
			if (JFolder::exists ( JPATH_BASE . DS . "components" . DS . "com_rubriestav" . DS . "tracciati" )) {
				JFolder::delete ( JPATH_BASE . DS . "components" . DS . "com_rubriestav" . DS . "tracciati" );
			}
			JFolder::create ( JPATH_BASE . DS . "components" . DS . "com_rubriestav" . DS . "tracciati" . DS );
			$dest = JPATH_BASE . DS . "components" . DS . "com_rubriestav" . DS . "tracciati" . DS . $filename;
			
			JFile::upload ( $src, $dest );
			return true;
		} else {
			$mainframe->enqueueMessage ( JText::_ ( 'ESTENSIONE_ERRATA' ), 'error' );
			return false;
		}
	}
	
	function import() {
		
		global $mainframe;
		jimport ( 'joomla.filesystem.file' );
		$file = JRequest::getVar ( 'upload', null, 'files', 'array' );
		$filename = JFile::makeSafe ( $file ['name'] );
		$ext_originaria = JFile::getExt ( $filename );
		if (! $this->saveFile ()) {
			$mainframe->enqueueMessage ( JText::_ ( 'RIPRISTINO_ERRORE' ), 'error' );
			return $this->execute ( 'importusers' );
		}
		if ($ext_originaria == 'zip') {
			$zipAdapter = & JArchive::getAdapter ( 'zip' );
			$zipAdapter->extract ( JPATH_BASE . DS . "components" . DS . "com_rubriestav" . DS . "tracciati" . DS . $filename, JPATH_BASE . DS . "components" . DS . "com_rubriestav" . DS . "tracciati" . DS . "tracciati" . DS );
			$files = JFolder::files ( JPATH_BASE . DS . "components" . DS . "com_rubriestav" . DS . "tracciati" . DS . "tracciati", '.', false, false );
		}else{
			$files = JFolder::files ( JPATH_BASE . DS . "components" . DS . "com_rubriestav" . DS . "tracciati" , '.', false, false );
		}
		
		
		foreach ( $files as $file ) {
			if (strtolower ( JFile::getExt ( $file ) ) == 'csv') {
				if ($ext_originaria == 'zip'){
				$stringa = JFile::read ( JPATH_BASE . DS . "components" . DS . "com_rubriestav" . DS . "tracciati" . DS . "tracciati" . DS . $file );
				}else{
					$stringa = JFile::read ( JPATH_BASE . DS . "components" . DS . "com_rubriestav" . DS . "tracciati" .  DS . $file );
				}
				$righe = explode ( "\n", $stringa );
				
				$i = 0;
				$errore = false;
				foreach ( $righe as $riga ) {
					if (!empty($riga))
					if ($i > 0) {
						$riga = str_replace("'", "\'", $riga);
						$token = explode ( ";", $riga );
						$prepareStatement = "";
						$u = 0;
						foreach ( $token as $questoToken ) {
							$prepareStatement .= ",'" . $questoToken . "'";
							if ($u == 0)
								$codiceAsl = $questoToken;
							if ($u == 1)
								$matricola = $questoToken;
							$u ++;
						}
						if ($u != 19) {
							echo ("<b>ERRORE a riga " . $i . ":</b> numero campi separati da ';' = <b>" . $u . "</b><br>");
							echo ($riga . "<br>");
							echo ("La riga non verr&agrave; inserita.<br>
							Il numero dei campi esatto deve essere: 19<br><hr>");
							$errore = true;
						
		//exit();
						} else {
							
							$cancellaRighe = " delete from #__rubriestav_user where id = '" . $codiceAsl . $matricola . "'";
							utility::executeQuery ( $cancellaRighe );
							
							$prepareStatement = " insert into #__rubriestav_user values(" . $codiceAsl . $matricola . $prepareStatement;
							$prepareStatement .= ",'0000-00-00 00:00:00','" . utility::getDataOdierna ( 'Y-m-d H:i:s' ) . "','0000-00-00 00:00:00',0,'0000-00-00 00:00:00',0);\n";
							
							utility::executeQuery ( $prepareStatement );
						}
					}
					$i ++;
				}
				if (! $errore) {
					$mainframe->enqueueMessage ( JText::_ ( 'Import Tracciati - Procedura terminata senza errori' ), 'message' );
					return $this->execute ( 'importusers' );
				} else {
					$mainframe->enqueueMessage ( JText::_ ( 'Import Tracciati - Errore' ), 'error' );
					return $this->execute ( 'importusers' );
				}
			} else {
				$mainframe->enqueueMessage ( JText::_ ( 'Import Tracciati - Errore' ), 'error' );
				return $this->execute ( 'importusers' );
			}
		}
		$mainframe->enqueueMessage ( JText::_ ( 'Import Tracciati - Errore' ), 'error' );
		JFolder::delete ( JPATH_BASE . DS . "components" . DS . "com_rubriestav" . DS . "tracciati" );
		return $this->execute ( 'importusers' );
		
		parent::display ();
	
	}
}
?>
