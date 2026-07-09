<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table      = 'setting_sistem';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'setting_key',
        'setting_value',
        'setting_label',
        'setting_group',
        'setting_type',
        'description',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getValue(string $key, $default = null)
    {
        if (!$this->db->tableExists($this->table)) {
            return $default;
        }

        $row = $this->where('setting_key', $key)->first();
        return $row['setting_value'] ?? $default;
    }

    public function getByGroup(string $group): array
    {
        if (!$this->db->tableExists($this->table)) {
            return [];
        }

        return $this->where('setting_group', $group)
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function getMap(string $group = null): array
    {
        if (!$this->db->tableExists($this->table)) {
            return [];
        }

        $builder = $this;
        if ($group !== null) {
            $builder = $builder->where('setting_group', $group);
        }

        $rows = $builder->findAll();
        $map  = [];

        foreach ($rows as $row) {
            $map[$row['setting_key']] = $row['setting_value'];
        }

        return $map;
    }

    public function setValue(string $key, string $value, array $meta = []): bool
    {
        if (!$this->db->tableExists($this->table)) {
            return false;
        }

        $existing = $this->where('setting_key', $key)->first();

        $payload = array_merge([
            'setting_key'   => $key,
            'setting_value' => $value,
        ], $meta);

        if ($existing) {
            return (bool) $this->update($existing['id'], $payload);
        }

        return (bool) $this->insert($payload);
    }
}
