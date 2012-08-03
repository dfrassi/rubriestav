<?php defined('_JEXEC') or die('Restricted access'); 

//Ordering allowed ?
$ordering = ($this->lists['order'] == 'ordering');

//DEVNOTE: import html tooltips
JHTML::_('behavior.tooltip');


?>


<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm"   enctype="multipart/form-data">
<div class="col50">
<fieldset class="adminform"><legend><?php echo JText::_ ( 'Carica il file CSV' ); ?></legend>
	<table class="admintable" style="text-align:left;">
	<thead>
	
	<tr>
		 <td class="key">
			<label for="title"><?php echo JText::_ ( 'Tracciato Utenti Rubrica' ); ?>:</label>
		</td>
		  <td>
			<input type="file" name="upload" id="upload" size="30" maxlength="512" /> 
			<input type="hidden" name="update_file" value="TRUE" />
			<input type="hidden" name="filename" value="" />
			<input type="hidden" name="filename_sys" value="" />
			<input type="submit" value="<?php echo JText::_ ( 'INVIO' ); ?>"/>
		</td>
		
	</tr>
	
		
	</thead>	
	

	</table> 
	 </fieldset>
</div>

<input type="hidden" name="controller" value="importusers" />
<input type="hidden" name="task" value="import" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
