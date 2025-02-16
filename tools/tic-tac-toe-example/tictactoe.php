<!DOCTYPE html>
<html>
<head>
    <title>Tic Tac Toe with AI</title>
</head>
<body class="min-h-screen bg-gray-100 flex flex-col items-center justify-center p-8">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-6 space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Tic Tac Toe vs AI</h1>
            <a href="/" class="text-blue-500 hover:text-blue-700 transition-colors">
                ← Back to Home
            </a>
        </div>

        <div id="status" class="text-xl text-center font-medium text-gray-700"></div>
        
        <div id="board" class="grid grid-cols-3 gap-2 bg-gray-200 p-2 rounded-lg w-fit mx-auto"></div>
        
        <button onclick="resetGame()" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">
            Reset Game
        </button>
    </div>

    <script>
        let gameBoard = Array(9).fill('');
        let gameActive = true;
        const human = 'X';
        const ai = 'O';

        const board = document.getElementById('board');
        const status = document.getElementById('status');

        // Create board cells with Tailwind classes
        for (let i = 0; i < 9; i++) {
            const cell = document.createElement('div');
            cell.className = 'w-20 h-20 bg-white rounded-md flex items-center justify-center text-4xl font-bold cursor-pointer hover:bg-gray-50 transition-colors';
            cell.setAttribute('data-index', i);
            cell.addEventListener('click', handleCellClick);
            board.appendChild(cell);
        }

        function handleCellClick(e) {
            const index = e.target.getAttribute('data-index');
            if (gameBoard[index] || !gameActive) return;

            makeMove(index, human);
            if (gameActive) {
                setTimeout(() => {
                    const aiMove = getBestMove();
                    makeMove(aiMove, ai);
                }, 100);
            }
        }

        function makeMove(index, player) {
            gameBoard[index] = player;
            const cell = document.querySelector(`[data-index="${index}"]`);
            cell.textContent = player;
            cell.className = `w-20 h-20 rounded-md flex items-center justify-center text-4xl font-bold cursor-pointer transition-colors ${
                player === human ? 'bg-blue-100 text-blue-600' : 'bg-red-100 text-red-600'
            }`;

            if (checkWin(gameBoard, player)) {
                status.textContent = `${player === human ? 'You win!' : 'AI wins!'}`;
                status.className = 'text-xl text-center font-medium text-green-600';
                gameActive = false;
                return;
            }

            if (gameBoard.every(cell => cell !== '')) {
                status.textContent = 'Draw!';
                status.className = 'text-xl text-center font-medium text-yellow-600';
                gameActive = false;
                return;
            }

            status.textContent = `${player === human ? 'AI' : 'Your'} turn`;
            status.className = 'text-xl text-center font-medium text-gray-700';
        }

        function getBestMove() {
            let bestScore = -Infinity;
            let bestMove;

            for (let i = 0; i < 9; i++) {
                if (gameBoard[i] === '') {
                    gameBoard[i] = ai;
                    let score = minimax(gameBoard, 0, false);
                    gameBoard[i] = '';
                    if (score > bestScore) {
                        bestScore = score;
                        bestMove = i;
                    }
                }
            }
            return bestMove;
        }

        function minimax(board, depth, isMaximizing) {
            if (checkWin(board, human)) return -1;
            if (checkWin(board, ai)) return 1;
            if (board.every(cell => cell !== '')) return 0;

            if (isMaximizing) {
                let bestScore = -Infinity;
                for (let i = 0; i < 9; i++) {
                    if (board[i] === '') {
                        board[i] = ai;
                        bestScore = Math.max(bestScore, minimax(board, depth + 1, false));
                        board[i] = '';
                    }
                }
                return bestScore;
            } else {
                let bestScore = Infinity;
                for (let i = 0; i < 9; i++) {
                    if (board[i] === '') {
                        board[i] = human;
                        bestScore = Math.min(bestScore, minimax(board, depth + 1, true));
                        board[i] = '';
                    }
                }
                return bestScore;
            }
        }

        function checkWin(board, player) {
            const wins = [[0,1,2],[3,4,5],[6,7,8],[0,3,6],[1,4,7],[2,5,8],[0,4,8],[2,4,6]];
            return wins.some(([a,b,c]) => 
                board[a] === player && board[b] === player && board[c] === player
            );
        }

        function resetGame() {
            gameBoard = Array(9).fill('');
            gameActive = true;
            status.textContent = 'Your turn (X)';
            status.className = 'text-xl text-center font-medium text-gray-700';
            document.querySelectorAll('[data-index]').forEach(cell => {
                cell.textContent = '';
                cell.className = 'w-20 h-20 bg-white rounded-md flex items-center justify-center text-4xl font-bold cursor-pointer hover:bg-gray-50 transition-colors';
            });
        }

        // Initialize game
        resetGame();
    </script>
</body>
</html>