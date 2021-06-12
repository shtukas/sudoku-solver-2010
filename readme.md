### Sudoku Solver (PHP)

(Update in June 2021)

This is a Sudoku solver I had written in 2010 (I think..., at least that's what I infer from the name of the repository) in PHP.

Looking at it years later, I noticed that the `resolveAttempt($board)` method of `class Cell` was only correct if there was already enough numbers on the starting board to ensure that there was only one solution (an assumption I probably thought was fair if all the examples I was using came from magazines).