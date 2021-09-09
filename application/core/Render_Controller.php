<?php

// use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

defined('BASEPATH') or exit('No direct script access allowed');

class Render_Controller extends CI_Controller
{
	protected $default_template;
	protected $title;
	protected $title_show = true;
	protected $template_type;
	protected $page_setting;
	protected $page_nav;
	protected $app_name;
	protected $copyright;
	protected $breadcrumb_show = true;
	protected $breadcrumb_1;
	protected $breadcrumb_1_url;
	protected $breadcrumb_2;
	protected $breadcrumb_2_url;
	protected $breadcrumb_3;
	protected $breadcrumb_3_url;
	protected $breadcrumb_4;
	protected $breadcrumb_4_url;
	protected $content;

	protected $navigation		= array();
	protected $data 			= array();
	protected $plugins 			= array();
	private   $plugin_scripts 	= array();
	private   $plugin_styles 	= array();


	protected function preRender()
	{
	}

	protected function render($template = NULL)
	{
		$this->preRender();
		$this->loadPlugins();

		if ($template == NULL) {
			$template = $this->default_template;
		}

		$data = array(
			// Application
			'template_type' 	=> $this->template_type,
			'page_setting' 		=> $this->page_setting,
			'page_nav' 		=> $this->page_nav,
			'app_name' 			=> $this->app_name,
			'copyright' 		=> $this->copyright,


			// Breadcrumb
			'breadcrumb_show' 	=> $this->breadcrumb_show,
			'breadcrumb_1' 		=> $this->breadcrumb_1,
			'breadcrumb_1' 		=> $this->breadcrumb_1,
			'breadcrumb_2' 		=> $this->breadcrumb_2,
			'breadcrumb_3' 		=> $this->breadcrumb_3,
			'breadcrumb_4' 		=> $this->breadcrumb_4,
			'breadcrumb_1_url' 	=> $this->breadcrumb_1_url,
			'breadcrumb_2_url' 	=> $this->breadcrumb_2_url,
			'breadcrumb_3_url' 	=> $this->breadcrumb_3_url,
			'breadcrumb_4_url' 	=> $this->breadcrumb_4_url,


			// Content
			'plugin_styles' 	=> $this->plugin_styles,
			'plugin_scripts' 	=> $this->plugin_scripts,
			'title' 				=> $this->title,
			'title_show' 			=> $this->title_show,
			'navigation' 			=> $this->navigationHtml($this->default->menu()),
			'content' 				=> $this->content,
			'navigation_array'		=> $this->navigation
		);

		$data = array_merge($data, $this->data);
		$this->load->view($template, $data);
	}


	protected function output_json($data, $code = null)
	{
		$code = $code == null ? 200 : $code;
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
		$this->output->set_status_header($code);
	}


	private function loadPlugins()
	{
		if (empty($this->plugins)) return;

		$result 				= $this->plugin->load_plugins($this->plugins);
		$this->plugin_styles 	= $result['styles'];
		$this->plugin_scripts 	= $result['scripts'];
	}


	function __construct()
	{
		parent::__construct();

		$this->app_name 		= $this->config->item('app_name');
		$this->copyright 		= $this->config->item('copyright');
		$this->page_setting 	= $this->config->item('page_setting');
		$this->page_nav 		= $this->config->item('page_nav');
		$this->template_type 	= $this->config->item('template_type');

		$this->load->library('plugin');
	}

	private function navigationHtml($navigation)
	{
		$menu_header = '<nav class="mt-2"><ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">';
		$menu_body = '';
		$menu_footer = '	</ul></nav>';
		$button_logout = '<li class="nav-item btn-logout"> <a href="#" class="nav-link "> <i class="nav-icon fas fa-sign-out-alt"></i><p>Logout</p> </a> </li>';
		$main_menu = $this->navigationToArray($navigation);
		foreach ($main_menu as $menu) {
			$menu_open = $menu['active_sub'] ? ' menu-open' : '';
			$menu_active = $menu['active'] ? ' active' : '';
			$menu_nama = $menu['menu_nama'];
			$menu_icon = $menu['menu_icon'];
			$menu_url = base_url($menu['menu_url']);
			$menu_have_sub = $menu['sub_menu'] ? '<i class="right fas fa-angle-left"></i>' : '';
			$sub_menu_header = '<ul class="nav nav-treeview">';
			$sub_menu_body = '';
			$sub_menu_footer = '</ul>';
			foreach ($menu['sub_menu'] as $sub_menu) {
				$sub_menu_active = $sub_menu['active'] ? ' active' : '';
				$sub_menu_nama = $sub_menu['menu_nama'];
				$sub_menu_url = base_url($sub_menu['menu_url']);
				$sub_menu_body .= '
					<li class="nav-item">
						<a href="' . $sub_menu_url . '" class="nav-link ' . $sub_menu_active . '">
							<i class="far fa-circle nav-icon"></i>
							<p>' . $sub_menu_nama . '</p>
						</a>
					</li>
				';
			}

			$sub_menu_html = $menu['sub_menu'] ? $sub_menu_header . $sub_menu_body . $sub_menu_footer : '';

			$menu_body .= '
				<li class="nav-item' . $menu_open . '">
					<a href="' . $menu_url . '" class="nav-link ' . $menu_active . '">
						<i class="nav-icon ' . $menu_icon . '"></i>
						<p>
						' . $menu_nama . '
						' . $menu_have_sub . '
						</p>
						</a>
						' . $sub_menu_html . '
				</li>
			';
		}
		$result = $menu_header . $menu_body . $button_logout . $menu_footer;
		return $result;
	}

	private function navigationToArray($menu)
	{
		$main_menu = [];
		foreach ($menu as $nav) {
			$main_menu_active = in_array($nav['menu_nama'], $this->navigation);
			$sub_menu_list = $this->default->sub_menu($nav['menu_id']);
			$sub_menu_in_active = false;
			$sub_menu_row = [];
			if ($sub_menu_list) {
				foreach ($sub_menu_list as $row) {
					$sub_menu_cek_aktif = in_array($row['menu_nama'], $this->navigation);;
					$sub_menu_row[] = array_merge($row, [
						'active' => $sub_menu_cek_aktif,
					]);
					if ($sub_menu_cek_aktif) {
						$sub_menu_in_active = true;
					}
				}
			}

			$main_menu[] = array_merge(
				$nav,
				[
					'active' => (bool) ($main_menu_active || $sub_menu_in_active),
					'active_sub' => (bool) $sub_menu_in_active,
					'sub_menu' => $sub_menu_row
				]
			);
		}
		return $main_menu;
	}
}
