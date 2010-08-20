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
 * lists all available template variables
 * 
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @author Christian Zenker <christian.zenker@599media.de>
 */
class Tx_FluidDebug_ViewHelpers_ListVariablesViewHelper extends Tx_FluidDebug_Core_ViewHelper_AbstractViewHelper {

	/**
	 * @author Christian Zenker <christian.zenker@599media.de>
	 */
	public function render() {
		if(!$this->debugEnabled()) {
			return $this->renderChildren();
		}
		
		$variables = array();
		foreach($this->templateVariableContainer->getAllIdentifiers() as $identifier) {
			$variable = $this->templateVariableContainer->get($identifier);
			$type = gettype($variable);
			
			if($type === 'integer' || $type === 'double' || $type === 'string') {
				$type .= ': '.$variable;
			} elseif($type === 'boolean') {
				$type .= ': '.($variable ? 'true' : 'false');
			} elseif($type === 'object') {
				$type = get_class($variable);
			} elseif($type === 'array') {
				$type .= sprintf(': %d items', count($variable));
			}
			$variables[] = array(
				$identifier,
				$type
			); 
		}
		ob_start();
			t3lib_div::debug($variables);
		return ob_get_clean();
	}

}
?>