<?php
declare(strict_types=1);

namespace Models;

use Interfaces\FileConvertible;
use Faker\Factory;

class RestaurantLocation implements FileConvertible {
    private string $name;
    private string $address;
    private string $city;
    private string $state;
    private string $zipCode;
    private array $employees;

    public function __construct(
        string $name,
        string $address,
        string $city,
        string $state,
        string $zipCode,
        array $employees
    ){
        $this->name = $name;
        $this->address = $address;
        $this->city = $city;
        $this->state = $state;
        $this->zipCode = $zipCode;
        $this->employees = $employees;
    }

    #仕様2
    public function displayLocationInfo(): string {
        return sprintf(
            "Location: %s\nAddress: %s, %s, %s %s",
            $this->name,
            $this->address,
            $this->city,
            $this->state,
            $this->zipCode
        );
    }

    #仕様3
    public static function RandomGenerator(): RestaurantLocation {
        $faker = Factory::create();
        $employees = [];
        $employeeCount = rand(2, 5);
        for ($i = 0; $i < $employeeCount; $i++) {
            $employees[] = Employee::RandomGenerator();
        }
        return new self(
            $faker->company . " Branch",
            $faker->streetAddress,
            $faker->city,
            $faker->state,
            $faker->postcode,
            $employees
        );
    }

    public function toString(): string {
        $output = $this->displayLocationInfo() . "\nEmployees:\n";
        foreach ($this->employees as $employee) {
            $output .= "- " . $employee->toString() . "\n";
        }
        return $output;
    }

    public function toHTML(): string {
        $employeeHtml = "";
        foreach ($this->employees as $employee) {
            $employeeHtml .= $employee->toHTML();
        }

        return sprintf(
            "<div class='location-card'>
                <h2>%s</h2>
                <p>%s, %s, %s %s</p>
                <h3>Employees</h3>
                <div class='employee-list'>
                    %s
                </div>
            </div>",
            htmlspecialchars($this->name),
            htmlspecialchars($this->address),
            htmlspecialchars($this->city),
            htmlspecialchars($this->state),
            htmlspecialchars($this->zipCode),
            $employeeHtml
        );
    }

    public function toMarkdown(): string {
        $markdown = "## Location: {$this->name}\n- Address: {$this->address}, {$this->city}, {$this->state} {$this->zipCode}\n\n### Employees\n";
        foreach ($this->employees as $employee) {
            $markdown .= $employee->toMarkdown() . "\n";
        }
        return $markdown;
    }

    public function toArray(): array {
        return [
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zipCode' => $this->zipCode,
            'employees' => array_map(fn($e) => $e->toArray(), $this->employees)
        ];
    }
}