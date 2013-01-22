libPhewtick
=========

PHP Library for automating Phewtick operations

  - Get QR code strings
  - Perform "Meetups"
  - Test token validity

There are many possbilities for this, such as running this on a crontab to perform "regular meetups"

Usage
--------------

Creating a meetup between two users

    $firstToken = "000000000000000000000000000000000";
	$secondToken = "111111111111111111111111111111111";

	$meetingLat = '1.276153';
	$meetingLng = '103.854760';

	echo ptSetMeetingBetweenTwoTokens($firstToken, $secondToken, $meetingLat. $meetingLng);


License
-

MIT