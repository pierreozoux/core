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

namespace OCA\DAV\Tests\Unit\CalDAV;

use OCA\DAV\CalDAV\CalDavBackend;
use OCA\DAV\CalDAV\Calendar;
use Test\TestCase;

class CalendarTest extends TestCase {

	public function testDelete() {
		/** @var \PHPUnit_Framework_MockObject_MockObject | CalDavBackend $backend */
		$backend = $this->getMockBuilder('OCA\DAV\CalDAV\CalDavBackend')->disableOriginalConstructor()->getMock();
		$backend->expects($this->once())->method('updateShares');
		$backend->method('getShares')->willReturn([
			['href' => 'principal:user2']
		]);
		$calendarInfo = [
			'{http://owncloud.org/ns}owner-principal' => 'user1',
			'principaluri' => 'user2',
			'id' => 666
		];
		$c = new Calendar($backend, $calendarInfo);
		$c->delete();
	}

	/**
	 * @expectedException \Sabre\DAV\Exception\Forbidden
	 */
	public function testDeleteFromGroup() {
		/** @var \PHPUnit_Framework_MockObject_MockObject | CalDavBackend $backend */
		$backend = $this->getMockBuilder('OCA\DAV\CalDAV\CalDavBackend')->disableOriginalConstructor()->getMock();
		$backend->expects($this->never())->method('updateShares');
		$backend->method('getShares')->willReturn([
			['href' => 'principal:group2']
		]);
		$calendarInfo = [
			'{http://owncloud.org/ns}owner-principal' => 'user1',
			'principaluri' => 'user2',
			'id' => 666
		];
		$c = new Calendar($backend, $calendarInfo);
		$c->delete();
	}
}
