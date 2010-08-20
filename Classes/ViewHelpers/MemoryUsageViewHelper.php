<?php

/*
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Outputs the current memory usage
 * 
 * <code title="self-closing example">
 * 	<debug:memoryUsage />
 * </code>
 * outputs:
 * 	<!-- 12345678 -->
 * 
 * 
 * <code title="formats example">
 * 	<debug:memoryUsage format="short" />
 * 	<debug:memoryUsage format="readable" />
 * </code>
 * outputs:
 * 	<!-- 12.3 Mb -->
 * 	<!-- 12,345,678 -->
 * 
 * 
 * When using a opening and a closing tag, the added memory while rendering will
 * be displayed.
 * 
 * <code title="tags with content example">
 * 	<debug:memoryUsage>
 * 		<f:saveTheWorld />
 * 		<f:writeSomeViewHelpers />
 * 		<f:doTheLaundry />
 * 	</debug:memoryUsage>
 * </code>
 * outputs:
 * 	//...
 * 	<!-- 123456789 added -->
 * 
 * The <b>verbose argument</b> is helpfull to debug bottlenecks if the memory_limit is exceeded.
 * It prints the memory value immediately. 
 * 
 * The <b>format argument</b> will be parsed through sprintf before returning the result. Don't
 * forget to have the "%s" in it or it will be rather useless.
 * 
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @author Christian Zenker <christian.zenker@599media.de>
 */
class Tx_FluidDebug_ViewHelpers_MemoryUsageViewHelper extends Tx_FluidDebug_Core_ViewHelper_AbstractViewHelper implements Tx_Fluid_Core_ViewHelper_Facets_ChildNodeAccessInterface {

	/**
	 * if this node has children
	 */
	protected $hasChildren;
	
	/**
	 * 
	 * @param string $format the output is run through it with sprintf() before it is returned
	 * @param boolean $verbose if verbose the return value is printed immediatly. Usefull to find bottlenecks on memory overflow.
	 * @param string $transform could be null (returns '12345678'), 'short' ('12.3 Mb') or 'readable' ('12,345,678')
	 * @return string Formatted string
	 * @author Christian Zenker <christian.zenker@599media.de>
	 */
	public function render($format = '<!-- %s -->', $verbose = false, $transform = null) {
		if(!$this->debugEnabled()) {
			return $this->renderChildren();
		}
		
		if(!function_exists('memory_get_usage')) {
			$value = 'memory_get_usage() not available.';
		} else {
			$value = memory_get_usage();
			$return = '';
			
			if($this->hasChildren) {
				$return = $this->renderChildren();
				$value = memory_get_usage() - $value;
			}
			
			if($transform === null) {
			} elseif($transform === 'short') {
				$value = self::renderByteForDisplay($value);
			} elseif($transform === 'readable') {
				$value = number_format($value);
			}
			
			if($this->hasChildren) {
				$value .= ' added';
			}
		}
		
		if(!empty($format)) {
			$value = sprintf($format, $value);
		}
		if($verbose) {
			t3lib_div::debug($value);
			return $return;
		} else {
			return $return.$value;
		}
	}
	
	/**
	 * Sets the direct child nodes of the current syntax tree node.
	 *
	 * @param array<Tx_Fluid_Core_Parser_SyntaxTree_AbstractNode> $childNodes
	 * @return void
	 */
	public function setChildNodes(array $childNodes) {
		$this->hasChildren = isset($childNodes[0]);
	}

	/**
	 * Sets the rendering context which needs to be passed on to child nodes
	 *
	 * @param Tx_Fluid_Core_Rendering_RenderingContext $renderingContext the renderingcontext to use
	 * @return void
	 */
	public function setRenderingContext(Tx_Fluid_Core_Rendering_RenderingContext $renderingContext) {/* just implement the interface */}
	
	/**
	 * renders an integer representing a number of bytes to be displayed
	 * in an easier to read formula
	 * 
	 * @param integer $byte number of bytes
	 * @return string
	 * @license WTFPL Version 2
	 * @author Christian Zenker <christian.zenker@599media.de>
	 */
	static public function renderByteForDisplay($byte) {
		$byte = intval($byte);
		
		$limit = 1;
		$labels = array('Tb', 'Gb', 'Mb', 'kb');
		
		if($byte < 820) {
			return $byte.' b';
		}
		
		while($limit = $limit << 10) {
			$unit = array_pop($labels);
			if($byte < $limit * 10) {
				return round($byte/$limit,2).' '.$unit;
			} elseif($byte < $limit * 100) {
				return round($byte/$limit,1).' '.$unit;
			} elseif($byte < $limit * 820) {
				return round($byte/$limit,0).' '.$unit;
			}
		}
	}
}
?>