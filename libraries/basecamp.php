<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
date_default_timezone_set('America/Chicago');
class BasecampAPI {
	
	/* 
	 * Use debugging?
	 *
	 * @private boolean
	 */
	private $debug = true;
	
	/* 
	 * Errors
	 *
	 * @private array
	 */
	private $errors = array();
	
	/* 
	 * Basecamp primary account ID
	 *
	 * @private string
	 */
	private $account_id;
	
	/*
	 * Basecamp username
	 *
	 * @private string
	 */
	private $username;
	
	/*
	 * Basecamp password
	 *
	 * @private string
	 */
	private $password;
	
	/*
	 * Application name using this library
	 *
	 * @private string
	 */
	private $app_name;
	
	/*
	 * Content type, Basecamp requires JSON
	 *
	 * @private string
	 */
	private $content_type = "application/json";
	
	/*
	 * Basecamp URL
	 *
	 * @private string
	 */
	private $basecamp_url = "https://basecamp.com/";
	
	/*
	 * Basecamp API Version
	 *
	 * @private int
	 */
	private $api_version = 1;
	
	/*
	 * Stored RESTful request
	 *
	 * @private array
	 */
	private $request = array();
	
	/*
	 * Stored RESTful Response
	 *
	 * @private array
	 */
	private $response = array();
	
