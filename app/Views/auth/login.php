<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIAGA</title>
    <!-- Load custom Tailwind CSS output -->
    <link rel="stylesheet" href="<?= base_url('css/output.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Manrope', sans-serif;
        }
    </style>
</head>
<body class="bg-neutral min-h-screen flex items-center justify-center">

    <div class="bg-surface rounded-lg shadow-md p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-primary font-bold text-3xl">SIAGA</h1>
            <p class="text-gray-500 mt-2 text-sm">Sistem Informasi dan Administrasi Gereja</p>
        </div>

        <!-- Tampilkan pesan error jika ada -->
        <?php if (session('error')) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <?= session('error') ?>
            </div>
        <?php endif ?>

        <?php if (session('errors')) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <ul class="list-disc list-inside">
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <form action="<?= url_to('login') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-primary mb-1">Email / Username</label>
                <input type="text" name="email" id="email" class="w-full border border-secondary rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-tertiary focus:border-transparent" placeholder="Masukkan email atau username" value="<?= old('email') ?>" required>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-primary mb-1">Password</label>
                <input type="password" name="password" id="password" class="w-full border border-secondary rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-tertiary focus:border-transparent" placeholder="Masukkan password" required>
            </div>

            <!-- Ingat Saya (Remember Me) -->
            <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                <div class="flex items-center mb-6">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-tertiary border-secondary rounded focus:ring-tertiary" <?php if (old('remember')): ?> checked <?php endif ?>>
                    <label for="remember" class="ml-2 text-sm text-gray-600">Ingat Saya</label>
                </div>
            <?php endif; ?>

            <button type="submit" class="w-full bg-tertiary text-on-primary rounded-md py-2 font-medium hover:opacity-90 transition-opacity">
                Login
            </button>
        </form>
    </div>

</body>
</html>
