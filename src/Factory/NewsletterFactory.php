<?php

namespace App\Factory;

use App\Entity\Newsletter;
use App\Repository\NewsletterRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Newsletter>
 *
 * @method static Newsletter|Proxy createOne(array $attributes = [])
 * @method static Newsletter[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Newsletter|Proxy find(object|array|mixed $criteria)
 * @method static Newsletter|Proxy findOrCreate(array $attributes)
 * @method static Newsletter|Proxy first(string $sortedField = 'id')
 * @method static Newsletter|Proxy last(string $sortedField = 'id')
 * @method static Newsletter|Proxy random(array $attributes = [])
 * @method static Newsletter|Proxy randomOrCreate(array $attributes = [])
 * @method static Newsletter[]|Proxy[] all()
 * @method static Newsletter[]|Proxy[] findBy(array $attributes)
 * @method static Newsletter[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Newsletter[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static NewsletterRepository|RepositoryProxy repository()
 * @method Newsletter|Proxy create(array|callable $attributes = [])
 */
final class NewsletterFactory extends ModelFactory
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
            'email' => self::faker()->text(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Newsletter $newsletter): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Newsletter::class;
    }
}
