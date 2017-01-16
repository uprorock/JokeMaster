<?php

namespace app\controllers;

use yii\web\Controller;
use yii\data\Pagination;
use app\models\Jokes;

class JokesController extends Controller
{
    public function actionIndex()
    {
		// Запрос к БД
		$randomJokeID = rand(1,3);
        $query = Jokes::find()
			->select('JokeID, JokeText')
			->from('Jokes')
			->where("JokeID='$randomJokeID'");

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);

        $jokes = $query->orderBy('JokeID')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'jokes' => $jokes,
            'pagination' => $pagination,
        ]);
    }
}