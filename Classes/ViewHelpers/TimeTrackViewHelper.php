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
 * Adds an entry to the timetracker
 * 
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @author Christian Zenker <christian.zenker@599media.de>
 */
class Tx_FluidDebug_ViewHelpers_TimeTrackViewHelper extends Tx_FluidDebug_Core_ViewHelper_AbstractViewHelper {

	/**
	 * 
	 * @param string $label a label that describes what happens
	 * @param string $value
	 * @return string Formatted string
	 * @author Christian Zenker <christian.zenker@599media.de>
	 */
	public function render($label = null, $value = null) {
		if(!$this->debugEnabled()) {
			return $this->renderChildren();
		}
		$GLOBALS['TT']->push($label, $value);
		$ret = $this->renderChildren();
		$GLOBALS['TT']->pull($ret);
		return $ret;
	}

}
?>