<script src="/public/js/comments.js" defer></script>

<section class="comments-section mt-8 px-8">
    <?php if (!isset($itemId) || !isset($itemType)): ?>
        <div class="text-red-500">Erreur: Paramètres manquants pour l'affichage des commentaires</div>
        <?php
        error_log("Variables manquantes dans comments.php:");
        error_log("itemId: " . (isset($itemId) ? $itemId : 'non défini'));
        error_log("itemType: " . (isset($itemType) ? $itemType : 'non défini'));
        ?>
    <?php else: ?>
        <?php if (isset($_SESSION['user_id'])): ?>
            <form id="comment-form" class="mb-6" 
                  data-item-id="<?= $itemId ?>" 
                  data-item-type="<?= $itemType ?>">
                <textarea 
                    class="w-full p-4 rounded bg-gray-700 text-white resize-none focus:outline-none focus:ring-2 focus:ring-red-500"
                    rows="3"
                    placeholder="Partagez votre avis..."
                    required
                ></textarea>
                <button type="submit" class="mt-2 px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition duration-300">
                    Publier
                </button>
            </form>
        <?php else: ?>
            <div class="bg-gray-800 p-4 rounded mb-6">
                <p class="text-gray-400">
                    <a href="/Login" class="text-red-500 hover:underline">Connectez-vous</a> 
                    pour laisser un commentaire.
                </p>
            </div>
        <?php endif; ?>

        <div id="comments-container" class="space-y-6">
            <?php if (empty($comments)): ?>
                <p class="text-gray-400">Aucun commentaire pour le moment.</p>
            <?php else: ?>
                <?php 
                $totalComments = count($comments);
                $displayComments = array_slice($comments, 0, 5); 
                ?>
                
                <?php foreach ($displayComments as $comment): ?>
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

                <?php if ($totalComments > 5): ?>
                    <div class="text-center mt-4">
                        <a href="/allComments/<?= $itemType ?>/<?= $itemId ?>" 
                           class="inline-block px-6 py-2 bg-gray-700 text-white rounded hover:bg-gray-600 transition duration-300">
                            Voir tous les commentaires (<?= $totalComments ?>)
                        </a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>
