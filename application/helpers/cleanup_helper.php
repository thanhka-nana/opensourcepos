<?php

function correct_data($analyses, $sale_items) 
{
	$found = 0;
	foreach($analyses as $analysis)
	{
		$matches = array();
		//[1]?(?(-1)[0123]|[0-9])
		if (preg_match("/(\w{3,}\s)*?(maat|m|men\s?\m?)?\s?((1)?(?(-1)[0-4]|[6-9])(?:\s|$|[,\.]5)+)/", $analysis['comments'], $matches))
		{
			if (strlen($matches[1]) > 2) {
				echo date($analysis[ANALYSIS_FORMATTED_DATE_ALIAS]) . " " . $analysis[ 'analysis_id' ]. "<br>";
				echo $analysis['comments'] . "<br>";
				echo $matches[1] . " " . $matches[2] . " " . $matches[3] . " <br>";
				// ok we were able to format the comments.. setup the scoring??
				$articles = array();
				$i = 0;
				foreach($sale_items as $sale_item)
				{
					if (strtotime($analysis['creation_date']) < strtotime($sale_item['sale_time']))
					{
							
						$analysis_id = $analysis['id'];
						$item_id = $sale_item['item_id'];
						$sale_item_id = $sale_item[SALE_ITEM_SALE_ID];
						$score = calc_score($analysis, $sale_item, $matches);
						$size_score = calc_item_size_score($sale_item, $matches);
						$name_score = calc_name_score($sale_item, $matches);
						if (sizeof($articles) == 0) {
							if (($score > 90 && ($size_score == 40 && $name_score == 40 || $size_score > 37)) ||
							   ($score < 90 && $score > 80 && ($size_score > 37 || $name_score > 37)) || 
							   ($score < 80 && $score > 70 && ($size_score == 40 || $name_score == 40)))
							{
								$articles[] = array($item_id, $sale_item_id, $i);
							}
							echo $sale_item['name'] . " " . $sale_item['size'] . " SCORE ($name_score) $item_id : " . $score . "<br>";
						}
					}
					$i++;
				}
				$found = update_data($articles, $sale_items, $analysis, $found);			
			}
		}
	}
	return $found;
}

function update_data($articles, $sale_items, $analysis, $found)
{
	if (sizeof($articles) == 1)
	{
		$found++;
		$ids = $articles[0];
		var_dump(sizeof($sale_items));
		unset($sale_items[$ids[2]]);
		var_dump(sizeof($sale_items));
		//$sale_items = array_values($sale_items);
		$CI =& get_instance();
		$CI->Analysis->save(array('item_id' => $ids[0]), $analysis['id']);
		var_dump("updating $ids[1] with " . $analysis['person_id'] );
		$CI->Sale->update(array('customer_id' => $analysis['person_id']), $ids[1]);
		echo "saved " . $ids[0] . '<br>';
	}
	echo "_____________________________<br><br>";
	return $found;
}

function calc_score($analysis, $sale_item, $matches)
{
	$score = calc_name_score($sale_item, $matches);
	//var_dump("name " . $levenshtein . " max " . $max_levensthein . " score " . $score . " <br>");
	$score += calc_item_size_score($sale_item, $matches);
	//var_dump("size " . $sizediff . " max " . $max_sizediff . " score " . $score . "<br>");
	$score += calc_sale_time_score($analysis, $sale_item, $matches);
	//var_dump("time " . $timediff . " max " .  $max_timediff . " score " . $score . " <br>");
	return $score;
}

function calc_sale_time_score($analysis, $sale_item, $matches)
{
	$timediff = strtotime($sale_item['sale_time']) - strtotime($analysis['creation_date']);
	$max_timediff = 12 * 60 * 60;
	return map_to_range($timediff, $max_timediff, 20);
}

function calc_item_size_score($sale_item, $matches)
{
	$item_size = empty($matches[3]) ? $matches[2] : $matches[3];
	$item_size = str_replace(",", ".", $matches[3]);
	$sizediff = abs(doubleval($item_size) - $sale_item['size']);
	$max_sizediff = 15;
	return map_to_range($sizediff, $max_sizediff, 40);
}

function calc_name_score($sale_item, $matches)
{
	$levenshtein = levenshtein($sale_item['name'], $matches[1]);
	$max_levensthein = max(strlen($matches[1]), strlen($sale_item['name']));
	if (strripos($sale_item['name'],trim($matches[1])) !== false) {
		return 40;
	}
	return map_to_range($levenshtein, $max_levensthein, 40);
}

function map_to_range($int, $max, $new_max)
{
	//var_dump("value " . $int . " max " . $max . " " . $new_max);
	$result = ($max - $int) * $new_max / $max;
	//var_dump("result" . $result);
	return $result;
}



?>