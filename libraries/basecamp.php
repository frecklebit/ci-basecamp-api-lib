<?php
/**
  * PHP Class: Basecamp
  *
  * This class allows you to connect to the new Basecamp API.
  * It is not compatible with the Basecamp Classic API. This class
  * contains its own built-in RESTful service.
  *
  * The Basecamp class is fully cusomizable and easy to use!
  *
  * @category	PHP Class
  * @package	Basecamp
  * @author		Adam Jenkins <adam@comicfuse.com>
  * @copyright	2012, Comicfuse/Adam Jenkins
  * @license	http://www.php.net/license/3_01.txt  PHP License 3.01
  * @version	Release: 1.0
  * @link		https://github.com/adamcole83/ci-basecamp-api-lib
  */  
class Basecamp {
	
	/* 
	 * Shall we use debugging?
	 *
	 * @var boolean
	 * @access public
	 */
	public $debug = true;
	
	/**
	 * An array containing any errors, use getter
	 *
	 * @var array
	 * @access private
	 */
	private $errors = array();
	
	/**
	 * Basecamp primary account ID, use setters/getters
	 *
	 * @var int
	 * @access private
	 */
	private $account_id = null;
	
	/**
	 * Basecamp username, use setters/getters
	 *
	 * @var string
	 * @access private
	 */
	private $username = '';
	
	/**
	 * Basecamp password, use setters/getters
	 *
	 * @var string
	 * @access private
	 */
	private $password = '';
	
	/**
	 * Application name using this library
	 *
	 * @var string
	 * @access public
	 */
	public $app_name = '';
	
	/**
	 * Content type, Basecamp requires JSON
	 *
	 * @var string
	 * @access public
	 */
	public $content_type = "application/json";
	
	/**
	 * Basecamp base URL
	 *
	 * @var string
	 * @access public
	 */
	public $basecamp_url = "https://basecamp.com/";
	
	/**
	 * Basecamp API Version
	 *
	 * @var string
	 * @access public
	 */
	public $api_version = 1;
	
	/**
	 * Stored RESTful request
	 *
	 * @var array
	 * @access private
	 */
	private $request = array();
	
	/**
	 * Stored RESTful Response
	 *
	 * @var array
	 * @access private
	 */
	private $response = array();
	
	/**
	 * The full built URL
	 *
	 * @var string
	 * @access private
	 */
	private $url = "";
	
	/**
	 * Initialization
	 * 
	 * The Basecamp constructor doesn't require the arguments, you can set them afterwards.
	 *
	 * Here's an example of instantiation:
	 * <code>
	 * required_once 'libraries/basecamp.php';
	 *
	 * $basecamp = new Basecamp('My App', 123456, 'tjones', 'abc123');
	 * </code>
	 * 
	 * @param string	$app_name The name of your application
	 * @param int		$account_id Your new Basecamp account identifier
	 * @param string	$username The username of an administrator
	 * @param string	$password The password of the administrator
	 *
	 * @return void
	 */
	public function __construct($app_name="", $account_id=null, $username="", $password="")
	{
		// Set defaults
		$this->setAppName($app_name);
		$this->setAccountID($account_id);
		$this->setUsername($username);
		$this->setPassword($password);
	}
	
	// --------------------------------------------------------------------
	//		ACCESSES
	// --------------------------------------------------------------------
	
	/**
	  * Retrieve all accesses for a specific project
	  * 
	  * <pre>GET /projects/1/accesses.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @return array List of people with access to the project
	  */  
	public function getAccessesForProject($project_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required for project accesses.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/accesses", 'GET');
	}
	
	/**
	  * Retrieve all accesses for a specific calendar
	  *
	  * <pre>GET /calendars/1/accesses.json</pre>
	  *
	  * @param int $calendar_id The calendar ID
	  * @return array List of people with access to the calendar
	  */  
	public function getAccessesForCalendar($calendar_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($calendar_id))
		{
			$this->errors[] = "Calendar ID is required for calendar accesses.";
			return false;
		}
		
