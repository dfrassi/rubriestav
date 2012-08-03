<?php
/**

 * php echo $lang->getName();
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

//DEVNOTE: import html tooltips
JHTML::_ ( 'behavior.tooltip' );
?>


<form action="<?php echo JRoute::_ ( $this->request_url )?>" method="post" name="adminForm" id="adminForm"   enctype="multipart/form-data">


<fieldset class="adminform">
<div class="col50" >

	<legend><?php echo JText::_ ( 'Dettaglio Scheda Utente' ); ?></legend>


<table class="admintable" style="width:100%;" ><tr><td>

<table class="admintable"  >


<tr>
		<td valign="top" align="right" class="key" style="width:200px;text-align:right;">
		<label for="id_modules">
		 <?php
			echo JText::_ ( 'Cognome' );
			?>: </label></td>
		<td>
			<input class="text_area" type="hidden" name="cognome" id="cognome" size="60" value="<?php echo $this->detail->cognome; ?>"/>
			<?php echo ('<span style="color:blue;font-weight:bold;font-size:1.2em;">'.$this->detail->cognome.'</span>');  ?>
		</td>
	</tr>
	<tr>
		<td valign="top" align="right" class="key" style="width:200px;text-align:right;">
			<label for="id_modules"><?php echo JText::_ ( 'Nome' ); ?>: </label>
		</td>
		<td>
			<input class="text_area" type="hidden" name="nome" id="nome" size="60" value="<?php echo $this->detail->nome; ?>" />
			 <?php echo ('<span style="color:blue;font-weight:bold;font-size:1.2em;">'.$this->detail->nome.'</span>');  ?>
		</td>
	</tr>
	
	<tr>
		<td valign="top" align="right" class="key" style="width:200px;text-align:right;"><label for="id_modules"><?php
		echo JText::_ ( 'Matricola' );
		?>: </label></td>
		<td style="width: 200px;">
		<input class="text_area" readonly="readonly" type="hidden" name="matricola" id="matricola" size="60" value="<?php echo $this->detail->matricola; ?>" />
		<?php echo ('<span >'.$this->detail->matricola.'</span>');  ?>

		</td>
	</tr>
	
	<tr>
		<td valign="top" align="right" class="key" style="width:200px;text-align:right;"><label for="id_modules"><?php
		echo JText::_ ( 'Qualifica' );
		?>: </label></td>
		<td><?php
		echo $this->detail->qualifica;
		?></td>
	</tr>

	<tr>
		<td valign="top" align="right" class="key" style="width:200px;text-align:right;"><label for="id_modules"><?php
		echo JText::_ ( 'E-mail' );
		?>: </label></td>
		<td style="width: 350px;">
			<?php
				$mailmia = $this->detail->email;
				
				if (!utility::startsWith($mailmia,$this->detail->matricola))
				
				echo ('<img src="media/com_rubriestav/images/email_famfamfam.gif"/>&nbsp;&nbsp;'."<a style='font-size:0.9em;' href='mailto:".$mailmia."'>".$mailmia."</a>");
			 ?>
		</td>
	</tr>


	<tr>
		<td valign="top" align="right" class="key" style="width:200px;text-align:right;"><label for="id_modules"><?php
		echo JText::_ ( 'Numero' );
		?>: </label></td>
		<td >
			<?php echo ('<img src="media/com_rubriestav/images/phone.png"/>&nbsp;&nbsp;<span style="font-weight:bold;">0'.$this->detail->tele_1_prefisso." - ".$this->detail->tele_1_numero.'</span>');  ?>
		</td>
	</tr>

	<tr>
		<td valign="top" align="right" class="key" style="width:200px;text-align:right;"><label for="id_modules"><?php
		echo JText::_ ( 'Altri numeri' );
		?>: </label></td>
		<td>
		<?php echo ('<img src="media/com_rubriestav/images/contatto_cell.gif"/>&nbsp;&nbsp;<span style="font-weight:bold;">'.$this->detail->tele_altri_numeri.'</span>');  ?>
		</td>
	</tr>

	
	<tr>
		<td valign="top" align="right" class="key" style="width:200px;text-align:right;"><label for="id_modules"><?php
		echo JText::_ ( 'Unit&agrave; Operativa' );
		?>: </label></td>
		<td>
			
			<?php echo $this->detail->unita_operativa;  ?>
		</td>
	</tr>


	<tr>
		<td valign="top" align="right" class="key" style="width:200px;text-align:right;"><label for="id_modules"><?php
		echo JText::_ ( 'Sede di lavoro' );
		?>: </label></td>
		<td>
			<?php  echo $this->detail->sede_descrizione;  ?><br>
			<?php  echo $this->detail->sede_via;  ?><br>
			<?php  echo $this->detail->sede_cap;  ?>
			<?php  echo $this->detail->sede_citta;  ?>
			(<?php  echo $this->detail->sede_provincia;  ?>)
		</td>
	</tr>


<?php if ($this->detail->note != "*\r"){?>

	<tr>
		<td valign="top" align="right" class="key" style="width:200px;text-align:right;"><label for="id_modules"><?php
		echo JText::_ ( 'Note' );
		?>: </label></td>
		<td>
			
		    <?php   echo $this->detail->note;  ?>
		</td>
	</tr>
<?php } ?>





</table>
</td>
<td class="key" style="vertical-align:top;width:180px;">
<div style="margin-top:5px;padding:0;text-align:center;">
<a href="javascript:history.back();"/>Torna indietro</a><br><br>
<?php
		$commento_nofoto="<div style=\"text-align:center;font-weight:normal;\">commento</div>";
		global $mainframe;
		if (!empty($this->detail->fototessera_url)){
			 $urlimage = $this->detail->fototessera_url;
		}else{
			$urlimage = 'media/com_rubriestav/images/nofoto.jpg';
			$commento_nofoto = 'Ogni utente pu&ograve; caricare in quest\'area una sua foto personale. Al momento per questo utente la foto non &egrave; disponibile';
		}

	?>
		<!--<img width="150" style="margin-top:0;border:2px solid #cccccc;" src="<?php echo($urlimage);?>"/>--> <br/>
		<p style="text-align:justify;font-weight:normal;margin-left:14px;margin-right:14px;"><?php //echo $commento_nofoto;?>


</div>

<div style="margin-top:38px;text-align:center;">
		<?php echo ('Ultimo Aggiornamento:<br>');  ?>
		<?php echo ($this->detail->datemodify);  ?>
</div>
</td></tr></table>

</div>
</fieldset>


<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="user_detail" />
</form>


