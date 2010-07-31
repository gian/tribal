<?php
class Template {
	var $title;
	var $content;
	var $nav;

	function template_init() {
		;
	}

	function Template($title, $content) {
		$this->title = $title;
		$this->content = $content;

		$this->template_init();
	}

	function render($mode="web") {
		return "<html><head><title>{$this->title}</title></head>\n" .
				"<body>{$this->content}</body></html>\n";
	}
}
?>

