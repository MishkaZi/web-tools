<?php

use Core\Config;
// use Core\Debug;
?>
</div><!-- Close flex-grow div from header -->
<footer class='mt-auto bg-gray-800 text-white w-full'>
    <div class='container mx-auto px-4 py-6'>
        <div class='text-center text-gray-400'>
            <p>&copy; <?= date('Y') ?> Mike Zino Web Dev. All rights reserved.</p>
        </div>
    </div>
</footer>



<?php //Debug::display();
    //debug($_SERVER);
    if (isset($debug)) debug($debug);
?>
</body>

</html>