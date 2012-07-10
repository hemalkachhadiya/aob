<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
/**
 * Autocomplete for CodeIgniter
 * @property CI_Loader load
 * @property CI_Config config
 * @property CI_Form_validation form_validation
 * @property CI_Input input
 * @property CI_Output output
 * @property CI_Email email
 * @property CI_DB_active_record db
 * @property CI_DB_forge dbforge
 * @property CI_Table table
 * @property CI_Session session
 * @property CI_FTP ftp
 * @property CI_Encrypt encrypt
 * @property CI_Parser parser
 * @property CI_User_agent agent
 * @property AuthCheck authcheck
 * @property LayoutManager layoutmanager
 * @property StaticInfo StaticInfo
 * @property Users Users
 * @property Industry Industry
 * @property UserPreferences UserPreferences
 * @property Category Category
 * @property Photos Photos
 * @property Connections Connections
 * @property Mail Mail
 * @property Dashboard Dashboard
 * @property Companies Companies
 * @property GroupManager GroupManager
 * @property GroupPhotos GroupPhotos
 * @property CI_Upload upload
 * @property CI_Image_lib image_lib
 */
class CI_Controller {

	private static $instance;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		self::$instance =& $this;
		
		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');

		$this->load->initialize();
		
		log_message('debug', "Controller Class Initialized");
	}

	public static function &get_instance()
	{
		return self::$instance;
	}
}
// END Controller class

/* End of file Controller.php */
/* Location: ./system/core/Controller.php */