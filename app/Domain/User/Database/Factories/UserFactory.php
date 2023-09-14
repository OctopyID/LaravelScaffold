<?php

namespace App\Domain\User\Database\Factories;

use App\Domain\Category\Models\Category;
use App\Domain\Category\Pipes\CreateInitialCategory;
use App\Domain\Source\Models\Source;
use App\Domain\Transaction\Models\Transaction;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'name'        => fake()->name(),
            'email'       => fake()->unique()->safeEmail(),
            'password'    => bcrypt('secret'),
            'verified_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified() : self
    {
        return $this->state(fn(array $attributes) => [
            'verified_at' => null,
        ]);
    }

    /**
     * @return self
     */
    public function dummy() : self
    {
        return $this->sources(2)->categories()->transactions(10);
    }

    /**
     * @param  int $count
     * @return self
     */
    public function sources(int $count = 2) : self
    {
        return $this->has(Source::factory()->count($count)->sequence(
            ['type' => 'CASH'],
            ['type' => 'BANK'],
        ));
    }

    /**
     * @param  int $count
     * @return self
     */
    public function transactions(int $count = 1) : self
    {
        return $this->has(
            Transaction::factory()->count($count)->state(function (array $attributes, User $user) {
                if ($attributes['type'] !== Category::TYPE_TF) {
                    return [
                        'from_source_id' => $user->sources->random()->getKey(),
                    ];
                }

                return [
                    'dest_source_id' => $user->sources[0]->getKey(),
                    'from_source_id' => $user->sources[1]->getKey(),
                ];
            })
        );
    }

    /**
     * @param  int $count
     * @return self
     */
    public function categories(int $count = 15) : self
    {
        return $this->has(Category::factory()->count($count)->sequence(
            ['type' => Category::TYPE_EX],
            ['type' => Category::TYPE_IN],
            ['type' => Category::TYPE_TF],
        ));
    }

    /**
     * @return self
     */
    public function configure() : self
    {
        return $this->afterCreating(function (User $user) {
            $user->transactions->each(function (Transaction $transaction) {
                if ($transaction->type !== Category::TYPE_TF) {
                    $transaction->update([
                        'category_id' => Category::owner($transaction->user)->type($transaction->type)->inRandomOrder()->first()->getKey(),
                    ]);
                }
            });
        });
    }
}
