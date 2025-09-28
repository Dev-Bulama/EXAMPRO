<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'ExamPro Platform', ENT_QUOTES) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.14/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-950 text-white min-h-screen flex flex-col">
    <header class="bg-slate-900/80 backdrop-blur border-b border-slate-800">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <span class="text-2xl font-bold">ExamPro</span>
            <nav class="hidden md:flex gap-6 text-sm uppercase tracking-wide">
                <a href="#features" class="hover:text-emerald-400">Features</a>
                <a href="#exams" class="hover:text-emerald-400">Exam Types</a>
                <a href="#pricing" class="hover:text-emerald-400">Pricing</a>
                <a href="#contact" class="hover:text-emerald-400">Contact</a>
            </nav>
            <a href="/docs" class="px-4 py-2 bg-emerald-500 text-slate-950 rounded-lg font-semibold">View Docs</a>
        </div>
    </header>
    <main class="flex-1">
        <?= $slot ?? '' ?>
    </main>
    <footer class="bg-slate-900 border-t border-slate-800">
        <div class="max-w-6xl mx-auto px-4 py-6 text-sm text-slate-400">
            &copy; <?= date('Y') ?> ExamPro Platform. All rights reserved.
        </div>
    </footer>
</body>
</html>
