<?php

namespace App\Factory;

use App\Entity\Quizz;
use App\Repository\QuizzRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Quizz>
 *
 * @method static Quizz|Proxy createOne(array $attributes = [])
 * @method static Quizz[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Quizz|Proxy find(object|array|mixed $criteria)
 * @method static Quizz|Proxy findOrCreate(array $attributes)
 * @method static Quizz|Proxy first(string $sortedField = 'id')
 * @method static Quizz|Proxy last(string $sortedField = 'id')
 * @method static Quizz|Proxy random(array $attributes = [])
 * @method static Quizz|Proxy randomOrCreate(array $attributes = [])
 * @method static Quizz[]|Proxy[] all()
 * @method static Quizz[]|Proxy[] findBy(array $attributes)
 * @method static Quizz[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Quizz[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static QuizzRepository|RepositoryProxy repository()
 * @method Quizz|Proxy create(array|callable $attributes = [])
 */
final class QuizzFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'question1' => self::faker()->text(),
            'question2' => self::faker()->text(),
            'reponse1' => self::faker()->text(),
            'reponse2' => self::faker()->text(),
            'reponse3' => self::faker()->text(),
            'reponse4' => self::faker()->text(),
            'bonnereponse1' => self::faker()->text(),
            'bonnereponse2' => self::faker()->text(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Quizz $quizz): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Quizz::class;
    }
}
