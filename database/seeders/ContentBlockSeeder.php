<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContentBlock;

class ContentBlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $content_blocks = [
            [
                'key' => 'hero_title',
                'type' => 'text',
                'content' => 'Business with Barakah',
                'page' => 'homepage',
                'section' => 'hero',
                'order' => 1,
            ],
            [
                'key' => 'hero_subtitle',
                'type' => 'text',
                'content' => 'Promoting halal, ethical, and impactful entrepreneurship through UNISSA\'s Tijarah Co.',
                'page' => 'homepage',
                'section' => 'hero',
                'order' => 2,
            ],
            [
                'key' => 'hero_background_image',
                'type' => 'text',
                'content' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1600&q=80',
                'page' => 'homepage',
                'section' => 'hero',
                'order' => 3,
            ],
            [
                'key' => 'about_content',
                'type' => 'html',
                'content' => '<p>Welcome to TIJARAH CO SDN BHD, established under UNISSA, is dedicated to fostering entrepreneurship, innovation, and halal trade. We provide a platform for students, alumni, and the community to develop businesses, showcase products, and grow sustainably in line with Islamic values.</p>',
                'page' => 'homepage',
                'section' => 'about',
                'order' => 1,
            ],
            [
                'key' => 'about_logo',
                'type' => 'text',
                'content' => 'images/tijarahco_sdn_bhd.png',
                'page' => 'homepage',
                'section' => 'about',
                'order' => 2,
            ],
            [
                'key' => 'services_title',
                'type' => 'text',
                'content' => 'Our Services',
                'page' => 'homepage',
                'section' => 'services',
                'order' => 1,
            ],
            [
                'key' => 'services_description',
                'type' => 'html',
                'content' => '<p>We provide comprehensive business solutions designed to accelerate growth and maximize potential.</p>',
                'page' => 'homepage',
                'section' => 'services',
                'order' => 2,
            ],
            [
                'key' => 'contact_title',
                'type' => 'text',
                'content' => 'Get In Touch',
                'page' => 'homepage',
                'section' => 'contact',
                'order' => 1,
            ],
            [
                'key' => 'contact_address',
                'type' => 'html',
                'content' => 'Universiti Islam Sultan Sharif Ali<br>Simpang 347, Jalan Pasar Gadong<br>Bandar Seri Begawan, Brunei',
                'page' => 'homepage',
                'section' => 'contact',
                'order' => 2,
            ],
            [
                'key' => 'contact_phone',
                'type' => 'text',
                'content' => '+673 123 4567',
                'page' => 'homepage',
                'section' => 'contact',
                'order' => 3,
            ],
            [
                'key' => 'contact_email',
                'type' => 'text',
                'content' => 'tijarahco@unissa.edu.bn',
                'page' => 'homepage',
                'section' => 'contact',
                'order' => 4,
            ],
            [
                'key' => 'contact_hours',
                'type' => 'html',
                'content' => 'Mon-Thu & Sat<br>9:00am - 4:30pm',
                'page' => 'homepage',
                'section' => 'contact',
                'order' => 5,
            ],
            [
                'key' => 'contact_content',
                'type' => 'html',
                'content' => '<p>Ready to transform your business? Get in touch with our team to explore how we can help you achieve your goals.</p>',
                'page' => 'homepage',
                'section' => 'contact',
                'order' => 6,
            ],
        ];

        foreach ($content_blocks as $block) {
            ContentBlock::updateOrCreate(
                ['key' => $block['key'], 'page' => $block['page']],
                $block
            );
        }
    }
}
