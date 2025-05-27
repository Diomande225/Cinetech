<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\FavoriteService;
use App\Models\FavoriteModel;

class FavoriteServiceTest extends TestCase
{
    private $favoriteService;
    private $favoriteModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->favoriteModel = $this->createMock(FavoriteModel::class);
        $this->favoriteService = new FavoriteService($this->favoriteModel);
    }

    public function testAddFavorite()
    {
        // Arrange
        $userId = 1;
        $movieId = 2;

        $this->favoriteModel->expects($this->once())
            ->method('add')
            ->with($userId, $movieId)
            ->willReturn(true);

        // Act
        $result = $this->favoriteService->addFavorite($userId, $movieId);

        // Assert
        $this->assertTrue($result);
    }

    public function testRemoveFavorite()
    {
        // Arrange
        $userId = 1;
        $movieId = 2;

        $this->favoriteModel->expects($this->once())
            ->method('remove')
            ->with($userId, $movieId)
            ->willReturn(true);

        // Act
        $result = $this->favoriteService->removeFavorite($userId, $movieId);

        // Assert
        $this->assertTrue($result);
    }

    public function testGetUserFavorites()
    {
        // Arrange
        $userId = 1;
        $expectedFavorites = [
            [
                'id' => 1,
                'movie_id' => 2,
                'user_id' => $userId
            ],
            [
                'id' => 2,
                'movie_id' => 3,
                'user_id' => $userId
            ]
        ];

        $this->favoriteModel->expects($this->once())
            ->method('getUserFavorites')
            ->with($userId)
            ->willReturn($expectedFavorites);

        // Act
        $result = $this->favoriteService->getUserFavorites($userId);

        // Assert
        $this->assertEquals($expectedFavorites, $result);
    }
} 