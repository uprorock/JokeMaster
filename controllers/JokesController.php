<?php

namespace app\controllers;

use yii\web\Controller;
use yii\data\Pagination;
use app\models\Jokes;

class JokesController extends Controller
{
    public function actionIndex()
    {
		//Считаем количество строк в таблице БД
		$rowCount = Jokes::find()->count();
		// Запрос к БД
		$randomJokeIDs = JokesController::UniqueRandomNumbersWithinRange(1, $rowCount, 3);//array_rand(range(1,$rowCount), 3);
        $query = Jokes::find()
			->select('JokeID, JokeText')
			->from('Jokes')
			->where("JokeID='$randomJokeIDs[0]' OR JokeID='$randomJokeIDs[1]'
			OR JokeID='$randomJokeIDs[2]'");

        $jokes = $query->orderBy('JokeID')
            ->all();

        return $this->render('index', [
            'jokes' => $jokes,
        ]);
    }
	
	private function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
}
}