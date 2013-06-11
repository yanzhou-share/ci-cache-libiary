<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  class prefix_Cache {
    var $cached_data,  $cache_key;

    function write($key, &$data) {
      $filename = 'your workspace' . $key . '.cache';

      if ($fp = @fopen($filename, 'w')) {
        flock($fp, 2); // LOCK_EX
        fputs($fp, serialize($data));
        flock($fp, 3); // LOCK_UN
        fclose($fp);

        return true;
      }

      return false;
    }

    function read($key, $expire = 0) {
      $this->cache_key = $key;

      $filename = DIR_FS_WORK . $key . '.cache';

      if (file_exists($filename)) {
        $difference = floor((time() - filemtime($filename)) / 60);

        if ( ($expire == '0') || ($difference < $expire) ) {
          if ($fp = @fopen($filename, 'r')) {
            $this->cached_data = unserialize(fread($fp, filesize($filename)));

            fclose($fp);

            return true;
          }
        }
      }

      return false;
    }

    function &getCache() {
      return $this->cached_data;
    }

    function startBuffer() {
      ob_start();
    }

    function stopBuffer() {
      $this->cached_data = ob_get_contents();

      ob_end_clean();

      $this->write($this->cache_key, $this->cached_data);
    }

    function writeBuffer(&$data) {
      $this->cached_data = $data;

      $this->write($this->cache_key, $this->cached_data);
    }

    function clear($key) {
      $key_length = strlen($key);

      $d = dir(DIR_FS_WORK);

      while ($entry = $d->read()) {
        if ((strlen($entry) >= $key_length) && (substr($entry, 0, $key_length) == $key)) {
          @unlink(DIR_FS_WORK . $entry);
        }
      }

      $d->close();
    }
  }
?>
