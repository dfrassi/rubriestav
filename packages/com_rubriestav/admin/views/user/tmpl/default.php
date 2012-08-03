<?php defined('_JEXEC') or die('Restricted access'); 

//Ordering allowed ?
$ordering = ($this->lists['order'] == 'ordering');

//onsubmit="return submitform();"

//DEVNOTE: import html tooltips
JHTML::_('behavior.tooltip');

?>

<script language="javascript" type="text/javascript">
/**
* Submit the admin form
* 
* small hack: let task desides where it comes
*/
function submitform(pressbutton){
var form = document.adminForm;
   if (pressbutton)
    {form.task.value=pressbutton;}
     
	 if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')||(pressbutton=='approve')||(pressbutton=='unapprove')
	 ||(pressbutton=='orderdown')||(pressbutton=='orderup')||(pressbutton=='saveorder')||(pressbutton=='remove') )
	 {
	  form.controller.value="user_detail";
	 }
	try {
		form.onsubmit();
		}
	catch(e){}
	
	form.submit();
}


</script>

<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm" >

<div id="editcell">


<table>
				<tr>
					<td width="480">
					<?php 
					$session = JSession::getInstance('none',array());
		
					$ricerca = $this->search;
					if ($ricerca=="") {
						$ricerca=$session->get('search');
					}
					
					
					
					?>
						<?php echo JText::_( 'Filter' ); ?>:
						<input type="text" name="search" id="search" value="<?php echo $ricerca;?>" class="text_area" onchange="document.adminForm.submit();" />
						<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
						<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
					</td>
					
					
					
					
				</tr>
			</table>

	<table class="adminlist" >
	<thead>
		<tr ><th width="1%" style="text-align:left;">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="1%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', 'Azienda', 'h.azienda', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
	
			<th width="1%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', 'Matricola', 'h.matricola', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
				<th width="15%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', 'Nome', 'h.nome', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>		
				<th width="15%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', 'Cognome', 'h.cognome', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
				<th width="45%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', 'UO', 'h.unita_operativa', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
				<th width="15%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', 'Email', 'h.email', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
			
			<th width="15%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'Telephone', 'h.telephone', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
			
			
			
		</tr>
	</thead>	
	
	
	<?php 
	//echo $this->pulsanti; 
	$iconUnPublish = " <img border=\"0\" src=\"images/publish_x.png\" alt=\"add new hello world link\" />";
	$iconPublish = " <img border=\"0\" src=\"images/tick.png\" alt=\"add new hello world link\" />";		
	$user =& JFactory::getUser();
	$k = 0;
	
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$link 	= JRoute::_( 'index.php?option=com_rubriestav&controller=user_detail&task=edit&cid[]='. $row->id );
		$checked 	= JHTML::_('grid.checkedout',$row, $i );
		$published 	= JHTML::_('grid.published', $row, $i );		
		//$approved 	= JHTML::_('grid.approved', $row, $i );

		?>
		
		<?php
				
					$script_ceccato="";
					$ceccato = false;
					$ceccato = JTable::isCheckedOut($user->get ('id'), $row->checked_out);
				
		?>
		
		<tr class="<?php echo "row$k"; ?>">
			
			
			
			<td>
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td>
				<?php
				if (  $ceccato ) {
					echo $row->azienda;
				} else {
				?>
					<a <?php echo $script_ceccato; ?> href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit user' ); ?>">
						<?php echo $row->azienda; ?></a>
				<?php
				}
				?>
			</td>  
			
			
	
			<td>
				<?php
					echo $row->matricola;
				
				?>
			</td>
			<td>
				<?php
				if (  $ceccato ) {
					echo $row->nome;
				} else {
				?>
					<a <?php echo $script_ceccato; ?> href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit user' ); ?>">
						<?php echo $row->nome; ?></a>
				<?php
				}
				?>
			</td>
			<td>
				<?php
				if (  $ceccato ) {
					echo $row->cognome;
				} else {
				?>
					<a <?php echo $script_ceccato; ?> href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit user' ); ?>">
						<?php echo $row->cognome; ?></a>
				<?php
				}
				?>
			</td>
			<td>
				<?php
				if (  $ceccato ) {
					echo $row->unita_operativa;
				} else {
				?>
					<a <?php echo $script_ceccato; ?> href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit user' ); ?>">
						<?php echo $row->unita_operativa; ?></a>
				<?php
				}
				?>
			</td>
		
		<td>
				<?php
				if (  $ceccato ) {
					echo $row->email;
				} else {
				?>
					<a <?php echo $script_ceccato; ?> href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit user' ); ?>">
						<?php 
						$pos1 = stripos($row->email, 'email');
						$mailpulita = "";
						if ($pos1===false){
							$mailpulita = $row->email;
						}
						
						echo $mailpulita;
						
						
						?></a>
				<?php
				}
				?>
			</td>
		
			<td>
				<?php
				if (  $ceccato ) {
					echo $row->tele_1_prefisso;
				} else {
				?>
					<a <?php echo $script_ceccato; ?> href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit user' ); ?>">
						<?php echo $row->tele_1_prefisso; ?></a>
				<?php
				}
				?>
			
			</td>
			
			
			
			
			

		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
<tfoot>
		<td colspan="13">
			<?php echo $this->pagination->getListFooter(); ?>
			
		</td>
	</tfoot>
	</table>
</div>
<div>
<p><b>Nota:</b> I record che contengono l'icona lucchetto potrebbero essere in uso da parte di un utente regolarmente loggato. Si consiglia di riprovare in un secondo momento.</p>
</div>


<input type="hidden" name="controller" value="user" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />

<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
