<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>
<h1>Jokes</h1>
<ul>
<?php foreach ($jokes as $joke): ?>
    <li>
        <?= Html::encode("{$joke->JokeID} ") ?>:
        <?= $joke->JokeText ?>
    </li>
<?php endforeach; ?>
</ul>

<?= LinkPager::widget(['pagination' => $pagination]) ?>