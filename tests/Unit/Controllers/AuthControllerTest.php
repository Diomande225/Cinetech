<?php

namespace Tests\Unit\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\LoginController;
use App\Models\User;

class LoginControllerTest extends TestCase
{
    private $loginController;
    private $userModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userModel = $this->createMock(User::class);
        $this->loginController = new LoginController($this->userModel);
    }

    public function testLoginWithValidCredentials()
    {
        // Arrange
        $email = 'test@example.com';
        $password = 'password123';
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $this->userModel->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn([
                'id' => 1,
                'email' => $email,
                'password' => $hashedPassword
            ]);

        // Act
        $result = $this->loginController->login($email, $password);

        // Assert
        $this->assertTrue($result);
    }

    public function testLoginWithInvalidCredentials()
    {
        // Arrange
        $email = 'test@example.com';
        $password = 'wrongpassword';

        $this->userModel->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn(null);

        // Act
        $result = $this->loginController->login($email, $password);

        // Assert
        $this->assertFalse($result);
    }
} 