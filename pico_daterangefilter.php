<?php

/**
 * Date range filter Pico plugin
 *
 * @author D.B.C.
 * @link https://github.com/DBC-Works
 * @license http://opensource.org/licenses/MIT
 */
class Pico_DateRangeFilter {
	private $base_url;
	private $from_date;
	private $to_date;
	private $pages_filtered_by_date;
	private $latest_date;

	/*
	 * Constructor
	 */
	public function __construct() {
		$this->from_date = NULL;
		$this->to_date = NULL;
		$this->pages_filtered_by_date = array();
		$this->latest_date = '';
	}

	/*
	 * Callback functions
	 */
	public function plugins_loaded() {
	}

	public function config_loaded(&$settings) {
		$this->base_url = $settings['base_url'];
	}

	public function request_url(&$url) {
	}

	public function before_load_content(&$file)	{
	}

	public function after_load_content(&$file, &$content) {
	}

	public function before_404_load_content(&$file) {
	}
	
	public function after_404_load_content(&$file, &$content) {
	}

	public function before_read_file_meta(&$headers) {
		$headers['from_date'] = 'FromDate';
		$headers['to_date'] = 'ToDate';
	}

	public function file_meta(&$meta) {
		$this->from_date = $meta['from_date'];
		$this->to_date = $meta['to_date'];
	}

	public function before_parse_content(&$content) {
	}

	public function after_parse_content(&$content) {
	}

	public function get_page_data(&$data, $page_meta) {
	}

	public function get_pages(&$pages, &$current_page, &$prev_page, &$next_page) {
		if ($this->from_date != NULL && $this->to_date != NULL) {
			foreach ($pages as $page) {
				$file_url = substr($page['url'], strlen($this->base_url));
				$file_name = CONTENT_DIR . $file_url . ".md";

				if (file_exists($file_name)) {
					$date = $this->get_meta_date(file_get_contents($file_name));
					if ($date != null && $date != ''
					&& ($this->from_date <= $date && $date <= $this->to_date)) {
						$page['date'] = $date;
						array_push($this->pages_filtered_by_date, $page);
						if ($this->latest_date < $date) {
							$this->latest_date = $date;
						}
					}
				}
			}
		}
	}

	public function before_twig_register() {
	}

	public function before_render(&$twig_vars, &$twig, &$template) {
		if ($this->from_date != NULL && $this->to_date != NULL) {
			$twig_vars['is_front_page'] = true;
			$twig_vars['pages_filtered_by_date'] = $this->pages_filtered_by_date;
		}
		if ($this->latest_date != '') {
			$twig_vars['latest_date'] = $this->latest_date;
		}
	}

	public function after_render(&$output) {
	}

	/*
	 * Private functions
	 */
	private function get_meta_date($content) {
		$date = NULL;
		if (preg_match('/^[ \t\/*#@]*Date:(.*)$/mi', $content, $match) && $match[1]) {
			$date = trim(preg_replace('/\s*(?:\*\/|\?>).*/', '', $match[1]));
		}

		return $date;
	}
}

?>
