<html>
<head>
<title>Runwalk Nieuwsbrief <?=$newsid?></title>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" bgcolor='#99CC00' >


<STYLE>
 .headerTop { background-color:#FFCC66; border-top:0px solid #000000; border-bottom:1px solid #FFFFFF; text-align:center; }
 .adminText { font-size:10px; color:#996600; line-height:200%; font-family:verdana; text-decoration:none; }
 .headerBar { background-color:#FFFFFF; border-top:0px solid #333333; border-bottom:10px solid #FFFFFF; }
 .title { font-size:20px; font-weight:bold; color:#CC6600; font-family:arial; line-height:110%; }
 .subTitle { font-size:11px; font-weight:normal; color:#666666; font-style:italic; font-family:arial; }
 .defaultText { font-size:12px; color:#000000; line-height:150%; font-family:trebuchet ms; }
 .footerRow { background-color:#FFFFCC; border-top:10px solid #FFFFFF; }
 .footerText { font-size:10px; color:#996600; line-height:100%; font-family:verdana; }
 a { color:#FF6600; color:#FF6600; color:#FF6600; }
</STYLE>



<table width="100%" cellpadding="10" cellspacing="0" class="backgroundTable" bgcolor='#99CC00' >
<tr>
<td valign="top">


<table width="550" cellpadding="0" cellspacing="0" align="center" style="text-align:left;">
<?php if (isset($clientId)) : ?>
<tr>
<td style="background-color:#FFCC66;border-top:0px solid #000000;border-bottom:1px solid #FFFFFF;text-align:center;" align="center"><span style="font-size:10px;color:#996600;line-height:200%;font-family:verdana;text-decoration:none;">Wordt deze E-mail niet correct weergegeven? <a href="http://www.runwalk.be/index.php/newsletters/show/<?=$newsid?>/<?=$clientId?>" target="_blank" style="font-size:10px;color:#996600;line-height:200%;font-family:verdana;text-decoration:none;">Bekijk hem in je browser.</a></span></td>
</tr>

<?php endif; ?>
 
<tr>
<td style="background-color:#FFFFFF;border-top:0px solid #333333;border-bottom:10px solid #FFFFFF;"><center><a href=""><IMG id=editableImg1 SRC="http://www.runwalk.be/img/newsletterbanner.png" BORDER="0" alt="Runwalk - Uw runningspecialist" align="center"></a></center></td>
</tr>


</table>


<table width="550" cellpadding="20" cellspacing="0" align="center" bgcolor="#FFFFFF">
<tr>
<td bgcolor="#FFFFFF" valign="top" style="text-align:left;font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;">

<p>
<span style="font-size:20px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;">&#8220;De tien van Hertals&#8221;</span><br><br>
<span style="font-size:12px;font-weight:normal;color:#666666;font-style:italic;font-family:arial;">Startschot op zondag 17 mei 2009</span><br><br>
Beste<?php echo ' ' . $name=(isset($firstname)) ? " " . $firstname : 'loopliefhebber'; ?>, <br><br>

Hou je van sportieve lentekriebels? Gewoon een gezellige natuurloop voor de lol en de ontspanning? Of een 10 km wedstrijd op eigen niveau?
Tijdens de 'tien van Hertals' is er voor ieder wel wat wils.<br><br>
Je kan zowel deelnemen aan een recreatieve loop van 5 km voor recreatievelingen en een 10 km wedstrijd. Ook is er een jeugdloop voorzien voor de jongeren met aangepaste afstanden per leeftijdscategorie. Voor de afstanden van 5 en 10 km zijn er geldprijzen per categorie en vele andere naturaprijzen uit de winkel te winnen. Deze wedstrijd zal van start gaan op zondag 17 mei aan de petanqueclub in de Wijngaard. Voor meer inlichtingen, kijk online op <a href="http://detienvanhertals.runwalk.be">http://detienvanhertals.runwalk.be</a>. 
Online inschrijven kan je op <a href="http://www.runwalk.be/index.php/tienkm/subscribe/">op deze pagina.</a>.
</p>
<p>Sportieve groetjes en tot dan!<br>
Het RunWalk-team.</p>
<?php $this->load->view('tienkm/includes/sponsors');?>
</td>
</tr>
<tr>
<td style="background-color:#FFFFCC;border-top:10px solid #FFFFFF;line-height:2px;text-align:left;" valign="top">
<span style="font-size:10px;color:#996600;line-height:13px;font-family:verdana;">
Runwalk Nieuwsbrief #<?=$newsid?> <br />
<br />
RunWalk - Uw runningspecialist<br />
Lichtaartseweg 49<br />
2200 Herentals<br />
Tel. 014/70 00 04<br />
<a href="mailto:info@runwalk.be">info@runwalk.be</a><br />
<a href="http://www.runwalk.Be">http://www.runwalk.be</a><br />
<br />
<?php if (isset($clientId)): ?>
<a href="http://www.runwalk.be/index.php/site/unsubscribe/<?=$clientId?>" target="_blank">Verwijder</a> mijn adres van de mailinglist.<br />
<?php endif; ?>
<br />
</span>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>