<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DtlrBranch;
use App\Models\DtlrUser;
use App\Models\DtlrDocumentType;
use App\Models\DtlrDocument;
use App\Models\DtlrDocumentLog;
use App\Models\DtlrDocumentReview;
use Illuminate\Support\Facades\Hash;

class DTLRSeeder extends Seeder
{
    public function run()
    {
        // Create branches
        $branches = [
            ['name' => 'Main Branch', 'location' => 'Headquarters, Manila'],
            ['name' => 'North Branch', 'location' => 'Quezon City'],
            ['name' => 'South Branch', 'location' => 'Makati City'],
            ['name' => 'East Branch', 'location' => 'Pasig City'],
            ['name' => 'West Branch', 'location' => 'Caloocan City'],
        ];

        foreach ($branches as $branch) {
            DtlrBranch::create($branch);
        }

        // Create users
        $users = [
            [
                'username' => 'superadmin',
                'email' => 'superadmin@logistics.com',
                'role' => 'superadmin',
                'branch_id' => 1,
                'password' => Hash::make('password123')
            ],
            [
                'username' => 'admin',
                'email' => 'admin@logistics.com',
                'role' => 'admin',
                'branch_id' => 1,
                'password' => Hash::make('password123')
            ],
            [
                'username' => 'manager',
                'email' => 'manager@logistics.com',
                'role' => 'manager',
                'branch_id' => 2,
                'password' => Hash::make('password123')
            ],
            [
                'username' => 'secretary',
                'email' => 'secretary@logistics.com',
                'role' => 'secretary',
                'branch_id' => 1,
                'password' => Hash::make('password123')
            ],
        ];

        foreach ($users as $user) {
            DtlrUser::create($user);
        }

        // Create document types
        $documentTypes = [
            ['name' => 'Invoice', 'description' => 'Commercial invoices'],
            ['name' => 'Purchase Order', 'description' => 'Purchase orders'],
            ['name' => 'Delivery Receipt', 'description' => 'Delivery receipts'],
            ['name' => 'Waybill', 'description' => 'Shipping waybills'],
            ['name' => 'Contract', 'description' => 'Business contracts'],
            ['name' => 'Certificate', 'description' => 'Various certificates'],
            ['name' => 'Report', 'description' => 'Business reports'],
            ['name' => 'License', 'description' => 'Business licenses'],
        ];

        foreach ($documentTypes as $type) {
            DtlrDocumentType::create($type);
        }

        // Create sample documents
        for ($i = 1; $i <= 20; $i++) {
            $document = DtlrDocument::create([
                'tracking_number' => 'DTLR-' . date('Y') . '-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'document_type_id' => rand(1, 8),
                'title' => 'Sample Document ' . $i,
                'description' => 'This is a sample document for testing purposes.',
                'file_path' => 'documents/sample_' . $i . '.pdf',
                'status' => ['pending', 'processed', 'approved'][rand(0, 2)],
                'current_branch_id' => rand(1, 5),
                'created_by' => rand(1, 4),
                'updated_by' => rand(1, 4),
            ]);

            // Create sample logs
            DtlrDocumentLog::create([
                'document_id' => $document->id,
                'action' => 'accessed',
                'from_branch_id' => $document->current_branch_id,
                'performed_by' => $document->created_by,
                'notes' => 'Document uploaded and digitized',
                'ip_address' => '192.168.1.' . rand(1, 255)
            ]);

            // Create sample reviews for some documents
            if (rand(0, 1)) {
                DtlrDocumentReview::create([
                    'document_id' => $document->id,
                    'reviewer_id' => rand(1, 4),
                    'review_status' => ['pending', 'approved', 'rejected'][rand(0, 2)],
                    'comments' => 'Sample review comments for document ' . $i,
                    'reviewed_at' => now()->subDays(rand(1, 30))
                ]);
            }
        }

        $this->command->info('DTLR sample data seeded successfully!');
    }
}