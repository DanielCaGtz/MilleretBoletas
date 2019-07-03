<?php

class ctrTest extends MX_Controller{

	function __construct(){
		$this->open_opcs=array('('=>array("is_opened"=>FALSE,"times"=>0),'['=>array("is_opened"=>FALSE,"times"=>0),'{'=>array("is_opened"=>FALSE,"times"=>0));
		$this->close_opcs=array(')'=>array("is_opened"=>FALSE,"times"=>0),']'=>array("is_opened"=>FALSE,"times"=>0),'}'=>array("is_opened"=>FALSE,"times"=>0));
	}

	public function index(){
		$tests=array(
			//'([)]',
			'{(()[{{}}])}',
			'{[(])}',
			'function () { console.log(["cat", "dog")] }',
			'{([]}',
			'function ( { console.log(["cat", "dog"]) }'
		);
		foreach($tests AS $e){
			echo $this->parensAreBalanced($e) ? "TRUE" : "FALSE";
			echo "<br>";
		}
	}

	public function parensAreBalanced($data){
		$len=strlen($data);
		
		for($i=0; $i<$len; $i++){

			if(array_key_exists($data[$i],$this->open_opcs)){
				$this->open_opcs[$data[$i]]["times"]++;
				$this->open_opcs[$data[$i]]["is_opened"]=TRUE;
			}

			if(array_key_exists($data[$i],$this->close_opcs)){

				$pos_temp=array_search($data[$i], array_keys($this->close_opcs));
				$open_temp=array_keys($this->open_opcs)[$pos_temp];

				if( !$this->open_opcs[$open_temp]["is_opened"] ) return FALSE;

				$this->open_opcs[$open_temp]["times"]--;
				$this->open_opcs[$open_temp]["is_opened"]=FALSE;
			}
			
		}
		
		foreach ($this->open_opcs as $key => $value) {
			if($value["times"]>0 || $value["is_opened"]) return FALSE;
		}
		return TRUE;
	}

	public function get_inside_text_from_close_key($data,$char,$pos,$len){
		$pos_temp=array_search($char, array_keys($this->close_opcs));
		$open_temp=array_keys($this->open_opcs)[$pos_temp];
		for($i=$len; $i>0; $i--){
			if(array_key_exists($data[$i-1],$this->close_opcs))
				echo $open_temp.": ".$data[$i-1]."<br>";
				if($data[$i-1]!==$open_temp) return FALSE;
				else return $i;
		}
	}

}

?>