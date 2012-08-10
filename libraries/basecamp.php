<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class BasecampAPI {
	
	/* 
	 * Basecamp primary account ID
	 *
	 * @private string
	 */
	private $account_id = "1916473";
	
	/*
	 * Basecamp username
	 *
	 * @private string
	 */
	private $username = "jenkinsac@health.missouri.edu";
	
	/*
	 * Basecamp password
	 *
	 * @private string
	 */
	private $password = "d3st1Ny.I7";
	
	/*
	 * Application name using this library
	 *
	 * @private string
	 */
	private $app_name = "";
	
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
	
	public function __construct()
	{
		
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
		
	}
	
	/**
	  * Retrieves token for attachment upload
	  * POST /attachments.json		-> Uploads a file
	  * 
	  *	$DATA:
	  *		[]
	  *
	  * @return string token to save locally to upload file
	  */  
	public function createAttachment()
	{
		
	}
	
	/**
	  * Creates new entry in the files section
	  * POST /projects/$PROJECT_ID/uploads.json		-> Uploads file (requires token)
	  *
	  * Attaching files requires both the token and the name of the attachement. Token returned from @createAttachment()
	  * Subscribers (optional) is a list of $PERSON_IDs that will be notified of file
	  *
	  * $DATA:
	  * 	[]
	  *
	  * @param int $project_id, array $data
	  * @return boolean success/fails
	  */  
	public function createUpload($project_id=null, $data=array())
	{
		
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
		
	}
	
	/**
	  * Get single calendar event in a calendar
	  * GET /calendars/$PROJECT_ID/calendar_events/$EVENT_ID.json	-> Returns the specified calendar event
	  *
	  * @param int $calendar_id, int $event_id 
	  * @return object event data
	  */ 
	public function getSingleCalendarEvent($calendar_id=null, $event_id=null)
	{
		
	}
	
	/**
	  * Create a calendar event in a project
	  * POST /projects/$PROJECT_ID/calendar_events.json		-> Creates a new calendar event
	  *
	  * $DATA:
	  *		[summary]		- string Summary of calendar event
	  *		[description]	- string Description of calendar event
	  *		[all_day]		- boolean Is this an all day event?
	  *		[starts_at]		- string Start time of event (Format: 0000-00-00[T00:00:00-00:00])  [] - time not required
	  *		[ends_at]		- string End time of event (Format: 0000-00-00[T00:00:00-00:00])  [] - time not required
	  *
	  * @param int $project_id, array $data
	  * @return object calendar data or false
	  */  
	public function createCalendarEvent($project_id=null, $data=array())
	{
		
	}
	
	/**
	  * Updates a single calendar event in a project
	  * PUT /projects/$PROJECT_ID/calendar_events/$EVENT_ID.json	-> Updates the specific calendar event on a project
	  *
	  * $DATA:
	  *		[summary]		- string Summary of calendar event
	  *		[description]	- string Description of calendar event
	  *		[all_day]		- boolean Is this an all day event?
	  *		[starts_at]		- string Start time of event (Format: 0000-00-00[T00:00:00-00:00])  [] - time not required
	  *		[ends_at]		- string End time of event (Format: 0000-00-00[T00:00:00-00:00])  [] - time not required
	  *
	  * @param int $project_id, int $event_id, array $data 
	  * @return object calendar data or false
	  */  
	public function updateProjectCalendarEvent($project_id=null, $event_id=null, $data=array())
	{
		
	}
	
	/**
	  * Updates a single calendar event in a specified calendar
	  * PUT /calendars/$CALENDAR_ID/calendar_events/$EVENT_ID.json	-> Updates the specific calendar event on a project
	  *
	  * $DATA:
	  *		[summary]		- string Summary of calendar event
	  *		[description]	- string Description of calendar event
	  *		[all_day]		- boolean Is this an all day event?
	  *		[starts_at]		- string Start time of event (Format: 0000-00-00[T00:00:00-00:00])  [] - time not required
	  *		[ends_at]		- string End time of event (Format: 0000-00-00[T00:00:00-00:00])  [] - time not required
	  *
	  * @param int $calendar_id, int $event_id, array $data 
	  * @return object calendar data or false
	  */  
	public function updateCalendarEvent($calendar_id=null, $event_id=null, $data=array())
	{
		
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
		// if $data is_array or is_string
	}
	
	/**
	  * Updates a single calendar
	  * PUT /calendars/$CALENDAR_ID.json		-> Updates the calendar from the params passed
	  *
	  * @param int $calendar_id, string $data
	  * @return object calendar data or false
	  */  
	public function updateCalendar($calendar_id=null, $data="")
	{
		
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
		
	}
	
	// --------------------------------------------------------------------
	//		COMMENTS 
	//			No getter, comments are included in topics directly
	// --------------------------------------------------------------------
	
	/**
	  * Creates a new comment for specified project and topic
	  * POST /projects/$PROJECT_ID/<topic>/$TOPIC_ID/comments.json
	  *
	  * @param int $project_id, string $topic, int $topic_id, array $data
	  * @return object comment data or false
	  */  
	public function createComment($project_id=null, $topic="", $topic_id=null, $data=array())
	{
		// Files can be attached
	}
	
	/**
	  * Deletes a comment specified
	  * DELETE /projects/$PROJECT_ID/comments/$COMMENT_ID.json
	  *
	  * @param int $project_id, int $comment_id
	  * @return boolean success/fail
	  */  
	public function deleteComment()
	{
		
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
		
	}
	
	/**
	  * Creates a new document from the parameters passed
	  * POST /projects/$PROJECT_ID/documents.json		-> Creates new document
	  *
	  * $DATA:
	  *		[title]		- string, The title of the document
	  *		[content]	- string, the content in the document
	  *
	  * @param int $project_id, array $data
	  * @return object document data or false
	  */  
	public function createDocument($project_id=null, $data=array())
	{
		
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
	  * @param string $datetime, int $page
	  * @return array list of global event data
	  */  
	public function getAllEvents($datetime="", $page=1)
	{
		
	}
	
	/**
	  * Retieves 50 project events at a time, with pagination
	  * GET /projects/$PROJECT_ID/events.json?since=0000-00-00T00:00:00-00:00&page=0	-> Returns all project events since datetime
	  *
	  * @param int $project_id, string $datetime, int $page
	  * @return array list of project event data
	  */  
	public function getProjectEvents($project_id=null, $datetime="", $page=1)
	{
		
	}
	
	/**
	  * Retrieves 50 person events at a time, with pagination
	  * GET /people/$PERSON_ID/events.json?since=0000-00-00T00:00:00-00:00$page=0		-> Returns all events by that person
	  *
	  * @param int $person_id, string $datetime, int $page
	  * @return array list of person's event data
	  */  
	public function getPersonsEvents($person_id=null, $datetime="", $page=1)
	{
		
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
		
	}
	
	/**
	  * Retrieves the person logged in (you)
	  * GET /people/me.json
	  *
	  * @return object your data
	  */  
	public function getMe()
	{
		
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
		
	}
	
	/**
	  * Retrieves all archived projects
	  * GET /projects/archived.json
	  *
	  * @return array list of archived project data
	  */  
	public function getArchivedProjects()
	{
		
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
		
	}
	
	
	
	
	
	
	
	
	
	// --------------------------------------------------------------------
	//		CONFIGURATION SETTERS
	// --------------------------------------------------------------------
	
	public function setAccountID($id=null)
	{
		$this->account_id = $id;
	}
	
	public function setUsername($username="")
	{
	    $this->username = $username;
	}
	
	public function setPassword($password="")
	{
	    $this->password = $password;
	}
	
	// --------------------------------------------------------------------
	//		BRAINS OF THE OPERATION
	// --------------------------------------------------------------------
	
	private function processRequest()
	{
	    
	}
}

/* End of file Basecamp.php */