	/*
	 * The full built URL
	 *
	 * @private string
	 */
	private $url = "";
		
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
	  * GET /projects/$PROJECT_ID/accesses.json			-> Returns all persons with access to the project
	  *
	  * @param int $project_id
	  * @return array list of persons
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
	  * GET /calendars/$CALENDAR_ID/accesses.json		-> Returns all persons with access to the calendar
	  *
	  * @param int $calendar_id
	  * @return array list of persons
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
	  * POST /projects/$PROJECT_ID/accesses.json		-> Grants access to the project
	  *
	  * e.g. grantAccessToProject( array( "ids" => array( 5, 6, 10 ), "email_addresses" => array( "someone@example.com", "bob@example.com" ) ) )
	  *
	  * $DATA:
	  * 	[ids]				- array, Grant to existing users
	  *		[email_addresses]	- array, Grant access to new users (invitation)
	  *
	  * @param int $project_id, array $data -OR- string $emailaddress -OR- int $person_id
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
	  * POST /calendar/$CALENDAR_ID/accesses.json		-> Grants access to the calendar
	  *
	  * e.g. grantAccessToCalendar( array( "ids" => array( 5, 6, 10 ), "email_addresses" => array( "someone@example.com", "bob@example.com" ) ) )
	  *
	  *	$DATA:
	  * 	[ids]				- array, Grant to existing users
	  *		[email_addresses]	- array, Grant access to new users (invitation)
	  *
	  * @param int $calendar_id, array $data -OR- string $emailaddress -OR- int $person_id
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
	  * DELETE /projects/$PROJECT_ID/accesses/$PERSON_ID.json		-> Revoke access of the person to the project
	  *
	  * @param int $project_id, int $person_id
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
	  * DELETE /calendars/$CALENDAR_ID/accesses/$PERSON_ID.json		-> Revoke access of the person to the calendar
	  *
	  * @param int $calendar_id, int $person_id
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
	  * GET /projects/$PROJECT_ID/attachments.json		-> Show attachments for the project
	  *
	  * @param int $project_id
	  * @return array metadata, urls and associated attachables
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
	  * POST /attachments.json		-> Uploads a file
	  * 
	  *	$DATA:
	  *		[content_type]
	  *
	  * @return string token to save locally to upload file
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
	  * POST /projects/$PROJECT_ID/uploads.json		-> Uploads file (requires token)
	  *
	  * Attaching files requires both the token and the name of the attachement. Token returned from @createAttachment()
	  * Subscribers (optional) is a list of $PERSON_IDs that will be notified of file
	  *
	  * $DATA:
	  * 	[content]
	  *		[subscribers]
	  *		[attachments]
	  *
	  * @param int $project_id, array $data
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
	  * GET /projects/$PROJECT_ID/uploads/$UPLOAD_ID,json		-> Returns content, comments and attachments for this upload.
	  *
	  * @param int $project_id, int $upload_id 
	  * @return object file data
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
	  * GET /projects/$PROJECT_ID/calendar_events.json			-> Returns upcoming calendar events for the project
	  *
	  * @param int $project_id 
	  * @return array list of calendar events
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
	  * GET /calendars/$CALENDAR_ID/calendar_events.json		-> Returns upcoming calendar events for the calendar
	  *
	  * @param int $calendar_id 
	  * @return array list of calendar events
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
	  * GET /projects/$PROJECT_ID/calendar_events/past.json		-> Returns past calendar events for the project
	  *
	  * @param int $project_id 
	  * @return array list of calendar events
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
	  * GET /calendars/$CALENDAR_ID/calendar_events/past.json	-> Returns past calendar events for the calendar
	  *
	  * @param int $calendar_id 
	  * @return array list of calendar events
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
	  * GET /projects/$PROJECT_ID/calendar_events/$EVENT_ID.json	-> Returns the specified calendar event
	  *
	  * @param int $project_id, int $event_id 
	  * @return object event data
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
	  * GET /calendars/$CALENDAR_ID/calendar_events/$EVENT_ID.json	-> Returns the specified calendar event
	  *
	  * @param int $calendar_id, int $event_id 
	  * @return object event data
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
	  * POST /projects/$PROJECT_ID/calendar_events.json		-> Creates a new calendar event
	  *
	  * $DATA:
	  *		[summary]		- string Summary of calendar event
	  *		[description]	- string Description of calendar event
	  *		[all_day]		- boolean Is this an all day event?
	  *		[starts_at]		- string Start time of event (ISO 8601 format - time not required)
	  *		[ends_at]		- string End time of event (ISO 8601 format - time not required)
	  *
	  * @param int $project_id, array $data
	  * @return object calendar data or false
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
	  * POST /calendars/$CALENDAR_ID/calendar_events.json		-> Creates a new calendar event
	  *
	  * $DATA:
	  *		[summary]		- string Summary of calendar event
	  *		[description]	- string Description of calendar event
	  *		[all_day]		- boolean Is this an all day event?
	  *		[starts_at]		- string Start time of event (ISO 8601 format - time not required)
	  *		[ends_at]		- string End time of event (ISO 8601 format - time not required)
	  *
	  * @param int $project_id, array $data
	  * @return object calendar data or false
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
	  * PUT /projects/$PROJECT_ID/calendar_events/$EVENT_ID.json	-> Updates the specific calendar event on a project
	  *
	  * $DATA:
	  *		[summary]		- string Summary of calendar event
	  *		[description]	- string Description of calendar event
	  *		[all_day]		- boolean Is this an all day event?
	  *		[starts_at]		- string Start time of event (ISO 8601 format - time not required)
	  *		[ends_at]		- string End time of event (ISO 8601 format - time not required)
	  *
	  * @param int $project_id, int $event_id, array $data 
	  * @return object calendar data or false
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
	  * PUT /calendars/$CALENDAR_ID/calendar_events/$EVENT_ID.json	-> Updates the specific calendar event on a project
	  *
	  * $DATA:
	  *		[summary]		- string Summary of calendar event
	  *		[description]	- string Description of calendar event
	  *		[all_day]		- boolean Is this an all day event?
	  *		[starts_at]		- string Start time of event (ISO 8601 format - time not required)
	  *		[ends_at]		- string End time of event (ISO 8601 format - time not required)
	  *
	  * @param int $calendar_id, int $event_id, array $data 
	  * @return object calendar data or false
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
	  * DELETE /projects/$PROJECT_ID/calendar_events/$EVENT_ID.json 	-> Deletes calendar event specified
	  *
	  * @param int $project_id, int $event_id
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
	  * DELETE /calendars/$CALENDAR_ID/calendar_events/$EVENT_ID.json 	-> Deletes calendar event specified
	  *
	  * @param int $calendar_id, int $event_id
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
	  * GET /calendars.json			-> Returns all calendars sorted alphabetically
	  *	
	  * @return array list of calendars
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
	  * GET /calendars/$CALENDAR_ID.json	-> Returns the specified calendar
	  *
	  * @param int $calendar_id
	  * @return object calendar data
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
	  * POST /calendars.json		-> Creates a new calendar from the params passed
	  *
	  * $DATA:
	  *		[name]	- string, The name of the calendar
	  *
	  * @param array $data -OR- string $name
	  * @return object calendar data or false
	  */  
	public function createCalendar($data=null)
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
	  * PUT /calendars/$CALENDAR_ID.json		-> Updates the calendar from the params passed
	  *
	  * $DATA:
	  *		[name]	- string, The name of the calendar
	  *
	  * @param int $calendar_id, string $data
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
	  * DELETE /calendars/$CALENDAR_ID.json		-> Deletes the calendar specified
	  *
	  * @param int $calendar_id
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
	  * POST /projects/$PROJECT_ID/<topic>/$TOPIC_ID/comments.json
	  *
	  * $DATA:
	  *		[content]		- (required) The comment content
	  *		[subscribers]	- (optional) List of people IDs that you want to notify
	  *		[attachments]	- (optional) Attaching a file to the comment
	  *
	  * @param int $project_id, string $topic, int $topic_id, array $data
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
	  * DELETE /projects/$PROJECT_ID/comments/$COMMENT_ID.json
	  *
	  * @param int $project_id, int $comment_id
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
	  * GET /projects/$PROJECT_ID/document.json		-> Returns all the documents on the project
	  *
	  * @param int $project_id
	  * @return object document data
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
	  * GET /projects/$PROJECT_ID/documents/$DOCUMENT_ID.json		-> Returns specified document with comments
	  *
	  * @param int $project_id, int $document_id
	  * @return object document data 
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
	  * POST /projects/$PROJECT_ID/documents.json		-> Creates new document
	  *
	  * $DATA:
	  *		[title]		- (required) string, The title of the document
	  *		[content]	- (required) string, the content in the document
	  *
	  * @param int $project_id, array $data
	  * @return object document data or false
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
	  * PUT /projects/$PROJECT_ID/documents/$DOCUMENT_ID.json		-> Updates the message
	  *
	  *	$DATA:
	  *		[title]		- (required) string, The title of the document
	  *		[content]	- (required) string, the content in the document
	  *
	  * @param int $project_id, int $document_id, array $data
	  * @return object document data
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
	  * DELETE /projects/$PROJECT_ID/documents/$DOCUMENT_ID.json	-> Deletes the document specified
	  *
	  * @param int $project_id, int $document_id
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
	  * GET /events.json?since=0000-00-00T00:00:00-00:00&page=0		-> Returns all events on account since datetime
	  *
	  * Returns 50 events per page. If the result has 50 entries, check next page.
	  *
	  * Datetime: ISO 8601 format
	  *
	  * @param string $datetime, int $page
	  * @return array list of global event data
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
	  * GET /projects/$PROJECT_ID/events.json?since=0000-00-00T00:00:00-00:00&page=0	-> Returns all project events since datetime
	  *
	  * Datetime: ISO 8601 format
	  *
	  * @param int $project_id, string $datetime, int $page
	  * @return array list of project event data
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
	  * GET /people/$PERSON_ID/events.json?since=0000-00-00T00:00:00-00:00$page=0		-> Returns all events by that person
	  * 
	  * Datetime: ISO 8601 format
	  *
	  * @param int $person_id, string $datetime, int $page
	  * @return array list of person's event data
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
	  * GET /projects/$PROJECT_ID/messages/$MESSAGE_ID.json
	  *
	  * @param int $project_id, int $message_id
	  * @return object message data
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
	  * POST /projects/$PROJECT_ID/messages.json		-> Creates a new message
	  *
	  *	$DATA:
	  *		[subject]		- string, The subject of the message/discussion
	  *		[content]		- string, The content of the message/discussion
	  *		[subscribers]	- array, The people IDs that should be notified of message/discussion
	  *		[attachments]	- array, The files that need to be attached, tokens will be added
	  *
	  * @param int $project_id, array $data
	  * @return object message data or false
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
	  * PUT /projects/$PROJECT_ID/messages/$MESSAGE_ID.json		-> Updates the message
	  *
	  *	$DATA:
	  *		[subject]		- string, The subject of the message/discussion
	  *		[content]		- string, The content of the message/discussion
	  *		[subscribers]	- array, The people IDs that should be notified of message/discussion
	  *		[attachments]	- array, The files that need to be attached, tokens will be added
	  *
	  * @param int $project_id, int $message_id, array $data
	  * @return object message data or false
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
	  * DELETE /projects/$PROJECT_ID/messages/$MESSAGE_ID.json		-> Deletes the message
	  *
	  * @param int $project_id, int $message_id
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
	  * GET /people.json		-> Retrives people
	  *
	  * @return array list of people
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
	  * GET /people/$PERSON_ID.json		-> Returns specified person
	  *
	  * @param int $person_id
	  * @return object person data
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
	  * GET /people/me.json
	  *
	  * @return object your data
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
	  * DELETE /people/$PERSON_ID.json
	  *
	  * @param int $person_id
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
	  * GET /projects.json
	  *
	  * @return array list of active project data
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
	  * GET /projects/archived.json
	  *
	  * @return array list of archived project data
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
	  * GET /projects/$PROJECT_ID.json	-> Returns specified project
	  *
	  * @param int $project_id
	  * @return object project data
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
	  * POST /projects.json
	  *
	  * $DATA:
	  *		[name]			- string, The name of the project
	  *		[description]	- string, A description of the project
	  *
	  * @param array $data
	  * @return object project data or false
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
	  * PUT /projects/$PROJECT_ID.json	-> Updates the project
	  *
	  * $DATA:
	  *		[name]			- string, The name of the project
	  *		[description]	- string, A description of the project
	  *
	  * @param int $project_id, array $data
	  * @return object project data
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
	  * PUT /projects/$PROJECT_ID.json
	  *
	  * @param int $project_id
	  * @return object project data
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
	  *	PUT /projects/$PROJECT_ID.json
	  *
	  * @param int $project_id
	  * @return object project data
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
	  * DELETE /project/$PROJECT_ID.json
	  *
	  * @param int $project_id
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
	//		TODO LISTS
	// --------------------------------------------------------------------
	
