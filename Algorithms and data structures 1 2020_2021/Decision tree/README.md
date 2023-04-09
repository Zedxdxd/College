# Magic-squares

-Part of Algorithms and data structures 1 course

This is a magic square solver using the decision trees to generate every possible outcome. The user inputs the starting state of the square and the numbers for the square to be filled with. The user can choose to form the magic squares, generating the decision tree. 
The root of the decision tree is the starting state, and every next level of the tree has one more number added to that state. If the generated state can't form the magic square, that node won't have children.
There's the option to print the tree using level-order and to print the solutions using the post-order. Also the program can print the perfect magic squares if there exists any.
