<?php
/**
 * Samils\Functions
 * @version 1.0
 * @author Sammy
 *
 * @keywords Samils, ils, ils-global-functions
 * ------------------------------------
 * - Autoload, application dependencies
 *
 * Make sure the command base internal function is not
 * declared in the php global scope defore creating
 * it.
 * It ensures that the script flux is not interrupted
 * when trying to run the current command by the cli
 * API.
 * ----
 * @Function Name: backDir
 * @Function Description: back to th prev directory in a path name
 * @Function Args: $dir, $level = 1
 */
if (!function_exists ('backDir')) {
/**
 * @version 1.0
 *
 * THE CURRENT ILS COMMAND IS PROVIDED
 * TO AID THE DEVELOPMENT PROCESS IN ORDER
 * IT GET IN THE SAME WAY WHEN MOVING IT FROM
 * ANOTHER TO ANOTHER ENVIRONMENT.
 *
 * Note: on condition that this is an automatically
 * generated file, it should not be directly changed
 * without saving whole the changes into the original
 * repository source.
 *
 * @author Ag
 * @keywords Function Keywords
 */
function backDir ($dir, int $level = 1) {
  $fs = requires ('fs');

  return $fs->backDir ($dir, $level);
}}
