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

namespace OCA\DAV\Files;

use OCA\DAV\Connector\Sabre\File;
use OCP\ILogger;
use Sabre\DAV\Server;
use Sabre\DAV\ServerPlugin;
use Sabre\HTTP\RequestInterface;
use Sabre\HTTP\ResponseInterface;

class PreviewPlugin extends ServerPlugin {

	/** @var Server */
	protected $server;
	/** @var ILogger */
	private $logger;

	public function __construct(ILogger $logger) {
		$this->logger = $logger;
	}

	/**
	 * Initializes the plugin and registers event handlers
	 *
	 * @param Server $server
	 * @return void
	 */
	function initialize(Server $server) {

		$this->server = $server;
		$this->server->on('method:GET', [$this, 'httpGet'], 90);
	}

	/**
	 * Intercepts GET requests on addressbook urls ending with ?photo.
	 *
	 * @param RequestInterface $request
	 * @param ResponseInterface $response
	 * @return bool
	 */
	function httpGet(RequestInterface $request, ResponseInterface $response) {

		$queryParams = $request->getQueryParameters();
		if (!array_key_exists('preview', $queryParams)) {
			return true;
		}

		$path = $request->getPath();
		$node = $this->server->tree->getNodeForPath($path);

		if (!($node instanceof File)) {
			return true;
		}

		// Checking ACL, if available.
		if ($aclPlugin = $this->server->getPlugin('acl')) {
			/** @var \Sabre\DAVACL\Plugin $aclPlugin */
			$aclPlugin->checkPrivileges($path, '{DAV:}read');
		}

		if ($result = $this->getPreview($node)) {
			$response->setHeader('Content-Type', $result['Content-Type']);
//			$response->setHeader('Content-Disposition', 'attachment');
			$response->setStatus(200);

			$response->setBody($result['body']);

			// Returning false to break the event chain
			return false;
		}
		return true;
	}

	function getPreview(File $node) {
		// TODO: this might need a totally different interface where we separate caching from preview generation
		$image = \OC::$server->getPreviewManager()->createPreview($node->getFileInfo()->getInternalPath());
		if ($image === null || !$image->valid()) {
			return false;
		}
		$type = $image->mimeType();
		if (!in_array($type, ['image/png', 'image/jpeg', 'image/gif'])) {
			$type = 'application/octet-stream';
		}

		// Enable output buffering
		ob_start();
		// Capture the output
		$image->show();
		$imageData = ob_get_contents();
		// Clear the output buffer
		ob_end_clean();
		return [
			'Content-Type' => $type,
			'body' => $imageData
		];
	}
}
