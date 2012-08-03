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
		$document->addStyleSheet ( 'components/com_rubriestav/assets/css/menu.css' );
		$document->addScript('includes/js/joomla.javascript.js');
		
		$document->setTitle ( JText::_ ( 'Area utente' ) );
		
		
	
		$uri 	=& JFactory::getURI();
		$user 	=& JFactory::getUser();
		$uid = $user->get ( 'id' );
		$idjusers="";
		
		$model	=& $this->getModel();
			
		$this->setLayout('form');
		
		$lists = array();
		
		$detail	=& $this->get('data');
		$uid = $user->get ( 'id' );
		
		foreach ( $model->getrubriestavUserFromJuser($uid) as $rubriestav_user ) {
			$detail = $rubriestav_user;
		} 
		
		$isNew		= ($detail->id < 1);

	/*
	 * Questo � il controllo del checkedout dell'utente, � importante che l'utente trovi sempre libero. Sar� l'amministratore ad aspettare
	 */
	/*	if ($detail->checked_out!=$detail->idjusers && $detail->checked_out!=0 ) {
			$msg = JText::sprintf ( 'DESCBEINGEDITTED', JText::_ ( 'THE DETAIL' ), $detail->idjusers );
			$msg = "Attenzione, sulla richiesta ci sta lavorando l'amministratore, riprovare piu' tardi!";
			$mainframe->redirect ( 'index.php', $msg );
		}*/
		
		$text = $isNew ? JText::_( 'NEW' ) : JText::_( 'EDIT' );
		JToolBarHelper::title(   JText::_( 'Djf User - Utenti' ), 'user' );
		userHelperToolbar::save();
	
		if ($isNew)  {
			userHelperToolbar::cancel();
		} else {
			userHelperToolbar::cancel( 'cancel', 'Close' );
		}
		if (!$isNew)
		{
			$model->checkout( $user->get('id') );
			$detail->datesignin = $user->get('registerDate');
			$detail->datelastaccess = $user->get('lastvisitDate');

		} else {
			
			$detail->id=0;
			$detail->idjusers=0;
			$detail->username=null;
			$detail->firstname=null;
			$detail->surname=null;
			$detail->address=null;
			$detail->fax=null;
			$detail->ufficiostruttura=null;
			$detail->images=null;
			
			
			
			
			$detail->birthday=null;
			$detail->tax=null;
			$detail->telephone=null;
			$detail->mobile=null;
			$detail->email=null;
			$detail->website=null;
			$detail->note=null;
			
			$detail->datesignin=null;
			$detail->datemodify=null;
			$detail->datelastaccess=null;
			
			$detail->credits=null;			
			$detail->checked_out=0;
			$detail->checked_out_time=0;
			$detail->published=0;
		}
		$lists['utenti_associati'] = $this->Utenti_associati('idjusers', null, '', 'name',1, $detail->idjusers ) ;
		//$lists['stati_associati'] = $this->Stati_associati('nation', null, '', 'description',1, $detail->nation ) ;
		
		//$lists['nazionalita_associati'] = $this->Stati_associati('state', null, '', 'description',1, $detail->state ) ;
		//$lists['tipologie_utente'] = $this->Tipologia_utente('typology', null, '', 'name',1, $detail->typology ) ;
		//$lists['status_utente'] = $this->Status_utente('status', null, ' disabled = "disabled" ', 'name',1, $detail->status ) ;
		
		
		jimport('joomla.filter.filteroutput');
		
		JFilterOutput::objectHTMLSafe( $detail, ENT_QUOTES, 'description' );

		$passwordjoomla = utility::getField ('select password as value from #__users where id = '.$detail->idjusers);
	
		$items			= & $this->get( 'Data');
		$total			= & $this->get( 'Total');
		$query_per_log = 'select a.* from #__rubriestav_idr_log_user a, #__rubriestav_user b where a.iduser = b.id and b.idjusers = '.$user->id. ' and a.typology="bought" ';
		//echo($query_per_log);
		$items = utility::getArray($query_per_log, 'a.dateevent desc');
		
		$this->assignRef('items',		$items);
	
		$this->assignRef('lists',			$lists);
		$this->assignRef('isNew',			$isNew);
		$this->assignRef('passwordjoomla',			$passwordjoomla);
		$this->assignRef ( 'pulsanti', userHelperToolbar::getToolbar () );
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}		
	
	function user_associati( $name, $active=NULL, $javascript=NULL, $order='title', $size=1, $sel_desc=1 )
	{
		global $mainframe;
		$model	=& $this->getModel();
		$user_associati[] = JHTML::_('select.option', '0', '- '. JText::_( 'Seleziona un modulo' ) .' -' );
		$user_associati = array_merge( $user_associati, $model->getModules($order) );

		if ( count( $user_associati ) < 1 ) {
			$mainframe->redirect( 'index.php?option=com_rubriestav', JText::_( 'Devi prima creare un modulo associato.' ) );
		}

		$modulo = JHTML::_('select.genericList', $user_associati, $name, 'class="inputbox" size="'. $size .'" '. $javascript, 'value', 'text', $sel_desc );
		return $modulo;
	}
	
	function Utenti_associati( $name, $active=NULL, $javascript=NULL, $order='name', $size=1, $sel_desc=1 )
	{
		global $mainframe;
		$model	=& $this->getModel();
		$utenti_associati[] = JHTML::_('select.option', '0', '- '. JText::_( 'Seleziona un utente' ) .' -' );
		$utenti_associati = array_merge( $utenti_associati, $model->getUsers($order) );

		if ( count( $utenti_associati ) < 1 ) {
			$mainframe->redirect( 'index.php?option=com_rubriestav', JText::_( 'Devi prima creare un utente associato.' ) );
		}

		$utente = JHTML::_('select.genericList', $utenti_associati, $name, 'class="inputbox" size="'. $size .'" '. $javascript, 'value', 'text', $sel_desc );
		return $utente;
	}
	
	function Stati_associati( $name, $active=NULL, $javascript=NULL, $order='description', $size=1, $sel_desc=1 )
	{
		global $mainframe;
		$model	=& $this->getModel();
		$stati_associati[] = JHTML::_('select.option', '0', '- '. JText::_( 'Seleziona uno stato' ) .' -' );
		$stati_associati = array_merge( $stati_associati, $model->getState($order) );

		if ( count( $stati_associati ) < 1 ) {
			$mainframe->redirect( 'index.php?option=com_rubriestav', JText::_( 'Devi prima creare uno stato') );
		}

		$stato = JHTML::_('select.genericList', $stati_associati, $name, 'class="inputbox" size="'. $size .'" '. $javascript, 'value', 'text', $sel_desc );
		return $stato;
	}
	
	function Tipologia_utente( $name, $active=NULL, $javascript=NULL, $order='name', $size=1, $sel_desc=1 )
	{
		$tipologie_utente[2] = JHTML::_('select.option', '2', JText::_( 'User Player' )  );
		$tipologie_utente[1] = JHTML::_('select.option', '1', JText::_( 'User Agency' )  );
		//$tipologie_utente[0] = JHTML::_('select.option', '0', JText::_( 'Administrator' ));
	
		$tipologia = JHTML::_('select.genericList', $tipologie_utente, $name, 'class="inputbox" size="'. $size .'" '. $javascript, 'value', 'text', $sel_desc );
		return $tipologia;
	}
	
	function Status_utente( $name, $active=NULL, $javascript=NULL, $order='name', $size=1, $sel_desc=1 )
	{
		$status_utente[0] = JHTML::_('select.option', '0', JText::_( 'In attesa di conferma' )  );
		$status_utente[1] = JHTML::_('select.option', '1', JText::_( 'Confermato' )  );
		$status_utente[2] = JHTML::_('select.option', '2', JText::_( 'Bloccato' ));
		
		$status = JHTML::_('select.genericList', $status_utente, $name, 'class="inputbox" size="'. $size .'" '. $javascript, 'value', 'text', $sel_desc );
		return $status;
	}
}


?>
