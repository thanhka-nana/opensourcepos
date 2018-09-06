<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Feedback gevraagd!</title>
<STYLE type="text/css">
  body { background-color: #99CC00; }
 .headerTop { background-color:#FFCC66; border-top:0px solid #000000; border-bottom:1px solid #FFFFFF; text-align:center; }
 .adminText { font-size:10px; color:#996600; line-height:200%; font-family:verdana; text-decoration:none; }
 .headerBar { background-color:#FFFFFF; border-top:0px solid #333333; border-bottom:10px solid #FFFFFF; }
 .title { font-size:20px; font-weight:bold; color:#CC6600; font-family:arial; line-height:110%; }
 .subTitle { font-size:11px; font-weight:normal; color:#666666; font-style:italic; font-family:arial; }
 .defaultText { font-size:12px; color:#000000; line-height:150%; font-family:trebuchet ms; }
 .footerRow { background-color:#FFFFCC; border-top:10px solid #FFFFFF; }
 .footerText { font-size:10px; color:#996600; line-height:100%; font-family:verdana; }
 .backgroundTable { text-align:center; }
 a { color:#FF6600; color:#FF6600; color:#FF6600; }
</STYLE>
</head>
<body>
<?php $domain_root='https://www.runwalk.be'; ?>
<table width="100%" cellpadding="10" cellspacing="0" class="backgroundTable" bgcolor='#99CC00' >
<tr>
<td valign="top">


<table width="550" cellpadding="0" cellspacing="0" align="center" style="text-align:left;">
<tr>
<td style="background-color:#FFCC66;border-top:0px solid #000000;border-bottom:1px solid #FFFFFF;text-align:center;" align="center">
	<span style="font-size:10px;color:#996600;line-height:200%;font-family:verdana;text-decoration:none;">Wordt deze E-mail niet correct weergegeven? <a href="<?=site_url("site/feedback_mail/". $feedback_token) ?>" target="_blank" style="font-size:10px;color:#996600;line-height:200%;font-family:verdana;text-decoration:none;">Bekijk hem in je browser.</a></span>
</td>
</tr>
 
<tr>
<td style="background-color:#FFFFFF;border-top:0px solid #333333;border-bottom:10px solid #FFFFFF;"><center><a href="<?=$domain_root?>"><IMG id=editableImg1 SRC="<?=$domain_root?>/img/newsletterbanner.png" BORDER="0" alt="Runwalk - Uw runningspecialist"></a></center></td>
</tr>


</table>


<table width="550" cellpadding="20" cellspacing="0" align="center" bgcolor="#FFFFFF">
<tr>
<td bgcolor="#FFFFFF" valign="top" style="text-align:left;font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;">

<p>
<span style="font-size:20px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;">Uw mening interesseert ons!</span><br><br>
</p>
<p>Op <?=$creation_date?> bracht u een bezoek aan onze winkel. Wij hebben u toen een schoen en/of correctie geadviseerd. Graag blijven wij op de hoogte van de evolutie van uw loopprobleem.
Daarom hadden wij graag uw feedback ontvangen via de onderstaande link naar onze website</p>

<a href="<?=$domain_root?>/feedback-analyse/?id=<?=$feedback_token?>"><?=$domain_root?>/feedback-analyse/?id=<?=$feedback_token?></a><br>

<p>Alle gegevens die u ons verstrekt blijven strik vertrouwelijk en zijn enkel bestemd om u in de toekomst nog beter te kunnen begeleiden.</p>

<p>Alvast bedankt,</p>

<p>Christa &amp; Erik</p>
</td>
</tr>
<tr>
<td style="background-color:#FFFFCC;border-top:10px solid #FFFFFF;line-height:2px;text-align:left;" valign="top">
<span style="font-size:10px;color:#996600;line-height:13px;font-family:verdana;">
RunWalk - Uw runningspecialist<br>
Lichtaartseweg 49<br>
2200 Herentals<br>
Tel. 014/70 00 04<br>
<a href="mailto:info@runwalk.be">info@runwalk.be</a><br>
<a href="<?=$domain_root?>">http://www.runwalk.be</a><br>
<br>
<?php if (isset($clientId)): ?>
<a href="<?=$domain_root?>/uitschrijven-nieuwsbrief/?id=<?=$clientId?>" target="_blank">Verwijder</a> mijn adres van de mailinglist.<br>
<?php endif; ?>
<br>
</span>
</td>
</tr>
</table>
</td>
</tr>
</table>
<img src="<?=site_url('site/feedback_read/'.$feedback_token)?>">
</body>
</html>
