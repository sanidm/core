<?php
/**
 * @author Thomas Müller <thomas.mueller@tmit.eu>
 *
 * @copyright Copyright (c) 2017, ownCloud GmbH
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


namespace OCA\DAV\Connector\Sabre;


use Sabre\DAV\IFile;

class FileVersion implements IFile {

	/**
	 * @inheritdoc
	 *
	 * @param resource|string $data
	 * @return string|null
	 */
	function put($data) {
		throw new Exception\Forbidden('Permission denied to write to file (filename ' . $this->getName() . ')');
	}

	/**
	 * Returns the data
	 *
	 * This method may either return a string or a readable stream resource
	 *
	 * @return mixed
	 */
	function get() {
		// TODO: Implement get() method.
	}

	/**
	 * Returns the mime-type for a file
	 *
	 * If null is returned, we'll assume application/octet-stream
	 *
	 * @return string|null
	 */
	function getContentType() {
		// TODO: Implement getContentType() method.
	}

	/**
	 * Returns the ETag for a file
	 *
	 * An ETag is a unique identifier representing the current version of the file. If the file changes, the ETag MUST change.
	 *
	 * Return null if the ETag can not effectively be determined.
	 *
	 * The ETag must be surrounded by double-quotes, so something like this
	 * would make a valid ETag:
	 *
	 *   return '"someetag"';
	 *
	 * @return string|null
	 */
	function getETag() {
		// TODO: Implement getETag() method.
	}

	/**
	 * Returns the size of the node, in bytes
	 *
	 * @return int
	 */
	function getSize() {
		// TODO: Implement getSize() method.
	}

	/**
	 * Deleted the current node
	 *
	 * @return void
	 */
	function delete() {
		// TODO: Implement delete() method.
	}

	/**
	 * Returns the name of the node.
	 *
	 * This is used to generate the url.
	 *
	 * @return string
	 */
	function getName() {
		// TODO: Implement getName() method.
	}

	/**
	 * Renames the node
	 *
	 * @param string $name The new name
	 * @return void
	 */
	function setName($name) {
		// TODO: Implement setName() method.
	}

	/**
	 * Returns the last modification time, as a unix timestamp. Return null
	 * if the information is not available.
	 *
	 * @return int|null
	 */
	function getLastModified() {
		// TODO: Implement getLastModified() method.
	}
}
