# TODO

* Logging of API calls, also log user identity!?
* return existing access code when a new request comes in from the same 
  client,ro,scope
* Make it possible to disable (access) token expiry
* rename database tables to lower case and make the names plural
* fix no scope scenario to have a default scope (DONE?)
* create a "removeMe" API to completely remove all user data from the service
* make it possible to link a client to one or more resource servers
* make it possible to skip user consent (i.e.: for management clients etc.)
* figure out of CORS headers are correctly set on authorization endpoints of 
  the API
* move client data to JSON object in DB instead of separate columns for 
  everything
