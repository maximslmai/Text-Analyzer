<?php


class Analyzer{
	
	private $text;
	private $text2;
	
	public function Analyzer($text1, $text2=null){
		$this->text = $text1;
		if($text2) {
			$this->text2 = $text2;
		}else{
			throw new Exception("Empty Document"); 
		}
	}

	public function sCount(){
		return array(preg_match_all('/[^\s](\.|\!|\?)(?!\w)/',$this->text,$match), preg_match_all('/[^\s](\.|\!|\?)(?!\w)/',$this->text2,$match));
	}
	
	public function wCount($unique=False){
		if($unique){
			return array(sizeof(array_unique(str_word_count($this->text, 1))), sizeof(array_unique(str_word_count($this->text2, 1))));
		} else{
			return array(str_word_count($this->text),str_word_count($this->text2));
		}
	}
	
	// case senstive version
	public function freq(){
		$char_set1 =  count_chars($this->text, 1);
		$result1 = array();
		foreach($char_set1 as $c => $freq){
			if(($c>=65 && $c<=90) || ($c>=97 && $c<=122)){
				$result1[$c] = $freq;
			}
		}
		
		$char_set2 =  count_chars($this->text2, 1);
		$result2 = array();
		foreach($char_set2 as $c => $freq){
			if(($c>=65 && $c<=90) || ($c>=97 && $c<=122)){
				$result2[$c] = $freq;
			}
		}
		
		return array($result1, $result2);
	}
	
	public function topk($k){
		$arr = array_count_values(str_word_count($this->text, 1));
		arsort($arr);
		$result1 = array_slice($arr, 0, $k);
		
		$arr2 = array_count_values(str_word_count($this->text2, 1));
		arsort($arr2);
		$result2 = array_slice($arr2, 0, $k);
		
		return array($result1,$result2);
	}
		
	// Find the number of words in their intersection = > n
	// Find the number of words in their union => m
	// The similarity score is n/m
	public function jaccard(){
		if ($this->text == Null || $this->text2 == Null){
			return 0;
		}else{
			$set1 = str_word_count($this->text, 1);
			$set2 = str_word_count($this->text2, 1);
			$intersection = array_intersect($set1, $set2);
			$union = array_merge($set1, $set2);
			$sim = sizeof($intersection) / sizeof($union);
			if ($sim >= 0 && $sim <= 1){
				return $sim;
			}else{
				return -1;
			}
		}
	}
	
}