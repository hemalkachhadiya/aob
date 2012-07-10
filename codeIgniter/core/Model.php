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
 * CodeIgniter Model Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/config.html
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
class CI_Model {

	/**
	 * Constructor
	 *
	 * @access public
	 */
	function __construct()
	{
		log_message('debug', "Model Class Initialized");
	}

	/**
	 * __get
	 *
	 * Allows models to access CI's loaded classes using the same
	 * syntax as controllers.
	 *
	 * @param	string
	 * @access private
	 */
	function __get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
	}
}
// END Model Class

/* End of file Model.php */
/* Location: ./system/core/Model.php */