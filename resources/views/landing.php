<?php
$title = 'ExamPro – Online Exam Management';
ob_start();
?>
<section class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 via-slate-900 to-slate-950"></div>
    <div class="relative max-w-6xl mx-auto px-4 py-24 grid md:grid-cols-2 gap-16 items-center">
        <div>
            <h1 class="text-4xl md:text-6xl font-bold leading-tight">Deliver high-stakes exams with confidence.</h1>
            <p class="mt-6 text-lg text-slate-300">ExamPro gives training providers a fast, secure, and adaptive platform for professional certification prep. Launch live quizzes, reward learners, and manage everything from one control center.</p>
            <div class="mt-8 flex flex-col sm:flex-row gap-3">
                <a href="/docs" class="px-6 py-3 rounded-lg bg-emerald-500 text-slate-950 font-semibold text-center">Explore Documentation</a>
                <a href="#pricing" class="px-6 py-3 rounded-lg border border-emerald-500 text-emerald-400 font-semibold text-center">See Plans</a>
            </div>
        </div>
        <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 shadow-xl">
            <h2 class="text-xl font-semibold mb-4">Why teams choose ExamPro</h2>
            <ul class="space-y-4 text-slate-300">
                <li class="flex gap-3"><span class="text-emerald-400">✔</span> Role-based admin controls with audit-ready activity logs.</li>
                <li class="flex gap-3"><span class="text-emerald-400">✔</span> CBT-ready player with live timer, session lockdown, and analytics.</li>
                <li class="flex gap-3"><span class="text-emerald-400">✔</span> Monetize instantly with Paystack, manual bank transfers, and rewards.</li>
            </ul>
        </div>
    </div>
</section>
<section id="features" class="max-w-6xl mx-auto px-4 py-20 grid md:grid-cols-3 gap-10">
    <?php
    $features = [
        ['title' => 'Advanced Authoring', 'description' => 'Compose seven question types, attach media, and reuse banks.'],
        ['title' => 'Adaptive Journeys', 'description' => 'Deliver lessons, practice sets, and live mock exams in one plan.'],
        ['title' => 'Insightful Analytics', 'description' => 'Progress dashboards, leaderboard, and exportable reports.'],
        ['title' => 'Global Delivery', 'description' => 'Multi-language content and RTL-ready interfaces built in.'],
        ['title' => 'Secure Sessions', 'description' => 'Single active session, IP tracking, and tamper proof timers.'],
        ['title' => 'Team Collaboration', 'description' => 'Add graders, moderators, and lab assistants with custom permissions.'],
    ];
    foreach ($features as $feature): ?>
        <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6">
            <h3 class="text-lg font-semibold mb-2 text-emerald-400"><?= htmlspecialchars($feature['title']) ?></h3>
            <p class="text-slate-300 text-sm leading-relaxed"><?= htmlspecialchars($feature['description']) ?></p>
        </div>
    <?php endforeach; ?>
</section>
<section id="pricing" class="bg-slate-900/60 border-y border-slate-800">
    <div class="max-w-6xl mx-auto px-4 py-20">
        <h2 class="text-3xl font-bold text-center mb-12">Flexible pricing for growing academies</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <?php
            $plans = [
                ['name' => 'Starter', 'price' => 'NGN 9,999', 'desc' => 'Solo trainers and small cohorts', 'items' => ['Unlimited practice sets', 'Email support', 'Resource downloads']],
                ['name' => 'Professional', 'price' => 'NGN 29,999', 'desc' => 'Bootcamps and certification teams', 'items' => ['Mock exams & live quizzes', 'Progress analytics', 'Paystack integration']],
                ['name' => 'Enterprise', 'price' => 'Contact Us', 'desc' => 'Enterprise academies and universities', 'items' => ['Custom SLAs', 'Dedicated success manager', 'Advanced reporting']],
            ];
            foreach ($plans as $plan): ?>
                <div class="rounded-2xl border border-slate-800 bg-slate-950/80 p-6 flex flex-col">
                    <h3 class="text-xl font-semibold mb-2"><?= htmlspecialchars($plan['name']) ?></h3>
                    <span class="text-3xl font-bold text-emerald-400 mb-3"><?= htmlspecialchars($plan['price']) ?></span>
                    <p class="text-slate-300 text-sm mb-4"><?= htmlspecialchars($plan['desc']) ?></p>
                    <ul class="space-y-2 text-slate-400 text-sm flex-1">
                        <?php foreach ($plan['items'] as $item): ?>
                            <li class="flex gap-2"><span class="text-emerald-400">•</span><?= htmlspecialchars($item) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="mailto:sales@exampro.test" class="mt-6 inline-block text-center px-4 py-2 rounded-lg border border-emerald-500 text-emerald-400">Talk to sales</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section id="contact" class="max-w-6xl mx-auto px-4 py-20 grid md:grid-cols-2 gap-12">
    <div>
        <h2 class="text-3xl font-bold mb-4">Ready to launch your academy?</h2>
        <p class="text-slate-300">Schedule a walkthrough with our solutions team and see how ExamPro streamlines onboarding, authoring, and secure delivery for your professional learners.</p>
        <div class="mt-6">
            <a href="mailto:hello@exampro.test" class="px-6 py-3 bg-emerald-500 text-slate-950 rounded-lg font-semibold">Book a demo</a>
        </div>
    </div>
    <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6">
        <h3 class="text-lg font-semibold mb-3">Global support</h3>
        <p class="text-slate-400 text-sm">We serve training organizations across Africa, Europe, and North America with localized content, RTL-ready layouts, and dedicated implementation specialists.</p>
        <ul class="mt-4 space-y-2 text-slate-400 text-sm">
            <li>📞 +234 800 EXAMPRO</li>
            <li>💬 support@exampro.test</li>
            <li>📍 Remote-first, available 24/7</li>
        </ul>
    </div>
</section>
<?php
$slot = ob_get_clean();
include __DIR__ . '/layouts/app.php';
