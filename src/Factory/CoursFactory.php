<?php

namespace App\Factory;

use App\Entity\Cours;
use App\Repository\CoursRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Cours>
 *
 * @method static Cours|Proxy createOne(array $attributes = [])
 * @method static Cours[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Cours|Proxy find(object|array|mixed $criteria)
 * @method static Cours|Proxy findOrCreate(array $attributes)
 * @method static Cours|Proxy first(string $sortedField = 'id')
 * @method static Cours|Proxy last(string $sortedField = 'id')
 * @method static Cours|Proxy random(array $attributes = [])
 * @method static Cours|Proxy randomOrCreate(array $attributes = [])
 * @method static Cours[]|Proxy[] all()
 * @method static Cours[]|Proxy[] findBy(array $attributes)
 * @method static Cours[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Cours[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static CoursRepository|RepositoryProxy repository()
 * @method Cours|Proxy create(array|callable $attributes = [])
 */
final class CoursFactory extends ModelFactory
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
            'titre' => self::faker()->text(),
            'cours' => self::faker()->text(),
            'video' => self::faker()->text(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Cours $cours): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Cours::class;
    }
}
