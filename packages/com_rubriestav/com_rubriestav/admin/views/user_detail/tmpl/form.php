<?php
/**

 * php echo $lang->getName();  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

//DEVNOTE: import html tooltips
JHTML::_ ( 'behavior.tooltip' );
?>

<?php
$nuovoutente = 0;
if ($this->detail->id == 0) {
	$nuovoutente = 1;
}

$params = JComponentHelper::getParams ( 'com_rubriestav' );
$rubriestav = $params->get ( 'rubriestav' );

?>

<script type="text/javascript">

	function submitbutton(pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		
		if (pressbutton == 'save') {

			if (form.username.value == ""){
				alert( "<?php
				echo JText::_ ( 'Inserire un Nome utente', true );
				?>" );					
			} 

			<?php
			if ($nuovoutente == 1) {
				?>
			else 
			if (form.password.value == ""){
				alert( "<?php
				echo JText::_ ( 'Digitare una password', true );
				?>" );					
			}
			else
			if (form.password.value != form.password2.value){
				alert( "<?php
				echo JText::_ ( 'Digitare due volte la stesa password', true );
				?>" );	
			}
			<?php
			}
			?>

			else
				if (form.firstname.value == ""){
					alert( "<?php
					echo JText::_ ( 'Inserire un Firstname', true );
					?>" );			
				} 
				else 
				if (form.surname.value == ""){
					alert( "<?php
					echo JText::_ ( 'Inserire un Surname', true );
					?>" );	
				} 
				else 
				if (form.email.value == ""){
					alert( "<?php
					echo JText::_ ( 'Inserire una Mail', true );
					?>" );	
				} 
					
			<?php
			if ($nuovoutente == 0) {
				?>

			
			else if (form.password.value != "" || form.password2.value != "" ){
				if (form.password.value == ""){
					alert( "<?php
				echo JText::_ ( 'Digitare una password', true );
				?>" );					
				}
				else
				if (form.password.value != form.password2.value){
					alert( "<?php
				echo JText::_ ( 'Digitare due volte la stesa password', true );
				?>" );	
				}
				
				else{
					submitform(pressbutton);
					return;
				}
			}
		
			<?php
			}
			?>


			
		
			else {
				submitform(pressbutton);
				return;
			}
		}
	}
</script>
<style type="text/css">
table.paramlist td.paramlist_key {
	width: 92px;
	text-align: left;
	height: 30px;
}
</style>




<form action="<?php echo JRoute::_ ( $this->request_url )?>" method="post" name="adminForm" id="adminForm"   enctype="multipart/form-data">

<div class="col50">
<fieldset class="adminform"><legend><?php
echo JText::_ ( 'Dettaglio utente rubriestav' );
?></legend>

<table class="admintable">


	<tr>
		<td valign="top" align="right" class="key"><label for="id_modules"><?php
		echo JText::_ ( 'Username' );
		?>: </label></td>
		<td style="width: 400px;"><input class="text_area" type="text"
			name="username" id="username" size="100"
			value="<?php
			echo $this->detail->username;
			?>" /></td>
	</tr>



	<tr>
		<td valign="top" align="right" class="key"><label for="password">
			<?php
			echo JText::_ ( 'Password' );
			?>:
		</label></td>
		<td><input class="inputbox validate-password" type="password"
			id="password" name="password" value="" size="40" /></td>
	</tr>
	<tr>
		<td valign="top" align="right" class="key"><label for="password2">
			<?php
			echo JText::_ ( 'Verifica Password' );
			?>:
		</label></td>
		<td><input class="inputbox validate-passverify" type="password"
			id="password2" name="password2" size="40" /></td>
	</tr>
	<tr>
		<td valign="top" align="right" class="key"><label for="id_modules">
		<?php
		echo JText::_ ( 'Nome' );
		?>: </label></td>
		<td><input class="text_area" type="text" name="firstname"
			id="firstname" size="100"
			value="<?php
			echo $this->detail->firstname;
			?>" /></td>
	</tr>
	<tr>
		<td valign="top" align="right" class="key"><label for="id_modules">
		 <?php
			echo JText::_ ( 'Cognome' );
			?>: </label></td>
		<td><input class="text_area" type="text" name="surname" id="surname"
			size="100" value="<?php
			echo $this->detail->surname;
			?>" /></td>
	</tr>
	<tr>
		<td valign="top" align="right" class="key"><label for="id_modules"><?php
		echo JText::_ ( 'email' );
		?>: </label></td>
		<td><input class="text_area" type="text" name="email" id="email"
			size="100" maxlength="450"
			value="<?php
			echo $this->detail->email;
			?>" /></td>
	</tr>


	<tr>
		<td valign="top" align="right" class="key"><label for="id_modules"><?php
		echo JText::_ ( 'Indirizzo' );
		?>: </label></td>
		<td><input class="text_area" type="text" name="address" id="address"
			size="100"
			value="<?php
			echo $this->detail->address;
			?>" /></td>
	</tr>


	<tr>
		<td valign="top" align="right" class="key"><label for="id_modules"><?php
		echo JText::_ ( 'Data di nascita' );
		?>: </label></td>
		<td> 
					<?php
					$nascita = $this->detail->birthday;
					if ($nascita == '0000-00-00 00:00:00') {
						$this->detail->birthday = $this->detail->nascita;
					}
					echo JHTML::_ ( 'calendar', $this->detail->birthday, 'birthday', 'birthday', '%Y-%m-%d %H:%M:%S', array ('class' => 'inputbox', 'size' => '25', 'maxlength' => '19' ) );
					?>		
					
					 </td>
	</tr>

	<tr>
		<td valign="top" align="right" class="key"><label for="id_modules"><?php
		echo JText::_ ( 'Telefono' );
		?>: </label></td>
		<td><input class="text_area" type="text" name="telephone"
			id="telephone" size="100"
			value="<?php
			echo $this->detail->telephone;
			?>" /></td>
	</tr>

	<tr>
		<td valign="top" align="right" class="key"><label for="id_modules"><?php
		echo JText::_ ( 'Cellulare' );
		?>: </label></td>
		<td><input class="text_area" type="text" name="mobile" id="mobile"
			size="100" value="<?php
			echo $this->detail->mobile;
			?>" /></td>
	</tr>

	<tr>
		<td valign="top" align="right" class="key"><label for="id_modules"><?php
		echo JText::_ ( 'Fax' );
		?>: </label></td>
		<td><input class="text_area" type="text" name="fax" id="fax"
			size="100" value="<?php
			echo $this->detail->fax;
			?>" /></td>
	</tr>
	<!--	<tr>
					<td valign="top" align="right" class="key"><label for="id_modules"><?php
					echo JText::_ ( 'Piano Edificio' );
					?>: </label></td>
					<td><input class="text_area" type="text" name="pianoedificio" id="pianoedificio" size="100"
						 value="<?php
							echo $this->detail->pianoedificio;
							?>" /></td>
				</tr>-->

	<tr>
		<td valign="top" align="right" class="key"><label for="id_modules"><?php
		echo JText::_ ( 'Ufficio Struttura' );
		?>: </label></td>
		<td><input class="text_area" type="text" name="ufficiostruttura"
			id="ufficiostruttura" size="100"
			value="<?php
			echo $this->detail->ufficiostruttura;
			?>" /></td>
	</tr>


	<!-- <tr>
					<td valign="top" align="right" class="key"><label for="id_modules"><?php
					echo JText::_ ( 'website' );
					?>: </label></td>
					<td><input class="text_area" type="text" name="website" id="website" size="100"
						 value="<?php
							echo $this->detail->website;
							?>" /></td>
				</tr>-->
	<tr>
		<td valign="top" align="right" class="key"><label for="id_modules"><?php
		echo JText::_ ( 'Note' );
		?>: </label></td>
		<td><input class="text_area" type="text" name="note" id="note"
			size="100" value="<?php
			echo $this->detail->note;
			?>" /></td>
	</tr>
	<tr>
		<td valign="top" align="right" class="key"><label for="id_modules"><?php
		echo JText::_ ( 'Data ultimo accesso' );
		?>: </label></td>
		<td><input type="text" disabled="disabled" size="100"
			name="datelastaccess"
			value="<?php
			echo $this->detail->datelastaccess;
			?>" /></td>
	</tr>
	
	
	<tr>
		<td valign="top" align="right" class="key">
		<label for="images"><?php echo JText::_ ( 'Immagine' ); ?>: </label></td>
		<td>
		
			<?php 
			
			if ($this->detail->images!=""){
			 $urlimage = $mainframe->getSiteURL().'images/stories/rubriestav_folder/uploads/' . $this->detail->images;
			
				?><img width="150" src="<?php echo($urlimage);?>"/> <br/>
				
				
			<?php } ?>
		
			<input type="file" name="upload" id="upload" size="30" maxlength="512" /> 
			<input type="hidden" name="update_file" value="TRUE" />
			<input type="hidden" name="filename" value="" />
			<input type="hidden" name="filename_sys" value="" />

		</td>
	</tr>
	
	<?php
	if ($rubriestav == "1") {
		?>
				<tr>
		<td valign="top" align="right" class="key"><label for="id_modules"><?php
		echo JText::_ ( 'Data Assunzione' );
		?>: </label></td>
		<td><?php
		echo $this->detail->assunzione;
		?></td>
	</tr>
	<tr>
		<td valign="top" align="right" class="key"><label for="id_modules"><?php
		echo JText::_ ( 'Zona' );
		?>: </label></td>
		<td><?php
		echo $this->detail->zona;
		?></td>
	</tr>
	<tr>
		<td valign="top" align="right" class="key"><label for="id_modules"><?php
		echo JText::_ ( 'Unit&agrave; Operativa' );
		?>: </label></td>
		<td><?php
		echo $this->detail->uo;
		?></td>
	</tr>
	<tr>
		<td valign="top" align="right" class="key"><label for="id_modules"><?php
		echo JText::_ ( 'Qualifica' );
		?>: </label></td>
		<td><?php
		echo $this->detail->qualifica;
		?></td>
	</tr>

	<tr>
		<td valign="top" align="right" class="key"><label for="id_modules"><?php
		echo JText::_ ( 'Ruolo' );
		?>: </label></td>
		<td><?php
		echo $this->detail->ruolo;
		?></td>
	</tr>

	<tr>
		<td valign="top" align="right" class="key"><label for="id_modules"><?php
		echo JText::_ ( 'Categoria Giuridica' );
		?>: </label></td>
		<td><?php
		echo $this->detail->categoria_giuridica;
		?></td>
	</tr>
				
				
				
				<?php
	}
	?>
				
			<!-- 	<tr>
					<td valign="top" align="right" class="key"><label for="id_modules"><?php
					echo JText::_ ( 'datesignin' );
					?>: </label></td>
					<td><input type="text" disabled="disabled" size="100" name="datesignin" value="<?php
					echo $this->detail->datesignin;
					?>"/></td>
				</tr>
				
					<tr>
					<td valign="top" align="right" class="key"><label for="id_modules"><?php
					echo JText::_ ( 'datemodify' );
					?>: </label></td>
					<td><input type="text" disabled="disabled" size="100" name="datemodify" value="<?php
					echo $this->detail->datemodify;
					?>"/></td>
				</tr>
				 -->
	




</table>
</fieldset>


</div>
<div class="col50"></div>
<div class="clr"></div>

<input type="hidden" name="cid[]"
	value="<?php
	echo $this->detail->id;
	?>" /> <input type="hidden"
	name="task" value="" />
<?php
if ($this->detail->idjusers != 0) {
	?>
<input type="hidden" name="idjusers"
	value="<?php
	echo $this->detail->idjusers;
	?>" />
<?php
}
?>
<input type="hidden" name="controller" value="user_detail" /></form>


