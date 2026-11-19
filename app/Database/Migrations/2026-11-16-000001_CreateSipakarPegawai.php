<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Create sipakar_pegawai table
 * Stores ASN employee data imported from CSV for SIPAKAR login.
 */
class CreateSipakarPegawai extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nip' => [
                'type'       => 'VARCHAR',
                'constraint' => 25,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'jabatan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => '',
            ],
            'pangkat_golongan' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => '',
            ],
            'unit_kerja' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => '',
            ],
            'jenis_asn' => [
                'type'       => 'ENUM',
                'constraint' => ['PNS', 'PPPK', 'LAINNYA'],
                'default'    => 'PNS',
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'aktif' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'login_attempts' => [
                'type'    => 'TINYINT',
                'constraint' => 3,
                'unsigned' => true,
                'default' => 0,
            ],
            'locked_until' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'last_login' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'last_ip' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'default'    => '',
            ],
            'must_change_password' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'comment' => 'Force password change on first login',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nip');
        $this->forge->addKey(['aktif', 'locked_until']);
        $this->forge->addKey('jabatan');

        $this->forge->createTable('sipakar_pegawai', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);
    }

    public function down(): void
    {
        $this->forge->dropTable('sipakar_pegawai', true);
    }
}