	/**
	  * Retrieves the todo lists of a specified project
	  * GET /projects/$PROJECT_ID/todolists.json	-> Returns all todolists with remaining todos sorted by position
	  *
	  * @param int $project_id
	  * @return array list of todolist data
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
	  * GET /projects/$PROJECT_ID/todolists/completed.json	-> Returns all completed todolists
	  *
	  * @param int $project_id
	  * @return array completed todolist data
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
	  * See method getProjectsCompletedToDoLists()
	  *
	  * @param int $project_id
	  * @return array completed todolist data
	  */  
	public function getCompletedTDL($project_id=null)
	{
		return $this->getProjectsCompletedToDoLists($project_id);
	}
	
	/**
	  * Retrieves assigned todolists of the specified person
	  * GET /people/$PERSON_ID/assigned_todos.json
	  *
	  * @param int $person_id
	  * @return array assigned todolist data
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
	  * See method getPersonsAssignedToDoList()
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
	  * GET /projects/$PROJECT_ID/todolists/$TODOLIST_ID.json
	  *
	  * @param int $project_id, int $todolist_id
	  * @return object todolist data
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
	  * POST /projects/$PROJECT_ID/todolists.json
	  *
	  * $DATA:
	  *		[name]			- string, The name of the todolist
	  *		[description]	- string, A description of the todolist
	  *
	  * @param int $project_id, array $data
	  * @return object todolist data
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
	  * See method createProjectToDoList
	  *
	  * @param int $project_id, array $data
	  * @return object todolist data
	  */  
	public function createToDoList($project_id=null, $data=array())
	{
		return $this->createProjectToDolist($project_id, $data);
	}
	
