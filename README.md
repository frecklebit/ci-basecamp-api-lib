# The New Basecamp API CI Library
===================

This CodeIgniter library connects to the NEW Basecamp API. It does not work with Basecamp Classic. For more information on the Basecamp API, visit the [37signals Github page](https://github.com/37signals/bcx-api).

Accesses
--------

Retrieve all accesses for a specific project

	getAccessesForProject($project_id)

Retrieve all accesses for a specific calendar

	getAccessesForCalendar($calendar_id)
		
Grant access to a specific project

	// accepted data: ids, email_addresses
	grantAccessToProject($project_id, $data)

Grant access to a specific project

	// accepted data: ids, email_addresses
	grantAccessToProject($project_id, $data)

Grant access to a specific calendar

	// accepted data: ids, email_addresses
	grantAccessToCalendar($calendar_id, $data)

Revoke access to projects

	revokeAccessToProject($project_id, $person_id)

Revoke access to calendars

	revokeAccessToCalendar($calendar_id, $person_id)

Attachments/Files
-----------------

Retrieve all attachments

	getAttachments($project_id)

Retrieves token for attachment upload

	createAttachment($file_path)

Creates new entry in the files section

	// accepted data: content, subscribers, attachments
	uploadFile($project_id, $data)

Get content, comments and attachments of a specific upload

	getUpload($project_id, $upload_id)

Calendar Events
---------------

Retrieve all calendar events for a project

	getProjectCalendarEvents($project_id)

Retrieve all calendar events for a calendar

	getCalendarEvents($calendar_id)

Retrieve all past calendar events for a project

	getPastProjectCalendarEvents($project_id)

Retrieve all past calendar events for a calendar

	getPastCalendarEvents($calendar_id)

Retrieve single calendar event in a project

	getSingleProjectCalendarEvent($project_id, $event_id)

Retrieve single calendar event in a calendar

	getSingleCalendarEvent($calendar_id, $event_id)

Create a calendar event in a project

	// accepted data: summary, description, all_day, starts_at, ends_at
	createProjectCalendarEvent($project_id, $data)

Create a calendar event

	// accepted data: summary, description, all_day, starts_at, ends_at
	createCalendarEvent($calendar_id, $data)

Updates a single calendar event in a project

	// accepted data: summary, description, all_day, starts_at, ends_at
	updateProjectCalendarEvent($project_id, $event_id, $data)

Updates a single calendar event in a specified calendar

	// accepted data: summary, description, all_day, starts_at, ends_at
	updateCalendarEvent($calendar_id, $event_id, $data)

Deletes a single calendar event

	deleteProjectCalendarEvent($project_id, $event_id)

Deletes a single calendar event

	deleteCalendarEvent($calendar_id, $event_id)

Calendars
---------

Retrieve all calendars

	getCalendars()

Retrieves single calendar

	getSingleCalendar($calendar_id)

Creates a new calendar or multiple calendars if $data is array

	// accepted data: name
	createCalendar($data)

Updates a single calendar

	// accepted data: name
	updateCalendar($calendar_id, $data)

Deletes a single calendar

	deleteCalendar($calendar_id)

Comments
--------

Creates a new comment for specified project and topic

	// accepted data: content, subscribers, attachments
	createComment($project_id, $topic, $topic_id, $data)

Deletes a comment specified

	deleteComment($project_id, $comment_id)

Documents
---------

Retrieves most recent version of documents

	getDocuments($project_id)

Retrieves recent version of single document

	getSingleDocument($project_id, $document_id)

Creates a new document from the parameters passed

	// accepted data: title, content
	createDocument($project_id, $data)

Updates a document in a specified project

	// accepted data: title, content
	updateDocument($project_id, $document_id, $data)

Deletes a specified document from a project

	deleteDocument($project_id, $document_id)

Events
------

Returns all global events between specified dates passed

	// datetime format: ISO 8601 0000-00-00T00:00:00-00:00
	getGlobalEvents($datetime, $page)

Retieves 50 project events at a time, with pagination

	// datetime format: ISO 8601 0000-00-00T00:00:00-00:00
	getProjectEvents($project_id, $datetime, $page)

Retrieves 50 person events at a time, with pagination

	// datetime format: ISO 8601 0000-00-00T00:00:00-00:00
	getPersonsEvents($person_id, $datetime, $page)

Messages/Discussions
--------------------

Retrieves specified message/discussion from project

	getSingleMessage($project_id, $message_id)

Creates a message/discussion in a project

	// accepted data: subject, content, subscribers, attachments
	createMessage($project_id, $data)

Updates the message/discussion in a project

	// accepted data: subject, content, subscribers, attachments
	updateMessage($project_id, $message_id, $data)

Deletes the specified message/discussion from a project

	deleteMessage($project_id, $message_id)

People
------

Retrieves all the people on the account

	getPeople()

Retrieves a single person

	getPerson($person_id)

Retrieves the person logged in (you)

	getMe()

Deletes a specified person

	deletePerson($person_id)

Projects
--------

Retrieves all active projects

	getProjects()

Retrieves all archived projects

	getArchivedProjects()

Retrieves single project

	getSingleProject($project_id)

Creates a new project

	// accepted data: name, description
	createProject($data)

Updates a single project

	// accepted data: name, description
	updateProject($project_id, $data)

Activates an archived project

	activateProject($project_id)

Archives an active project

	archiveProject($project_id)

Deletes a specified project

	deleteProject($project_id)

ToDo Lists
----------

Retrieves the todo lists of a specified project

	getProjectToDoLists($project_id)

Retrieves completed todolists

	getProjectsCompletedToDoLists($project_id)

Shorthand method to retrieve completed todolists

	getCompletedTDL($project_id)

Retrieves assigned todolists of the specified person

	getPersonsAssignedToDoLists($person_id)

Shorthand method to retrieve person's assigned todo lists

	getAssignedTDL($person_id)

Retrieves a single ToDo List in specified project

	getSingleToDoList($project_id, $todolist_id)

Creates a new ToDo List in a specified project

	// accepted data: name, description
	createProjectToDoList($project_id, $data)

Shorthand method to create a project todo list

	// accepted data: name, description
	createToDoList($project_id, $data)

Update a ToDoList in a specified project

	// accepted data: name, description, position
	updateProjectToDoList($project_id, $todolist_id, $data)

Shorthand method to update a project todo list

	// accepted data: name, description, position
	updateToDoList($project_id, $todolist_id, $data)

Deletes a specified project ToDo List

	deleteToDoList($project_id, $todolist_id)

ToDos
-----

Retrieves a single todo from a project

	getToDo($project_id, $todo_id)

Creates a todo item for a project

	// accepted data: content, due_at, assignee
	createToDo($project_id, $todolist_id, $data)

Update a single todo in a project

	// accepted data: content, due_at, assignee, position
	updateToDo($project_id, $todo_id, $data)

Delete the todo item from a ToDoList in a project

	deleteToDo($project_id, $todo_id)

Topics
------

Retrieves all topics from a project

	getTopics($project_id)