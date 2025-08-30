<?php

declare(strict_types=1);

namespace Djoudi\Bigbluebutton\Contracts;

use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Parameters\DeleteRecordingsParameters;
use BigBlueButton\Parameters\PublishRecordingsParameters;
use BigBlueButton\Parameters\UpdateRecordingsParameters;

interface Meeting
{
    /**
     *  Return a list of all meetings.
     *
     * @return mixed
     */
    public function all(): mixed;

    /**
     * @param \BigBlueButton\Parameters\CreateMeetingParameters $meeting
     *
     * @return bool
     */
    public function create(CreateMeetingParameters $meeting): bool;

    /**
     *  Join meeting.
     *
     * @param \BigBlueButton\Parameters\JoinMeetingParameters $meeting
     * @param string|null $meetingName Custom display name for the meeting
     *
     * @return string
     */
    public function join(JoinMeetingParameters $meeting, ?string $meetingName = null): string;

    /**
     *  Returns information about the meeting.
     *
     * @param \BigBlueButton\Parameters\GetMeetingInfoParameters $meeting
     *
     * @return bool|\SimpleXMLElement
     */
    public function get(GetMeetingInfoParameters $meeting): \SimpleXMLElement|false;

    /**
     *  Close meeting.
     *
     * @param \BigBlueButton\Parameters\EndMeetingParameters $meeting
     *
     * @return \BigBlueButton\Responses\EndMeetingResponse
     */
    public function close(EndMeetingParameters $meeting): \BigBlueButton\Responses\EndMeetingResponse;

    /**
     * @param \BigBlueButton\Parameters\GetRecordingsParameters $recording
     *
     * @return mixed
     */
    public function getRecording(GetRecordingsParameters $recording): mixed;

    public function publishRecordings(PublishRecordingsParameters $recording): bool;

    public function deleteRecordings(DeleteRecordingsParameters $recording): bool;

    public function updateRecordings(UpdateRecordingsParameters $recording): bool;

    public function isRunning(IsMeetingRunningParameters $meeting): bool;
}
