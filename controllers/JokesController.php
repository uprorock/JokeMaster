<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use app\models\Jokes;

class JokesController extends Controller
{
	
	public function actionLoad(){
		$jokesID=JokesController::AddThreeRandomJokes();
		$jokes = JokesController::makeJokes();
        return $this->render('index', [
            'jokes' => $jokes,
			'jokesid'=>$jokesID,
        ]);
	}
	
    public function actionIndex()
    {	
        $jokes = JokesController::makeJokes();
        return $this->render('index', [
            'jokes' => $jokes,
        ]);
    }
	
	private function makeJokes(){
		//Считаем количество строк в таблице БД
		$allRows = Jokes::find()
					->select('JokeID')
					->from('Jokes')
					->all();

		// Запрос к БД
		$randomJokeIDs = JokesController::getUniqueIDs($allRows);
		//print_r($randomJokeIDs);
        $query = Jokes::find()
			->select('JokeID, JokeText')
			->from('Jokes')
			->where("JokeID='$randomJokeIDs[0]' OR JokeID='$randomJokeIDs[1]'
			OR JokeID='$randomJokeIDs[2]'");

        $jokes = $query->orderBy('JokeID')
            ->all();
			return $jokes;
	}
	
	private function getUniqueIDs($allRows) {
		$randomJokeIDs = JokesController::UniqueRandomNumbersWithinRange(1, count($allRows), 3);
		print_r($randomJokeIDs);
		//print_r($allRows[$randomJokeIDs[0]]["JokeID"]);
		$toReturn = array(
					$allRows[$randomJokeIDs[0]]["JokeID"], 
					$allRows[$randomJokeIDs[1]]["JokeID"], 
					$allRows[$randomJokeIDs[2]]["JokeID"]);
		print_r($toReturn);
		return $toReturn;
	}
	
	private function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
		$numbers = range($min, $max-1);
		shuffle($numbers);
		print_r(array_slice($numbers, 0, $quantity));
		return array_slice($numbers, 0, $quantity);
	}
	
	
	public function AddThreeRandomJokes()
	{
		$stack = array();
		$idsToAdd = JokesController::GetRandomJokesId();
		foreach ($idsToAdd as &$idToAdd) {

			$bdContainsJoke = true;
			$jokeExists = false;
			
			//echo "\n generated id = ";
			//echo $idToAdd;
			
			$num_rows = Jokes::find()
				->select('*')
				->from('Jokes')
				->where("JokeID='$idToAdd'")
				->count();
			
			if ($num_rows == 0)
			{
				$bdContainsJoke = false;
				$newJokeText = JokesController::GetJokeText($idToAdd);
				//echo "\n joke = ";
				//echo "$newJokeText";
				$jokeExists = ($newJokeText != '') ? true : false;
			}
			else
				$bdContainsJoke = true;	

			if (($bdContainsJoke == false) && ($jokeExists == true))
			{
				// запись объекта в бд
				$model = new Jokes();
				$model->JokeID = $idToAdd;
				$data = $str = mb_convert_encoding($newJokeText, 'windows-1252', 'HTML-ENTITIES');
				$data = $str = mb_convert_encoding($newJokeText, 'windows-1251', 'windows-1252');
				$data = $str = mb_convert_encoding($newJokeText, 'UTF-8', 'windows-1251');
				$model->JokeText = $data;
				$model->save();
				array_push($stack,$idToAdd);
			}	
		}
		return $stack;
	}
	
	// функция получения рандомного id
	private function GetRandomJokesId()
	{
		$url = "http://bash.im/random";
		$content = file_get_contents($url);

		$result = array();		
		$first_step = explode('class="id">#' , $content );		
		for ($i=1; $i<=5; $i++)
		{
			$second_step = explode("</a>" , $first_step[$i]);
			array_push($result, $second_step[0]);
		}

		return $result;	
	}
	
	// функция получения текста шутки по ссылке
	private function GetJokeText($jokeId)
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

		return $joke_text;
	}
}