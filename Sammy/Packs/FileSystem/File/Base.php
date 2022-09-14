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
  if (!trait_exists('Sammy\Packs\FileSystem\File\Base')){
  /**
   * @trait Base
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
  trait Base {
    /**
     * @var string $abs
     *
     * file absolute path
     *
     */
    private $abs;
    /**
     * @var string $abs
     *
     * file absolute path
     *
     */
    private $dir;

    public function __construct ($fileAbsolutePath = null) {
      $this->setAbs ($fileAbsolutePath);
    }

    public function __set ($prop, $value) {}

    public function __get ($prop = null) {
      if (!(is_string ($prop) && $prop)) {
        return;
      }

      $propName = join ('', ['get', $prop]);

      if (method_exists ($this, $propName)) {
        return call_user_func ([$this, $propName]);
      }
    }

    public function getAbs () {
      return $this->abs;
    }

    public function getName () {
      return self::Name ($this->abs);
    }

    public function getFileName () {
      return self::FileName ($this->abs);
    }

    public function getExtension () {
      return self::FileExtension ($this->abs);
    }

    public function getDir () {
      return $this->dir;
    }

    public function getLinesCount () {
      if (!!(is_array ($fileLines))) {
        return count ($this->readLines ());
      }

      return 0;
    }

    public function getFirstLine () {
      $fileHandler = fopen ($this->abs, 'r');

      if ($fileHandler) {
        $fisrtLine = (string)(fgets ($fileHandler));

        fclose ($fileHandler);

        return $fisrtLine;
      }
    }

    public function getLastLine () {
      $fileLines = $this->readLines ();

      if (!!(is_array ($fileLines) && $fileLines)) {
        return $fileLines [ -1 + count ($fileLines) ];
      }
    }

    public function getLastModify () {
      return date ('YmdHis', filemtime ($this->abs));
    }

    public function getLastModifyTime () {
      return date ('His', filemtime ($this->abs));
    }

    public function getLastModifyDate () {
      return date ('Ymd', filemtime ($this->abs));
    }

    private function setAbs ($fileAbsolutePath) {
      $fileDirectory = dirname ($fileAbsolutePath);
      $this->dir = new Folder ($fileDirectory);

      if (!is_file ($fileAbsolutePath)) {
        $this->abs = self::Create (
          $fileAbsolutePath
        );
      }

      $this->abs = $fileAbsolutePath;
    }

    /**
     * @method FileSystem\File append
     *
     * Append a given data to the end of the
     * given file path.
     *
     * @param string|FileSystem\File $file
     *
     * The file reference or the FileSystem\File
     * object as the straem for the given file.
     *
     * @param mixed $data
     *
     * The data to append at the end of the given
     * file.
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
    public function append ($data = '', $callBack = null) {
      $data = self::Stringify ($data);

      if (is_file ($this->abs)) {
        $fileHandler = fopen ($this->abs, 'a');

        fwrite ($fileHandler, $data);

        fclose($fileHandler);

        if (is_callable ($callBack)) {
          call_user_func_array ($callBack, [$this]);
        }

        return $this;
      }
    }

    /**
     * @method FileSystem\File copy
     *
     * Copy the current file path in the given
     * destination.
     *
     * @param string|FileSystem\File $file
     *
     * The file reference or the FileSystem\File
     * object as the straem for the given file.
     *
     * @param string|array $dest
     *
     * A list of or the final destination for copying
     * the current file path to.
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
    public function copy ($dest, $callBack = null) {
      if (is_string ($dest) && $dest) {
        /**
         * Verify if the given destination is an
         * existing directory, if it is, considere
         * the current file name as the final in that
         * directory;
         *
         * So, use it as a sufix for the '$dest' string
         */
        if (is_dir ($dest)) {
          $dest = join (DIRECTORY_SEPARATOR, [
            $dest,
            $this->filename
          ]);
        }

        if (!is_file ($dest)) {
          copy ($this->abs, $dest);

          if (is_callable ($callBack)) {
            call_user_func_array ($callBack, [
              $this, new static ($dest)
            ]);
          }
        }

      } elseif (is_array ($dest) && $dest) {
        return $this->copyTo ($dest, $callBack);
      }
    }

    /**
     * @method FileSystem\File copyTo
     *
     * Copy the current file path in the given
     * destination.
     *
     * @param string|FileSystem\File $file
     *
     * The file reference or the FileSystem\File
     * object as the straem for the given file.
     *
     * @param mixed $dest
     *
     * A list of the final destination for copying
     * the current file path to.
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
    public function copyTo ($destinationList, $callBack = null) {
      if (!is_array ($destinationList)) {
        $destinationList = [ $destinationList ];
      }

      $destinationListCount = count ($destinationList);

      for ($i = 0; $i < $destinationListCount; $i++) {
        $destination = $destinationList [ $i ];

        $this->copy ($destination, $callBack);
      }

      return $this;
    }

    /**
     * @method FileSystem\File duplicate
     *
     * Duplicate the current file path in the given
     * destination.
     *
     * The final name for the file in the destination
     * should be passed as a parameter for the method
     * as an absolute path in the '$dest' string;
     *
     * But if the destinations points to an existing
     * directory, it should generated a different name
     * for each duplications of the file.
     * Succeding the file name with the 'copy' dtring and
     * an incrementing counter acording to the number of
     * copies already done for the current file.
     *
     * @param string|FileSystem\File $file
     *
     * The file reference or the FileSystem\File
     * object as the straem for the given file.
     *
     * @param string|array $dest
     *
     * A list of or the final destination for copying
     * the current file path to.
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
    public function duplicate ($dest, $callBack = null) {
      if (is_string ($dest) && $dest) {
        /**
         * Verify if the given destination is an
         * existing directory, if it is, considere
         * the current file name as the final in that
         * directory;
         *
         * So, use it as a sufix for the '$dest' string
         */
        if (is_dir ($dest)) {
          $destination = new Folder ($dest);
          $fileName = $this->filename;

          if ($destination->containsFile ($fileName)) {
            $destinationNamesAlternates = self::generateDestinationAlternates (
              $this, $destination->filesCount
            );

            foreach ($destinationNamesAlternates as $alternate) {
              $alternateFileName = join ('', [
                $this->name, $alternate, '.', $this->extension
              ]);

              if (!$destination->containsFile ($alternateFileName)) {
                $fileName = $alternateFileName;
                break;
              }
            }
          }

          $dest = join (DIRECTORY_SEPARATOR, [
            $dest, $fileName
          ]);
        }

        return $this->copy ($dest, $callBack);

      } elseif (is_array ($dest) && $dest) {
        return $this->duplicateTo ($dest, $callBack);
      }
    }

    /**
     * @method FileSystem\File duplicateTo
     *
     * duplicate the current file path in the given
     * destination.
     *
     * @param string|FileSystem\File $file
     *
     * The file reference or the FileSystem\File
     * object as the straem for the given file.
     *
     * @param mixed $dest
     *
     * A list of the final destination for duplicating
     * the current file path to.
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
    public function duplicateTo ($destinationList, $callBack = null) {
      if (!is_array ($destinationList)) {
        $destinationList = [ $destinationList ];
      }

      $destinationListCount = count ($destinationList);

      for ($i = 0; $i < $destinationListCount; $i++) {
        $destination = $destinationList [ $i ];

        $this->duplicate ($destination, $callBack);
      }

      return $this;
    }

    /**
     * @method FileSystem\File move
     *
     * move the current file path in the given
     * destination.
     *
     * @param string|FileSystem\File $file
     *
     * The file reference or the FileSystem\File
     * object as the straem for the given file.
     *
     * @param string|array $dest
     *
     * A list of or the final destination for moving
     * the current file path to.
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
    public function move ($dest, $callBack = null) {
      if (is_string ($dest) && $dest) {
        /**
         * Verify if the given destination is an
         * existing directory, if it is, considere
         * the current file name as the final in that
         * directory;
         *
         * So, use it as a sufix for the '$dest' string
         */
        if (is_dir ($dest)) {
          $dest = join (DIRECTORY_SEPARATOR, [
            $dest,
            $this->filename
          ]);
        }

        if (!is_file ($dest)) {
          @rename ($this->abs, $dest);

          if (is_callable ($callBack)) {
            call_user_func_array ($callBack, [
              new static ($dest)
            ]);
          }
        }

      } elseif (is_array ($dest) && $dest) {
        return $this->moveTo ($dest, $callBack);
      }
    }


  }}
}