	/**
	  * Update a ToDoList in a specified project
	  * PUT /projects/$PROJECT_ID/todolists/$TODOLIST_ID.json
	  *
	  * $DATA:
	  *		[name]			- string, The name of the todolist
	  *		[description]	- string, A description of the todolist
	  *		[position]		- int, Reorder the position of the todolist
	  *
	  * @param int $project_id, int $todolist_id, array $data
	  * @return object todolist data
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
	  * See method updateProjectToDoList()
	  *
	  * @param int $project_id, int $todolist_id, array $data
	  * @return object todolist data
	  */  
	public function updateToDoList($project_id=null, $todolist_id=null, $data=array())
	{
		return $this->updateProjectToDoList($project_id, $todolist_id, $data);
	}
	
	/**
	  * Deletes a specified project ToDo List
	  * DELETE /projects/$PROJECT_ID/todolists/$TODOLIST_ID.json
	  *
	  * @param int $project_id, int $todolist_id
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
	//		TODOS
	// --------------------------------------------------------------------
	
	/**
	  * Retrieves a single todo from a project 
	  * GET /projects/$PROJECT_ID/todos/$TODO_ID.json
	  *
	  * @param int $project_id, int $todo_id
	  * @return object todo data
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
	  * POST /projects/$PROJECT_ID/todolists/$TODOLIST_ID/todos.json
	  *
	  * $DATA:
	  *		[content]	- string, The content of the todo
	  *		[due_at]	- string, The due date of the todo (ISO 8601 format)
	  *		[assignee]	- array, The assignee if necessary
	  *			[id]		- int, The id of the assignee Person or Group
	  *			[type]		- string, The type of the assignee Person or Group
	  *
	  * @param int $project_id, int $todolist_id, array $data
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
	  * PUT /projects/$PROJECT_ID/todos/$TODO_ID.json
	  *
	  * $DATA:
	  *		[content]	- string, The content of the todo
	  *		[due_at]	- string, The due date of the todo (ISO 8601 format)
	  *		[assignee]	- array, The assignee if necessary
	  *			[id]		- int, The id of the assignee Person or Group
	  *			[type]		- string, The type of the assignee Person or Group
	  *		[position]	- int, Reorder the position of the todo
	  *
	  * @param int $project_id, int $todo_id, array $data
	  * @return object todo data or false
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
	  * DELETE /projects/$PROJECT_ID/todos/$TODO_ID.json
	  *
	  * @param int $project_id, int $todo_id
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
	  * GET /projects/$PROJECT_ID/topics.json
	  *
	  * @param int $project_id
	  * @return array list of topic data
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
	  * @param string $prefix, string $suffix
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
	  * @param string $method, array $args
	  * @access private
	  * @return boolean, success/fail
	  */  
	private function function_check($method='', $args=array())
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
	  * @param array $keys, array $data
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
	  * @param string $message, string $level
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
	  * @param string $url, string $type
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
	  * @param string $url, array $params
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
	  * Set the request body
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
	  * Get the response headers
	  *
	  * @return 
	  */  
	public function getResponseHeaders()
	{
		return substr($this->response['body'], 0, $this->response['info']['header_size']);
	}
	
	/**
	  * 
	  *
	  * @param 
	  * @return 
	  */  
	public function getReponseBody()
	{
		return json_decode(substr($this->response['body'], $this->response['info']['header_size']));
	}
	
	/**
	  * 
	  *
	  * @param 
	  * @return 
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
	  * 
	  *
	  * @param 
	  * @return 
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
	  * @return false if error
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