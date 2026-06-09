<?php

namespace Database\Seeders;

use App\Models\DocumentTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DocumentTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Clinical Associate Employment Contract',
                'type' => 'employment_contract',
                'file' => 'TeleDoctorSA_Clinical_Associate_Employment_Contract.docx',
                'description' => 'Employment contract template for Clinical Associate roles.',
                'fields' => ['salary', 'start_date', 'manager_name'],
            ],
            [
                'name' => 'Corporate Payslip Template',
                'type' => 'payslip',
                'file' => 'TeleDoctorSA_Corporate_Payslip_Template.docx',
                'description' => 'Corporate payslip template.',
                'fields' => ['salary', 'allowances', 'deductions', 'tax', 'net_pay'],
            ],
            [
                'name' => 'Employment Offer Letter',
                'type' => 'offer_letter',
                'file' => 'TeleDoctorSA_Employment_Offer_Letter_Template.docx',
                'description' => 'Standard employment offer letter template.',
                'fields' => ['salary', 'start_date', 'manager_name', 'offer_expiry_date'],
            ],
            [
                'name' => 'Policy Acknowledgement Form',
                'type' => 'policy_acknowledgement',
                'file' => 'TeleDoctorSA_Policy_Acknowledgement_Form.docx',
                'description' => 'Policy acknowledgement form for employees.',
                'fields' => ['policy_name', 'acknowledgement_date'],
            ],
            [
                'name' => 'Practice Manager Employment Contract',
                'type' => 'employment_contract',
                'file' => 'TeleDoctorSA_Practice_Manager_Employment_Contract.docx',
                'description' => 'Employment contract template for Practice Manager roles.',
                'fields' => ['salary', 'start_date', 'manager_name'],
            ],
            [
                'name' => 'Salary Confirmation Letter',
                'type' => 'salary_confirmation',
                'file' => 'TeleDoctorSA_Salary_Confirmation_Letter_Template.docx',
                'description' => 'Salary confirmation letter template.',
                'fields' => ['salary', 'reason', 'recipient_name'],
            ],
            [
                'name' => 'Supervising Doctor Employment Contract',
                'type' => 'employment_contract',
                'file' => 'TeleDoctorSA_Supervising_Doctor_Employment_Contract.docx',
                'description' => 'Employment contract template for Supervising Doctor roles.',
                'fields' => ['salary', 'start_date', 'manager_name'],
            ],
            [
                'name' => 'Clinical Associate Full-Time Contract',
                'type' => 'employment_contract',
                'file' => 'TeleDoctor_Clinical_Associate_Full_Time_Contract.docx',
                'description' => 'Full-time contract for Clinical Associate roles.',
                'fields' => ['salary', 'start_date', 'manager_name'],
            ],
            [
                'name' => 'Practice Manager Full-Time Contract',
                'type' => 'employment_contract',
                'file' => 'TeleDoctor_Practice_Manager_Full_Time_Contract.docx',
                'description' => 'Full-time contract for Practice Manager roles.',
                'fields' => ['salary', 'start_date', 'manager_name'],
            ],
            [
                'name' => 'Supervising Doctor Full-Time Contract',
                'type' => 'employment_contract',
                'file' => 'TeleDoctor_Supervising_Doctor_Full_Time_Contract.docx',
                'description' => 'Full-time contract for Supervising Doctor roles.',
                'fields' => ['salary', 'start_date', 'manager_name'],
            ],
        ];

        foreach ($templates as $template) {
            $path = 'document-templates/originals/' . $template['file'];

            if (! Storage::disk('public')->exists($path)) {
                continue;
            }

            DocumentTemplate::updateOrCreate(
                [
                    'name' => $template['name'],
                ],
                [
                    'type' => $template['type'],
                    'description' => $template['description'],
                    'content' => '',
                    'file_path' => $path,
                    'file_original_name' => $template['file'],
                    'file_mime_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'fields' => $template['fields'],
                    'is_active' => true,
                ]
            );
        }
    }
}