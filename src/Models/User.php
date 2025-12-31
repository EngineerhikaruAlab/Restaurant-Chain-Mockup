<?php
declare(strict_types=1);
namespace Models;

use Interfaces\FileConvertible;
use Faker\Factory;
use DateTime;

class User implements FileConvertible {
    protected int $id;
    protected string $firstName;
    protected string $lastName;
    protected string $email;
    protected string $hashedPassword;
    protected string $phoneNumber;
    protected string $address;
    protected DateTime $birthDate;
    protected DateTime $membershipExpirationDate;
    protected string $role;

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
        string $role
    ){
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->phoneNumber = $phoneNumber;
        $this->address = $address;
        $this->birthDate = $birthDate;
        $this->membershipExpirationDate = $membershipExpirationDate;
        $this->role = $role;
    }

    public static function RandomGenerator(): User {
        $faker = Factory::create(); 
        return new self(
            $faker->randomNumber(),
            $faker->firstName,
            $faker->lastName,
            $faker->email,
            "password123",
            $faker->phoneNumber,
            $faker->address,
            $faker->dateTimeBetween('-50 years', '-18 years'),
            $faker->dateTimeBetween('now', '+2 years'),
            $faker->randomElement(['Admin', 'User', 'Manager'])
        );
    }

    // --- FileConvertible の実装

    public function toString(): string {
        return sprintf(
            "User ID: %d\nName: %s %s\nEmail: %s\nPhone: %s\nAddress: %s\nBirth: %s\nExp: %s\nRole: %s\n",
            $this->id, $this->firstName, $this->lastName, $this->email,
            $this->phoneNumber, $this->address,
            $this->birthDate->format('Y-m-d'),
            $this->membershipExpirationDate->format('Y-m-d'),
            $this->role
        );
    }

    public function toHTML(): string {
        return sprintf("
            <div class='user-card'>
                <div class='avatar'>User</div>
                <h2>%s %s</h2>
                <p>Email: %s</p>
                <p>Phone: %s</p>
                <p>Address: %s</p>
                <p>Birth Date: %s</p>
                <p>Membership Expiration: %s</p>
                <p>Role: %s</p>
            </div>",
            htmlspecialchars($this->firstName), // XSS対策
            htmlspecialchars($this->lastName),
            htmlspecialchars($this->email),
            htmlspecialchars($this->phoneNumber),
            htmlspecialchars($this->address),
            $this->birthDate->format('Y-m-d'),
            $this->membershipExpirationDate->format('Y-m-d'),
            htmlspecialchars($this->role)
        );
    }

    public function toMarkdown(): string {
        return "## User: {$this->firstName} {$this->lastName}
- Email: {$this->email}
- Phone: {$this->phoneNumber}
- Address: {$this->address}
- Birth Date: {$this->birthDate->format('Y-m-d')}
- Role: {$this->role}";
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'phoneNumber' => $this->phoneNumber,
            'address' => $this->address,
            'birthDate' => $this->birthDate->format('Y-m-d'),
            'role' => $this->role
        ];
    }

    // --- UML図のメソッド実装 ---

    public function login(string $password): bool {
        return password_verify($password, $this->hashedPassword);
    }

    public function updateProfile(string $address, string $phoneNumber): void {
        $this->address = $address;
        $this->phoneNumber = $phoneNumber;
    }

    public function renewMembership(DateTime $expirationDate): void {
        $this->membershipExpirationDate = $expirationDate;
    }

    public function changePassword(string $newPassword): void {
        $this->hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    }

    public function hasMembershipExpired(): bool {
        return new DateTime() > $this->membershipExpirationDate;
    }
}