# Laravel Lumen Demo

## Task Management System

* This system provides API's to Get, Create, Update and Delete the following
    * To Do Lists
    * Steps for a To Do List
    * Tasks for a Step
    * Status of a Task (Create, Delete, Update for admin only)

* Task management system may have one or more To Do Lists
* A To Do List may have one or more Steps
* Each Step may have one or more Tasks
* Each Task has an "Active" or "Completed" Status

## Developer Information


### API's
API | HTTP | Auth | API Link |
|---|---|---|---|
| To Do List | GET | `JWT` | 'api/v1/todolists' |
| To Do List | GET | `JWT` | 'api/v1/todolists/{toDoListId}' |
| To Do List | POST | `JWT` | 'api/v1/todolists' |
| To Do List | PUT | `JWT` | 'api/v1/todolists/{toDoListId}' |
| To Do List | DELETE | `JWT` | 'api/v1/todolists/{toDoListId}' |

API Name | HTTP | Auth | API |
|---|---|---|---|
| Step | GET | `JWT` | 'api/v1/todolists/{toDoListId}/steps' |
| Step | GET | `JWT` | 'api/v1/todolists/{toDoListId}/steps/{stepId}' |
| Step | POST | `JWT` | 'api/v1/todolists/{toDoListId}/steps' |
| Step | PUT | `JWT` | 'api/v1/todolists/{toDoListId}/steps/{stepId}' |
| Step | DELETE | `JWT` | 'api/v1/todolists/{toDoListId}/steps/{stepId}' |

API Name | HTTP | Auth | API |
|---|---|---|---|
| Task | GET | `JWT` | 'api/v1/todolists/{toDoListId}/steps/{stepId}/tasks' |
| Task | GET | `JWT` | 'api/v1/todolists/{toDoListId}/steps/{stepId}/tasks/{taskId}' |
| Task | POST | `JWT` | 'api/v1/todolists/{toDoListId}/steps/{stepId}/tasks' |
| Task | PUT | `JWT` | 'api/v1/todolists/{toDoListId}/steps/{stepId}/tasks/{taskId}' |
| Task | DELETE | `JWT` | 'api/v1/todolists/{toDoListId}/steps/{stepId}/tasks/{taskId}' |

API Name | HTTP | Auth | API | Admin Use Only
|---|---|---|---|---|
| State | GET | `JWT` | 'api/v1/states' | NO |
| State | GET | `JWT` | 'api/v1/states/{stateId}' | NO |
| State | POST | `JWT` | 'api/v1/states/' | YES |
| State | PUT | `JWT` | 'api/v1/states/{stateId}' | YES |
| State | DELETE | `JWT` | 'api/v1/states/{stateId}' | YES |

API Name | HTTP | Auth | API |
|---|---|---|---|
| JWT Auth | GET | NO | 'api/register' |
| JWT Auth | GET | NO | 'api/login' |
| JWT Auth | POST | `JWT` | 'api/profile' |
| JWT Auth | PUT | `JWT` | 'api/users/{id}' |
| JWT Auth | DELETE | `JWT` | 'api/users' |


### Environment

> Laravel Lumen Version 6.2.0
>
>***
> PHP Version 7.3.5
>
>***
### Installation
* Run `$ composer install`
### Environment File Setup
* Look at the `.env.example` file.
* Create a `.env` file in the project root folder.

    * MySql Database used
    * Update Mailtrap username and password
    * Generate a secret key using `$ php artisan jwt:secret`

* A Laravel Lumen Generator added
    * Look at https://github.com/flipboxstudio/lumen-generator
    * Following Artisan commands are made available

          ----------------------------------------------------
          key:generate      Set the application key

          make:command      Create a new Artisan command
          make:controller   Create a new controller class
          make:event        Create a new event class
          make:job          Create a new job class
          make:listener     Create a new event listener class
          make:mail         Create a new email class
          make:middleware   Create a new middleware class
          make:migration    Create a new migration file
          make:model        Create a new Eloquent model class
          make:policy       Create a new policy class
          make:provider     Create a new service provider class
          make:seeder       Create a new seeder class
          make:test         Create a new test class
* Run `php artisan migrate --seed` to migrate and seed
* After seeding the following users is created
---
    Username : `user1`
    User Email: `user1@test.com`
    User Password: `password`
---
    Username : `user1`
    User Email: `user1@test.com`
    User Password: `password`
* After seeding if required a user may be created using `/api/register`
* To obtain the JWT Auth Token use `/api/login`
* Ensure the token is passed in the header else the user will be unauthorised.

### Useage
* Look at the API's index for information on CRUD operations.
* Look at the controllers for validation using Lumen validator for Create and Update operations.
---
    'name' => 'required|min:5|max:50',          [ To Do List, Step, Task and State ]
    'description' => 'required|min:5|max:255'   [ To Do List, Step and Task ]
---
* HATEOAS implemented
    * Look at `"links"` field in the response
    * Example `/api/v1/todolists/1` response
    ---
        {
            "id": 1,
            "name": "Task Name sp1Pet3PjB",
            "description": "Task Description x3j12",
            "order": 1,
            "user_id": 1,
            "links": [
                {
                    "ref": "toDoList",
                    "href": "api/v1/todolists/1",
                    "action": "PUT"
                },
                {
                    "ref": "toDoList",
                    "href": "api/v1/todolists/1",
                    "action": "DELETE"
                },
                {
                    "rel": "todolist",
                    "href": "api/v1/todolist",
                    "action": "GET"
                },
                {
                    "rel": "steps",
                    "href": "api/v1/todolist/1/steps",
                    "action": "GET"
                }
            ]
        }
    ---
* Delete Information
    * If a To Do List is deleted, then corresponding Steps and Tasks are deleted as well.
    * If a Step is deleted, then corresponding Tasks are deleted as well.
    * If a Task is deleted, then only the Task is deleted.

### Events and Queues
* Once a To Do List, or a Step, or a Task is created the following occurs
    * A Job is Queued for an Email to be sent to the user.
    * Use `$ php artisan queue:listen database` to view jobs.
    * Emails sent are logged to the 'email_logs' table.

