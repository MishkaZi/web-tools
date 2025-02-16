<?php

use Core\Config;
?>
<!DOCTYPE html>
<html lang='en' class='h-full'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title><?= $pageTitle ?? 'Mike Zino Web Dev' ?></title>

    <!-- Favicon -->
    <link rel='icon' type='image/x-icon' href='<?= Config::getAssetsPath('images/favicon.ico') ?>'>

    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name='base-url' content='<?= Config::getBaseUrl() ?>'>

    <!-- CSS -->
    <link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>
    <link href='<?= Config::getAssetsPath('css/main.css') ?>' rel='stylesheet'>

    <!-- Dropzone first -->
    <script src='https://unpkg.com/dropzone@5/dist/min/dropzone.min.js'></script>
    <link href='https://unpkg.com/dropzone@5/dist/min/dropzone.min.css' rel='stylesheet'>
    
    <!-- Then our scripts -->
    <script src='<?= Config::getAssetsPath('js/modal-manager.js') ?>'></script>
    <script src='<?= Config::getAssetsPath('js/tool-manager.js') ?>'></script>

    <!-- Modal Container - Add after existing CSS links -->
    <style>
        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 40;
        }

        .modal-content {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 50;
            width: 90%;
            max-width: 600px;
        }

        .modal-open {
            display: block;
        }
    </style>
</head>
</head>

<body class='flex flex-col min-h-screen bg-gray-100'>

    <body class='flex flex-col min-h-screen bg-gray-100'>
        <!-- Global Modal Container -->
        <div id='modalContainer' class='hidden fixed inset-0 z-50'>
            <!-- Modal Backdrop -->
            <div class='absolute inset-0 bg-black bg-opacity-50' id='modalBackdrop'></div>
            <!-- Modal Content -->
            <div class='relative min-h-screen flex items-center justify-center p-4'>
                <div class='relative bg-white rounded-lg shadow-xl max-w-2xl w-full' id='modalContent'>
                    <!-- Modal content will be injected here -->
                </div>
            </div>
        </div>

        <header class='bg-gray-800 text-white shadow-lg'>
            <div class='container mx-auto px-4 py-4'>
                <div class='flex flex-col md:flex-row justify-between items-center gap-4'>
                    <a href='<?= Config::getBaseUrl() ?>' class='text-2xl font-bold hover:text-gray-300 transition-colors'>
                        Mike Zino Web Dev
                    </a>
                    <?php if (isset($toolName)): ?>
                        <div class='flex items-center gap-4'>
                            <span class='text-xl text-gray-300'><?= ucwords(str_replace('-', ' ', $toolName)) ?></span>
                            <a href='<?= Config::getBaseUrl() ?>'
                                class='bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors flex items-center gap-2'>
                                <svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 19l-7-7m0 0l7-7m-7 7h18' />
                                </svg>
                                Back to Home
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </header>
        <div class='flex-grow'>