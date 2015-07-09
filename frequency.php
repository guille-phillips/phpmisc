<?php

	class Frequencies {
		var $frequencies;
		var $frequencies_original;

		public function __construct($frequencies) {
			$this->frequencies = $frequencies;
		}

		public function Adjust($key,$amount) {
			$this->frequencies[$key] += $amount;
		}

		public function Add($key,$amount) {
			$this->frequencies[$key] = $amount;
			$this->frequencies_original[$key] = $amount;
		}

		public function Remove($key) {
			unset($this->frequencies[$key]);
			unset($this->frequencies_original[$key]);
		}

		public function AsTable() {
			$table = array();
			foreach ($this->frequencies as $key=>$frequency) {
				$table[] = array($key,$frequency);
			}
			return $table;
		}

		public function Reset() {
			$this->frequencies = $this->frequencies_original;
		}
	}

