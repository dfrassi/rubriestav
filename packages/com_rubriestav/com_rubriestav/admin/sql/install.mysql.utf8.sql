 CREATE TABLE  #__rubriestav_user(
 
  id int(10) unsigned  NOT NULL auto_increment,  
  codice_ente int(4) unsigned NOT NULL, /* 105 (usl5), 901 (aoup) ecc......*/
  matricola int(10) unsigned NOT NULL, /* senza prefisso regionale es. 3754 */
  cognome varchar(255), /* Frassi */
  nome varchar(255), /* David */
  qualifica varchar(255), /* COLLAB. TECNICO PROGRAMMATORE CAT D */
  azienda varchar(255), /* ESTAV */
  unita_operativa varchar(255), /* UOSI */
  sede_descrizione varchar(255), /* Palazzo USL 5 quartiere "Le Gondole" */
  sede_via varchar(255), /* Via Zamenhof, 1 */
  sede_cap varchar(255), /* 56100 */
  sede_citta varchar(255), /* Pisa */
  sede_provincia varchar(2), /* PI */
  fototessera_url varchar(255), /* http://ww.usl5.toscana.it/fototessere/3754.jpg */
  tele_1_prefisso varchar(4), /* 0587 */
  tele_1_numero varchar(7), /* 273962 */
  tele_altri_numeri varchar(255), /* 050-954250 (altro numero di Pisa) */
  voip varchar(3), /* 145 */
  email varchar(255), /* d.frassi@usl5.toscana.it */
  note varchar(255),
  datesignin datetime NOT NULL default '0000-00-00 00:00:00',
  datemodify datetime NOT NULL default '0000-00-00 00:00:00',
  datelastaccess datetime NOT NULL default '0000-00-00 00:00:00',
  checked_out int(11) NOT NULL default '0',
  checked_out_time datetime NOT NULL default '0000-00-00 00:00:00',
   published tinyint(1) NOT NULL default '0',  
  PRIMARY KEY  (id),
  index joomla_utenti_FKIndex1 (id)
) COMMENT='Contiene gli utenti registrati al portale';

 