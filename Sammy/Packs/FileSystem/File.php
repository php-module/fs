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
  if (!class_exists('Sammy\Packs\FileSystem\File')){
  /**
   * @class File
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
  class File {
    use File\Base;
    use File\Core;
    use Data\Stringify;

    private static function arrayJoin ($array, $key = ' ') {
      $key = is_string ($key) ? $key : ' ';

      $finalString = '';
      $arrayCount = count ($array);

      for ($i = 0; $i < $arrayCount; $i++) {
        $arrayItem = self::Stringify ($array [ $i ]);

        $finalString .= $arrayItem . (
          ($i + 1) >= $arrayCount ? '' : $key
        );
      }

      return $finalString;
    }

    /**
     * @method FileSystem\File write
     *
     * Write a given data as a string in the
     * current file.
     *
     * @param string|FileSystem\File $file
     *
     * The file reference or the FileSystem\File
     * object as the straem for the given file.
     *
     * @param mixed $data
     *
     * Data/Content to write in the end of the
     * current file.
     *
     * @param [Closure $callBack]
     *
     * An optional callBack for a necessary action at the end
     * of the operation.
     *
     * @author Agostinho Sam'l
     * @license MIT
     * @return FileSystem\File
     */
    public function write () {
      $args = func_get_args ();

      if (!(count ($args) >= 1)) {
        return false;
      }

      $callBack = $args [ -1 + count ($args) ];

      $argsSliceLimit = is_callable ($callBack) ? -1 + count ($args) : 1 + count ($args);

      $data = self::arrayJoin (
        array_slice ($args, 0, $argsSliceLimit)
      );

      $fileHandler = fopen ($this->abs, 'a');

      fwrite ($fileHandler, $data);

      fclose ($fileHandler);

      if (is_callable ($callBack)) {
        call_user_func_array ($callBack, [$this]);
      }

      return $this;
    }

    /**
     * @method FileSystem\File writeLine
     *
     * Write a given data as a string in the
     * current file ending with an empty line.
     *
     * @param string|FileSystem\File $file
     *
     * The file reference or the FileSystem\File
     * object as the straem for the given file.
     *
     * @param mixed $data
     *
     * Data/Content to write in the end of the
     * current file.
     *
     * @param [Closure $callBack]
     *
     * An optional callBack for a necessary action at the end
     * of the operation.
     *
     * @author Agostinho Sam'l
     * @license MIT
     * @return FileSystem\File
     */
    public function writeLine () {
      $args = func_get_args ();

      if (!(count ($args) >= 1)) {
        return false;
      }

      $callBack = $args [ -1 + count ($args) ];

      $argsSliceLimit = -1 + count ($args);

      if (!is_callable ($callBack)) {
        $argsSliceLimit = 1 + count ($args);

        $callBack = null;
      }

      $firstArgs = array_slice ($args, 0, $argsSliceLimit);
      $lastArgs = is_null ($callBack) ? [] : [$callBack];

      return call_user_func_array (
        [$this, 'write'],
        array_merge (
          $firstArgs, ["\n"], $lastArgs
        )
      );
    }

    /**
     * @method FileSystem\File writeLines
     *
     * Write a given data as a string in the
     * current file ending with an empty line.
     *
     * @param string|FileSystem\File $file
     *
     * The file reference or the FileSystem\File
     * object as the straem for the given file.
     *
     * @param array $linesList
     *
     * Data/Content to write in the end of the
     * current file.
     *
     * @param [Closure $callBack]
     *
     * An optional callBack for a necessary action at the end
     * of the operation.
     *
     * @author Agostinho Sam'l
     * @license MIT
     * @return FileSystem\File
     */
    public function writeLines ($linesList = []) {
      if (!(is_array ($linesList) && $linesList)) {
        return;
      }

      $args = func_get_args ();

      if (!(count ($args) >= 1)) {
        return false;
      }

      #exit ('LLAS');

      $this->clear ();

      #exit ($this->abs);

      $callBack = $args [ -1 + count ($args) ];

      foreach ($linesList as $i => $line) {
        $this->writeLine ($line);
      }

      if (is_callable ($callBack)) {
        call_user_func_array ($callBack, [$this]);
      }

      return $this;
    }

    /**
     * @method string clear
     *
     * Clear the current file data.
     * Set the content to null<empty>.
     *
     * @author Agostinho Sam'l
     * @license MIT
     * @return string
     */
    public function clear () {
      fclose (fopen ($this->abs, 'w'));

      return $this;
    }

    /**
     * @method string read
     *
     * Read the current file content.
     *
     * @author Agostinho Sam'l
     * @license MIT
     * @return string
     */
    public function read () {
      return join ('', $this->readLines ());
    }

    /**
     * @method array readLines
     *
     * Read the current file lines as an array
     * containg whole of them.
     *
     * @author Agostinho Sam'l
     * @license MIT
     * @return array
     */
    public function readLines () {
      $fileHandler = fopen ($this->abs, 'r');

      $lines = [];

      while (!feof ($fileHandler)) {
        $fileLine = fgets ($fileHandler);

        array_push ($lines, $fileLine);
      }

      fclose($fileHandler);

      return $lines;
    }

    /**
     * @method array readLinesRange
     *
     * Read the current file lines as an array
     * containg whole in a given range.
     *
     * @param int $offset
     *
     * The initial point for th given range.
     * This'll be an int value pointing to an
     * index in the fileLines array in order
     * creating the range from that index.
     *
     * @param int $limit
     *
     * The limit for the given range.
     *
     * @author Agostinho Sam'l
     * @license MIT
     * @return array
     */
    public function readLinesRange ($offset = 0, $limit = null) {
      $fileLines = $this->readLines ();

      $offset = !is_numeric ($offset) ? 0 : (int)($offset);
      $limit = !!is_numeric ($limit) ? (int)($limit) : (
        1 + count ($fileLines)
      );

      return array_slice ($fileLines, $offset, $limit);
    }
  }}
}
