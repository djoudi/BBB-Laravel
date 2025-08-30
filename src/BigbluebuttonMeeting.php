<?php

declare(strict_types=1);

namespace Djoudi\Bigbluebutton;

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Parameters\DeleteRecordingsParameters;
use BigBlueButton\Parameters\PublishRecordingsParameters;
use BigBlueButton\Parameters\UpdateRecordingsParameters;
use Djoudi\Bigbluebutton\Contracts\Meeting;

class BigbluebuttonMeeting implements Meeting
{
    protected BigBlueButton $bbb;

    public function __construct(BigBlueButton $bbb)
    {
        $this->bbb = $bbb;
    }

    /**
     *  Return a list of all meetings.
     *
     * @return mixed
     */
    public function all(): mixed
    {
        $response = $this->bbb->getMeetings();
        if ($response->getReturnCode() == 'SUCCESS') {
            return $response->getRawXml()->meetings->meeting;
        }

        return false;
    }

    /**
     * @param \BigBlueButton\Parameters\CreateMeetingParameters $meeting
     *
     * @return bool
     */
    public function create(CreateMeetingParameters $meeting): bool
    {
        $response = $this->bbb->createMeeting($meeting);
        if ($response->getReturnCode() == 'FAILED') {
            return false;
        } else {
            return true;
        }
    }

    /**
     *  Join meeting.
     *
     * @param \BigBlueButton\Parameters\JoinMeetingParameters $meeting
     * @param string|null $meetingName Custom display name for the meeting
     *
     * @return string
     */
    public function join(JoinMeetingParameters $meeting, ?string $meetingName = null): string
    {
        if ($meetingName !== null) {
            $meeting->setUsername($meetingName);
        }

        return $this->bbb->getJoinMeetingURL($meeting);
    }

    /**
     *  Returns information about the meeting.
     *
     * @param \BigBlueButton\Parameters\GetMeetingInfoParameters $meeting
     *
     * @return bool|\SimpleXMLElement
     */
    public function get(GetMeetingInfoParameters $meeting): \SimpleXMLElement|false
    {
        $response = $this->bbb->getMeetingInfo($meeting);
        if ($response->getReturnCode() == 'FAILED') {
            return false;
        }

        return $response->getRawXml();
    }

    /**
     *  Close meeting.
     *
     * @param \BigBlueButton\Parameters\EndMeetingParameters $meeting
     *
     * @return \BigBlueButton\Responses\EndMeetingResponse
     */
    public function close(EndMeetingParameters $meeting): \BigBlueButton\Responses\EndMeetingResponse
    {
        return $this->bbb->endMeeting($meeting);
    }

    /**
     * @param \BigBlueButton\Parameters\GetRecordingsParameters $recording
     *
     * @return mixed
     */
    public function getRecording(GetRecordingsParameters $recording): mixed
    {
        $response = $this->bbb->getRecordings($recording);

        if ($response->getReturnCode() == 'SUCCESS') {
            return $response->getRawXml()->recordings->recording;
        }

        return false;
    }

    public function publishRecordings(PublishRecordingsParameters $recording): bool
    {
        $response = $this->bbb->publishRecordings($recording);

        return $response->isPublished();
    }

    public function deleteRecordings(DeleteRecordingsParameters $recording): bool
    {
        $response = $this->bbb->deleteRecordings($recording);

        return $response->isDeleted();
    }

    public function updateRecordings(UpdateRecordingsParameters $recording): bool
    {
        $response = $this->bbb->updateRecordings($recording);

        return $response->isUpdated();
    }

    public function isRunning(IsMeetingRunningParameters $meeting): bool
    {
        $response = $this->bbb->isMeetingRunning($meeting);

        return $response->isRunning();
    }
}
