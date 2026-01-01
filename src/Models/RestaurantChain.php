<?php
declare(strict_types=1);

namespace Models;

use Interfaces\FileConvertible;
use Faker\Factory;

class RestaurantChain extends Company implements FileConvertible {
    private int $chainId;
    private array $restaurantLocations;
    private string $cuisineType;
    private int $numberOfLocations;
    private bool $hasDriveThru;
    // yearFounded は親クラス Company の foundingYear を利用
    private string $parentCompany;

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
        int $totalEmployees,
        int $chainId,
        array $restaurantLocations,
        string $cuisineType,
        int $numberOfLocations,
        bool $hasDriveThru,
        string $parentCompany
    ) {
        parent::__construct(
            $name, $foundingYear, $description, $website, $phone,
            $industry, $ceo, $isPubliclyTraded, $country, $founder, $totalEmployees
        );

        $this->chainId = $chainId;
        $this->restaurantLocations = $restaurantLocations;
        $this->cuisineType = $cuisineType;
        $this->numberOfLocations = $numberOfLocations;
        $this->hasDriveThru = $hasDriveThru;
        $this->parentCompany = $parentCompany;
    }

    #仕様2
    public function displayAllLocations(): string {
    $output = "";
    foreach ($this->restaurantLocations as $location) {
        // 各場所の displayLocationInfo() を呼び出して連結
        $output .= $location->displayLocationInfo() . "\n" . str_repeat("-", 20) . "\n";
    }
    return $output;
    }

    public function addLocation(RestaurantLocation $location): void {
        $this->restaurantLocations[] = $location;
        $this->numberOfLocations = count($this->restaurantLocations);
    }

    public function displayChainInfo(): string {
    // 親クラスのメソッドを呼び出して会社情報を取得
    $companyInfo = parent::displayCompanyInfo(); 

    // チェーン独自の情報を付け加える
    return sprintf(
        "%s\nChain ID: %d\nCuisine: %s\nDrive-Thru: %s\nParent Company: %s",
        $companyInfo,
        $this->chainId,
        $this->cuisineType,
        $this->hasDriveThru ? 'Yes' : 'No',
        $this->parentCompany
    );
    }

    #仕様3 RandomGenerator
    public static function RandomGenerator(): RestaurantChain
    {
        $faker = Factory::create();

        $locations = [];
        $locationCount = rand(2, 3);
        for ($i = 0; $i < $locationCount; $i++) {
            $locations [] = RestaurantLocation::RandomGenerator();
        }
        return new self(
            $faker->company . " Group",
            (int)$faker->year,
            $faker->catchPhrase,
            $faker->url,
            $faker->phoneNumber,
            "Restaurant Business",
            $faker->name,
            $faker->boolean,
            $faker->country,
            $faker->name,
            $faker->numberBetween(100, 10000),
            $faker->randomNumber(),
            $locations,
            $faker->randomElement(['Italian', 'Japanese', 'Mexican', 'Fast Food']),
            $locationCount,
            $faker->boolean,
            $faker->company . " Holdings"
        );
    }

    #FileConvertibleの実装
    public function toString(): string {
        return sprintf(
            "Chain: %s (ID: %d)\nCuisine: %s\nLocations: %d\nDrive-Thru: %s\nParent: %s",
            $this->name, $this->chainId, $this->cuisineType,
            $this->numberOfLocations, $this->hasDriveThru ? 'Yes' : 'No',
            $this->parentCompany
        );
    }

    public function toHTML(): string {
        $locationHtml = "";
        foreach ($this->restaurantLocations as $location) {
            $locationHtml .= $location->toHTML();
        }

        return sprintf(
            "<div class='chain-container'>
                <h1>%s</h1>
                <div class='chain-meta'>
                    <p><strong>Cuisine:</strong> %s</p>
                    <p><strong>Drive-Thru:</strong> %s</p>
                    <p><strong>Parent Company:</strong> %s</p>
                </div>
                %s <div class='locations-list'>
                    %s
                </div>
            </div>",
            htmlspecialchars($this->name),
            htmlspecialchars($this->cuisineType),
            $this->hasDriveThru ? 'Yes' : 'No',
            htmlspecialchars($this->parentCompany),
            parent::toHTML(),
            $locationHtml
        );
    }

    public function toMarkdown(): string {
        return "# {$this->name}\n- Cuisine: {$this->cuisineType}\n- Parent: {$this->parentCompany}";
    }

    public function toArray(): array {
        return array_merge(parent::toArray(), [
            'chainId' => $this->chainId,
            'cuisineType' => $this->cuisineType,
            'hasDriveThru' => $this->hasDriveThru,
            'parentCompany' => $this->parentCompany,
            'locations' => array_map(fn($l) => $l->toArray(), $this->restaurantLocations)
        ]);
    }
}
