<?php
/**
 * @author Piotr Mrowczynski <piotr@owncloud.com>
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

namespace OC;

use OC\Group\BackendGroup;
use OC\User\Account;
use OCP\AppFramework\Db\Mapper;
use OCP\AppFramework\Db\Entity;
use OCP\IConfig;
use OCP\IDBConnection;
use OCP\AppFramework\Db\DoesNotExistException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IMembershipManager;

class MembershipManager implements IMembershipManager {

	/**
	 * types of memberships in the group
	 */
	const MEMBERSHIP_TYPE_GROUP_USER = 0;
	const MEMBERSHIP_TYPE_GROUP_ADMIN = 1;

	protected $db;

	/** @var \OC\Group\GroupMapper */
	private $groupMapper;

	/** @var \OC\Group\GroupMapper */
	private $accountMapper;

	public function __construct(IDBConnection $db, \OC\User\AccountMapper $accountMapper, \OC\Group\GroupMapper $groupMapper) {
		$this->db = $db;
		$this->groupMapper = $groupMapper;
		$this->accountMapper = $accountMapper;
	}

	private function getTableName() {
		return 'memberships';
	}

	/**
	 * Return backend group entities for given account (identified by user's accountId)
	 *
	 * @param int $accountId
	 *
	 * @return Entity[]
	 */
	public function getUserBackendGroups($accountId) {

	}

	/**
	 * Return backend group entities for given user accountId of which
	 * the user is admin.
	 *
	 * @param int $accountId
	 *
	 * @return Entity[]
	 */
	public function getAdminBackendGroups($userId) {

	}

	/**
	 * Return user account entities for given backend group (identified with gid)
	 *
	 * @param string $gid
	 *
	 * @return Entity[]
	 */
	public function getGroupUserAccounts($gid) {

	}

	/**
	 * Return admin account entities for given backend group (identified with gid)
	 *
	 * @param string $gid
	 *
	 * @return Entity[]
	 */
	public function getGroupAdminAccounts($gid) {

	}

	/**
	 * Return admin account entities for all backend groups
	 *	 *
	 * @return Entity[]
	 */
	public function getAdminAccounts() {

	}

	/**
	 * Check whether given account (identified by user's accountId) is user of
	 * backend group (identified by group's gid)
	 *
	 * @param int $accountId
	 * @param string $gid - group identified by gid or any group if null
	 *
	 * @return boolean
	 */
	public function isGroupUser($accountId, $gid) {

	}

	/**
	 * Check whether given account (identified by user's accountId) is admin of
	 * backend group (identified by group's gid)
	 *
	 * @param int $accountId
	 * @param string $gid
	 *
	 * @return boolean
	 */
	public function isGroupAdmin($accountId, $gid) {

	}

	/**
	 * Search for accounts which match the pattern and
	 * are members of backend group (identified by group's gid)
	 *
	 * @param string $gid
	 * @param string $pattern
	 * @param integer $limit
	 * @param integer $offset
	 * @return Entity[]
	 */
	public function find($gid, $search, $searchLimit, $searchOffset) {

	}

	/**
	 * Count accounts which match the pattern and
	 * are members of backend group (identified by group's gid)
	 *
	 * @param string $gid
	 * @param string $pattern
	 * @param integer $limit
	 * @param integer $offset
	 * @return Entity[]
	 */
	public function count($gid, $search, $searchLimit, $searchOffset) {

	}

	/**
	 * Add a group account (identified by user's accountId) to group.
	 *
	 * @param int $accountId
	 * @param string $gid group user becomes member of
	 * @return bool
	 */
	public function addGroupUsers($accountId, $gid) {

	}

	/**
	 * Add a group admin account (identified by user's accountId) to the group.
	 *
	 * @param int $accountId
	 * @param string $gid group user becomes admin of
	 * @return bool
	 */
	public function addGroupAdmin($accountId, $gid) {

	}

	/**
	 * Delete a group member account (identified by user's accountId)
	 * from group, regardless of his role in the group.
	 *
	 * @param int $accountId
	 * @param string $gid group user is member of
	 * @return bool
	 */
	public function removeGroupMember($accountId, $gid) {

	}

	/**
	 * Delete a group member accounts from group,
	 * regardless of the role in the group.
	 *
	 * @param int $userId user
	 * @param string $gid group user is member of
	 * @return bool
	 */
	public function removeGroupMembers($gid) {

	}

	/**
	 * Delete the memberships of account (identified by user's accountId),
	 * regardless of the role in the group.
	 *
	 * @param int $accountId
	 * @param string $gid group user is member of
	 * @return bool
	 */
	public function removeMemberships($accountId) {

	}

	/**
	 * TODO: add descriptions
	 *
	 * @param int $backendGroupId
	 * @param int $accountId
	 *
	 * @return boolean
	 */
	private function isGroupMember($backendGroupId, $accountId, $membershipType) {
		$qb = $this->db->getQueryBuilder();
		$qb->select($qb->expr()->literal('1'))
			->from($this->getTableName())
			->where($qb->expr()->eq('backend_group_id', $qb->createNamedParameter($backendGroupId)))
			->andWhere($qb->expr()->eq('account_id', $qb->createNamedParameter($accountId)))
			->andWhere($qb->expr()->eq('membership_type', $qb->createNamedParameter($membershipType)));
		$resultArray = $qb->execute()->fetchAll();

		return empty($resultArray) ? false : true;
	}


	/**
	 * TODO: add descriptions
	 *
	 * @param int $backendGroupId
	 * @param int $accountId
	 * @param int $membershipType
	 *
	 * @return boolean
	 */
	private function addGroupMember($backendGroupId, $accountId, $membershipType) {
		$qb = $this->db->getQueryBuilder();

		$qb->insert($this->getTableName())
			->values([
				'backend_group_id' => $qb->createNamedParameter($backendGroupId),
				'account_id' => $qb->createNamedParameter($accountId),
				'membership_type' => $qb->createNamedParameter($membershipType),
			]);


		try {
			$qb->execute();
			return true;
		} catch (UniqueConstraintViolationException $e) {
			// TODO: hmmm raise some warning?
			return false;
		}
	}


	/*
	 * TODO: add descriptions
	 *
	 * @param int $backendGroupId
	 * @param int[] $membershipTypeArray
	 *
	 * @return IQueryBuilder
	 */
	private function deleteGroupMemberAccountsSqlQuery($backendGroupId, $membershipTypeArray) {
		$qb = $this->db->getQueryBuilder();
		$qb->delete($this->getTableName())
			->where($qb->expr()->eq('backend_group_id', $qb->createNamedParameter($backendGroupId)))
			->andWhere($qb->expr()->in('membership_type',
				$qb->createNamedParameter($membershipTypeArray, IQueryBuilder::PARAM_INT_ARRAY)));
		return $qb;
	}

	/*
	 * Get SQL fetching all backend groups
	 *	 *
	 * @return IQueryBuilder
	 */
	private function getBackendGroupsSqlQuery() {
		$qb = $this->db->getQueryBuilder();
		$qb->select(['g.id', 'g.group_id', 'g.display_name', 'g.backend'])
			->from($this->getTableName(), 'm')
			->innerJoin('m', $this->groupMapper->getTableName(), 'g', $qb->expr()->eq('g.id', 'm.backend_group_id'));
		return $qb;
	}

	/*
	 * Get SQL fetching all accounts
	 *
	 * @return IQueryBuilder
	 */
	private function getAccountsSqlQuery() {
		$qb = $this->db->getQueryBuilder();
		$qb->select(['a.id','a.user_id', 'a.lower_user_id', 'a.display_name', 'a.email', 'a.last_login', 'a.backend', 'a.state', 'a.quota', 'a.home'])
			->from($this->getTableName(), 'm')
			->innerJoin('m', $this->accountMapper->getTableName(), 'a', $qb->expr()->eq('a.id', 'm.account_id'));
		return $qb;
	}
}
