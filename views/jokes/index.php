<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\bootstrap\Button;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
?>
<h1>Jokes</h1>
<div class="body-content">
	<div class="row">
<?php 

foreach ($jokes as $joke): 
?>
		<div class="col-lg-4">
			<h2>Шуточка #<?= $joke->JokeID ?></h2>

			<p><?= $joke->JokeText?></p>
		</div>
<?php endforeach; ?>
	</div>
	<div align="center">
    <?= Html::a("Добавить", ['jokes/load'], ['class' => 'btn btn-lg btn-primary']); ?>
	<?= Html::a("Обновить", ['jokes/index'], ['class' => 'btn btn-lg btn-primary']); ?>
	<p style="margin-top:10px;"><?php if($jokesid!=null) echo 'Добавлены шутки: #'.implode(', #',$jokesid) ?></p>
</div>
</div>
