# The New Basecamp API CI Library
===================

This CodeIgniter library connects to the NEW Basecamp API. It does not work with Basecamp Classic. For more information on the Basecamp API, visit the [37signals Github page](https://github.com/37signals/bcx-api).

## Sections
========

### Accesses

* `getAccessesForProject($project_id)` - Retrieve all accesses for a specific project
* `getAccessesForCalendar($calendar_id)` - Retrieve all accesses for a specific calendar
* `grantAccessToProject($project_id, $data)` - Grant access to a specific project
* `grantAccessToProject($project_id, $data)` - Grant access to a specific project
* `grantAccessToCalendar($calendar_id, $data)` - Grant access to a specific calendar
* `revokeAccessToProject($project_id, $person_id)` - Revoke access to projects
* `revokeAccessToCalendar($calendar_id, $person_id)` - Revoke access to calendars

### Attachments/Files

* `getAttachments($project_id)` - Retrieve all attachments
* `createAttachment($file_path)` - Retrieves token for attachment upload
* `uploadFile($project_id, $data)` - Creates new entry in the files section