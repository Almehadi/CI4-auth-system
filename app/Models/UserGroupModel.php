<?php

namespace App\Models;

use CodeIgniter\Model;

class UserGroupModel extends Model
{
    protected $table = 'user_groups';
    protected $primaryKey = 'id';
    protected $allowedFields = ['group_name', 'descriptions', 'permissions'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';

    public function getAllGroups()
    {
        return $this->findAll();
    }

    public function getGroupByName($groupName)
    {
        return $this->where('group_name', $groupName)->first();
    }

    public function getPermissions($groupId)
    {
        $group = $this->find($groupId);
        if ($group && !empty($group['permissions'])) {
            return json_decode($group['permissions'], true);
        }
        return [];
    }
}