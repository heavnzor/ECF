<?php

namespace App\Factory;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Formation>
 *
 * @method static Formation|Proxy createOne(array $attributes = [])
 * @method static Formation[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Formation|Proxy find(object|array|mixed $criteria)
 * @method static Formation|Proxy findOrCreate(array $attributes)
 * @method static Formation|Proxy first(string $sortedField = 'id')
 * @method static Formation|Proxy last(string $sortedField = 'id')
 * @method static Formation|Proxy random(array $attributes = [])
 * @method static Formation|Proxy randomOrCreate(array $attributes = [])
 * @method static Formation[]|Proxy[] all()
 * @method static Formation[]|Proxy[] findBy(array $attributes)
 * @method static Formation[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Formation[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static FormationRepository|RepositoryProxy repository()
 * @method Formation|Proxy create(array|callable $attributes = [])
 */
final class FormationFactory extends ModelFactory
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
            'description' => self::faker()->text(),
            'image' => self::faker()->text(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Formation $formation): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Formation::class;
    }
}
