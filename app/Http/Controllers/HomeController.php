<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
	public function index(Request $request) {
        $highscore = self::getTop10();
		return view('newhome', ['highscore' => $highscore]);
	}

	public function getRandNumber() {
		$randomNumber = array();
		$oneEightCheck = false;

		for ($i=0; $i < 4; $i++) {
			$numCheck = true;

			$d = mt_rand(0,9);

			while ($numCheck) {
				if (!in_array($d, $randomNumber)) {				
					$randomNumber[$i] = $d;
                    $numCheck = false;
				} else {
                    $d = mt_rand(0,9);
                }
			}
		}
        
        // 1-8 rule apply
        if (in_array(1, $randomNumber) && in_array(8, $randomNumber)) {
            $indx1 = array_search(1, $randomNumber);
            $indx8 = array_search(8, $randomNumber);
            if (abs($indx1 - $indx8) > 1) { 
                $max = max($indx1, $indx8);
                $oneEightCheck = true;
                if ($max == $indx1) {
                    $valueToMove = $randomNumber[$indx1-1];
                    $randomNumber[$indx8] = $valueToMove;
                    $randomNumber[$indx1-1] = 8;
                } else {   
                    $valueToMove = $randomNumber[$indx8-1];
                    $randomNumber[$indx1] = $valueToMove;
                    $randomNumber[$indx8-1] = 1;
                }
            }
        }

        // 4-5 rule apply
        if (in_array(4, $randomNumber)) {
            $indx4 = array_search(4, $randomNumber);
            if ($indx4 % 2 == 0 && $oneEightCheck == false) {
                if ($indx4 > 0) {
                    $valueToMove = $randomNumber[$indx4-1];
                    $randomNumber[$indx4] = $valueToMove;  
                    $randomNumber[$indx4-1] = 4;
                } else {
                    $valueToMove = $randomNumber[$indx4+1];
                    $randomNumber[$indx4] = $valueToMove; 
                    $randomNumber[$indx4+1] = 4;
                }
            } elseif ($indx4 % 2 == 0 && !in_array(6, $randomNumber)) {
                $randomNumber[$indx4] = 6;
            } elseif ($indx4 % 2 == 0 && !in_array(2, $randomNumber)) {
                $randomNumber[$indx4] = 2;
            }
        } elseif (in_array(5, $randomNumber)) {
            $indx5 = array_search(5, $randomNumber);
            if ($indx5 % 2 == 0 && $oneEightCheck == false) {
                if ($indx5 > 0) {
                    $valueToMove = $randomNumber[$indx5-1];
                    $randomNumber[$indx5] = $valueToMove;  
                    $randomNumber[$indx5-1] = 5;
                } else {
                    $valueToMove = $randomNumber[$indx5+1];
                    $randomNumber[$indx5] = $valueToMove; 
                    $randomNumber[$indx5+1] = 5;
                }
            } elseif ($indx5 % 2 == 0 && !in_array(7, $randomNumber)) {
                $randomNumber[$indx5] = 7;
            } elseif ($indx5 % 2 == 0 && !in_array(3, $randomNumber)) {
                $randomNumber[$indx5] = 3;
            }
        }
		return $randomNumber;
	}

    public function newGame(Request $request) {
        $data = self::getRandNumber();        
        $response = response()->json(['num' => $data]);
        return $response;
    }

    public function getTop10() {
        $json = json_decode(Storage::get('top10.json'));
        if (!is_null($json)) {
            $elementCount  = count($json);

            if (!empty($json)) {
                usort($json, function($a, $b) {
                    if (isset($a->score) && isset($b->score)) {
                        return $b->score - $a->score;
                    } else {
                        return -1;
                    }
                });
            }

            $json = json_encode($json);
        } else {
            $json = json_encode(['name'=> '...', 'tries'=> '...', 'score'=> '...']);
        }

        return $json;
    }

    public function storeGame($name, $tries, $score) {
        $gameEntry = ['name'=> $name, 'tries'=> $tries, 'score'=> $score];
        $current_data = Storage::get('top10.json');
        $array_data = json_decode($current_data, true);
        $extra = $gameEntry;
        $array_data[] = $extra;
        $final_data = json_encode($array_data);

        Storage::put('top10.json', $final_data);
    }

    public function checkGame(Request $request) {
        $cows = 0;
        $bulls = 0;
        $name = $request->input('name');
        $number = $request->input('number');
        $guessNumber = $request->input('guessNumber');
        $tries = $request->input('tries');
        $score = $request->input('score');

        $guessNumber = str_split($guessNumber);

        foreach ($guessNumber as $key => $value) {
            if (in_array($value, $number)) {
                $bullIndx = array_search($value, $number);
                if ($key == $bullIndx) {
                    $bulls++;
                } else {
                    $cows++;
                }
            }
        }

        $check = '';
        if ($bulls == 4) {
            $check = 'win';
            self::storeGame($name, $tries, $score);
        } else {
            $check = 'no';
        }

        $resp = array("result" => 0, "message" => "");
        $resp['check'] = $check;
        $resp['bulls'] = $bulls;
        $resp['cows'] = $cows;

        return response()->json($resp);
    }
}
