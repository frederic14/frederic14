<?php
/**
*
* @version $Id: formulaire de presentation ,v 1.0.0 01/03/2016  ErnadoO Exp $
* @copyright (c) 2008 ErnadoO
* @modifier par frederic14 pour phpbb 3.1
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace frederic14\presentation\controller;

use Symfony\Component\HttpFoundation\Response;

class main_controller
{
	protected $config;
	protected $db;
	protected $auth;
	protected $template;
	protected $user;
	protected $helper;
	protected $phpbb_root_path;
	protected $php_ext;

	public function __construct(\phpbb\config\config $config, \phpbb\request\request_interface $request, \phpbb\pagination $pagination, \phpbb\db\driver\driver_interface $db, \phpbb\auth\auth $auth, \phpbb\template\template $template, \phpbb\user $user, \phpbb\controller\helper $helper, $phpbb_root_path, $php_ext, $table_prefix)
	{
		$this->config = $config;
		$this->request = $request;
		$this->pagination = $pagination;
		$this->db = $db;
		$this->auth = $auth;
		$this->template = $template;
		$this->user = $user;
		$this->helper = $helper;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->table_prefix = $table_prefix;
	}

	public function main()
	{
		// Output the page
		$this->template->assign_vars(array(
			'TITRE_PAGE'	=> $this->user->lang('TITRE_PAGE'),
			'VOTRETEXTE'	=> $this->user->lang('VOTRE_TEXTE'),
		));

		page_header($this->user->lang('TITRE_PAGE'));
		$this->template->set_filenames(array(
			'body' => 'presentation_form_body.html')
		);

		page_footer();
		return new Response($this->template->return_display('body'), 200);
	}
}