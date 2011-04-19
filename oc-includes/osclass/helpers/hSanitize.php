<?php
/*
 *      OSCLass – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2010 OSCLASS
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

 
    /**
     * Sanitize a website URL.
     * 
     * @param <string> $value value to sanitize
     * @return string sanitized
     */
    function osc_sanitize_url($value) {
        return filter_var($value, FILTER_SANITIZE_URL);
    }


    /**
     * Sanitize capitalization for a string.
     * Capitalize first letter of each name.
     * If all-caps, remove all-caps.
     * 
     * @param <string> $value value to sanitize
     * @return string sanitized
     */
    function osc_sanitize_name($value) {
        return ucwords( osc_sanitize_allcaps( trim( $value ) ) );
    }	


    /**
     * Sanitize string that's all-caps
     * 
     * @param <string> $value value to sanitize
     * @return string sanitized
     */
    function osc_sanitize_allcaps($value) {
        if ( preg_match("/^([A-Z][^A-Z]*)+$/", $value) && !preg_match("/[a-z]+/", $value) ) {
            $value = ucfirst(strtolower($value));
        }
        return $value;
    }


    /**
     * Sanitize number (with no periods)
     * 
     * @param <string> $value value to sanitize
     * @return string sanitized
     */
    function osc_sanitize_int($value) {
        if ( !preg_match("/^[0-9]*$/", $value) ) {
            return (int)$value;
        }
        return $value;
    }


    /**
     * Format phone number. Supports 10-digit with extensions,
     * and defaults to international if cannot match US number.
     * 
     * @param <string> $value value to sanitize
     * @return string sanitized
     */
    function osc_sanitize_phone($value) {
        if (empty($value))	return;

        // Remove strings that aren't letter and number.
        $value = preg_replace("/[^a-z0-9]/", "", strtolower($value));

        // Remove 1 from front of number.
        if (preg_match("/^([0-9]{11})/", $value) && $value[0] == 1) {
            $value = substr($value, 1);
        }

        // Check for phone ext.
        if (!preg_match("/^[0-9]$/", $value)) {	
            $value = preg_replace("/^([0-9]{10})([a-z]+)([0-9]+)/", "$1ext$3", $value); // Replace 'x|ext|extension' with 'ext'.
            list($value, $ext) = explode("ext", $value); // Split number & ext.
        }

        // Add dashes: ___-___-____
        if (strlen($value) == 7) {
            $value = preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $value);
        } else if (strlen($value) == 10) {
            $value = preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $value);
        }

        return ($ext)? $value." x".$ext : $value;
    } 
?>
