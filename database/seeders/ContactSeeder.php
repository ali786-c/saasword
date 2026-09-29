<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Contact\Enums\ContactStatusEnum;
use Botble\Contact\Models\Contact;
use Illuminate\Support\Arr;

class ContactSeeder extends BaseSeeder
{
    public function run(): void
    {
        Contact::query()->truncate();

        $names = [
            'John Smith',
            'Emily Johnson',
            'Michael Brown',
            'Sarah Davis',
            'David Wilson',
            'Jessica Taylor',
            'Christopher Anderson',
            'Amanda Thomas',
            'Matthew Martinez',
            'Ashley Garcia',
        ];

        $emails = [
            'john.smith@example.com',
            'emily.johnson@example.com',
            'michael.brown@example.com',
            'sarah.davis@example.com',
            'david.wilson@example.com',
            'jessica.taylor@example.com',
            'chris.anderson@example.com',
            'amanda.thomas@example.com',
            'matt.martinez@example.com',
            'ashley.garcia@example.com',
        ];

        $phones = [
            '(555) 123-4567',
            '(555) 234-5678',
            '(555) 345-6789',
            '(555) 456-7890',
            '(555) 567-8901',
            '(555) 678-9012',
            '(555) 789-0123',
            '(555) 890-1234',
            '(555) 901-2345',
            '(555) 012-3456',
        ];

        $addresses = [
            '123 Main Street, New York, NY 10001',
            '456 Oak Avenue, Los Angeles, CA 90001',
            '789 Pine Road, Chicago, IL 60601',
            '321 Elm Boulevard, Houston, TX 77001',
            '654 Maple Drive, Phoenix, AZ 85001',
            '987 Cedar Lane, Philadelphia, PA 19101',
            '147 Birch Court, San Antonio, TX 78201',
            '258 Walnut Street, San Diego, CA 92101',
            '369 Spruce Way, Dallas, TX 75201',
            '741 Willow Place, San Jose, CA 95101',
        ];

        $subjects = [
            'Question about your services',
            'Partnership inquiry',
            'Request for more information',
            'Feedback on recent experience',
            'Technical support needed',
            'Pricing and availability',
            'General inquiry',
            'Collaboration opportunity',
            'Product question',
            'Service feedback',
        ];

        $contents = [
            'I would like to learn more about your services and how they can benefit my business. Could you please send me additional information or schedule a call to discuss further?',
            'We are interested in exploring a potential partnership with your company. Our organization has been looking for reliable partners in this industry, and we believe there could be mutual benefits.',
            'I recently came across your website and was impressed by what I saw. I have a few questions about your offerings and would appreciate if someone could get back to me at their earliest convenience.',
            'Thank you for the excellent service I received recently. The team was professional and helpful throughout the entire process. I wanted to share my positive experience.',
            'I am experiencing some issues with the product I purchased and would like to request technical assistance. The problem started a few days ago and I have tried several troubleshooting steps.',
            'Could you please provide me with detailed pricing information for your premium services? I am comparing different options and would like to make an informed decision.',
            'I have a general question about your company policies and procedures. It would be great if you could clarify some points that I found on your website.',
            'Our team is looking for collaboration opportunities in this field. We believe that working together could lead to innovative solutions and mutual growth.',
            'I purchased one of your products last month and have a question about its features. The documentation was helpful but I need some clarification on a specific functionality.',
            'I wanted to provide some feedback on the service I received. Overall, it was a good experience, but there are a few areas where I think improvements could be made.',
        ];

        for ($i = 0; $i < 10; $i++) {
            Contact::query()->create([
                'name' => $names[$i],
                'email' => $emails[$i],
                'phone' => $phones[$i],
                'address' => $addresses[$i],
                'subject' => $subjects[$i],
                'content' => $contents[$i],
                'status' => Arr::random([ContactStatusEnum::READ, ContactStatusEnum::UNREAD]),
            ]);
        }
    }
}
