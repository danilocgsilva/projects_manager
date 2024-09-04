For unit testing, you can set the environament to have the following variables:

* PROJECTS_MANAGER_DB_TEST_HOST
* PROJECTS_MANAGER_DB_TEST_USER
* PROJECTS_MANAGER_DB_TEST_PASSWORD

## Domain models

### Project

The main model for project.

### Execution Environment

Where the project will be handled.

### Databases

Projects may have databases on which they operates upon.

**Notice** The database model does not have either host or port. Because both depends upon the environment where they are beign handled.

A database may belongs to a project.
