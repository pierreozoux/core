<?php
/**
 * @author Robin Appelman <icewind@owncloud.com>
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

namespace OCA\Files_External\Lib;

use OC\Files\Cache\CacheEntry;
use OCP\Constants;
use OCP\Files\Cache\ICache;

/**
 * Storage placeholder to represent a missing precondition, storage unavailable
 */
class FailedCache implements ICache {

	public function getNumericStorageId() {
		return -1;
	}

	public function get($file) {
		if ($file === '') {
			return new CacheEntry([
				'fileid' => -1,
				'size' => 0,
				'mimetype' => 'httpd/unix-directory',
				'mimepart' => 'httpd',
				'permissions' => Constants::PERMISSION_READ,
				'mtime' => time()
			]);
		} else {
			return false;
		}
	}

	public function getFolderContents($folder) {
		return [];
	}

	public function getFolderContentsById($fileId) {
		return [];
	}

	public function put($file, array $data) {
		return;
	}

	public function insert($file, array $data) {
		return;
	}

	public function update($id, array $data) {
		return;
	}

	public function getId($file) {
		return -1;
	}

	public function getParentId($file) {
		return -1;
	}

	public function inCache($file) {
		return false;
	}

	public function remove($file) {
		return;
	}

	public function move($source, $target) {
		return;
	}

	public function moveFromCache(ICache $sourceCache, $sourcePath, $targetPath) {
		return;
	}

	public function clear() {
		return;
	}

	public function getStatus($file) {
		return ICache::NOT_FOUND;
	}

	public function search($pattern) {
		return [];
	}

	public function searchByMime($mimetype) {
		return [];
	}

	public function searchByTag($tag, $userId) {
		return [];
	}

	public function getAll() {
		return [];
	}

	public function getIncomplete() {
		return [];
	}

	public function getPathById($id) {
		return null;
	}

	public function normalize($path) {
		return $path;
	}
}
