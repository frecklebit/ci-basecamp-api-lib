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
	  * 	[ids]				- grant to existing users
	  *		[email_addresses]	- grant access to new users (invitation)
	  *
	  * @param int $project_id, array $persons 
	  * @return boolean success/fail
	  */  
	public function grantAccessToProject($project_id=null, $persons=array())
	{
		
	}
	
	/**
	  * Grant access to a specific calendar
	  * POST /calendar/$CALENDAR_ID/accesses.json		-> Grants access to the calendar
	  *
	  * e.g. grantAccessToCalendar( array( "ids" => array( 5, 6, 10 ), "email_addresses" => array( "someone@example.com", "bob@example.com" ) ) )
	  *
	  * 	[ids]				- grant to existing users
	  *		[email_addresses]	- grant access to new users (invitation)
	  *
	  * @param int $calendar_id, array $persons 
	  * @return boolean success/fail
	  */  
	public function grantAccessToCalendar($calendar_id=null, $persons=array())
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
	  * @param int $project_id, string $content, string $filename, array $subscribers
	  * @return boolean success/fails
	  */  
	public function createUpload($project_id=null, $content="", $file="", $subscribers=array())
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
	  * Retrieve all calendar events
	  * GET /projects/$PROJECT_ID/calendar_events.json			-> Returns upcoming calendar events for the project
	  * GET /calendars/$CALENDAR_ID/calendar_events.json		-> Returns upcoming calendar events for the calendar
	  * GET /projects/$PROJECT_ID/calendar_events/past.json		-> Returns past calendar events for the project
	  * GET /calendars/$CALENDAR_ID/calendar_events/past.json	-> Returns past calendar events for the calendar
	  *
	  * @param string $what, int $id 
	  * @return array list of calendar events
	  */  
	public function getCalendarEvents($what="", $id=null)
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
	  * 
	  *
	  * @param  
	  * @return  
	  */  
	public function getComments()
	{
		
	}
	
	public function getDocuments()
	{
		
	}
	
	public function getEvents()
	{
		
	}
	
	public function getPeople()
	{
		
	}
	
	public function getProjects()
	{
		
	}
	
	public function getToDoLists()
	{
		
	}
	
	public function getToDos()
	{
		
	}
	
	public function getTopics()
	{
		
	}
	    
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
	
	private function processRequest()
	{
	    
	}
}

/* End of file Basecamp.php */