<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>
<h1>Jokes</h1>
<div class="body-content">
	<div class="row">
<?php 
foreach ($jokes as $joke): 
?>
		<div class="col-lg-4">
			<h2>Шуточка <?= $joke->JokeID ?></h2>

			<p><?= $joke->JokeText?></p>
		</div>
<?php endforeach; ?>
	</div>
</div>
<?
// Функция замещает символы перехода на новую строку на <br />
function format ($text) {
	$result = htmlspecialchars($text);
	$result = str_replace ("\r\n\r\n", "<br><br>", $result); 
	return $result;
}

// функция получения текста шутки по ссылке
function get_joke_text($jokeId)
{
	$url = "http://bash.im/quote/{$jokeId}";

	$content = file_get_contents($url);
	
	$str = trim(preg_replace('/\s+/', ' ', $content)); // supports line breaks inside <title>
    preg_match("/\<title\>(.*)\<\/title\>/i",$content,$title); // ignore case
    $page_title = $title[1];	

	if (strpos($page_title, '#') == false) 
	{
		// если в заголовке нет # - значит, цитату найти не удалось, возвращаем пустую строку
		echo 'Not Found';
		return '';
	}
	
	$first_step = explode( '<div class="text">' , $content );
	$second_step = explode("</div>" , $first_step[1] );
	$joke_text = $second_step[0];
	
	echo $joke_text;
	return $joke_text;
}
//= LinkPager::widget(['pagination' => $pagination]) ?>