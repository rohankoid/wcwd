# WCWD 
WCWD is an example Silex Application for Token Based Authentication for API.

Application attempts to define best practices for API development using Silex.

Uses Doctrine DBAL for accessing the database. 

##Getting Started
Clone or download the project and extract it.
Setup database found under db/wcwd.mwb. (Use MysqlWorkBench for wcwd.mwb)

###Install dependencies

`composer install`

### Run Server
php -S 0.0.0.0:8880 -t public

## API

### Authentication

- GET auth/login   - provides new token for user
  >Parameters: [username, password]
  
  > (success) Response: `{"status":true,"info":{"token":"xxx_token_xxx"}}`
  
- GET api/user/register   - register a new user and receive token
  >Parameters: [username, password, mail]
  
  > (success) Response: `{"status":true,"info":{"token":"xxx_token_xxx", "user": 'user_name'}}`  
  
### Events
The application provides a REST API for managing events. All the events can be accessed only with a valid token and user name passed
with each request.

- GET api/events - Provides a listing of events. 
- GET api/event - Retrieves a single event.
- POST api/event - Creates a new event.
- PUT api/event/{event} - Updates a single event.
- DELETE api/artist/{event} - Deletes a single event.

The output format is JSON.
