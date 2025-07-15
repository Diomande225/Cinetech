<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\MovieModel;
use PDO;

class MovieModelTest extends TestCase
{
    private $movieModel;
    private $pdo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pdo = $this->createMock(PDO::class);
        $this->movieModel = new MovieModel($this->pdo);
    }

    public function testGetMovieById()
    {
        // Arrange
        $movieId = 1;
        $expectedMovie = [
            'id' => $movieId,
            'title' => 'Test Movie',
            'description' => 'Test Description',
            'release_date' => '2024-01-01'
        ];

        $pdoStatement = $this->createMock(\PDOStatement::class);
        $pdoStatement->expects($this->once())
            ->method('fetch')
            ->willReturn($expectedMovie);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($pdoStatement);

        // Act
        $result = $this->movieModel->getById($movieId);

        // Assert
        $this->assertEquals($expectedMovie, $result);
    }

    public function testGetAllMovies()
    {
        // Arrange
        $expectedMovies = [
            [
                'id' => 1,
                'title' => 'Movie 1',
                'description' => 'Description 1',
                'release_date' => '2024-01-01'
            ],
            [
                'id' => 2,
                'title' => 'Movie 2',
                'description' => 'Description 2',
                'release_date' => '2024-01-02'
            ]
        ];

        $pdoStatement = $this->createMock(\PDOStatement::class);
        $pdoStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expectedMovies);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($pdoStatement);

        // Act
        $result = $this->movieModel->getAll();

        // Assert
        $this->assertEquals($expectedMovies, $result);
    }
} 