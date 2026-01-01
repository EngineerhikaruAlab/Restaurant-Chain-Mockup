<?php
declare(strict_types=1);

namespace Models;

use Interfaces\FileConvertible;
use Faker\Factory;

class Company implements FileConvertible {
    protected string $name;
    protected int $foundingYear;
    protected string $description;
    protected string $website;
    protected string $phone;
    protected string $industry;
    protected string $ceo;
    protected bool $isPubliclyTraded;
    protected string $country;
    protected string $founder;
    protected int $totalEmployees;

    public function __construct(
        string $name,
        int $foundingYear,
        string $description,
        string $website,
        string $phone,
        string $industry,
        string $ceo,
        bool $isPubliclyTraded,
        string $country,
        string $founder,
        int $totalEmployees
    ){
        $this->name = $name;
        $this->foundingYear = $foundingYear;
        $this->description = $description;
        $this->website = $website;
        $this->phone = $phone;
        $this->industry = $industry;
        $this->ceo = $ceo;
        $this->isPubliclyTraded = $isPubliclyTraded;
        $this->country = $country;
        $this->founder = $founder;
        $this->totalEmployees = $totalEmployees;
    }

    #仕様2
    public function displayCompanyInfo(): string {
        return sprintf(
            "Company: %s\nFounded: %d\nDescription: %s\nWebsite: %s\nPhone: %s\nIndustry: %s\nCEO: %s\nPublicly Traded: %s",
            $this->name,
            $this->foundingYear,
            $this->description,
            $this->website,
            $this->phone,
            $this->industry,
            $this->ceo,
            $this->isPubliclyTraded ? 'Yes' : 'No'
        );
    }

    #仕様3 RandomGenerator
    public static function RandomGenerator(): Company {
        $faker = Factory::create();

        return new self(
            $faker->company,
            (int)$faker->year,
            $faker->catchPhrase,
            $faker->url,
            $faker->phoneNumber,
            $faker->jobTitle . " Industry", // 業界名
            $faker->name,
            $faker->boolean,
            $faker->country,
            $faker->name,
            $faker->numberBetween(10, 5000)
        );
    }

    #FileConvertibleの実装
    public function toString(): string {
        return sprintf(
            "Company: %s\nIndustry: %s\nCEO: %s\nFounded: %d",
            $this->name,
            $this->industry,
            $this->ceo,
            $this->foundingYear
        );
    }

    public function toHTML(): string {
        return sprintf(
            "<div class='company-card'>
                <h2>%s</h2>
                <p><strong>CEO:</strong> %s</p>
                <p><strong>Industry:</strong> %s</p>
                <p><strong>Description:</strong> %s</p>
                <p><strong>Website:</strong> <a href='%s'>%s</a></p>
                <p><strong>Publicly Traded:</strong> %s</p>
            </div>",
            htmlspecialchars($this->name),
            htmlspecialchars($this->ceo),
            htmlspecialchars($this->industry),
            htmlspecialchars($this->description),
            htmlspecialchars($this->website),
            htmlspecialchars($this->website),
            $this->isPubliclyTraded ? 'Yes' : 'No'
        );
    }

    public function toMarkdown(): string {
        return "## Company: {$this->name}\n- CEO: {$this->ceo}\n- Industry: {$this->industry}\n- Founded: {$this->foundingYear}";
    }

    public function toArray(): array {
        return get_object_vars($this);
    }
}