<?php

namespace Tests\Unit\Controllers;

use Mockery;
use PHPUnit\Framework\TestCase;
use App\Controllers\LoginController;
use App\Models\User;

class LoginControllerTest extends TestCase
{
    private $loginController;

    protected function setUp(): void
    {
        parent::setUp();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION = []; // Reset session before each test
        $this->loginController = new LoginController();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        $_POST = [];
        $_SESSION = [];
        parent::tearDown();
    }

    public function testLoginWithValidCredentials()
    {
        // Arrange
        $email = 'test@example.com';
        $password = 'password123';
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $mockUser = [
            'id' => 1,
            'email' => $email,
            'password' => $hashedPassword,
            'username' => 'testuser',
            'is_active' => 1
        ];

        $mock = Mockery::mock('alias:App\Models\User');
        $mock->shouldReceive('findByEmail')
            ->once()
            ->with($email)
            ->andReturn($mockUser);

        $_POST['email'] = $email;
        $_POST['password'] = $password;

        // Act
        ob_start(); // Capture output
        $this->loginController->login();
        ob_end_clean(); // Discard output

        // Assert
        $this->assertEquals(1, $_SESSION['user_id']);
        $this->assertEquals('testuser', $_SESSION['username']);
    }

    public function testLoginWithInvalidCredentials()
    {
        // Arrange
        $_SESSION = []; // Ensure session is empty at start
        $email = 'test@example.com';
        $password = 'wrongpassword';

        $mock = Mockery::mock('alias:App\Models\User');
        $mock->shouldReceive('findByEmail')
            ->once()
            ->with($email)
            ->andReturn(null);

        $_POST['email'] = $email;
        $_POST['password'] = $password;

        // Act
        ob_start(); // Capture output
        $this->loginController->login();
        $output = ob_get_clean();

        // Assert
        $this->assertArrayNotHasKey('user_id', $_SESSION);
        $this->assertArrayNotHasKey('username', $_SESSION);
        $this->assertStringContainsString('Email ou mot de passe incorrect', $output);
    }
} 