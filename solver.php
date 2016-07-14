<?php

function nslog($object){
	if(is_string($object) or is_numeric($object)){
		echo $object."\n";
	}else{
		echo serialize($object)."\n";
	}
}
class Cell {
	private $value = null;
	public function __construct(){
		
	}
	public function setValue($number){
		$this->value = $number;
	}
	public function value(){
		return $this->value;
	}
	public function resolveAttempt($board){
		if(is_null($this->value)){
			$possiblevalues = array();
			foreach(range(1,9) as $xvalue){
				$this->value = $xvalue;
				if($board->selfTest()){
					$possiblevalues[] = $xvalue;
				}
			}
			if(count($possiblevalues)==1){
				$this->value = $possiblevalues[0];
			}else{
				$this->value = null;
			}
		}
	}
}
class Board {

	private $lines = array();

	public function __construct(){
		foreach(range(1,9) as $linenumber){
			$line = array();
			foreach(range(1,9) as $rownumber){
				$line[] = new Cell();
			}
			$this->lines[] = $line;
		}
	}
	public function lines(){
		return $this->lines;
	}
	public function columns(){
		$answer = array();
		foreach(range(1,9) as $columnnumber){
			$column = array();
			foreach(range(1,9) as $linenumber){
				$column[] = $this->lines[$linenumber-1][$columnnumber-1];
			}
			$answer[] = $column;
		}
		return $answer;
	}

		// $yindice = 0,1,2
	// $xindice = 0,1,2
	public function zone($yindice,$xindice){
		$answer = array();
		foreach(range(($yindice*3)+1,($yindice*3)+3) as $linenumber){
			foreach(range(($xindice*3)+1,($xindice*3)+3) as $columnnumber){
				$answer[] = $this->lines[$linenumber-1][$columnnumber-1];
			}
		}
		return $answer;		
	}
	public function zones(){
		$answer = array();
		foreach(range(0,2) as $yindice){
			foreach(range(0,2) as $xindice){
				$answer[] = $this->zone($yindice,$xindice);
			}
		}
		return $answer;
	}
	public function allcells(){
		$answer = array();
		foreach(range(1,9) as $columnnumber){
			foreach(range(1,9) as $linenumber){
				$answer[] = $this->lines[$linenumber-1][$columnnumber-1];
			}
		}	
		return $answer;
	}

	// line_number = 1,...,9 ; row_number = 1,...,9 
	public function setValueForCell($line_number,$row_number,$value){ 
		$cell = $this->lines[$line_number-1][$row_number-1];
		$cell->setValue($value);
	}
	
	public function toString($separator){
		$string = '';
		foreach($this->lines() as $line){
			foreach($line as $cell){
				if($cell->value()){
					$string .= $cell->value();
				}else{
					$string .= $separator;
				}
			}
			$string .= "\n";
		}
		return $string;
	}

	public function selfTest(){
		if(!$this->selfTest_Lines()){
			return false;
		}
		if(!$this->selfTest_Columns()){
			return false;
		}
		if(!$this->selfTest_Zones()){
			return false;
		}
		return true;
	} 
	public function selfTest_Lines(){
		// We need to establish that elements in lines occur at least once.
		foreach($this->lines() as $collection){
			if(!$this->selfTest_Collection($collection)){
				return false;
			}
		}
		return true;
	}
	public function selfTest_Columns(){
		// We need to establish that elements in colums occur at least once.
		foreach($this->columns() as $collection){
			if(!$this->selfTest_Collection($collection)){
				return false;
			}
		}
		return true;
	}
	public function selfTest_Zones(){
		// We need to establish that elements in square zones occur at least once.
		foreach($this->zones() as $collection){
			if(!$this->selfTest_Collection($collection)){
				return false;
			}
		}
		return true;
	}
	public function selfTest_Collection($collection){
		$already_found = array();
		foreach($collection as $cell){
			if($cell->value() and in_array($cell->value(),$already_found)){
				return false;
			}else{
				$already_found[] = $cell->value();
			}
		}
		return true;
	}

	public function onePassResolutionAttempt(){
		foreach($this->allcells() as $cell){
			$cell->resolveAttempt($this);
		}
	}
	public function isComplete(){
		$count = 0;
		foreach($this->allcells() as $cell){
			if($cell->value()){
				$count++;
			}
		}
		return ($count==81);
	}

}

$board = new Board();

$board->setValueForCell(1,1,1);
$board->setValueForCell(1,4,6);
$board->setValueForCell(1,6,4);
$board->setValueForCell(1,8,9);
$board->setValueForCell(1,9,3);

$board->setValueForCell(2,2,3);
$board->setValueForCell(2,4,8);
$board->setValueForCell(2,5,9);
$board->setValueForCell(2,8,2);

$board->setValueForCell(3,6,1);
$board->setValueForCell(3,7,4);
$board->setValueForCell(3,8,6);

$board->setValueForCell(4,3,3);
$board->setValueForCell(4,6,2);
$board->setValueForCell(4,7,7);

$board->setValueForCell(5,1,9);
$board->setValueForCell(5,4,7);
$board->setValueForCell(5,5,5);
$board->setValueForCell(5,6,8);
$board->setValueForCell(5,9,1);

$board->setValueForCell(6,3,5);
$board->setValueForCell(6,4,1);
$board->setValueForCell(6,7,2);

$board->setValueForCell(7,2,9);
$board->setValueForCell(7,3,1);
$board->setValueForCell(7,4,3);

$board->setValueForCell(8,2,7);
$board->setValueForCell(8,5,1);
$board->setValueForCell(8,6,5);
$board->setValueForCell(8,8,8);

$board->setValueForCell(9,1,5);
$board->setValueForCell(9,2,6);
$board->setValueForCell(9,4,4);
$board->setValueForCell(9,6,9);
$board->setValueForCell(9,9,2);

echo $board->toString('.');
while(!$board->isComplete()){
	$board->onePassResolutionAttempt();
}
nslog('');
echo $board->toString('.');

