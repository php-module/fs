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
  use FileSystem\Folder;
  use FileSystem\File;
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!class_exists('Sammy\Packs\FileSystem\Base')){
  /**
   * @class Base
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
  abstract class Base {

    /**
     * @method mixed __call
     *
     * A handler for...
     *
     * @param string $methodName
     *
     * The used method name.
     *
     * @param array $arguments
     *
     * An array containing whole the arguments
     * sent when calling the current method.
     *
     * @author Agostinho Sam'l
     * @license MIT
     * @return FileSystem\File
     */
    public function __call ($methodName, $arguments) {
      $args = array_slice ($arguments, 1, count ($arguments));
      $re = '/(.+)(file|dir(ectory)?)$/i';

      if (!(count ($arguments) >= 1)) {
        return;
      }

      if (preg_match ($re, $methodName, $methodNameMatch)) {
        $fileRef = $arguments [ 0 ];
        $methodNameRef = $methodNameMatch [ 1 ];

        $fileStreamRef = $this->access ($fileRef);

        if (method_exists ($fileStreamRef, $methodNameRef)) {
          return call_user_func_array (
            [$fileStreamRef, $methodNameRef], $args
          );
        }
      }
    }

    /**
     * @method FileSystem\Folder appendFile
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
    public function appendFile ($file, $data, $callBack = null) {
      return call_user_func_array (
        [$this->useFile ($file), 'append'], [$data, $callBack]
      );
    }

    /**
     * @method FileSystem\File copyFile
     *
     * Copy the given file to a given destination.
     *
     * @param string|FileSystem\File $file
     *
     * The file reference or the FileSystem\File
     * object as the straem for the given file.
     *
     * @param mixed $dest
     *
     * The destination for the given file copy.
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
    public function copyFile ($file, $dest, $callBack = null) {
      return call_user_func_array (
        [$this->useFile ($file), 'copy'], [$dest, $callBack]
      );
    }

    /**
     * @method FileSystem\File duplicateFile
     *
     * duplicate the given file to a given destination.
     *
     * @param string|FileSystem\File $file
     *
     * The file reference or the FileSystem\File
     * object as the straem for the given file.
     *
     * @param mixed $dest
     *
     * The destination for the given file duplicate.
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
    public function duplicateFile ($file, $dest = null, $callBack = null) {
      return call_user_func_array (
        [$this->useFile ($file), 'duplicate'], [$dest, $callBack]
      );
    }

    /**
     * @method FileSystem\File renameFile
     *
     * rename the given file to a given destination.
     *
     * @param string|FileSystem\File $file
     *
     * The file reference or the FileSystem\File
     * object as the straem for the given file.
     *
     * @param mixed $dest
     *
     * The destination for the given file rename.
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
    public function renameFile ($file, $dest = null, $callBack = null) {
      return call_user_func_array (
        [$this->useFile ($file), 'rename'], [$dest, $callBack]
      );
    }

    /**
     * @method FileSystem\File deleteFile
     *
     * Delete the given file to a given destination.
     *
     * @param string|FileSystem\File $file
     *
     * The file reference or the FileSystem\File
     * object as the straem for the given file.
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
    public function deleteFile ($file, $callBack = null) {
      return call_user_func_array (
        [$this->useFile ($file), 'delete'], [$callBack]
      );
    }

    /**
     * @method FileSystem\File moveFile
     *
     * move the given file to a given destination.
     *
     * @param string|FileSystem\File $file
     *
     * The file reference or the FileSystem\File
     * object as the straem for the given file.
     *
     * @param mixed $dest
     *
     * The destination for the given file move.
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
    public function moveFile ($file, $dest, $callBack = null) {
      return call_user_func_array (
        [$this->useFile ($file), 'move'], [$dest, $callBack]
      );
    }

    /**
     * @method FileSystem\File useFile
     *
     * Create FileSystem\File instance based on
     * a given file reference.
     *
     * @param string|FileSystem\File $fileReference
     *
     * The given file reference.
     *
     * @author Agostinho Sam'l
     * @license MIT
     * @return FileSystem\File
     */
    public function useFile ($fileReference = null) {
      if ($fileReference instanceof File) {
        return $fileReference;
      }

      return new File ($fileReference);
    }

    /**
     * @method FileSystem\Folder useFolder
     *
     * Create FileSystem\Folder instance based on
     * a given file reference.
     *
     * @param string|FileSystem\Folder $folderReference
     *
     * The given file reference.
     *
     * @author Agostinho Sam'l
     * @license MIT
     * @return FileSystem\Folder
     */
    public function useDir ($folderReference = null) {
      if ($folderReference instanceof Folder) {
        return $folderReference;
      }

      return new Folder ($folderReference);
    }

    /**
     * @method FileSystem\File|FileSystem\Folder useFile
     *
     * Create FileSystem\File|FileSystem\Folder instance based on
     * a given file reference.
     *
     * @param string|FileSystem\File|FileSystem\Folder $fileReference
     *
     * The given file reference.
     *
     * @author Agostinho Sam'l
     * @license MIT
     * @return FileSystem\File|FileSystem\Folder
     */
    public function access ($fileReference = null) {
      $validStreatReference = ( boolean ) (
        $fileReference instanceof File ||
        $fileReference instanceof Folder
      );

      if ($validStreatReference) {
        return $fileReference;
      }

      if (is_dir ($fileReference)) {
        return new Folder ($fileReference);
      }

      return new File ($fileReference);
    }
  }}
}
