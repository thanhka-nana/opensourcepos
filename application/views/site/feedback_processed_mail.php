<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<body>
<p>Er werd een feedback formulier vanop de website ingevuld en vestuurd.<br>
De volgende gegevens werden ingevoerd:</p>
<div>
	Voornaam + Naam: <?=$first_name. " " . $last_name?><br>
	Datum bezoek: <?=$creation_date?><br>
	Dagen nadien: <?=$interval?> dagen<br>
	Progressie: <strong><?=$this->lang->line("score_" . $this->input->post(ANALYSIS_SCORE))?></strong><br>
	Commentaar: <?=$this->input->post( ANALYSIS_COMMENTS ) ? $this->input->post( ANALYSIS_COMMENTS ) : "<geen>" ?><br>
</div>
<p>Je kan op deze mail antwoorden door gewoon op reply te drukken.</p>
</body>
</html>