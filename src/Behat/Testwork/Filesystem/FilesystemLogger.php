<?php

/*
 * This file is part of the Behat Testwork.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Testwork\Filesystem;

/**
 * Testwork filesystem operations logger interface.
 *
 * Implement this interface in order to be able to log FS operations that testwork does.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
interface FilesystemLogger
{
    /**
     * Logs directory creation.
     *
     * @param string $path
     * @param string $reason
     */
    public function directoryCreated($path, $reason);

    /**
     * Logs file creation.
     *
     * @param string $path
     * @param string $reason
     */
    public function fileCreated($path, $reason);

    /**
     * Logs file update.
     *
     * @param string $path
     * @param string $reason
     */
    public function fileUpdated($path, $reason);
}
