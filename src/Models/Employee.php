<?php
declare(strict_types=1);
namespace Models;

use Interfaces\FileConvertible;
use Faker\Factory;
use DateTime;

class Employee extends User implements FileConvertible{
    private string $jobTitle;
    private float $salary;
    private string $startDate;
    private array $awards;

    public function __construct(
        int $id,
        string $firstName,
        string $lastName,
        string $email,
        string $password,
        string $phoneNumber,
        string $address,
        DateTime $birthDate,
        DateTime $membershipExpirationDate,
        string $role,
        string $jobTitle,
        float $salary,
        string $startDate,
        array $awards
    ){
        parent::__construct(
            $id, $firstName, $lastName, $email, $password,
            $phoneNumber, $address, $birthDate, $membershipExpirationDate, $role
        );
        $this->jobTitle = $jobTitle;
        $this->salary = $salary;
        $this->startDate = $startDate;
        $this->awards = $awards;
    }
    
    #仕様2
    public function displayEmployeeInfo(): string {
        return sprintf("Job Title: %s, Salary: $%.2f, Start Date: %s, Awards: %s",
        $this->jobTitle,
        $this->salary,
        $this->startDate,
        implode(", ", $this->awards)
        );
    }

    #仕様3 RandomGenerator
    public static function RandomGenerator(): Employee
    {
        $faker = Factory::create();

        return new self(
            $faker->randomNumber(),
            $faker->firstName,
            $faker->lastName,
            $faker->email,
            "password123",
            $faker->phoneNumber,
            $faker->address,
            $faker->dateTimeBetween('-60 years', '-20 years'),
            $faker->dateTimeBetween('now', '+2 years'),
            'Employee',
            $faker->jobTitle,
            $faker->randomFloat(2, 3000, 10000), // $3,000 ~ $10,000
            $faker->date('Y-m-d'),
            [$faker->word . " Award", $faker->word . " Award"]
        );
    }

    #FileConvertibleの実装
    public function toString(): string
    {
        return parent::toString() . "\n" . $this->displayEmployeeInfo();
    }

    public function toHTML(): string {
        // 親のHTML（User情報）を呼び出し、そこに従業員情報を追加する設計です
        $userHtml = parent::toHTML();
        
        return sprintf(
            "<div class='employee-card'>
                %s
                <div class='employee-info'>
                    <p><strong>Job:</strong> %s</p>
                    <p><strong>Salary:</strong> $%.2f</p>
                    <p><strong>Start Date:</strong> %s</p>
                    <p><strong>Awards:</strong> %s</p>
                </div>
            </div>",
            $userHtml,
            htmlspecialchars($this->jobTitle),
            $this->salary,
            htmlspecialchars($this->startDate),
            htmlspecialchars(implode(", ", $this->awards))
        );
    }

    public function toMarkdown(): string {
        return parent::toMarkdown() . "\n- **Job**: {$this->jobTitle}\n- **Salary**: \${$this->salary}";
    }

    public function toArray(): array {
        return array_merge(parent::toArray(), [
            'jobTitle' => $this->jobTitle,
            'salary' => $this->salary,
            'startDate' => $this->startDate,
            'awards' => $this->awards
        ]);
    }
}