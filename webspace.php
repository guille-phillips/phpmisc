<?php
	header('Content-Type: text/plain');

	// trigger(data) -> object -> update state -> side_effects(data)

	function Indent($indent) {
		return str_repeat("\t",$indent);
	}

	function IndentString($string,$indent) {
		return Indent($indent).str_replace("\n", "\n".Indent($indent),$string);
	}

	class Position {
		public $top;
		public $bottom;
		public $left;
		public $right;
		public function __construct($top,$bottom,$left,$right) {
			$this->top = $top;
			$this->bottom = $bottom;
			$this->left = $left;
			$this->right = $right;
		}

		public function Properties() {
			return array(
				'top'=>$this->top->Value(),
				'left'=>$this->left-Value(),
				'height'=>$this->bottom->Value().' - '.$this->top->Value(),
				'width'=>$this->right->Value().' - '.$this->left->Value();
		}
	}

	class Line {
		public $type = 'vertical';
		public $units = 'percent';
		public $refs = array();
		private $value = 0;

		public function __construct($type,$units,$value,$refs=array()) {
			$this->type = $type;
			$this->units = $units;
			$this->value = $value;
			$this->refs = $refs;
		}

		public function Value() {
			switch ($this->units) {
				case 'pixels':
					return $this->value.'px';
				case 'percent':
					return $this->value.'%';
			}
		}
	}

	class CSS {
		public $selector = '';
		public $properties = array();
		public function __construct($selector,$properties) {
			$this->selector = $selector;
			$this->properties = $properties;
		}

		public function Compile($indent=0) {
			$properties_array = array();
			foreach ($this->properties as $name=>$value) {
				$properties_array[] .= $name.':'.$value.";";
			}
			$properties = IndentString(implode("\n",$properties_array),$indent);
			$html =  <<<HTML
$this->selector {
$properties
}
HTML;
			return IndentString($html,$indent);
		}
	}

	class Block {
		public $refs = array();
		public $type = 'html';
		public $blocks = array();
		public $document = null;
		public function __construct($type,$top,$bottom,$left,$right) {
			$this->type = $type;
			$this->refs = array($top,$bottom,$left,$right);
		}

		public function Compile($indent=0) {
			$sub_html = '';
			$sub_html_array = array();
			foreach ($this->blocks as $block) {
				$sub_html_array[] = $block->Compile(1);
			}
			$sub_html = implode("\n",$sub_html_array);
			$html = <<<HTML
<$this->type>
$sub_html
</$this->type>
HTML;
			return IndentString($html,$indent);
		}
	}



	$left = new Line('vertical','pixels',0);
	$right = new Line('vertical','pixels',800);
	$top = new Line('horizontal','pixels',0);
	$bottom = new Line('horizontal','pixels',800);

	$html = new Block('html',$top,$bottom,$left,$right);
	$head = new Block('head',null,null,null,null);
	$body = new Block('body',null,null,null,null);

	$style = new Block('style',null,null,null,null);

	$css = new CSS('body',array('background-color'=>'#999'));

	$style->blocks = array($css);

	$head->blocks = array($style);
	$html->blocks = array($head,$body);

	echo $html->Compile();
?>