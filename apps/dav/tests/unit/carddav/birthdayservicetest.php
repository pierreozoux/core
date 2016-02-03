<?php
/**
 * @author Thomas Müller <thomas.mueller@tmit.eu>
 *
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\DAV\Tests\Unit\CardDAV;

use OCA\DAV\CalDAV\BirthdayService;
use OCA\DAV\CalDAV\CalDavBackend;
use OCA\DAV\CardDAV\CardDavBackend;
use Test\TestCase;

class BirthdayServiceTest extends TestCase {

	/** @var BirthdayService */
	private $service;

	public function setUp() {
		parent::setUp();

		/** @var CalDavBackend | \PHPUnit_Framework_MockObject_MockObject $cal */
		$cal = $this->getMockBuilder('OCA\DAV\CalDAV\CalDavBackend')->disableOriginalConstructor()->getMock();
		/** @var CardDavBackend | \PHPUnit_Framework_MockObject_MockObject $cal */
		$card = $this->getMockBuilder('OCA\DAV\CardDAV\CardDavBackend')->disableOriginalConstructor()->getMock();

		$this->service = new BirthdayService($cal, $card);
	}

	/**
	 * @dataProvider providesVCards
	 * @param boolean $nullExpected
	 * @param string | null $data
	 */
	public function test($nullExpected, $data) {
		$cal = $this->service->buildBirthdayFromContact($data);
		if ($nullExpected) {
			$this->assertNull($cal);
		} else {
			$this->assertInstanceOf('Sabre\VObject\Component\VCalendar', $cal);
			$this->assertTrue(isset($cal->VEVENT));
			$this->assertEquals('FREQ=YEARLY', $cal->VEVENT->RRULE->getValue());
			$this->assertEquals('12345\'s Birthday (1900)', $cal->VEVENT->SUMMARY->getValue());
			$this->assertEquals('TRANSPARENT', $cal->VEVENT->TRANSP->getValue());
		}
	}

	public function providesVCards() {
		return [
			[true, null],
			[true, ''],
			[true, 'yasfewf'],
			[true, "BEGIN:VCARD\r\nVERSION:3.0\r\nPRODID:-//Sabre//Sabre VObject 3.5.0//EN\r\nUID:12345\r\nFN:12345\r\nN:12345;;;;\r\nEND:VCARD\r\n", "Dr. Foo Bar"],
			[true, "BEGIN:VCARD\r\nVERSION:3.0\r\nPRODID:-//Sabre//Sabre VObject 3.5.0//EN\r\nUID:12345\r\nFN:12345\r\nN:12345;;;;\r\nBDAY:\r\nEND:VCARD\r\n", "Dr. Foo Bar"],
			[true, "BEGIN:VCARD\r\nVERSION:3.0\r\nPRODID:-//Sabre//Sabre VObject 3.5.0//EN\r\nUID:12345\r\nFN:12345\r\nN:12345;;;;\r\nBDAY:someday\r\nEND:VCARD\r\n", "Dr. Foo Bar"],
			[false, "BEGIN:VCARD\r\nVERSION:3.0\r\nPRODID:-//Sabre//Sabre VObject 3.5.0//EN\r\nUID:12345\r\nFN:12345\r\nN:12345;;;;\r\nBDAY:1900-01-01\r\nEND:VCARD\r\n", "Dr. Foo Bar"],
		];
	}
}
