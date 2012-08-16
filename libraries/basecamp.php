<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class BasecampAPI {
	
	/* 
	 * Use debugging?
	 *
	 * @private boolean
	 */
	private $debug = true;
	
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
		
	/*
	 * Codeigniter object, to enable use $basecamp->useCI(true);
	 *
	 * @private object
	 */
	private $CI = false;
	
	public function __construct($app_name="", $account_id=null, $username="", $password="")
	{
		// Is this library being used with Codeigniter?
		$this->integrateCI(function_exists('config_item'));
		
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
		
		return $this->processRequest("calendars/{$calendar_id}/accesses.json", 'GET');
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
		
		$data = $this->validateData(array('ids', 'email_addresses'), $data);
		
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
		
		$data = $this->validateData(array('ids', 'email_addresses'), $data);
		
		return $this->processRequest("calendar/{$calendar_id}/accesses", 'POST', $data);
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
		
		$data = $this->validateData(array('content', 'subscribers', 'attachments'), $data);
		
		// only one file can be uploaded at a time
		if(is_array($data['attachments']))
		{
			$data['attachments'] = array_pop($data['attachments']);
		}
		$file_path = $data['attachments'];
		
		// format the new attachment
		$data['attachments'][0] = array(
			'token'	=> $this->createAttachment($file_path),
			'name'	=> basename($file_path)
		);
		
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
	public function createCalendarEvent($project_id=null, $data=array())
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
		$data = $this->validateData(array('summary', 'description', 'all_day', 'starts_at', 'ends_at'), $data);
		
		return $this->processRequest("projects/{$project_id}/calendar_events", 'POST', $data);
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
		
		$data = $this->validateData(array('summary', 'description', 'all_day', 'starts_at', 'ends_at'), $data);
		
		return $this->processRequest("projects/{$project_id}/calendar_events/{$event_id}", 'PUT');
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
		
		$data = $this->validateData(array('summary', 'description', 'all_day', 'starts_at', 'ends_at'), $data);
		
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
		
		$data = $this->validateData(array('name'), $data);
		
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
		
		$data = $this->validateData(array('name'), $data);
		
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
		
		$data = $this->validateData(array('content', 'subscribers', 'attachments'), $data);
		
		// multiple files can be uploaded
		if(isset($data['attachments']))
		{
			$cleaned_attachments = array();
			foreach($data['attachments'] as $file_path)
			{
				$cleaned_attachments[] = array(
					'token'	=> $this->createAttachment($file_path),
					'name'	=> basename($file_path)
				);
			}
			$data['attachments'] = $cleaned_attachments;
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
		
		return $this->processRequest("projects/{$project_id}/document", 'GET');
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
		
		$data = $this->validateData(array('title', 'content'), $data);
		
		return $this->processRequest("projects/{$project_id}/documents". 'POST', $data);
	}
	
	/**
	  * Updates a document in a specified project
	  * PUT /projects/$PROJECT_ID/documents/$DOCUMENT_ID.json		-> Updates the message
	  *
	  *	$DATA:
	  *		[title]		- string, The title of the document
	  *		[content]	- string, the content in the document
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
		
		$data = $this->validateData(array('title', 'content'), $data);
		
		return $this->processRequest("")
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
		
	}
	
	// --------------------------------------------------------------------
	//		GLOBAL EVENTS
	// --------------------------------------------------------------------
	
	/**
	  * Returns all events between specified dates passed
	  * GET /events.json?since=0000-00-00T00:00:00-00:00&page=0		-> Returns all events on account since datetime
	  *
	  * Returns 50 events per page. If the result has 50 entries, check next page.
	  *
	  * Datetime: ISO 8601 format
	  *
	  * @param string $datetime, int $page
	  * @return array list of global event data
	  */  
	public function getAllEvents($datetime="", $page=1)
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
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
		
		return $this->processRequest('projects', 'GET');
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
	public function getProjectToDoLists()
	{
		if( ! $this->function_check())
		{
			return false;
		}
		
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
		
	}
	
	// --------------------------------------------------------------------
	//		CONFIGURATION SETTERS/GETTERS
	// --------------------------------------------------------------------
	
	public function setAccountID($id=null)
	{
		$this->account_id = $id;
	}
	
	public function getAccountID()
	{
		return $this->account_id;
	}
	
	public function setAppName($app_name="")
	{
		$this->app_name = $app_name;
	}
	
	public function getAppName()
	{
		return $this->app_name;
	}
	
	public function setUsername($username="")
	{
	    $this->username = $username;
	}
	
	protected function getUsername()
	{
		return $this->username;
	}
	
	public function setPassword($password="")
	{
	    $this->password = $password;
	}
	
	public function setFileContentType($file_content_type="")
	{
		$this->file_content_type = $file_content_type;
	}
	
	public function getFileContentType()
	{
		return $this->file_content_type;
	}
	
	protected function getPassword()
	{
		return $this->password;
	}
	
	// --------------------------------------------------------------------
	//		BRAINS OF THE OPERATION
	// --------------------------------------------------------------------
	
	/**
	  * Validate function arguments and makes sure we've got basic necessities set
	  *
	  * @param 
	  * @return 
	  */  
	private function function_check()
	{
		if(empty($this->basecamp_url))
		{
			$this->logit('Basecamp URL is not set.', 'error');
			return false;
		}
		
		if(empty($this->app_name))
		{
			$this->logit('Application name is not set.', 'error');
			return false;
		}
		
		if(empty($this->account_id))
		{
			$this->logit('Account ID is not set.', 'error');
			return false;
		}
		
		if(empty($this->username))
		{
			$this->logit('Username is not set.', 'error');
			return false;
		}
		
		if(empty($this->password))
		{
			$this->logit('Password is not set.', 'error');
			return false;
		}
		
		return true;
	}
	
	/**
	  * Logs message if debug is enables
	  *
	  * @param string $message, string $level
	  * @return void
	  */  
	private function logit($message="", $level="debug")
	{
		if(empty($message))
		{
			return;
		}
		
		if($debug == false && $level == 'debug')
		{
			return;
		}
		
		if(class_exists('Console'))
		{
			Console::log($message);
		}
		
		if($this->CI)
		{
			log_message($level, $message);
		}
		else
		{
			error_log('[BasecampAPI] '.ucfirst($level).': '.$message, 0);
		}
	}
	
	/**
	  * Attach Codeigniter.
	  *
	  * @param boolean $enable
	  * @return void
	  */  
	public function integrateCI($enable=false)
	{
		if($enable == true)
		{
			$this->CI =& get_instance();
			$this->logit('The BasecampAPI library is being used with the Codeigniter framework.');
		}
		else
		{
			$this->logit('The BasecampAPI library is NOT being used with the Codeigniter framework.');
		}
	}
	
	/**
	  * Process the RESTful Request
	  *
	  * @param string $url, string $type
	  * @return 
	  */  
	private function processRequest($url="", $type="", $data=array())
	{
	    // Set Request Body
	    $this->setRequestBody($data);
	    
	    // Set URL
	    $this->buildURL($url);
	    
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
	    
	    return $response;
	}
	
	/**
	  * 
	  *
	  * @param 
	  * @return 
	  */  
	private function buildURL($url="")
	{
		$this->url = $this->basecamp_url.$this->account_id.'/api/v'.$this->api_version.'/'.$url.'.'.array_pop(explode('/', $this->content_type));
	}
	
	// --------------------------------------------------------------------
	//		REST REQUESTS
	// --------------------------------------------------------------------
	
	/**
	  * 
	  *
	  * @param 
	  * @return 
	  */  
	protected function setRequestBody($data=array())
	{
		$this->request['body'] = json_encode($data);
	}
	
	/**
	  * 
	  *
	  * @param 
	  * @return 
	  */  
	protected function getResponseHeaders()
	{
		return substr($this->response['body'], 0, $this->response['info']['header_size']);
	}
	
	/**
	  * 
	  *
	  * @param 
	  * @return 
	  */  
	protected function getReponseBody()
	{
		return json_decode(substr($this->response['body'], $this->response['info']['header_size']));
	}
	
	/**
	  * 
	  *
	  * @param 
	  * @return 
	  */  
	protected function getResponseStatus()
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
	protected function getResponseLocation()
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
	  * @param 
	  * @return 
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
	  * 
	  *
	  * @param 
	  * @return 
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
	  * 
	  *
	  * @param 
	  * @return 
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
	  * 
	  *
	  * @param 
	  * @return 
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
	  * 
	  *
	  * @param 
	  * @return 
	  */  
	protected function executePut($ch)
	{
		$this->request['length'] = strlen($this->request['body']);
		
		$fh = fopen('php://memory', 'rw');
		fwrite($fh, $this->request['body']);
		rewind($fh);
		
		curl_setopt($ch, CURLOPT_INFILE, $fh);
		curl_setopt($ch, CURLOPT_INFILESIZE, 0);
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
	  * 
	  *
	  * @param 
	  * @return 
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
	  * 
	  *
	  * @param 
	  * @return 
	  */  
	protected function doExecute(&$curlHandle)
	{
		$this->setCurlOpts($curlHandle);
		$this->response['body'] = curl_exec($curlHandle);
		$this->response['info'] = curl_getinfo($curlHandle);
		
		curl_close($curlHandle);
	}
	
	/**
	  * 
	  *
	  * @param 
	  * @return 
	  */  
	protected function setCurlOpts(&$curlHandle)
	{
		curl_setopt($curlHandle, CURLOPT_TIMEOUT, 10);
		curl_setopt($curlHandle, CURLOPT_URL, $this->url);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlHandle, CURLOPT_HEADER, true);
		curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, !preg_match("!^https!i",$this->url));
	}
	
	/**
	  * 
	  *
	  * @param 
	  * @return 
	  */  
	protected function setRequestHeaders($ch, $data=array())
	{
		foreach($data as $key => $value) $headers[] = $key . ': ' . $value;
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	}
	
	/**
	  * 
	  *
	  * @param 
	  * @return 
	  */  
	protected function setAuth(&$curlHandle)
	{
		curl_setopt($curlHandle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curlHandle, CURLOPT_USERPWD, $this->username . ':' . $this->password);
	}
	
	/**
	  * 
	  *
	  * @param 
	  * @return 
	  */  
	protected function flush()
	{
		$this->url		= null;
		$this->request	= null;
		$this->response	= null;
	}
	
}

/* End of file Basecamp.php */