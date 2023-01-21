<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = Str::random(10);
		return [
			'user_id' => 1,
			'category_id' => fake()->numberBetween(1, 2),
			'imagePath' => 'storage/postsImages/ht0MahuH0FfTaMnZ8el5baNiN8V7a15TZPFbfRv0.jpg',
			'title' => $title,
			'slug' => Str::slug($title, '-'),
			'body' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Qui, et odit! Alias commodi a similique dignissimos cum doloremque veniam dolores, aliquid, cupiditate atque dolorum dolore.

            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Assumenda beatae laboriosam recusandae, molestias aperiam rerum repellendus esse ratione atque nam a possimus, ipsum provident amet? Exercitationem, quod inventore, soluta reprehenderit magni ratione incidunt impedit ut, assumenda magnam officia placeat porro atque! Molestias tenetur recusandae quod commodi facilis. Officia, deleniti suscipit.
            
            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ad placeat cupiditate magnam vitae laudantium eaque beatae nostrum dolores veniam nobis necessitatibus omnis dolorum deserunt natus eveniet saepe quibusdam, cum culpa non voluptatem quidem, quod repudiandae magni inventore! Natus commodi excepturi tenetur vero nostrum quaerat laborum explicabo nesciunt ducimus deleniti quae dolorem soluta error deserunt accusamus, impedit nisi pariatur unde, veniam iusto. Facere quibusdam ea ipsa fuga explicabo repellat odio iure debitis quasi perspiciatis architecto ab rem sunt deleniti esse illo amet autem sapiente temporibus quis qui, eum magni. Nemo, quo optio! Sunt numquam molestias, atque et error natus voluptas quos?
            
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Inventore sit, quae ipsa, aut aliquid sed laborum necessitatibus, libero velit repellendus animi nostrum nemo corrupti sapiente debitis quas eum recusandae hic.',
		];
    }
}
