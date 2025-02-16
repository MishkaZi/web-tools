<?php

use Core\Config;

// Get tools directory
$toolsDir = dirname(__DIR__, 2) . '/tools';
$tools = glob($toolsDir . '/*', GLOB_ONLYDIR);
?>

<div class='container mx-auto px-4 py-8'>
    <div class='flex flex-col md:flex-row justify-between items-center mb-8 gap-4'>
        <h1 class='text-4xl font-bold text-gray-800'>Mike Zino Web Dev Tools</h1>
        <button
            id='addToolButton'
            class='bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded transition-colors flex items-center'>
            <svg class='w-5 h-5 mr-2' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4'></path>
            </svg>
            Add New Tool
        </button>
    </div>

    <div class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4'>
        <?php foreach ($tools as $toolPath):
            $toolName = basename($toolPath);
            $toolDisplayName = ucwords(str_replace('-', ' ', $toolName));

            $mainFile = glob($toolPath . '/index.php')[0] ?? null;
            if (!$mainFile) continue;
        ?>
            <div class='relative bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow'>
                <button
                    class='absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white p-2 rounded-full transition-colors delete-tool'
                    data-tool-name='<?= htmlspecialchars($toolName) ?>'
                    title='Delete <?= htmlspecialchars($toolDisplayName) ?>'>
                    <svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'></path>
                    </svg>
                </button>
                <a href='<?= Config::getBaseUrl() . '/tool/' . $toolName ?>'
                    class='block p-6'>
                    <h2 class='text-xl font-semibold text-gray-800'><?= htmlspecialchars($toolDisplayName) ?></h2>
                    <p class='mt-2 text-gray-600'>Click to use this tool</p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>