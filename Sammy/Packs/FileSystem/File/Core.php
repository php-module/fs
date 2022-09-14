<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\FileSystem\File
 * - Autoload, application dependencies
 *
 * MIT License
 *
 * Copyright (c) 2020 Ysare
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
namespace Sammy\Packs\FileSystem\File {
  use FileSystem\Folder;
  /**
   * Make sure the module base internal trait is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists('Sammy\Packs\FileSystem\File\Core')){
  /**
   * @trait Core
   * Base internal trait for the
   * FileSystem\File module.
   * -
   * This is (in the ils environment)
   * an instance of the php module,
   * wich should contain the module
   * core functionalities that should
   * be extended.
   * -
   * For extending the module, just create
   * an 'exts' directory in the module directory
   * and boot it by using the ils directory boot.
   * -
   */
  trait Core {
    /**
     * @method string Create
     *
     * Create a new directory from a given
     * absolute path.
     *
     * @param string $paths The directory absolute path
     * @author Agostinho Sam'l <agostinhosaml832@gmail.com>
     * @license MIT
     */
    public static function Create ($path = null) {
      if (!(is_string ($path) && $path)) {
        return false;
      }

      $folder = new Folder (dirname ($path));
      $pathFileName = self::FileName ($path);

      return $folder->createFile ($pathFileName);
    }

    /**
     * @method string FileName
     *
     * Get the file name from a given
     * absolute path.
     * It should be different that using the
     * pathinfo function wich just return the
     * file name without the extension; it should
     * also return the file extension with its name.
     *
     * @param string $paths The directory absolute path
     * @author Agostinho Sam'l <agostinhosaml832@gmail.com>
     * @license MIT
     */
    public static function FileName ($path = null) {
      if (!(is_string ($path) && $path)) {
        return false;
      }

      $paths = preg_split ('/(\/|\\\)+/', $path);

      return $paths [ -1 + count ($paths) ];
    }

    public static function FileExtension ($path = null) {
      if (!(is_string ($path) && $path)) {
        return false;
      }

      return pathinfo ($path, PATHINFO_EXTENSION);
    }

    public static function Name ($path = null) {
      if (!(is_string ($path) && $path)) {
        return false;
      }

      return pathinfo ($path, PATHINFO_FILENAME);
    }

    protected static function generateDestinationAlternates ($file, $alternatesNumber = 1) {
      $alternatesNumber = !(is_int ($alternatesNumber) && $alternatesNumber >= 1) ? 1 : $alternatesNumber + 1;

      $alternates = [
        ' - Copy'
      ];

      for ($i = 1; $i <= $alternatesNumber; $i++) {
        array_push ($alternates, join (' ', [
          ' -', 'Copy', join ('', ['(', $i, ')'])
        ]));
      }

      return $alternates;
    }
  }}
}
