<script src="<?php echo $basePath; ?>/public/js/comments.js" defer></script>

<main class="pt-20 container mx-auto px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Tous les commentaires</h1>
        <a href="<?php echo $basePath; ?>/detail/<?= $mediaType ?>/<?= $itemId ?>" 
           class="inline-block mt-2 text-red-500 hover:text-red-600">
            <i class="fas fa-arrow-left mr-2"></i>Retour au détail
        </a>
    </div>

    <div class="space-y-6">
        <?php if (empty($comments)): ?>
            <p class="text-gray-400">Aucun commentaire pour le moment.</p>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment-item bg-gray-800 p-4 rounded" data-comment-id="<?= $comment['id'] ?>">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-400 text-sm mb-2">
                                Par <?= htmlspecialchars($comment['username']) ?> • 
                                <?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?>
                            </p>
                            <p class="text-white"><?= htmlspecialchars($comment['content']) ?></p>
                        </div>
                        <?php if (isset($_SESSION['user_id']) && $comment['user_id'] === $_SESSION['user_id']): ?>
                            <button class="delete-comment text-red-500 hover:text-red-700" 
                                    data-comment-id="<?= $comment['id'] ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
