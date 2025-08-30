
[![Build Status](https://travis-ci.org/djoudi/BBB-Laravel5.5.svg?branch=master)](https://travis-ci.org/djoudi/BBB-Laravel5.5)
[![StyleCI](https://styleci.io/repos/122086438/shield?branch=master)](https://styleci.io/repos/122086438)


# BigBlueButton API  Wrapper for Laravel 
This is a laravel wrapper for BigBlueButton API
## Requirements

- PHP 8.4 or above
- Laravel 12 or above

## Installation

Require package in your composer.json and update composer.  This downloads the package and the official bigbluebutton php library. 

```
composer require djoudi/bbb-laravel
```

After updating composer, add the ServiceProvider to the providers array in config/app.php
```
 Djoudi\Bigbluebutton\BigbluebuttonProviderService::class,
```
You can optionally use the facade for shorter code. Add this to your facades:
```
'Meeting' => Djoudi\Bigbluebutton\BigbluebuttonMeeting::class,
```

## Usage

You can define Big blue button secret key and server url in two ways. 
1. Define in .env file

 ```BBB_SECURITY_SALT =bbb_secret_key```  
 ```BBB_SERVER_BASE_URL=https://example.com/bigbluebutton/``` 
 
 2. Define in config/bigbluebutton.php
 
```
 'BBB_SECURITY_SALT' => 'bbb_secret_key',
 'BBB_SERVER_BASE_URL' => 'https://example.com/bigbluebutton/',
```

## Via Dependency Injection in Controller
**List all meetings**
```php
namespace App\Http\Controllers;
class MeetingController extends Controller
{
    /**
     * @var \Djoudi\Bigbluebutton\Contracts\Meeting
     */
    protected $meeting;

    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }
    /**
     *  Returns a list of meetings
     */
    public function all()
    {
        $meetings = $this->meeting->all();
        if ($meetings) {
            // do something with meetings
        }
    }

```
**Create meeting**
```php
use Djoudi\Bigbluebutton\Contracts\Meeting;
use BigBlueButton\Parameters\CreateMeetingParameters;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    /**
     * @var \Djoudi\Bigbluebutton\Contracts\Meeting
     */
    protected $meeting;

    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    /**
         * Create a bigbluebutton meeting
         *
         * @param \Illuminate\Http\Request $request
         * @return void
         */
        public function create(Request $request)
        {
            $meetingParams = new CreateMeetingParameters($request->meetingId, $request->meetingName);
            $meetingParams->setDuration(40);
            $meetingParams->setModeratorPassword('supersecretpwd');
    
            if ($this->meeting->create($meetingParams)) {
                // Meeting was created
            }
        }

```
**Join a meeting**
Pass a custom meeting name as the second argument to `join()` if you need to override the default.
```php
use Djoudi\Bigbluebutton\Contracts\Meeting;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    /**
     * @var \Djoudi\Bigbluebutton\Contracts\Meeting
     */
    protected $meeting;

    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }
    /**
     *  Join a bigbluebutton meeting
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function join(Request $request)
    {
        $meetingParams = new JoinMeetingParameters($request->meetingID, 'Guest', 'MyMeetingPassword');
        $meetingParams->setRedirect(true);
        $meetingUrl = $this->meeting->join($meetingParams, $request->meetingName);
        redirect()->setTargetUrl($meetingUrl);
    }

}

```
**Close meeting**
```php

use Djoudi\Bigbluebutton\Contracts\Meeting;
use BigBlueButton\Parameters\EndMeetingParameters;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    /**
     * @var \Djoudi\Bigbluebutton\Contracts\Meeting
     */
    protected $meeting;

    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }
    
    /**
     * End a bigbuebutton meeting
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function close(Request $request)
    {
        $meetingParams = new EndMeetingParameters($request->meetingID, $request->moderator_password);
        $this->meeting->close($meetingParams);
    }
}

```

**Check if a meeting is running**
```php
use Djoudi\Bigbluebutton\Contracts\Meeting;
use BigBlueButton\Parameters\IsMeetingRunningParameters;

class MeetingController extends Controller
{
    /**
     * @var \Djoudi\Bigbluebutton\Contracts\Meeting
     */
    protected $meeting;

    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    public function isRunning(string $meetingID)
    {
        $meetingParams = new IsMeetingRunningParameters($meetingID);
        if ($this->meeting->isRunning($meetingParams)) {
            // Meeting is currently running
        }
    }
}

```
**Manage recordings**

```php
use Djoudi\\Bigbluebutton\\Contracts\\Meeting;
use BigBlueButton\\Parameters\\PublishRecordingsParameters;
use BigBlueButton\\Parameters\\DeleteRecordingsParameters;
use BigBlueButton\\Parameters\\UpdateRecordingsParameters;

class RecordingController extends Controller
{
    public function publish(Meeting $meeting, string $recordingId)
    {
        $params = new PublishRecordingsParameters($recordingId, true); // false to unpublish
        if ($meeting->publishRecordings($params)) {
            // Recording publish state updated
        }
    }

    public function delete(Meeting $meeting, string $recordingId)
    {
        $params = new DeleteRecordingsParameters($recordingId);
        if ($meeting->deleteRecordings($params)) {
            // Recording deleted
        }
    }

    public function update(Meeting $meeting, string $recordingId)
    {
        $params = (new UpdateRecordingsParameters($recordingId))->addMeta('name', 'New name');
        if ($meeting->updateRecordings($params)) {
            // Recording metadata updated
        }
    }
}

```
## Via Laravel Facade
You can also manage meetings using the facade
```php
Meeting::all(); //get a list of all meetings
```
