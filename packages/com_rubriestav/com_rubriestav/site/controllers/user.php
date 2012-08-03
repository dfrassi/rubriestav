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

$document = & JFactory::getDocument ();
$document->setTitle ( JText::_ ( 'Organigramma Aziendale' ) );
$document->addStyleSheet ( 'components/com_rubriestav/assets/css/icon.css' );
$document->addStyleSheet ( 'components/com_rubriestav/assets/css/general.css' );
$document->addStyleSheet ( 'components/com_rubriestav/assets/css/tree.css' );
$document->addScript ( 'includes/js/joomla.javascript.js' );

class userController extends JController {
	
	function __construct($default = array()) {
		parent::__construct ( $default );
	}
	
	function cancel() {
		$this->setRedirect ( 'index.php' );
	}
	
	/**
	 * Method display
	 * 
	 * 1) crea il controller
	 * 2) passa il modello alla vista
	 * 3) carica il template e lo renderizza  	  	 	 
	 */
	
	function publish() {
		$id = JRequest::getVar ( 'id' );
		utility::executeQuery ( "update #__users set block = 0 where id = $id" );
		//echo("update #__users set block = 0 where id = $id");
		parent::display ();
	}
	
	function unpublish() {
		$id = JRequest::getVar ( 'id' );
		utility::executeQuery ( "update #__users set block = 1 where id = $id" );
		//echo("update #__users set block = 1 where id = $id");
		parent::display ();
	}
	
	function display() {
		parent::display ();
	}
	
	function purge() {
		$model = $this->getModel ( 'user' );
		echo ($model->purgeUsers ());
		echo ($model->purgeLogUsers ());
		$this->setRedirect ( 'index.php?option=com_rubriestav&controller=user', 'Pulizia della tabelle eseguita con successo!' );
	}
	
	function importJUser() {
		$model = $this->getModel ( 'user' );
		echo ($model->importJUser ());
		$model->purgeUsers ();
		$this->setRedirect ( 'index.php?option=com_rubriestav&controller=user', 'Importazione utenti Joomla eseguita!' );
	}
	
	function getQueryArray($query) {
		global $mainframe;
		$db = & JFactory::getDBO ();
		$db->setQuery ( $query );
		
		return $db->loadObjectList ();
	}
	
	function getNode($idparent = "0", $i = 0) {
		
		global $mainframe;
		
		$solojob = Jrequest::getVar ( 'solojob' );
		
		$query = '
			select distinct
			sym.sym_name as id, 
			tb_uo.codice_funzionale,
			tb_uo.descrizione,
			tb_uo.id as uo_id,
			sym.sym_desc_e as name, 
			sym.sym_name as codice_padre,
			pc.parent_name as parent
			from
  		     	#__rubriestav_tb_child pc,
  				#__rubriestav_tb_symbol sym
          		left join #__rubriestav_tb_uo tb_uo on (sym.sym_name = tb_uo.codice_funzionale)
			where
				sym.dim_index = pc.dim_index
				and sym.sym_index = pc.sym_index
				and pc.parent_name = "' . $idparent . '" 
				and pc.dim_index = 2
				and sym.sym_name <> "BDXXX"
				and sym.sym_name <> "BDYYY"
				and sym.sym_name not like "BDA%"

			order by id';
		
		$risultato = $this->getQueryArray ( $query );
		
		if (sizeof ( $risultato ) > 0) {
			
			echo ("<ul>");
			foreach ( $risultato as $riga ) {
				$i ++;
				$id = $riga->id;
				$parent = $riga->parent;
				$codice_funzionale = $riga->codice_funzionale;
				$name = $riga->name;
				$codice_padre = $riga->codice_padre;
				$descrizione = $riga->descrizione;
				$pos = strpos ( $id, 'R' );
				if (! empty ( $riga->uo_id )){
					$name = $name . " - " . $codice_padre . " - " . $riga->uo_id;
					$descrizione = $descrizione." - ".$codice_padre." - ".$riga->uo_id;
				}
				else{
					$name = $name . " - " . $codice_padre;
					$descrizione = $descrizione." - ".$codice_padre;
				}
				if ($pos === 0) {
					if (! empty ( $codice_funzionale )) {
						echo ('<li id="' . $id . '" class="foglie"><a href="index.php?option=com_rubriestav&controller=user&mode=avanzata&search_uo=' . $riga->uo_id . '">' . $descrizione . '</a>');
					} else {
						if ($solojob!="true")
							echo ('<li id="' . $id . '" class="foglie" >' . $name);
					}
				
				} else
					echo ('<li id="' . $id . '" class="nodi" >' . $name);
				
		//exit();
				$this->getNode ( $id, $i );
				echo ('</li>');
			
			}
			echo ("</ul>");
		}
	
	}
	
	function tree() {
		
		global $mainframe;
	
		?>
			<fieldset style="font-size: 10x;"><legend><?php echo JText::_ ( 'Organigramma Aziendale' ); ?></legend>
			<div style="text-align:right;"><a  href="index.php?option=com_rubriestav&controller=user&task=tree&solojob=true">Solo Jobtime</a> - <a href="index.php?option=com_rubriestav&controller=user&task=tree&solojob=false">Anche Budget</a></div>
	
		<div id="mainpage">

		<ul>
			<li id="node0" noDrag="true" noSiblings="true" noDelete="true" noRename="true">
				<a href="#">Root node</a>
					<?php echo ($this->getNode ( "BD1" )); ?>
			</li>
		</ul>
</div>
</fieldset>
<?php
	}
}
?>