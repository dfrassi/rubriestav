<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

//Ordering allowed ?
$ordering = ($this->lists ['order'] == 'ordering');

//onsubmit="return submitform();"


//DEVNOTE: import html tooltips
JHTML::_ ( 'behavior.tooltip' );

$mode = utility::getDjfVar ( "mode", "semplice" );
$search = utility::getDjfVar ( "search", "" );

$uri = & JFactory::getURI ();

if ($mode == 'semplice')
	$uristring =  "index.php?option=com_rubriestav&mode=semplice";
else
	$uristring =  "index.php?option=com_rubriestav&mode=avanzata";

?>

<script language="javascript" type="text/javascript">
function sub(){
	var form = document.adminForm;
	form.action="<?php echo $uristring; ?>";
	form.submit();
}

function resetSession(nome){
	var form = document.adminForm;
	form.action="<?php echo $uristring; ?>";
	form.submit();
}
</script>

<form method="post" name="adminForm">
<div id="editcell">
<div>
<fieldset>

<h1><?php echo JText::_ ( 'Ricerca Personale' ); ?></h1>
<!-- DATI UTENTE -->
<?php
$ricerca = utility::getDjfVar ( "search", '' );
if ($mode == 'semplice') {
	
	?>	
<div style="margin-left: 0px;">
<h3  id="1" style="font-size: 12px;"><span><?php
	echo JText::_ ( 'Ricerca Semplice' );
	?></span></h3>
<div class="jpane-slider content"
	style="background-color: white; margin-left: 0px;"><input type="text"
	name="search" id="search" value="<?php
	echo $ricerca?>"
	style="font-size: 1.2em; height: 25px; width: 250px;" onchange="sub();" />
<button onclick="sub();" style="height: 25px;"><?php
	echo JText::_ ( 'Go' );
	?></button>
<button style="height: 25px;"
	onclick="document.getElementById('search').value='';sub();"><?php
	echo JText::_ ( 'Reset' );
	?></button>
</div>
</div>
<?php
}
?>


</fieldset>


<?php
if ($mode == 'semplice') {
	?> 
<div>
<p><i>Nota:</i> La ricerca permette di ottenere una
lista di schede utente utilizzando una sola delle seguenti parole chiave in alternativa:
"nome", "cognome", "telefono", "altri numeri", "qualifica", "UO"
oppure tramite le coppie "nome cognome" oppure "cognome nome".
<!-- Per poter effettuare una ricerca che consideri pi&ugrave; attributi
contemporaneamente utilizzare la ricerca avanzata. --></p>
</div>
<?php
} else {
	?>
<div>
<p><i>Nota:</i> La modalit&agrave; avanzata permette di cumulare le
chiavi di ricerca in modo da impostare un certo numero di attributi
contemporaneamente.</p>
</div>
<?php
}
?>

</div>

<table class="adminlist"
	style="font-family: courier; font-size: 10px; text-transform: capitalize;">
	<thead>
		<tr>
			<!--<th width="1%" style="text-align: left;">
				<?php
				echo JText::_ ( 'NUM' );
				?>
			</th>
			<th width="1%" nowrap="nowrap" class="title"
				style="text-align: left;">
				<?php
				echo JHTML::_ ( 'grid.sort', 'Azienda', 'trim(h.azienda)', $this->lists ['order_Dir'], $this->lists ['order'] );
				?>
			</th>-->
					<th width="1%" nowrap="nowrap" class="title"
				style="text-align: left;">
				<?php
				echo JHTML::_ ( 'grid.sort', 'Cognome', 'trim(h.cognome)', $this->lists ['order_Dir'], $this->lists ['order'] );
				?>
			</th>	<th width="1%" nowrap="nowrap" class="title"
				style="text-align: left;">
				<?php
				echo JHTML::_ ( 'grid.sort', 'Nome', 'trim(h.nome)', $this->lists ['order_Dir'], $this->lists ['order'] );
				?>
			</th>

			<th width="25%" nowrap="nowrap" class="title"
				style="text-align: left;">
				<?php
				echo JHTML::_ ( 'grid.sort', 'Telefono', 'h.tele_1_prefisso', $this->lists ['order_Dir'], $this->lists ['order'] );
				?>
			</th>
				
			<th width="30%" nowrap="nowrap" class="title"
				style="text-align: left;">
				<?php
				echo JHTML::_ ( 'grid.sort', 'UO', 'trim(h.unita_operativa)', $this->lists ['order_Dir'], $this->lists ['order'] );
				?>
			</th>
			
			<th width="40%" nowrap="nowrap" class="title"
				style="text-align: left;">
				<?php
				echo JHTML::_ ( 'grid.sort', 'Qualifica', 'trim(h.qualifica)', $this->lists ['order_Dir'], $this->lists ['order'] );
				?>
				
			</th>

		
		</tr>
	</thead>	
	
	
	<?php
	//echo $this->pulsanti; 
	$iconUnPublish = " <img border=\"0\" src=\"images/publish_x.png\" alt=\"add new hello world link\" />";
	$iconPublish = " <img border=\"0\" src=\"images/tick.png\" alt=\"add new hello world link\" />";
	$user = & JFactory::getUser ();
	$k = 0;
	
	for($i = 0, $n = count ( $this->items ); $i < $n; $i ++) {
		$row = &$this->items [$i];
		$link = JRoute::_ ( 'index.php?option=com_rubriestav&controller=user_detail&task=edit&cid[]=' . $row->id );
		$script_ceccato = "";
		$ceccato = false;
		?>

		<tr class="<?php echo "row$k"; ?>"> 
		<!--<td><?php echo $this->pagination->getRowOffset ( $i ); ?> </td>
		<td>
			<?php echo $row->azienda; ?>
		</td>-->
		<td>
			<a style="font-size: 0.9em;"  href="<?php echo $link; ?>" title="<?php echo JText::_ ( 'Edit user' ); ?>">
			<?php echo $row->cognome; ?></a>
		</td>
<td>
			<a style="font-size: 0.9em;"  href="<?php echo $link; ?>" title="<?php echo JText::_ ( 'Edit user' ); ?>">
			<?php echo $row->nome; ?></a>
		</td>
				<td>
			<?php echo $row->numero_completo; ?>
		</td>
		
		<td>
			<?php echo $row->unita_operativa; ?>
		</td>
		
		<td>
			<?php echo $row->qualifica;	?>
		</td>
		
		</tr>
			<?php
				$k = 1 - $k;
			}
		?>
		<tfoot>
			<td colspan="8" >
				<?php
					echo $this->pagination->getListFooter ();
				?>
			</td>
		</tfoot>
</table>
</div>
<input type="hidden" name="controller" value="user" /> 
<input type="hidden" name="task" value="" /> 
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists ['order'];	?>" /> 
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists ['order_Dir']; ?>" />
</form>