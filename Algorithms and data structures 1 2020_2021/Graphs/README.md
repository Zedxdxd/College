# graphs

-Implementation of a graph using matrix representation

-Using that graph to simulate the modified ludo game. There are two players, Maja and Sanja. One node is set to be the starting node, and one node is set to be the finishing node. The player which manages to reach the finishing node in less moves is the winner. 
Maja always moves one tile per move so the number of moves is calculated using the standard BFS. Sanja on odd moves moves one tile per move, while on even moves moves two tiles per move.
So the number of moves it takes Sanja to reach the finishing node is also calculated using BFS, but a little bit modified.