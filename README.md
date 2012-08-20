# The New Basecamp API CI Library
===================

This CodeIgniter library connects to the NEW Basecamp API. It does not work with Basecamp Classic. For more information on the Basecamp API, visit the [37signals Github page](https://github.com/37signals/bcx-api).

## Sections
========

### Accesses

* `getAccessesForProject($project_id)` - Retrieve all accesses for a specific project
* `getAccessesForCalendar($calendar_id)` - Retrieve all accesses for a specific calendar
* `grantAccessToProject($project_id, $data)` - Grant access to a specific project
	* Accepted Data: `ids`, `email_addresses` 
* `grantAccessToProject($project_id, $data)` - Grant access to a specific project
	* Accepted Data: `ids`, `email_addresses` 
* `grantAccessToCalendar($calendar_id, $data)` - Grant access to a specific calendar
	* Accepted Data: `ids`, `email_addresses` 
* `revokeAccessToProject($project_id, $person_id)` - Revoke access to projects
* `revokeAccessToCalendar($calendar_id, $person_id)` - Revoke access to calendars

### Attachments/Files

* `getAttachments($project_id)` - Retrieve all attachments
* `createAttachment($file_path)` - Retrieves token for attachment upload
* `uploadFile($project_id, $data)` - Creates new entry in the files section
* `getUpload($project_id, $upload_id)` - Get content, comments and attachments of a specific upload

### Calendar Events

* `getProjectCalendarEvents($project_id)` - Retrieve all calendar events for a project
* `getCalendarEvents($calendar_id)` - Retrieve all calendar events for a calendar
* `getPastProjectCalendarEvents($project_id)` - Retrieve all past calendar events for a project
* `getPastCalendarEvents($calendar_id)` - Retrieve all past calendar events for a calendar
* `getSingleProjectCalendarEvent($project_id, $event_id)` - Retrieve single calendar event in a project
* `getSingleCalendarEvent($calendar_id, $event_id)` - Retrieve single calendar event in a calendar
* `createProjectCalendarEvent($project_id, $data)` - Create a calendar event in a project
* `createCalendarEvent($calendar_id, $data)` - Create a calendar event
* `updateProjectCalendarEvent($project_id, $event_id, $data)` - Updates a single calendar event in a project
* `updateCalendarEvent($calendar_id, $event_id, $data)` - Updates a single calendar event in a specified calendar
* `deleteProjectCalendarEvent($project_id, $event_id)` - Deletes a single calendar event
* `deleteCalendarEvent($calendar_id, $event_id)` - Deletes a single calendar event

### Calendars

* `getCalendars()` - Retrieve all calendars
* `getSingleCalendar($calendar_id)` - Retrieves single calendar
* `createCalendar($data)` - Creates a new calendar or multiple calendars if $data is array
* `updateCalendar($calendar_id, $data)` - Updates a single calendar
* `deleteCalendar($calendar_id)` - Deletes a single calendar

### Comments

* `createComment($project_id, $topic, $topic_id, $data)` - Creates a new comment for specified project and topic
* `deleteComment($project_id, $comment_id)` - Deletes a comment specified

### Documents

* `getDocuments($project_id)` - Retrieves most recent version of documents
* `getSingleDocument($project_id, $document_id)` - Retrieves recent version of single document
* `createDocument($project_id, $data)` - Creates a new document from the parameters passed
* `updateDocument($project_id, $document_id, $data)` - Updates a document in a specified project
* `deleteDocument($project_id, $document_id)` - Deletes a specified document from a project

### Events

* `getGlobalEvents($datetime, $page)` - Returns all global events between specified dates passed
* `getProjectEvents($project_id, $datetime, $page)` - Retieves 50 project events at a time, with pagination
* `getPersonsEvents($person_id, $datetime, $page)` - Retrieves 50 person events at a time, with pagination

### Messages/Discussions

* `getSingleMessage($project_id, $message_id)` - Retrieves specified message/discussion from project
* `createMessage($project_id, $data)` - Creates a message/discussion in a project
* `updateMessage($project_id, $message_id, $data)` - Updates the message/discussion in a project
* `deleteMessage($project_id, $message_id)` - Deletes the specified message/discussion from a project

### People

* `getPeople()` - Retrieves all the people on the account
* `getPerson($person_id)` - Retrieves a single person
* `getMe()` - Retrieves the person logged in (you)
* `deletePerson($person_id)` - Deletes a specified person

### Projects

* `getProjects()` - Retrieves all active projects
* `getArchivedProjects()` - Retrieves all archived projects
* `getSingleProject($project_id)` - Retrieves single project
* `createProject($data)` - Creates a new project
* `updateProject($project_id, $data)` - Updates a single project
* `activateProject($project_id)` - Activates an archived project
* `archiveProject($project_id)` - Archives an active project
* `deleteProject($project_id)` - Deletes a specified project

### ToDo Lists

* `getProjectToDoLists($project_id)` - Retrieves the todo lists of a specified project
* `getProjectsCompletedToDoLists($project_id)` - Retrieves completed todolists
* `getCompletedTDL($project_id)` - Shorthand method to retrieve completed todolists
* `getPersonsAssignedToDoLists($person_id)` - Retrieves assigned todolists of the specified person
* `getAssignedTDL($person_id)` - Shorthand method to retrieve person's assigned todo lists
* `getSingleToDoList($project_id, $todolist_id)` - Retrieves a single ToDo List in specified project
* `createProjectToDoList($project_id, $data)` - Creates a new ToDo List in a specified project
* `createToDoList($project_id, $data)` - Shorthand method to create a project todo list
* `updateProjectToDoList($project_id, $todolist_id, $data)` - Update a ToDoList in a specified project
* `updateToDoList($project_id, $todolist_id, $data)` - Shorthand method to update a project todo list
* `deleteToDoList($project_id, $todolist_id)` - Deletes a specified project ToDo List

### ToDos

* `getToDo($project_id, $todo_id)` - Retrieves a single todo from a project
* `createToDo($project_id, $todolist_id, $data)` - Creates a todo item for a project
* `updateToDo($project_id, $todo_id, $data)` - Update a single todo in a project
* `deleteToDo($project_id, $todo_id)` - Delete the todo item from a ToDoList in a project

### Topics

* `getTopics($project_id)` - Retrieves all topics from a project