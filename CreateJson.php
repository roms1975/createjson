<?php

class CreateJson
{
	function __construct($all_nodes, $childs) {
		$this->all_nodes = $all_nodes;
		$this->childs = $childs;
	}
	
	public function create_tree($name, $parent) {
		$children = array();
		$output_tree = array();

		if (isset($this->all_nodes[$name]['relation'])) {
			$children = isset($this->childs[$this->all_nodes[$name]['relation']]) ? 
							$this->childs[$this->all_nodes[$name]['relation']] : [];
							
		} else {
			$children = isset($this->childs[$name]) ? $this->childs[$name] : [];
		}

		foreach ($children as $child) {
			$output_tree[] = $this->create_tree($child, $name);
		}
		
		return array(
			'itemName' => $name,
			'parent' => $parent,
			'children' => $output_tree,
		);
	}
}
?>