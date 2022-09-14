<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\FileSystem
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
namespace Sammy\Packs\FileSystem {
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!class_exists('Sammy\Packs\FileSystem\Folder')){
  /**
   * @class Folder
   * Base internal class for the
   * FileSystem module.
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
  class Folder {
    use Folder\Base;
    use Folder\Core;
    use Data\Stringify;

    /**
     * @var abs
     * - Abslute path to the current folder
     * - it includes the folder name
     */
    protected $abs;

    protected $startSlashRe = '/^(\/|\\\)+/';

    protected function setAbs ($abs = null) {
      if (is_string ($abs) && !empty ($abs)) {

        if (!is_dir ($abs)) {
          $abs = self::Create ($abs);
        }

        $this->abs = $abs;
      }
    }

    public function abs_path () {
      return (string)( $this->abs );
    }

    public function __construct ($dir = null) {
      # Set the current folder absolute
      # folder as the 'abs' property value
      $this->setAbs ( $dir );

      # Trigger an error if
      # the abs property is
      # still null for any
      # reason
      if (!(is_string($this->abs) && $this->abs)) {
        _error_folder_no_found (
          $this->abs, debug_backtrace()
        );
      }
    }

    public function createFile ($file = null) {
      $re = $this->startSlashRe;
      $filePath = join ('', [
        $this->abs,
        DIRECTORY_SEPARATOR,
        preg_replace ($re, '', $file)
      ]);

      if (!is_file ($filePath)) {
        $fileHandler = fopen ($filePath, 'w');
        fclose ($fileHandler);

        return $filePath;
      }

      return false;
    }

    /**
     * @method contains
     * - Verify is the current directory
     * - contains a given file or directory
     * - name or relative path
     */
    public function contains ($file = null) {
      if (is_string ($file) && $file) {
        $re = $this->startSlashRe;

        return file_exists (join ('', [
          $this->abs,
          DIRECTORY_SEPARATOR,
          preg_replace ($re, '', $file)
        ]));
      }

      return false;
    }

    /**
     * @method containsFile
     * - Verify is the current directory
     * - contains a given file
     * - name or relative path
     */
    public function containsFile ($file = null) {
      if (is_string ($file) && $file) {
        $re = $this->startSlashRe;

        return is_file (join ('', [
          $this->abs,
          DIRECTORY_SEPARATOR,
          preg_replace ($re, '', $file)
        ]));
      }

      return false;
    }

    /**
     * @method containsFolder
     * - Verify is the current directory
     * - contains a given folder
     * - name or relative path
     */
    public function containsFolder ($file = null) {
      if (is_string ($file) && $file) {
        $re = $this->startSlashRe;

        return is_dir (join ('', [
          $this->abs,
          DIRECTORY_SEPARATOR,
          preg_replace ($re, '', $file)
        ]));
      }

      return false;
    }

    public function abs ($file) {
      if ($this->contains ($file)) {
        $re = $this->startSlashRe;

        return join ('', [
          $this->abs,
          DIRECTORY_SEPARATOR,
          preg_replace ($re, '', $file)
        ]);
      }
    }

  }}
}
