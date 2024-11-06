<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur - La Cinétech</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            background: #f4f4f4;
        }
        .error-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .error-title {
            color: #dc3545;
            margin-bottom: 20px;
        }
        .error-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .stack-trace {
            background: #272822;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1 class="error-title">Erreur</h1>
        
        <div class="error-details">
            <p><strong>Message:</strong> <?= htmlspecialchars($error['message']) ?></p>
            <p><strong>Fichier:</strong> <?= htmlspecialchars($error['file']) ?></p>
            <p><strong>Ligne:</strong> <?= $error['line'] ?></p>
        </div>

        <?php if (isset($error['trace'])): ?>
            <h2>Stack Trace:</h2>
            <pre class="stack-trace"><?= htmlspecialchars($error['trace']) ?></pre>
        <?php endif; ?>
    </div>
</body>
</html> 