		return $this->processRequest("calendars/{$calendar_id}/accesses", 'GET');
	}
	
	/**
	  * Grant access to a specific project
	  *
	  * <pre>POST /projects/1/accesses.json</pre>
	  *
	  * Example:
	  * <code>
	  *	// Using IDs
	  *	$basecamp->grantAccessToProject(123456, array("ids" => array(5, 6, 10)));
	  *
	  *	// Using email addresses (invite)
	  *	$basecamp->grantAccessToProject(12345, array("email_addresses" => array( "someone@example.com", "bob@example.com")));
	  * </code>
	  *
	  * @param int $project_id The project ID
	  * @param array $data Allowed data: [ids], [email_addresses]
	  * @return boolean success/fail
	  */  
	public function grantAccessToProject($project_id=null, $data=array())
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required for granting project accesses.";
			return false;
		}
		
		// validate data
		if( ! $this->validateData(array('|ids', '|email_addresses'), $data))
		{
			$this->errors[] = "Invalid input for granting access to projects.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/accesses", 'POST', $data);
	}
	
	/**
	  * Grant access to a specific calendar
	  *
	  * <pre>POST /calendar/1/accesses.json</pre>
	  *
	  * Example:
	  * <code>
	  *	// Using IDs
	  *	$basecamp->grantAccessToCalendar(123456, array("ids" => array(5, 6, 10)));
	  *
	  *	// Using email addresses (invite)
	  *	$basecamp->grantAccessToCalendar(12345, array("email_addresses" => array( "someone@example.com", "bob@example.com")));
	  * </code>
	  *
	  * @param int $calendar_id The calendar ID
	  * @param array $data Allowed data: [ids], [email_addresses]
	  * @return boolean success/fail
	  */  
	public function grantAccessToCalendar($calendar_id=null, $data=array())
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($calendar_id))
		{
			$this->errors[] = "Calendar ID is required for granting calendar access.";
			return false;
		}
		
		// validate data
		if( ! $this->validateData(array('|ids', '|email_addresses'), $data))
		{
			$this->errors[] = "Invalid input for granting access to calendars.";
			return false;
		}
		
		return $this->processRequest("calendars/{$calendar_id}/accesses", 'POST', $data);
	}
	
	/**
	  * Revoke access to projects
	  *
	  * <pre>DELETE /projects/1/accesses/1.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @param int $person_id The user ID
	  * @return boolean success/fail
	  */  
	public function revokeAccessToProject($project_id=null, $person_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required for revoking access to a project.";
			return false;
		}
		if (! isset($person_id))
		{
			$this->errors[] = "Person ID is required for revoking access to a project.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/accesses/{$person_id}", 'DELETE');
	}
	
	/**
	  * Revoke access to calendars
	  *
	  * <pre>DELETE /calendars/1/accesses/1.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @param int $person_id The user ID
	  * @return boolean success/fail
	  */  
	public function revokeAccessToCalendar($calendar_id=null, $person_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($calendar_id))
		{
			$this->errors[] = "Calendar ID is required for revoking access to calendars.";
			return false;
		}
		if( ! isset($person_id))
		{
			$this->errors[] = "Person ID is required for revoking access to calendars.";
			return false;
		}
		
		return $this->processRequest("calendars/{$calendar_id}/accesses/{$person_id}", 'DELETE');
	}
	
	// --------------------------------------------------------------------
	//		ATTACHMENTS/FILES
	// --------------------------------------------------------------------
	
	/**
	  * Retrieve all attachments
	  *
	  * <pre>GET /projects/1/attachments.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @return array Metadata, urls and associated attachables
	  */  
	public function getAttachments($project_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required for attachments.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/attachments", 'GET');
	}
	
	/**
	  * Retrieves token for attachment upload
	  *
	  * <pre>POST /attachments.json</pre>
	  * 
	  * Example:
	  * <code>
	  *	$basecamp->createAttachment('/var/www/assets/images/monkey.png');
	  * </code>
	  *
	  * @param string $file_path The path of the file to be uploaded
	  * @return string Token to save locally to upload file
	  */  
	public function createAttachment($file_path='')
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate file path
		if( ! isset($file_path) || empty($file_path) || ! file_exists($file_path))
		{
			$this->errors[] = "A valid file is required to create an attachment.";
			return false;
		}
		
		// set header content type
		$this->setFileContentType(@mime_content_type($file_path));
		
		return $this->processRequest("attachments", 'POSTFILE', file_get_contents($file_path));
	}
	
	/**
	  * Creates new entry in the files section
	  *
	  * <pre>POST /projects/1/uploads.json</pre>
	  *
	  * The <samp>attachments</samp> should be an array of file paths. Tokens are returned from 
	  * <samp>createAttachment()</samp> and names will be created automatically. The <samp>subscribers</samp>
	  * (optional) is a list of <samp>ids</samp> that will be notified of file.
	  *
	  * Example:
	  * <code>
	  * $basecamp->uploadFile(123456, array('content' => 'Test', 'subscribers' => array(1,4,2), 'attachments' => array('/path/to/file.png')));
	  * </code>
	  *
	  * @param int $project_id The project ID
	  * @param array $data Allowed data: <samp>content</samp>, <samp>subscribers</samp>, </samp>attachments</samp>
	  * @return boolean success/fails
	  */  
	public function uploadFile($project_id=null, $data=array())
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate data
		if( ! $this->validateData(array('content', 'subscribers', '*attachments'), $data))
		{
			$this->errors[] = "Invalid data input for uploading a file.";
			return false;
		}
		
		// only one file can be uploaded at a time
		if(is_array($data['attachments']))
		{
			$data['attachments'] = array_pop($data['attachments']);
		}
		$file_path = $data['attachments'];
		
		// Attempt to upload file and retrieve token
		if($token = $this->createAttachment($file_path)->token)
		{
			// format the new attachment
			unset($data['attachments']);
			$data['attachments'][0] = array(
				'token'	=> $token,
				'name'	=> basename($file_path)
			);
		}
		else
		{
			$this->errors[] = "Unable to upload file attachment.";
			return false;
		}

		return $this->processRequest("projects/{$project_id}/uploads", 'POST', $data);
	}
	
	/**
	  * Get content, comments and attachments of a specific upload
	  *
	  * <pre>GET /projects/1/uploads/$UPLOAD_ID.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @param int $upload_id The Upload ID
	  * @return object Content, comments and attachments
	  */  
	public function getUpload($project_id=null, $upload_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required for retrieving upload.";
			return false;
		}
		if( ! isset($upload_id))
		{
			$this->errors[] = "Upload ID is required for retrieving upload.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/uploads/{$upload_id}", 'GET');
	}
	
	// --------------------------------------------------------------------
	//		CALENDAR EVENTS
	// --------------------------------------------------------------------
	
	/**
	  * Retrieve all calendar events for a project
	  *
	  * <pre>GET /projects/1/calendar_events.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @return array Current JSON representation of the calendar lists
	  */  
	public function getProjectCalendarEvents($project_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required for project calendar events.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/calendar_events", 'GET');
	}
	
	/**
	  * Retrieve all calendar events for a calendar
	  *
	  * <pre>GET /calendars/1/calendar_events.json</pre>
	  *
	  * @param int $calendar_id The calendar ID
	  * @return array Current JSON representation of calendar events
	  */  
	public function getCalendarEvents($calendar_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($calendar_id))
		{
			$this->errors[] = "Calendar ID is required for calendar events.";
			return false;
		}
		
		return $this->processRequest("calendars/{$calendar_id}/calendar_events", 'GET');
	}
	
	/**
	  * Retrieve all past calendar events for a project
	  *
	  * <pre>GET /projects/1/calendar_events/past.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @return array Current JSON representation of project calendar events
	  */  
	public function getPastProjectCalendarEvents($project_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required for past project calendar events.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/calendar_events/past", 'GET');
	}
	
	/**
	  * Retrieve all past calendar events for a calendar
	  *
	  * <pre>GET /calendars/1/calendar_events/past.json</pre>
	  *
	  * @param int $calendar_id The calendar ID
	  * @return array Current JSON represenation of past calendar events
	  */  
	public function getPastCalendarEvents($calendar_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($calendar_id))
		{
			$this->errors[] = "Calendar ID is required for past calendar events.";
			return false;
		}
		
		return $this->processRequest("calendars/{$calendar_id}/calendar_events/past", 'GET');
	}
	
	/**
	  * Get single calendar event in a project
	  *
	  * <pre>GET /projects/1/calendar_events/1.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @param int $event_id The event ID
	  * @return object Current JSON represenation of the project event data
	  */ 
	public function getSingleProjectCalendarEvent($project_id=null, $event_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required for the project calendar event.";
			return false;
		}
		if( ! isset($event_id))
		{
			$this->errors[] = "Event ID is required for the project calendar event.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/calendar_events/{$event_id}", 'GET');
	}
	
	/**
	  * Get single calendar event in a calendar
	  * 
	  * <pre>GET /calendars/1/calendar_events/1.json</pre>
	  *
	  * @param int $calendar_id The calendar ID
	  * @param int $event_id The event ID
	  * @return object Current JSON representation of the calendar event data
	  */ 
	public function getSingleCalendarEvent($calendar_id=null, $event_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($calendar_id))
		{
			$this->errors[] = "Calendar ID is required for the calendar event.";
			return false;
		}
		if( ! isset($event_id))
		{
			$this->errors[] = "Event ID is required for the calendar event.";
			return false;
		}
		
		return $this->processRequest("calendars/{$calendar_id}/calendar_events/{$event_id}", 'GET');
	}
	
	/**
	  * Create a calendar event in a project
	  * 
	  * <pre>POST /projects/1/calendar_events.json</pre>
	  *
	  * The <samp>starts_at</samp> and <samp>ends_at</samp> parameters should be in ISO 8601 format (like "2012-08-16T16:00:00-05:00")
	  * The <samp>all_day</samp> parameter should be <samp>true</samp> or <samp>false</samp>.
	  * 
	  * @param int $project_id The project ID
	  * @param array $data Accepted data: <samp>summary</samp>, <samp>description</samp>, <samp>all_day</samp>, <samp>starts_at</samp>, <samp>ends_at</samp>
	  * @return object Current JSON representation of the project calendar event
	  */  
	public function createProjectCalendarEvent($project_id=null, $data=array())
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required for creating a calendar event.";
			return false;
		}
		
		// validate data
		if( ! $this->validateData(array('*summary', 'description', '|all_day', '|starts_at', 'ends_at'), $data))
		{
			$this->errors[] = "Invalid data input for creating a calendar event.";
			return false;
		}
		if(isset($data['starts_at']) && ! $this->validateDateTime($data['starts_at']))
		{
			$this->errors[] = "Starts_at requires ISO 8601 formatting.";
			return false;
		}
		if(isset($data['ends_at']) && ! $this->validateDateTime($data['ends_at']))
		{
			$this->errors[] = "Ends_at requires ISO 8601 formatting.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/calendar_events", 'POST', $data);
	}
	
	/**
	  * Create a calendar event
	  *
	  * <pre>POST /calendars/1/calendar_events.json</pre>
	  *
	  * The <samp>starts_at</samp> and <samp>ends_at</samp> parameters should be in ISO 8601 format (like "2012-08-16T16:00:00-05:00")
	  * The <samp>all_day</samp> parameter should be <samp>true</samp> or <samp>false</samp>.
	  * 
	  * @param int $calendar_id The calendar ID
	  * @param array $data Accepted data: <samp>summary</samp>, <samp>description</samp>, <samp>all_day</samp>, <samp>starts_at</samp>, <samp>ends_at</samp>
	  * @return object Current JSON representation of the calendar event
	  */  
	public function createCalendarEvent($calendar_id=null, $data=array())
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($calendar_id))
		{
			$this->errors[] = "Calendar ID is required for creating a calendar event.";
			return false;
		}
		
		// validate data
		if( ! $this->validateData(array('*summary', 'description', '|all_day', '|starts_at', 'ends_at'), $data))
		{
			$this->errors[] = "Invalid data input for creating a calendar event.";
			return false;
		}
		if(isset($data['starts_at']) && ! $this->validateDateTime($data['starts_at']))
		{
			$this->errors[] = "Starts_at requires ISO 8601 formatting.";
			return false;
		}
		if(isset($data['ends_at']) && ! $this->validateDateTime($data['ends_at']))
		{
			$this->errors[] = "Ends_at requires ISO 8601 formatting.";
			return false;
		}
		
		return $this->processRequest("calendars/{$calendar_id}/calendar_events", 'POST', $data);
	}
	
	/**
	  * Updates a single calendar event in a project
	  * 
	  * <pre>PUT /projects/1/calendar_events/1.json</pre>
	  *
	  * The <samp>starts_at</samp> and <samp>ends_at</samp> parameters should be in ISO 8601 format (like "2012-08-16T16:00:00-05:00")
	  * The <samp>all_day</samp> parameter should be <samp>true</samp> or <samp>false</samp>.
	  * 
	  * @param int $project_id The project ID
	  * @param int $event_id The event ID
	  * @param array $data Accepted data: <samp>summary</samp>, <samp>description</samp>, <samp>all_day</samp>, <samp>starts_at</samp>, <samp>ends_at</samp>
	  * @return object Current JSON representation of the calendar event
	  */  
	public function updateProjectCalendarEvent($project_id=null, $event_id=null, $data=array())
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required for updating project calendar events.";
			return false;
		}
		if( ! isset($event_id))
		{
			$this->errors[] = "Event ID is required for updating project calendar events.";
			return false;
		}
		
		// validate data
		if( ! $this->validateData(array('*summary', 'description', 'all_day', 'starts_at', 'ends_at'), $data))
		{
			$this->errors[] = "Invalid data input for updating the project calendar event.";
			return false;
		}
		if(isset($data['starts_at']) && ! $this->validateDateTime($data['starts_at']))
		{
			$this->errors[] = "Starts_at requires ISO 8601 formatting.";
			return false;
		}
		if(isset($data['ends_at']) && ! $this->validateDateTime($data['ends_at']))
		{
			$this->errors[] = "Ends_at requires ISO 8601 formatting.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/calendar_events/{$event_id}", 'PUT', $data);
	}
	
	/**
	  * Updates a single calendar event in a specified calendar
	  * 
	  * <pre>PUT /calendars/1/calendar_events/1.json</pre>
	  *
	  * The <samp>starts_at</samp> and <samp>ends_at</samp> parameters should be in ISO 8601 format (like "2012-08-16T16:00:00-05:00")
	  * The <samp>all_day</samp> parameter should be <samp>true</samp> or <samp>false</samp>.
	  * 
	  * @param int $calendar_id The calendar ID
	  * @param int $event_id The event ID
	  * @param array $data Accepted data: <samp>summary</samp>, <samp>description</samp>, <samp>all_day</samp>, <samp>starts_at</samp>, <samp>ends_at</samp>
	  * @return object Current JSON representation of the calendar event
	  */  
	public function updateCalendarEvent($calendar_id=null, $event_id=null, $data=array())
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($calendar_id))
		{
			$this->errors[] = "Calendar ID is required for updating a calendar event.";
			return false;
		}
		if( ! isset($event_id))
		{
			$this->errors[] = "Event ID is required for updating a calendar event.";
			return false;
		}
		
		// validate data
		if( ! $this->validateData(array('*summary', 'description', 'all_day', 'starts_at', 'ends_at'), $data))
		{
			$this->errors[] = "Invalid data input for updating the calendar event.";
			return false;
		}
		if(isset($data['starts_at']) && ! $this->validateDateTime($data['starts_at']))
		{
			$this->errors[] = "Starts_at requires ISO 8601 formatting.";
			return false;
		}
		if(isset($data['ends_at']) && ! $this->validateDateTime($data['ends_at']))
		{
			$this->errors[] = "Ends_at requires ISO 8601 formatting.";
			return false;
		}
		
		return $this->processRequest("calendars/{$calendar_id}/calendar_events/{$event_id}", 'PUT', $data);
	}
	
	/**
	  * Deletes a single calendar event
	  * 
	  * <pre>DELETE /projects/1/calendar_events/1.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @param int $event_id The event ID
	  * @return boolean success/fail
	  */  
	public function deleteProjectCalendarEvent($project_id=null, $event_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required for deleting project calendar events.";
			return false;
		}
		if( ! isset($event_id))
		{
			$this->errors[] = "Event ID is required for deleting project calendar events.";
		}
		
		return $this->processRequest("projects/{$project_id}/calendar_events/{$event_id}", 'DELETE');
	}
	
	/**
	  * Deletes a single calendar event
	  * 
	  * <pre>DELETE /calendars/1/calendar_events/1.json</pre>
	  *
	  * @param int $calendar_id The calendar ID
	  * @param int $event_id The event ID
	  * @return boolean success/fail
	  */  
	public function deleteCalendarEvent($calendar_id=null, $event_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($calendar_id))
		{
			$this->errors[] = "Calendar ID is required for deleting calendar events.";
			return false;
		}
		if( ! isset($event_id))
		{
			$this->errors[] = "Event ID is required for deleting calendar events.";
			return false;
		}
		
		return $this->processRequest("calendars/{$calendar_id}/calendar_events/{$event_id}", 'DELETE');
	}
	
	// --------------------------------------------------------------------
	//		CALENDARS
	// --------------------------------------------------------------------
	
	/**
	  * Retrieve all calendars
	  * 
	  * <pre>GET /calendars.json</pre>
	  *	
	  * @return array Current JSON representation of the list of calendars
	  */  
	public function getCalendars()
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		return $this->processRequest("calendars", 'GET');
	}
	
	/**
	  * Retrieves single calendar
	  * 
	  * <pre>GET /calendars/1.json</pre>
	  *
	  * @param int $calendar_id The calendar ID
	  * @return object Current JSON representation of the calendar data
	  */  
	public function getSingleCalendar($calendar_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($calendar_id))
		{
			$this->errors[] = "Calendar ID is required to retrieve a calendar.";
			return false;
		}
		
		return $this->processRequest("calendars/{$calendar_id}", 'GET');
	}
	
	/**
	  * Creates a new calendar or multiple calendars if $data is array
	  * 
	  * <pre>POST /calendars.json</pre>
	  *
	  * Example:
	  * <code>
	  * $basecamp->createCalendar(array('name' => 'My New Calendar'));
	  * </code>
	  *
	  * @param array $data Accepted data: <samp>name</samp>
	  * @return object calendar data or false
	  */  
	public function createCalendar($data=array())
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate data
		if( ! $this->validateData(array('*name'), $data))
		{
			$this->errors[] = "Invalid data input for creating a calendar.";
			return false;
		}
		
		return $this->processRequest("calendars", 'POST', $data);
	}
	
	/**
	  * Updates a single calendar
	  * 
	  * <pre>PUT /calendars/1.json</pre>
	  *
	  * Example:
	  *	<code>
	  * $basecamp->updateCalendar(123456, array('name' => 'Meetings'));
	  * </code>
	  *
	  * @param int $calendar_id The calendar ID
	  * @param array $data Accepted data: <samp>name</samp>
	  * @return object calendar data or false
	  */  
	public function updateCalendar($calendar_id=null, $data="")
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($calendar_id))
		{
			$this->errors[] = "Calendar ID is required for updating a calendar.";
			return false;
		}
		
		// validate data
		if( ! $this->validateData(array('*name'), $data))
		{
			$this->errors[] = "Invalid data input for updating a calendar.";
			return false;
		}
		
		return $this->processRequest("calendars/{$calendar_id}", 'PUT', $data);
	}
	
	/**
	  * Deletes a single calendar
	  * 
	  * <pre>DELETE /calendars/1.json</pre>
	  *
	  * @param int $calendar_id The calendar ID
	  * @return boolean success/fail
	  */  
	public function deleteCalendar($calendar_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($calendar_id))
		{
			$this->errors[] = "Calendar ID is required for deleting a calendar.";
			return false;
		}
		
		return $this->processRequest("calendars/{$calendar_id}", 'DELETE');
	}
	
	// --------------------------------------------------------------------
	//		COMMENTS 
	//			No getter, comments are included in topics directly
	// --------------------------------------------------------------------
	
	/**
	  * Creates a new comment for specified project and topic
	  * 
	  * <pre>POST /projects/1/<topic>/1/comments.json</pre>
	  *
	  * The <samp>subscribers</samp> (optional) is a list of <samp>ids</samp> that will be notified of file.
	  * The <samp>attachments</samp> should be an array of file paths. Tokens are returned from 
	  * <samp>createAttachment()</samp> and names will be created automatically. 
	  *
	  * @param int $project_id The project ID
	  * @param string $topic The topic to place comment
	  * @param int $topic_id The topic ID
	  * @param array $data Accepted data: <samp>content</samp>, <samp>subscribers</samp>, <samp>attachments</samp>
	  * @return object comment data or false
	  */  
	public function createComment($project_id=null, $topic="", $topic_id=null, $data=array())
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required for creating comments.";
			return false;
		}
		if( ! isset($topic) && empty($topic))
		{
			$this->errors[] = "A topic (messages, todos, uploads, etc) is required to create a comment.";
			return false;
		}
		if( ! isset($topic_id))
		{
			$this->errors[] = "Topic ID is required to create a comment.";
			return false;
		}
		
		// validate data
		if( ! $this->validateData(array('*content', 'subscribers', 'attachments'), $data))
		{
			$this->errors[] = "Invalid data input for creating a comment.";
			return false;
		}
		
		// multiple files can be uploaded
		if(isset($data['attachments']))
		{
			foreach($data['attachments'] as $file_path)
			{
				// Attempt to upload file and retrieve token
				if($token = $this->createAttachment($file_path)->token)
				{
					// format the new attachment
					unset($data['attachments']);
					$data['attachments'][0] = array(
						'token'	=> $token,
						'name'	=> basename($file_path)
					);
				}
				else
				{
					$this->errors[] = "Unable to upload file attachment.";
					return false;
				}
			}
		}
		
		return $this->processRequest("projects/{$project_id}/{$topic}/{$topic_id}/comments", 'POST', $data);
	}
	
	/**
	  * Deletes a comment specified
	  * 
	  * <pre>DELETE /projects/1/comments/1.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @param int $comment_id The comment ID
	  * @return boolean success/fail
	  */  
	public function deleteComment($project_id=null, $comment_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required for deleting comments.";
			return false;
		}
		if( ! isset($comment_id))
		{
			$this->errors[] = "Comment ID is required for deleting comments.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/comments/{$comment_id}", 'DELETE');
	}
	
	// --------------------------------------------------------------------
	//		DOCUMENTS
	// --------------------------------------------------------------------
	
	/**
	  * Retrieves most recent version of documents
	  * 
	  * <pre>GET /projects/1/document.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @return object Current JSON representation of the document data
	  */  
	public function getDocuments($project_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required for retrieving documents.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/documents", 'GET');
	}
	
	/**
	  * Retrieves recent version of single document
	  * 
	  * <pre>GET /projects/1/documents/1.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @param int $document_id The document ID
	  * @return object Current JSON representation of the document data 
	  */  
	public function getSingleDocument($project_id=null, $document_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required for retrieving documents.";
			return false;
		}
		if( ! isset($document_id))
		{
			$this->errors[] = "Document ID is required for retrieving documents.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/documents/{$document_id}", 'GET');
	}
	
	/**
	  * Creates a new document from the parameters passed
	  * 
	  * <pre>POST /projects/1/documents.json</pre>
	  *
	  * Basic HTML is allowed in the data content.
	  *
	  * @param int $project_id The project ID
	  * @param array $data Accepted data: <samp>title</samp>, <samp>content</samp>
	  * @return object Current JSON representation of the document data
	  */  
	public function createDocument($project_id=null, $data=array())
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to create a document.";
			return false;
		}
		
		// validate data
		if( ! $this->validateData(array('*title', '*content'), $data))
		{
			$this->errors[] = "Invalid data input for ";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/documents", 'POST', $data);
	}
	
	/**
	  * Updates a document in a specified project
	  * 
	  * <samp>PUT /projects/1/documents/1.json</samp>
	  *
	  * Basic HTML is allowed in the data content.
	  *
	  * @param int $project_id The project ID
	  * @param int $document_id The document ID
	  * @param array $data Accepted data: <samp>title</samp>, <samp>content</samp>
	  * @return object Current JSON representation of the document data
	  */  
	public function updateDocument($project_id=null, $document_id=null, $data=array())
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to update a document.";
			return false;
		}
		if( ! isset($document_id))
		{
			$this->errors[] = "Document ID is required to update a document.";
			return false;
		}
		
		// validate data
		if( ! $this->validateData(array('*title', '*content'), $data))
		{
			$this->errors[] = "Invalid data input for ";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/documents/{$document_id}", 'PUT', $data);
	}
	
	/**
	  * Deletes a specified document from a project
	  * 
	  * <samp>DELETE /projects/1/documents/1.json</samp>
	  *
	  * @param int $project_id The project ID
	  * @param int $document_id The document ID
	  * @return boolean success/fail
	  */  
	public function deleteDocument($project_id=null, $document_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to delete a document.";
			return false;
		}
		if( ! isset($document_id))
		{
			$this->errors[] = "Document ID is required to deleting a document.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/documents/{$document_id}", 'DELETE');
	}
	
	// --------------------------------------------------------------------
	//		GLOBAL EVENTS
	// --------------------------------------------------------------------
	
	/**
	  * Returns all global events between specified dates passed
	  * 
	  * <pre>GET /events.json?since=0000-00-00T00:00:00-00:00&page=0</pre>
	  *
	  * Returns 50 events per page. If the result has 50 entries, check next page.
	  * Datetime should be in the ISO 8601 format (like "0000:00:00T00:00:00-00:00").
	  *
	  * @param string $datetime The ISO 8601 datetime
	  * @param int $page The page number to begin query
	  * @return array Current JSON representation of global events
	  */  
	public function getGlobalEvents($datetime="", $page=1)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($datetime) || empty($datetime) || ! $this->validateDateTime($datetime))
		{
			$this->errors[] = "ISO 8601 formatted datetime is required to get global events.";
			return false;
		}
		
		return $this->processRequest("events", 'GET', '', array('since' => $datetime, 'page' => $page));
	}
	
	/**
	  * Retieves 50 project events at a time, with pagination
	  * 
	  * <pre>>GET /projects/1/events.json?since=0000-00-00T00:00:00-00:00&page=0</pre>
	  *
	  * Returns 50 events per page. If the result has 50 entries, check next page.
	  * Datetime should be in the ISO 8601 format (like "0000:00:00T00:00:00-00:00").
	  *
	  * @param int $project_id The project ID
	  * @param string $datetime The ISO 8601 datetime
	  * @param int $page The page number to begin query
	  * @return array Current JSON representation of project events
	  */  
	public function getProjectEvents($project_id=null, $datetime="", $page=1)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to get project events.";
			return false;
		}
		if( ! isset($datetime) || empty($datetime) || ! $this->validateDateTime($datetime))
		{
			$this->errors[] = "ISO 8601 formatted datetime is required to get project events.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/events", 'GET', '', array('since' => $datetime, 'page' => $page));
	}
	
	/**
	  * Retrieves 50 person events at a time, with pagination
	  * 
	  * <pre>GET /people/1/events.json?since=0000-00-00T00:00:00-00:00$page=0</pre>
	  * 
	  * Returns 50 events per page. If the result has 50 entries, check next page.
	  * Datetime should be in the ISO 8601 format (like "0000:00:00T00:00:00-00:00").
	  *
	  * @param int $person_id The person ID
	  * @param string $datetime The ISO 8601 datetime
	  * @param int $page The page number to begin query
	  * @return array Current JSON representation of user's events
	  */  
	public function getPersonsEvents($person_id=null, $datetime="", $page=1)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($person_id))
		{
			$this->errors[] = "Person ID is required to get user's events.";
			return false;
		}
		if( ! isset($datetime) || empty($datetime) || ! $this->validateDateTime($datetime))
		{
			$this->errors[] = "ISO 8601 formatted datetime is required to get user's events.";
			return false;
		}
		
		return $this->processRequest("people/{$person_id}/events", 'GET', '', array('since' => $datetime, 'page' => $page));
	}
	
	// --------------------------------------------------------------------
	//		MESSAGES/DISCUSSIONS
	// --------------------------------------------------------------------
	
	/**
	  * Retrieves specified message/discussion from project
	  * 
	  * <pre>GET /projects/1/messages/1.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @param int $message_id The message ID
	  * @return object Current JSON representation of the message
	  */  
	public function getSingleMessage($project_id=null, $message_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to get messages.";
			return false;
		}
		if( ! isset($message_id))
		{
			$this->errors[] = "Message ID is required to get messages.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/messages/{$message_id}", 'GET');
	}
	
	/**
	  * Creates a message/discussion in a project
	  * 
	  * <pre>POST /projects/1/messages.json</pre>
	  *
	  *	The <samp>subscribers</samp> (optional) is a list of <samp>ids</samp> that will be notified of file.
	  * The <samp>attachments</samp> should be an array of file paths. Tokens are returned from 
	  * <samp>createAttachment()</samp> and names will be created automatically. 
	  *
	  * @param int $project_id The project ID
	  * @param array $data Accepted data: <samp>subject</samp>, <samp>content</samp>, <samp>subscribers</samp>, <samp>attachments</samp>
	  * @return object Current JSON representation of the message
	  */  
	public function createMessage($project_id=null, $data=array())
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to create messages.";
			return false;
		}
		
		// validate data
		if( ! $this->validateData(array('*subject', '*content', 'subscribers', 'attachments'), $data))
		{
			$this->errors[] = "Invalid data input for creating messages.";
			return false;
		}
		
		// multiple files can be uploaded
		if(isset($data['attachments']))
		{
			foreach($data['attachments'] as $file_path)
			{
				// Attempt to upload file and retrieve token
				if($token = $this->createAttachment($file_path)->token)
				{
					// format the new attachment
					unset($data['attachments']);
					$data['attachments'][0] = array(
						'token'	=> $token,
						'name'	=> basename($file_path)
					);
				}
				else
				{
					$this->errors[] = "Unable to upload file attachment.";
					return false;
				}
			}
		}
		
		return $this->processRequest("projects/{$project_id}/messages", 'POST', $data);
	}
	
	/**
	  * Updates the message/discussion in a project
	  * PUT /projects/1/messages/1.json		-> Updates the message
	  * 
	  *	The <samp>subscribers</samp> (optional) is a list of <samp>ids</samp> that will be notified of file.
	  * The <samp>attachments</samp> should be an array of file paths. Tokens are returned from 
	  * <samp>createAttachment()</samp> and names will be created automatically. 
	  *
	  * @param int $project_id The project ID
	  * @param int $message_id The message ID
	  * @param array $data Accepted data: <samp>subject</samp>, <samp>content</samp>, <samp>subscribers</samp>, <samp>attachments</samp>
	  * @return object Current JSON representation of the message
	  */  
	public function updateMessage($project_id=null, $message_id=null, $data=array())
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required for updating messages.";
			return false;
		}
		if( ! isset($message_id))
		{
			$this->errors[] = "Message ID is required for updating messages.";
			return false;
		}
		
		// validate data
		if( ! $this->validateData(array('*subject', '*content', 'subscribers', 'attachments'), $data))
		{
			$this->errors[] = "Invalid data input for updating messages.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/messages/{$message_id}", 'PUT', $data);
	}
	
	/**
	  * Deletes the specified message/discussion from a project
	  * 
	  * <pre>DELETE /projects/1/messages/1.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @param int $message_id The message ID
	  * @return boolean success/fail
	  */  
	public function deleteMessage($project_id=null, $message_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required for deleting messages.";
			return false;
		}
		if( ! isset($message_id))
		{
			$this->errors[] = "Message ID is required for deleting messages.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/messages/{$message_id}", 'DELETE');
	}
	
	// --------------------------------------------------------------------
	//		PEOPLE
	//			New people are invited directly to projects via accesses
	// --------------------------------------------------------------------
	
	/**
	  * Retrieves all the people on the account
	  * 
	  * <pre>GET /people.json</pre>
	  *
	  * @return array Current JSON representation of all users
	  */  
	public function getPeople()
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		return $this->processRequest("people", 'GET');
	}
	
	/**
	  * Retrieves a single person
	  * 
	  * <pre>GET /people/1.json</pre>
	  *
	  * @param int $person_id The person ID
	  * @return object Current JSON representation of the user
	  */  
	public function getPerson($person_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($person_id))
		{
			$this->errors[] = "Person ID is required to retrieve user info.";
			return false;
		}
		
		return $this->processRequest("people/{$person_id}", 'GET');
	}
	
	/**
	  * Retrieves the person logged in (you)
	  * 
	  * <pre>GET /people/me.json</pre>
	  *
	  * @return object Current JSON representation of your user data
	  */  
	public function getMe()
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		return $this->processRequest("people/me", 'GET');
	}
	
	/**
	  * Deletes a specified person
	  * 
	  * <pre>DELETE /people/1.json</pre>
	  *
	  * @param int $person_id The user ID
	  * @return boolean success/fail
	  */  
	public function deletePerson($person_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($person_id))
		{
			$this->errors[] = "Person ID is required to delete a user.";
			return false;
		}
		
		return $this->processRequest("people/{$person_id}", 'DELETE');
	}
	
	// --------------------------------------------------------------------
	//		PROJECTS
	// --------------------------------------------------------------------
	
	/**
	  * Retrieves all active projects
	  * 
	  * <pre>GET /projects.json</pre>
	  *
	  * @return array Current JSON representation of active projects
	  */  
	public function getProjects()
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		return $this->processRequest("projects", 'GET');
	}
	
	/**
	  * Retrieves all archived projects
	  * 
	  * <pre>GET /projects/archived.json</pre>
	  *
	  * @return array Current JSON representation of archived projects
	  */  
	public function getArchivedProjects()
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		return $this->processRequest("projects/archived", 'GET');
	}
	
	/**
	  * Retrieves single project
	  * 
	  * <pre>GET /projects/1.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @return object Current JSON representaion of the project
	  */  
	public function getSingleProject($project_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to retrieve a project.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}", 'GET');
	}
	
	/**
	  * Creates a new project
	  * 
	  * <pre>POST /projects.json</pre>
	  *
	  * @param array $data Accepted data: <samp>name</samp>, <samp>description</samp>
	  * @return object Current JSON representation of the project
	  */  
	public function createProject($data=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate data
		if( ! $this->validateData(array('*name', 'description'), $data))
		{
			$this->errors[] = "Invalid data input for creating a project";
			return false;
		}
		
		return $this->processRequest("projects", 'POST', $data);
	}
	
	/**
	  * Updates a single project
	  * 
	  * <pre>PUT /projects/1.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @param array $data Accepted data: <samp>name</samp>, <samp>description</samp>
	  * @return object Current JSON representation of the project
	  */  
	public function updateProject($project_id=null, $data=array())
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to update project.";
			return false;
		}
		
		// validate data
		if( ! $this->validateData(array('name', 'description'), $data))
		{
			$this->errors[] = "Invalid data input for updating a project.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}", 'PUT', $data);
	}
	
	/**
	  * Activates an archived project
	  * 
	  * <pre>PUT /projects/1.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @return object Current JSON representation of the project
	  */  
	public function activateProject($project_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to activate a project.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}", 'PUT', array('archived' => false));
	}
	
	/**
	  * Archives an active project
	  *	
	  * <pre>PUT /projects/1.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @return object Current JSON representation of the project
	  */  
	public function archiveProject($project_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to archive a project.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}", 'PUT', array('archived' => true));
	}
	
	/**
	  * Deletes a specified project
	  * 
	  * <pre>DELETE /project/1.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @return boolean success/fail
	  */  
	public function deleteProject($project_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to delete a project.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}", 'DELETE');
	}
	
	// --------------------------------------------------------------------
	//		;TODOLISTS
	// --------------------------------------------------------------------
	
	/**
	  * Retrieves the todo lists of a specified project
	  * 
	  * <pre>GET /projects/1/todolists.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @return array Current JSON representation of project todolists
	  */  
	public function getProjectToDoLists($project_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required for project todo lists.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/todolists", 'GET');
	}
	
	/**
	  * Retrieves completed todolists
	  * 
	  * <pre>GET /projects/1/todolists/completed.json</pre>
	  *
	  * @param int $project_id
	  * @return array Current JSON representaion of completed project todolists
	  */  
	public function getProjectsCompletedToDoLists($project_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to retrieve completed project todo lists.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/todolists/completed", 'GET');
	}
	
	/**
	  * Shorthand method to retrieve completed todolists
	  * 
	  * <strong>See method <samp>getProjectsCompletedToDoLists()</samp></strong>
	  *
	  * @param int $project_id The project ID
	  * @return array Current JSON representation of completed project todolists
	  */  
	public function getCompletedTDL($project_id=null)
	{
		return $this->getProjectsCompletedToDoLists($project_id);
	}
	
	/**
	  * Retrieves assigned todolists of the specified person
	  * 
	  * <pre>GET /people/1/assigned_todos.json</pre>
	  *
	  * @param int $person_id The person ID
	  * @return array Current JSON representaion of user's assigned todolists
	  */  
	public function getPersonsAssignedToDoLists($person_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($person_id))
		{
			$this->errors[] = "Person ID is required to retrieve user's todo lists.";
			return false;
		}
		
		return $this->processRequest("people/{$person_id}/assigned_todos", 'GET');
	}
	
	/**
	  * Shorthand method to retrieve person's assigned todo lists
	  * 
	  * <strong>See method <samp>getPersonsAssignedToDoList()</samp></strong>
	  *
	  * @param int $person_id
	  * @return array assigned todolist data
	  */  
	public function getAssignedTDL($person_id=null)
	{
		return $this->getPersonsAssignedToDoLists($person_id);
	}
	
	/**
	  * Retrieves a single ToDo List in specified project
	  * 
	  * <pre>GET /projects/1/todolists/1.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @param int $todolist_id The todo list ID
	  * @return object Current JSON representation of the todolist
	  */  
	public function getSingleToDoList($project_id=null, $todolist_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to retrieve a todo list.";
			return false;
		}
		if( ! isset($todolist_id))
		{
			$this->errors[] = "Todo List ID is required to retrieve a todo list.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/todolists/{$todolist_id}", 'GET');
	}
	
	/**
	  * Creates a new ToDo List in a specified project
	  * 
	  * <pre>POST /projects/1/todolists.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @param array $data Accepted data: <samp>name</samp>, <samp>description</samp>
	  * @return object Current JSON representation of the project todo list.
	  */  
	public function createProjectToDoList($project_id=null, $data=array())
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to create a project todo list.";
			return false;
		}
		
		// validate data
		if( ! $this->validateData(array('*name', 'description'), $data))
		{
			$this->errors[] = "Invalid data input for ";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/todolists", 'POST', $data);
	}
	
	/**
	  * Shorthand method to create a project todo list
	  * 
	  * <strong>See method <samp>createProjectToDoList()</samp></strong>
	  *
	  * @param int $project_id, array $data
	  * @return object Current JSON representation of the project todo list
	  */  
	public function createToDoList($project_id=null, $data=array())
	{
		return $this->createProjectToDolist($project_id, $data);
	}
	
	/**
	  * Update a ToDoList in a specified project
	  * PUT /projects/1/todolists/1.json
	  *
	  * @param int $project_id The project ID
	  * @param int $todolist_id The todolist ID
	  * @param array $data Accepted data: <samp>name</samp>, <samp>description</samp>, <samp>position</samp>
	  * @return object Current JSON representation of the project todo list.
	  */  
	public function updateProjectToDoList($project_id=null, $todolist_id=null, $data=array())
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to update a project todo list.";
			return false;
		}
		if( ! isset($todolist_id))
		{
			$this->errors[] = "Todo list ID is required to update a project todo list.";
			return false;
		}
		
		// validate data
		if( ! $this->validateData(array('*name', 'description', 'position'), $data))
		{
			$this->errors[] = "Invalid data input for updating a project todo list.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/todolists/{$todolist_id}", 'PUT', $data);
	}
	
	/**
	  * Shorthand method to update a project todo list
	  * 
	  * <strong>See method <samp>updateProjectToDoList()</samp></strong>
	  *
	  * @param int $project_id The project ID
	  * @param int $todolist_id The todolist ID
	  * @param array $data Accepted data: <samp>name</samp>, <samp>description</samp>, <samp>position</samp>
	  * @return object Current JSON representation of the project todo list.
	  */  
	public function updateToDoList($project_id=null, $todolist_id=null, $data=array())
	{
		return $this->updateProjectToDoList($project_id, $todolist_id, $data);
	}
	
	/**
	  * Deletes a specified project ToDo List
	  * 
	  * <pre>DELETE /projects/1/todolists/1.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @param int $todolist_id The todolist ID
	  * @return boolean success/fail
	  */  
	public function deleteToDoList($project_id=null, $todolist_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to delete a todo list.";
			return false;
		}
		if( ! isset($todolist_id))
		{
			$this->errors[] = "Todo List ID is required to delete a todo list.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/todolists/{$todolist_id}", 'DELETE');
	}
	
	// --------------------------------------------------------------------
	//		;TODOS
	// --------------------------------------------------------------------
	
	/**
	  * Retrieves a single todo from a project 
	  * 
	  * <pre>GET /projects/1/todos/1.json</pre>
	  *
	  * @param int $project_id The project ID
	  * @param int $todo_id The task/todo ID
	  * @return object Current JSON representation of the task/todo
	  */  
	public function getToDo($project_id=null, $todo_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to get a task.";
			return false;
		}
		if( ! isset($todo_id))
		{
			$this->errors[] = "Todo ID is required to get a task.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/todos/{$todo_id}", 'GET');
	}
	
	/**
	  * Creates a todo item for a project
	  *
	  * <samp>POST /projects/1/todolists/1/todos.json</samp>
	  *
	  * The <samp>due_at</samp> parameter should be in ISO 8601 format (like "2012-08-16T16:00:00-05:00")
	  * The assignee parameters need a <samp>type</samp> field with either <samp>Person</samp> or <samp>Group</samp>,
	  * the <samp>id</samp> is the ID of the person or group.
	  *
	  * @param int $project_id The project ID
	  * @param int $todolist_id The todolist ID
	  * @param array $data Accepted data: [content], [due_at], [assignee[id],[type]]
	  * @return object todo data
	  */  
	public function createToDo($project_id=null, $todolist_id=null, $data=array())
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to create a task.";
			return false;
		}
		if( ! isset($todolist_id))
		{
			$this->errors[] = "Todo List ID is required to create a task.";
			return false;
		}
		
		// validate data
		if( ! $this->validateData(array('*content', 'due_at', 'assignee'), $data))
		{
			$this->errors[] = "Invalid data input for creating a task.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/todolists/{$todolist_id}/todos", 'POST', $data);
	}
	
	/**
	  * Update a single todo in a project
	  *
	  * <sample>PUT /projects/1/todos/1.json</sample>
	  *
	  * The <samp>due_at</samp> parameter should be in ISO 8601 format (like "2012-08-16T16:00:00-05:00")
	  * The assignee parameters need a <samp>type</samp> field with either <samp>Person</samp> or <samp>Group</samp>,
	  * the <samp>id</samp> is the ID of the person or group.
	  *
	  * @param int $project_id The project ID
	  * @param int $todo_id The task/todo ID
	  * @param array $data Accepted data: <samp>content</samp>, <samp>due_at</samp>, <samp>assignee</samp>, <samp>position</samp>
	  * @return object Current JSON representation of the todo if the creation was a success
	  */  
	public function updateToDo($project_id=null, $todo_id=null, $data=array())
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to update a task.";
			return false;
		}
		if( ! isset($todo_id))
		{
			$this->errors[] = "Task ID is requied to update a task.";
			return false;
		}
		
		// validate date
		if( ! $this->validateData(array('*content', 'due_at', 'assignee', 'position'), $data))
		{
			$this->errors[] = "Invalid data input for updating a task.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/todos/{$todo_id}", 'PUT', $data);
	}
	
	/**
	  * Delete the todo item from a ToDoList in a project
	  *
	  * <samp>DELETE /projects/1/todos/1.json</samp>
	  *
	  * @param int $project_id The project ID
	  * @param int $todo_id The task/todo ID
	  * @return boolean success/fail
	  */  
	public function deleteToDo($project_id=null, $todo_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate arguments
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to delete a task.";
			return false;
		}
		if( ! isset($todo_id))
		{
			$this->errors[] = "Task ID is required to delete a task.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/todos/{$todo_id}", 'DELETE');
	}
	
	// --------------------------------------------------------------------
	//		TOPICS
	// --------------------------------------------------------------------
	
	/**
	  * Retrieves all topics from a project
	  *
	  * <sample>GET /projects/1/topics.json</sample>
	  *
	  * @param int $project_id The project ID
	  * @return array List of topic data
	  */  
	public function getTopics($project_id=null)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		// validate argument
		if( ! isset($project_id))
		{
			$this->errors[] = "Project ID is required to retrieve topics.";
			return false;
		}
		
		return $this->processRequest("projects/{$project_id}/topics", 'GET');
	}
	
	// --------------------------------------------------------------------
	//		CONFIGURATION SETTERS/GETTERS
	// --------------------------------------------------------------------
	
	/**
	  * Retrieve any user-end errors for display.
	  *
	  * @param string $prefix The leading html for each error
	  * @param string $suffix The following html for each error
	  * @return string HTML error list
	  */  
	public function errors($prefix='<p>', $suffix='</p>')
	{
		$string = '';
		if(count($this->errors))
		{
			foreach($this->errors as $error)
			{
				$string .= $suffix.$error.$suffix;
			}
		}
		return $string;
	}
	
	/**
	  * Allows you to set/change accountID after instantiation
	  *
	  * @param int $id
	  * @return void
	  */  
	public function setAccountID($id=null)
	{
		$this->account_id = $id;
	}
	
	/**
	  * Allows you to get the current account ID
	  *
	  * @return int account id
	  */  
	public function getAccountID()
	{
		return $this->account_id;
	}
	
	/**
	  * Allows you to set/change the app name after instantiation
	  *
	  * @param string $app_name
	  * @return void
	  */  
	public function setAppName($app_name="")
	{
		$this->app_name = $app_name;
	}
	
	/**
	  * Allows you to get the current app name
	  *
	  * @return string app name
	  */  
	public function getAppName()
	{
		return $this->app_name;
	}
	
	/**
	  * Allows you to set/change the username after instantiation
	  *
	  * @param string $username
	  * @return void
	  */  
	public function setUsername($username="")
	{
	    $this->username = $username;
	}
	
	/**
	  * Allows you to get the current username
	  *
	  * @return string username
	  */  
	public function getUsername()
	{
		return $this->username;
	}
	
	/**
	  * Allows you to set/change the password after instantiation
	  *
	  * @param string $password
	  * @return void
	  */  
	public function setPassword($password="")
	{
	    $this->password = $password;
	}
	
	/**
	  * Allows you to get the current password
	  *
	  * @access protected
	  * @return string password
	  */  
	protected function getPassword()
	{
		return $this->password;
	}
	
	/**
	  * Allows you to set the file content type for the upload headers
	  *
	  * @param string $file_content_type
	  * @return void
	  */
	public function setFileContentType($file_content_type="")
	{
		$this->file_content_type = $file_content_type;
	}
	
	/**
	  * Allows you to get the current file content type previous set
	  *
	  * @return string content type
	  */  
	public function getFileContentType()
	{
		return $this->file_content_type;
	}
	
	// --------------------------------------------------------------------
	//		BRAINS OF THE OPERATION
	// --------------------------------------------------------------------
	
	/**
	  * Validate function arguments and makes sure we've got basic necessities set
	  *
	  * @access private
	  * @return boolean, success/fail
	  */  
	private function function_check()
	{
		if(empty($this->basecamp_url))
		{
			$this->errors[] = 'Basecamp URL is not set.';
			$this->logit('Basecamp URL is not set.', 'error');
			return false;
		}
		
		if(empty($this->app_name))
		{
			$this->errors[] = 'Application name is not set.';
			$this->logit('Application name is not set.', 'error');
			return false;
		}
		
		if(empty($this->account_id))
		{
			$this->errors[] = 'Account ID is not set.';
			$this->logit('Account ID is not set.', 'error');
			return false;
		}
		
		if(empty($this->username))
		{
			$this->errors[] = 'Username is not set.';
			$this->logit('Username is not set.', 'error');
			return false;
		}
		
		if(empty($this->password))
		{
			$this->errors[] = 'Password is not set.';
			$this->logit('Password is not set.', 'error');
			return false;
		}
		
		return true;
	}
	
	/**
	  * Validates datetime for ISO 8601
	  *
	  * @param string $datetime
	  * @access private
	  * @return boolean, success/fail
	  */  
	private function validateDateTime($datetime='')
	{
		return preg_match('/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/', $datetime);
	}
	
	/**
	  * Validates and removes unwanted data.
	  *
	  * @param array $keys
	  * @param array $data
	  * @access private
	  * @return boolean, success/fail
	  */  
	private function validateData($keys=array(), $data=array())
	{
		if(empty($keys))
		{
			return false;
		}
		
		if(empty($data))
		{
			return false;
		}
		
		// check for required data
		foreach($keys as $index => $key)
		{
			if(preg_match('/\*/', $key))
			{
				$keys[$index] = $key = str_replace('*', '', $key);
				if( ! isset($data[$key]))
				{
					$this->errors[] = ucfirst($key)." is required.";
					return false;
				}
			}
		}
		
		// check for either/or required data
		if(preg_match_all('/[\|](\w+)/', join(' ', $keys), $matches))
		{
			$matches[0] = $matches[1];
			
			// loop through matches
			for($i=0;$i<count($matches[0]);$i++)
			{
				// if has value, unset it
				if(!empty($data[$matches[0][$i]]) || $data[$matches[0][$i]] === false || $data[$matches[0][$i]] === 0)
				{
					unset($matches[0][$i]);
				}
			}
			
			// if neither have value, show error
			if(count($matches[0]) >= count($matches[1]))
			{
				$this->errors[] = "One of the following is required: ". join(', ',$matches[1]) . ".";
				return false;
			}
		}
		
		// remove unwanted data
		foreach($data as $key => $value)
		{
			if( ! in_array($key, $keys))
			{
				unset($data[$key]);
			}
		}
		
		return true;
	}
	
	/**
	  * Logs message if debug is enables
	  *
	  * @param string $message
	  * @param string $level
	  * @access private
	  * @return void
	  */  
	private function logit($message="", $level="debug")
	{
		if(empty($message))
		{
			return;
		}
		
		// If debug message and debug is turned off
		if($debug == false && $level == 'debug')
		{
			return;
		}
		
		// PHP Console error
		if(class_exists('Console'))
		{
			Console::log($message);
		}
		
		// Codeigniter error
		if(function_exists('log_message'))
		{
			log_message($level, $message);
		}
		
		// PHP error log
		error_log('[BasecampAPI] '.ucfirst($level).': '.$message, 0);
	}
		
	/**
	  * Process the RESTful Request
	  *
	  * @param string $url
	  * @param string $type
	  * @access private
	  * @return 
	  */  
	private function processRequest($url="", $type="", $data=array(), $url_params=array())
	{
	    // Set Request Body
	    $this->setRequestBody($data);
	    
	    // Set URL
	    $this->buildURL($url, $url_params);
	    
	    // Execute Request
	    if($this->execute($type) === FALSE)
	    {
		    return false;
	    }
	    
	    // Build return
	    $response = array(
	    	'status'	=> $this->getResponseStatus(),
	    	'headers'	=> $this->getResponseHeaders(),
	    	'location'	=> $this->getResponseLocation(),
	    	'body'		=> $this->getReponseBody()
	    );
	    	    
	    // Flush data to reuse
	    $this->flush();
	    
	    switch($response['status'])
	    {
		    case '200 OK':
		    	return $response['body'];
		    	
		    case '201 Created':
		    	return $response['body'];
		    	
		    case '204 No Content':
		    	return true;
		    	
		    case '400 Bad Request':
		    	$this->errors[] = "You have made a bad request";
		    	return false;
		    	
		    case '403 Forbidden':
		    	$this->errors[] = "Your access is restricted";
		    	return false;
		    
		    case '404 Not Found':
		    	$this->errors[] = "Request not found.";
		    	return false;
		    	
		    default:
		    	return false;
	    }
	}
	
	/**
	  * Build's the URL to be sent to the request
	  *
	  * @param string $url
	  * @param array $params
	  * @access private
	  * @return void
	  */  
	private function buildURL($url="", $params=array())
	{
		$this->url = $this->basecamp_url . $this->account_id . '/api/v' . $this->api_version .
					 '/' . $url . '.' . array_pop(explode('/', $this->content_type)) .
					 (! empty($params) ? '?'.http_build_query($params) : '');
		var_dump($this->url);
	}
	
	// --------------------------------------------------------------------
	//		REST REQUESTS
	// --------------------------------------------------------------------
	
	/**
	  * Set the REST request body
	  *
	  * @param array $data
	  * @access protected
	  * @return void
	  */  
	protected function setRequestBody($data=array())
	{
		$this->request['body'] = json_encode($data);
	}
	
	/**
	  * Retrieves the REST response headers
	  *
	  * @return string the reponse headers
	  */  
	public function getResponseHeaders()
	{
		return substr($this->response['body'], 0, $this->response['info']['header_size']);
	}
	
	/**
	  * Retrieves the REST response body
	  *
	  * @return string The REST response body
	  */  
	public function getReponseBody()
	{
		return json_decode(substr($this->response['body'], $this->response['info']['header_size']));
	}
	
	/**
	  * Retrieves the REST response status
	  *
	  * @return string The REST response status
	  */  
	public function getResponseStatus()
	{
		if(preg_match('!^Status: (.*)$!m', $this->getResponseHeaders(), $match))
		{
			return trim($match[1]);
		}
		else
		{
			return null;
		}
	}
	
	/**
	  * Retrieves the REST response location
	  *
	  * @return string The REST response location
	  */  
	public function getResponseLocation()
	{
		if(preg_match('!^Location: (.*)$!m', $this->getResponseHeaders(), $match))
		{
			return trim($match[1]);
		}
		else
		{
			return null;
		}
	}
		
	/**
	  * Executes curl command
	  *
	  * @param string $verb
	  * @access protected
	  * @return boolean success/fail
	  */  
	protected function execute($verb="")
	{
		$ch = curl_init();
		$this->setAuth($ch);
		
		if(empty($this->url))
		{
			$this->logit('URL has not been set.', 'error');
			return false;
		}
		
		switch($verb)
		{
			case 'GET':
				$this->executeGet($ch);
				break;
			case 'POST':
				$this->executePost($ch);
				break;
			case 'POSTFILE':
				$this->executePostFile($ch);
				break;
			case 'PUT':
				$this->executePut($ch);
				break;
			case 'DELETE':
				$this->executeDelete($ch);
				break;
			default:
				$this->logit('Current verb (' . $verb . ') is an invalid REST verb.', 'error');
				curl_close($ch);
				return false;
		}
	}
	
	/**
	  * Executes a get request
	  *
	  * @param resource identifier $ch
	  * @access protected
	  * @return void
	  */  
	protected function executeGet($ch)
	{
		$this->setRequestHeaders($ch, array(
									'User-Agent' => $this->app_name . ' ('.$this->username.')',
	    							'Accept' => $this->content_type,
	    							'Content-Type' => $this->content_type
								));
		$this->doExecute($ch);
	}
	
	/**
	  * Executes post request
	  *
	  * @param resource identifier $ch
	  * @access protected
	  * @return void
	  */  
	protected function executePost($ch)
	{
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->request['body']);
		curl_setopt($ch, CURLOPT_POST, 1);
		$this->setRequestHeaders($ch, array(
									'User-Agent' => $this->app_name . ' ('.$this->username.')',
	    							'Accept' => $this->content_type,
	    							'Content-Type' => $this->content_type
								));
		
		$this->doExecute($ch);
	}
	
	/**
	  * Executes post file request
	  *
	  * @param resource identifier $ch
	  * @access protected
	  * @return void
	  */  
	protected function executePostFile($ch)
	{
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->request['body']);
		curl_setopt($ch, CURLOPT_POST, 1);
		$this->setRequestHeaders($ch, array(
									'User-Agent' => $this->app_name . ' ('.$this->username.')',
	    							'Accept' => $this->content_type,
	    							'Content-Type' => $this->file_content_type
								));
		
		$this->doExecute($ch);
	}
	
	/**
	  * Executes put request
	  *
	  * @param resource identifier $ch
	  * @access protected
	  * @return void
	  */  
	protected function executePut($ch)
	{
		$this->request['length'] = strlen($this->request['body']);
		
		$fh = fopen('php://memory', 'rw');
		fwrite($fh, $this->request['body']);
		rewind($fh);
		
		curl_setopt($ch, CURLOPT_INFILE, $fh);
		curl_setopt($ch, CURLOPT_INFILESIZE, $this->request['length']);
		curl_setopt($ch, CURLOPT_PUT, true);
		$this->setRequestHeaders($ch, array(
									'User-Agent' => $this->app_name . ' ('.$this->username.')',
	    							'Accept' => $this->content_type,
	    							'Content-Type' => $this->content_type
								));
		
		$this->doExecute($ch);
		
		fclose($fh);
	}
	
	/**
	  * Execuses delete request
	  *
	  * @param resource identifier $ch
	  * @access protected
	  * @return void
	  */  
	protected function executeDelete($ch)
	{
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
	    $this->setRequestHeaders($ch, array(
	    							'User-Agent' => $this->app_name . ' ('.$this->username.')',
	    							'Accept' => $this->content_type,
	    							'Content-Type' => $this->content_type
	    						));
	    
	    $this->doExecute($ch);
	}
	
	/**
	  * Execute the request and get response/info
	  *
	  * @param resource identifier $ch
	  * @access protected
	  * @return void
	  */  
	protected function doExecute(&$ch)
	{
		$this->setCurlOpts($ch);
		$this->response['body'] = curl_exec($ch);
		$this->response['info'] = curl_getinfo($ch);
		
		curl_close($ch);
	}
	
	/**
	  * Set the Curl options
	  *
	  * @param resource identifier $ch
	  * @access protected
	  * @return void
	  */  
	protected function setCurlOpts(&$ch)
	{
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, !preg_match("!^https!i", $this->url));
	}
	
	/**
	  * Set the request headers
	  *
	  * @param resource identifier $ch
	  * @access protected
	  * @return void
	  */  
	protected function setRequestHeaders($ch, $data=array())
	{
		foreach($data as $key => $value) $headers[] = $key . ': ' . $value;
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	}
	
	/**
	  * Set the request authentication
	  *
	  * @param resource identifier $ch
	  * @access protected
	  * @return void
	  */  
	protected function setAuth(&$ch)
	{
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
	}
	
	/**
	  * Cleans response for reuse
	  *	
	  * @access protected
	  * @return void
	  */  
	protected function flush()
	{
		$this->url		= null;
		$this->request	= null;
		$this->response	= null;
	}
	
}

/* End of file Basecamp.php */