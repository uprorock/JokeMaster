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
//= LinkPager::widget(['pagination' => $pagination]) ?>