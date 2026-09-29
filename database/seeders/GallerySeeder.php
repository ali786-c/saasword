<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Gallery\Models\Gallery as GalleryModel;
use Botble\Gallery\Models\GalleryMeta;
use Botble\Slug\Facades\SlugHelper;
use Botble\Slug\Models\Slug;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class GallerySeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('galleries');

        GalleryModel::query()->truncate();
        GalleryMeta::query()->truncate();

        $descriptions = [
            'A stunning collection of carefully curated images that capture the essence of beauty and creativity.',
            'Explore breathtaking visuals that tell a story and inspire the imagination.',
            'A vibrant showcase of artistry and passion through captivating photography.',
            'Discover moments frozen in time, each image revealing a unique perspective.',
            'An inspiring gallery featuring exceptional photography and visual storytelling.',
            'Beautiful imagery that celebrates the art of photography and creative expression.',
            'A curated selection of photographs that evoke emotion and spark inspiration.',
            'Witness the beauty of the world through these carefully selected images.',
            'An artistic journey through light, color, and composition in visual form.',
        ];

        $galleries = [
            [
                'name' => 'Perfect',
            ],
            [
                'name' => 'New Day',
            ],
            [
                'name' => 'Happy Day',
            ],
            [
                'name' => 'Nature',
            ],
            [
                'name' => 'Morning',
            ],
            [
                'name' => 'Photography',
            ],
        ];

        $images = [];
        for ($i = 0; $i < 9; $i++) {
            $images[] = [
                'img' => 'galleries/' . ($i + 1) . '.jpg',
                'description' => $descriptions[$i],
            ];
        }

        foreach ($galleries as $index => $item) {
            $item['description'] = Arr::random($descriptions);
            $item['image'] = 'galleries/' . ($index + 1) . '.jpg';
            $item['user_id'] = 1;
            $item['is_featured'] = true;

            $gallery = GalleryModel::query()->create($item);

            Slug::query()->create([
                'reference_type' => GalleryModel::class,
                'reference_id' => $gallery->id,
                'key' => Str::slug($gallery->name),
                'prefix' => SlugHelper::getPrefix(GalleryModel::class),
            ]);

            GalleryMeta::query()->create([
                'images' => $images,
                'reference_id' => $gallery->id,
                'reference_type' => GalleryModel::class,
            ]);
        }
    }
}
